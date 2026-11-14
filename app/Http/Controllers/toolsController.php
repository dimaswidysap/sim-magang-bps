<?php

namespace App\Http\Controllers;

use App\Imports\ReadHeaderImport;
use App\Services\PythonScriptGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class toolsController extends Controller
{
    public function toolsIndex()
    {
        return view('pages.tools.index');
    }

    public function form()
    {
        return view('pages.tools.generate-fasih.index');
    }

    public function uploadExcel(Request $request)
    {
        $request->validate(
            [
                'excel_file' => 'required|file|mimes:xlsx,xls|max:10240',
            ],
            [
                'excel_file.required' => 'File Excel wajib diunggah.',
                'excel_file.file' => 'File yang diunggah tidak valid.',
                'excel_file.mimes' => 'Format file harus berupa .xlsx atau .xls.',
                'excel_file.max' => 'Ukuran file Excel maksimal 10 MB.',
            ],
        );

        // Bersihkan folder sementara dari sesi upload sebelumnya jika masih ada
        $oldWorkId = session('work_id');
        if ($oldWorkId) {
            $this->hapusTmpWorkDir($oldWorkId);
        }

        // Reset seluruh session konfigurasi & mapping lama agar tidak tercampur dengan file baru
        session()->forget(['work_id', 'excel_path', 'excel_headers', 'header_lines', 'field_mappings', 'folder_match_column', 'folder_match_label']);

        $workId = Str::uuid()->toString();
        $tmpDir = storage_path("app/tmp/{$workId}");

        if (!File::isDirectory($tmpDir)) {
            File::makeDirectory($tmpDir, 0755, true);
        }

        $uploadedFile = $request->file('excel_file');
        $extension = strtolower($uploadedFile->getClientOriginalExtension() ?: 'xlsx');
        $fileName = "data.{$extension}";
        $excelPath = "{$tmpDir}/{$fileName}";

        $uploadedFile->move($tmpDir, $fileName);

        try {
            $reader = new ReadHeaderImport();
            Excel::import($reader, $excelPath);
        } catch (Throwable $e) {
            $this->hapusTmpWorkDir($workId);
            report($e);

            return back()->withErrors([
                'excel_file' => 'Gagal membaca file Excel. Pastikan file tidak rusak atau terproteksi password.',
            ]);
        }

        if (empty($reader->headers)) {
            $this->hapusTmpWorkDir($workId);

            return back()->withErrors([
                'excel_file' => 'Baris header pada file Excel kosong atau tidak terbaca.',
            ]);
        }

        session([
            'work_id' => $workId,
            'excel_path' => $excelPath,
            'excel_headers' => $reader->headers,
        ]);

        return view('pages.tools.generate-fasih.mapping', [
            'headers' => $reader->headers,
        ]);
    }

    public function headerForm()
    {
        if (!session('excel_headers')) {
            return redirect()
                ->route('generate.form')
                ->withErrors(['msg' => 'Silakan upload Excel terlebih dahulu.']);
        }

        $existingLines = session('header_lines', ['BUKTI PENCAPAIAN PEKERJAAN', 'Screenshoot Aplikasi Fasih']);

        return view('pages.tools.generate-fasih.header-form', [
            'lines' => $existingLines,
        ]);
    }

    public function storeHeader(Request $request)
    {
        $request->validate([
            'header_lines' => 'required|array|min:1',
            'header_lines.*' => 'required|string|max:200',
        ]);

        session(['header_lines' => $request->input('header_lines')]);

        return redirect()->route('generate.mappingForm');
    }

    public function mappingForm()
    {
        if (!session('excel_headers')) {
            return redirect()
                ->route('generate.form')
                ->withErrors(['msg' => 'Silakan upload Excel terlebih dahulu.']);
        }

        if (!session('header_lines')) {
            return redirect()
                ->route('generate.headerForm')
                ->withErrors(['msg' => 'Silakan atur header dokumen terlebih dahulu.']);
        }

        $existingMappings = session('field_mappings', [['label' => 'Masukan Label', 'column' => ''], ['label' => 'Masukan Label', 'column' => ''], ['label' => 'Masukan Label', 'column' => ''], ['label' => 'Masukan Label', 'column' => '']]);

        return view('pages.tools.generate-fasih.mapping-form', [
            'headers' => session('excel_headers'),
            'mappings' => $existingMappings,
        ]);
    }

    public function storeMapping(Request $request)
    {
        $request->validate([
            'labels' => 'required|array|min:1',
            'labels.*' => 'required|string|max:100',
            'columns' => 'required|array|min:1',
            'columns.*' => 'required|string',
            'match_column' => 'required|integer',
        ]);

        $labels = $request->input('labels');
        $columns = $request->input('columns');
        $matchIndex = (int) $request->input('match_column');

        if (count($labels) !== count($columns)) {
            return back()->withErrors(['msg' => 'Data mapping tidak konsisten.']);
        }

        if (!isset($labels[$matchIndex])) {
            return back()->withErrors(['msg' => 'Kolom pencocokan folder yang dipilih tidak valid.']);
        }

        $mappings = [];
        foreach ($labels as $index => $label) {
            $mappings[] = [
                'label' => $label,
                'column' => $columns[$index],
            ];
        }

        session([
            'field_mappings' => $mappings,
            'folder_match_column' => $columns[$matchIndex],
            'folder_match_label' => $labels[$matchIndex],
        ]);

        return redirect()->route('generate.reviewGenerate');
    }

    public function reviewGenerate()
    {
        $headerLines = session('header_lines');
        $fieldMappings = session('field_mappings');
        $matchColumn = session('folder_match_column');

        if (!$headerLines || !$fieldMappings || !$matchColumn) {
            return redirect()
                ->route('generate.mappingForm')
                ->withErrors(['msg' => 'Data belum lengkap. Silakan ulangi dari langkah pengaturan header/mapping.']);
        }

        return view('pages.tools.generate-fasih.review', [
            'headerLines' => $headerLines,
            'fieldMappings' => $fieldMappings,
            'matchColumn' => $matchColumn,
        ]);
    }

    public function downloadScript(PythonScriptGenerator $generator)
    {
        $headerLines = session('header_lines');
        $fieldMappings = session('field_mappings');
        $matchColumn = session('folder_match_column');

        if (!$headerLines || !$fieldMappings || !$matchColumn) {
            return redirect()
                ->route('generate.mappingForm')
                ->withErrors(['msg' => 'Data belum lengkap, tidak bisa generate script.']);
        }

        $scriptContent = $generator->generate($headerLines, $fieldMappings, $matchColumn);

        // Bersihkan file Excel sementara setelah script berhasil di-generate untuk diunduh
        $workId = session('work_id');
        if ($workId) {
            $this->hapusTmpWorkDir($workId);
            session()->forget(['work_id', 'excel_path']);
        }

        return response($scriptContent, 200, [
            'Content-Type' => 'text/x-python',
            'Content-Disposition' => 'attachment; filename="script.py"',
        ]);
    }

    private function hapusTmpWorkDir(string $workId): void
    {
        // Pastikan workId hanya berisi karakter UUID/alfanumerik yang aman dari path traversal
        if (!preg_match('/^[a-zA-Z0-9\-]+$/', $workId)) {
            return;
        }

        $tmpDir = storage_path("app/tmp/{$workId}");
        if (File::isDirectory($tmpDir)) {
            File::deleteDirectory($tmpDir);
        }
    }
}
