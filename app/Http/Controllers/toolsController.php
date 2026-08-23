<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Imports\ReadHeaderImport;
use App\Services\PythonScriptGenerator;

use Illuminate\Support\Str;

class toolsController extends Controller
{
    //
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
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240', // max 10MB, sesuaikan
        ]);

        // buat session id unik untuk sesi kerja ini kalau belum ada
        $workId = session('work_id') ?? Str::uuid()->toString();
        session(['work_id' => $workId]);

        $tmpDir = storage_path("app/tmp/{$workId}");
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $excelPath = $tmpDir . '/data.xlsx';
        $request->file('excel_file')->move($tmpDir, 'data.xlsx');

        // simpan path ke session, BUKAN isi datanya
        session(['excel_path' => $excelPath]);

        $reader = new ReadHeaderImport();
        Excel::import($reader, $excelPath);

        session([
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

        // kalau user sudah pernah isi sebelumnya (misal balik dari langkah lain), tampilkan lagi
        $existingLines = session('header_lines', ['BUKTI PENCAPAIAN PEKERJAAN', 'Screenshoot Aplikasi Fasih']);

        return view('pages.tools.generate-fasih.header-form', ['lines' => $existingLines]);
    }

    public function storeHeader(Request $request)
    {
        $request->validate([
            'header_lines' => 'required|array|min:1',
            'header_lines.*' => 'required|string|max:200',
        ]);

        session(['header_lines' => $request->input('header_lines')]);

        // dd(session('header_lines'));

        return redirect()->route('generate.mappingForm');
        // route ini akan dibuat di langkah 4, untuk sekarang bisa diarahkan sementara ke halaman lain untuk testing
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
            'match_column' => 'required|integer', // WAJIB pilih satu, index baris mana yang jadi acuan folder
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
            'folder_match_column' => $columns[$matchIndex], // simpan NAMA kolom-nya, bukan cuma index
            'folder_match_label' => $labels[$matchIndex], // untuk ditampilkan lagi di halaman review nanti
        ]);

        //  dd($request->all());
        return redirect()->route('generate.reviewGenerate');
        // route ini untuk tombol "Generate Script" di langkah berikutnya
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

        return response($scriptContent, 200, [
            'Content-Type' => 'text/x-python',
            'Content-Disposition' => 'attachment; filename="script.py"',
        ]);
    }
}
