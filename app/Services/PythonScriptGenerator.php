<?php

namespace App\Services;

class PythonScriptGenerator
{
    public function __construct(private HeaderFontCalculator $fontCalculator) {}

    public function generate(array $headerLines, array $fieldMappings, string $matchColumn): string
    {
        $headerWithSizes = $this->fontCalculator->calculateAll($headerLines);
        $headerPythonList = $this->toPythonHeaderList($headerWithSizes);
        $mappingPythonList = $this->toPythonMappingList($fieldMappings);
        $matchColumnLiteral = $this->pyString($matchColumn);

        return $this->buildTemplate($headerPythonList, $mappingPythonList, $matchColumnLiteral);
    }

    private function toPythonHeaderList(array $headerWithSizes): string
    {
        $items = array_map(
            fn($h) => "    (" . $this->pyString($h['text']) . ", {$h['size']})",
            $headerWithSizes
        );
        return "[\n" . implode(",\n", $items) . "\n]";
    }

    private function toPythonMappingList(array $mappings): string
    {
        $items = array_map(
            fn($m) => "    (" . $this->pyString($m['label']) . ", " . $this->pyString($m['column']) . ")",
            $mappings
        );
        return "[\n" . implode(",\n", $items) . "\n]";
    }

    private function pyString(string $value): string
    {
        $escaped = str_replace(['\\', "'"], ['\\\\', "\\'"], $value);
        return "'{$escaped}'";
    }

    private function buildTemplate(string $headerList, string $mappingList, string $matchColumn): string
    {
        return <<<PYTHON
import os
import re
import glob
import difflib
from io import BytesIO
from PIL import Image
import pandas as pd
from docx import Document
from docx.shared import Inches, Pt, Cm
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.oxml import OxmlElement

# ============================================================
# HELPER NAMA FILE UNIK (Mencegah Error Overwrite / Duplikat)
# ============================================================
def get_unique_filename(filename):
    if not os.path.exists(filename):
        return filename
    name, ext = os.path.splitext(filename)
    counter = 1
    while True:
        new_filename = f"{name} ({counter}){ext}"
        if not os.path.exists(new_filename):
            return new_filename
        counter += 1

# ============================================================
# KONFIGURASI HASIL GENERATE - JANGAN DIUBAH MANUAL
# ============================================================
HEADER_LINES = {$headerList}

FIELD_MAPPINGS = {$mappingList}

MATCH_COLUMN = {$matchColumn}

OUTPUT_MAIN = get_unique_filename('HASIL_DOKUMEN.docx')
OUTPUT_PELANGGARAN = get_unique_filename('DAFTAR_PELANGGARAN.docx')

# ============================================================
# HELPER NILAI AMAN
# ============================================================
def nilai_aman(value):
    try:
        if pd.isna(value):
            return ''
    except (TypeError, ValueError):
        pass
    return str(value).strip()

# ============================================================
# DETEKSI FILE EXCEL & FOLDER FOTO
# ============================================================
def pilih_interaktif(daftar, tipe_label):
    if len(daftar) == 0:
        print(f"[X] Tidak ditemukan {tipe_label} di folder ini. Pastikan file/folder berada satu level dengan script.")
        exit()
    if len(daftar) == 1:
        return daftar[0]

    print(f"\\nDitemukan lebih dari satu {tipe_label}:")
    for i, item in enumerate(daftar, 1):
        print(f"  {i}. {item}")

    while True:
        pilihan = input(f"Pilih nomor {tipe_label} yang akan dipakai: ").strip()
        if pilihan.isdigit() and 1 <= int(pilihan) <= len(daftar):
            return daftar[int(pilihan) - 1]
        print("Input tidak valid, coba lagi.")

def deteksi_excel():
    kandidat = [
        f for f in glob.glob('*.xlsx')
        if not os.path.basename(f).startswith('~\$')
    ]
    return pilih_interaktif(kandidat, "file Excel")

def deteksi_folder_foto():
    kandidat = [
        f for f in os.listdir('.')
        if os.path.isdir(f) and not f.startswith('.') and f != '__pycache__'
    ]
    return pilih_interaktif(kandidat, "folder foto")

# ============================================================
# FUNGSI PENDUKUNG
# ============================================================
def is_foto_kamera(image_bytes):
    try:
        image_bytes.seek(0)
        img = Image.open(image_bytes)
        exif = img.getexif()
        if not exif:
            return False
        camera_tags = [271, 272, 33434, 34855]
        return any(tag in exif for tag in camera_tags)
    except Exception:
        return False

# ============================================================
# PROGRAM UTAMA
# ============================================================
file_excel = deteksi_excel()
folder_ss = deteksi_folder_foto()

print(f"\\n[OK] Memakai file Excel : {file_excel}")
print(f"[OK] Memakai folder foto: {folder_ss}\\n")

doc = Document()
for section in doc.sections:
    section.page_height = Inches(11.69)
    section.page_width = Inches(8.27)
    section.top_margin = Inches(0.5)
    section.bottom_margin = Inches(0.5)
    section.left_margin = Inches(0.5)
    section.right_margin = Inches(0.5)

style = doc.styles['Normal']
style.font.name = 'Times New Roman'
style.font.size = Pt(12)

try:
    df = pd.read_excel(file_excel)
except Exception as e:
    print(f"Gagal membaca excel: {e}")
    exit()

subfolders = [f for f in os.listdir(folder_ss) if os.path.isdir(os.path.join(folder_ss, f))]

daftar_pelanggar = []

# ============================================================
# HITUNG POSISI TAB BERDASARKAN LABEL TERPANJANG
# (Perkiraan lebar karakter kapital Times New Roman Bold 12pt.
#  Ini estimasi rata-rata, bukan pengukuran font presisi -
#  sesuaikan CHAR_WIDTH_INCH bila hasil masih meleset.)
# ============================================================
CHAR_WIDTH_INCH = 0.11
TAB_BUFFER_INCH = 0.15

if FIELD_MAPPINGS:
    panjang_label_maks = max(len(str(label).upper()) for label, _ in FIELD_MAPPINGS)
    TAB_POSISI_LABEL = Inches(panjang_label_maks * CHAR_WIDTH_INCH + TAB_BUFFER_INCH)
else:
    TAB_POSISI_LABEL = Inches(1.4)

for index, row in df.iterrows():
    nilai_cocok = nilai_aman(row.get(MATCH_COLUMN, None))
    if not nilai_cocok:
        continue

    print(f"Memproses: {nilai_cocok} ...")

    folder_terpilih = None
    if subfolders:
        matches = difflib.get_close_matches(nilai_cocok.lower(), [f.lower() for f in subfolders], n=1, cutoff=0.5)
        if matches:
            for f in subfolders:
                if f.lower() == matches[0]:
                    folder_terpilih = f
                    break

    valid_images = []
    pelanggaran_ini = []

    if folder_terpilih:
        path_folder = os.path.join(folder_ss, folder_terpilih)
        ekstensi = ('.png', '.jpg', '.jpeg', '.webp', '.bmp')
        files_gambar = sorted([f for f in os.listdir(path_folder) if f.lower().endswith(ekstensi)])

        for idx_file, file_name in enumerate(files_gambar):
            path_gambar = os.path.join(path_folder, file_name)
            try:
                with open(path_gambar, 'rb') as f_img:
                    content = f_img.read()
                img_bytes = BytesIO(content)
                nomor_file = idx_file + 1

                if is_foto_kamera(img_bytes):
                    pelanggaran_ini.append({
                        'nomor_file': nomor_file,
                        'alasan': 'Terdeteksi metadata EXIF kamera (bukan screenshot)'
                    })
                    continue

                img_bytes.seek(0)
                valid_images.append(img_bytes)
            except Exception as e:
                print(f"  -> [X] Gagal membaca gambar {file_name}: {e}")
    else:
        print(f"  -> [!] Folder untuk {nilai_cocok} tidak ditemukan.")

    if pelanggaran_ini:
        daftar_pelanggar.append({'data_row': row, 'bukti': pelanggaran_ini})

    # ---- Render Header (Ukuran 12 Pt Bold Semua Baris) ----
    for idx_h, (teks, _) in enumerate(HEADER_LINES):
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p.paragraph_format.line_spacing = 1.0
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(12) if idx_h == len(HEADER_LINES) - 1 else Pt(1)

        run = p.add_run(str(teks).upper())
        run.bold = True
        run.font.size = Pt(12)

    # ---- Render Field Mapping (Label Bold, Nilai Normal, Tab Biasa) ----
    if FIELD_MAPPINGS:
        for label, kolom in FIELD_MAPPINGS:
            nilai = nilai_aman(row.get(kolom, None))

            p = doc.add_paragraph()
            p.paragraph_format.space_before = Pt(1)
            p.paragraph_format.space_after = Pt(1)
            p.paragraph_format.line_spacing = 1.0
            p.paragraph_format.tab_stops.add_tab_stop(TAB_POSISI_LABEL)

            r_label = p.add_run(str(label).upper())
            r_label.bold = True

            p.add_run(f"\t: {str(nilai).upper()}")

        p_spacer = doc.add_paragraph()
        p_spacer.paragraph_format.space_before = Pt(0)
        p_spacer.paragraph_format.space_after = Pt(8)

    if not valid_images:
        doc.add_paragraph("[TIDAK ADA FOTO VALID]")
        doc.add_page_break()
        continue

    # ---- Render Gambar Tabel (SS FASIH Normal / Tidak Bold) ----
    chunk_size = 4
    for i in range(0, len(valid_images), chunk_size):
        chunk = valid_images[i:i + chunk_size]
        num_rows = (len(chunk) + 1) // 2
        table = doc.add_table(rows=num_rows, cols=2)
        table.style = 'Table Grid'

        for row_table in table.rows:
            trPr = row_table._tr.get_or_add_trPr()
            cantSplit = OxmlElement('w:cantSplit')
            trPr.append(cantSplit)

        for idx, img_bytes in enumerate(chunk):
            r = idx // 2
            c = idx % 2
            cell = table.cell(r, c)
            urutan = i + idx + 1

            # Teks SS FASIH X (Normal / Tidak Bold)
            p_label = cell.paragraphs[0]
            p_label.alignment = WD_ALIGN_PARAGRAPH.CENTER
            p_label.paragraph_format.space_before = Cm(0.5)
            p_label.paragraph_format.space_after = Cm(0.5)
            p_label.add_run(f"SS FASIH {urutan}")

            img_bytes.seek(0)
            p_img = cell.add_paragraph()
            p_img.alignment = WD_ALIGN_PARAGRAPH.CENTER
            p_img.paragraph_format.space_after = Cm(0.3)
            p_img.add_run().add_picture(img_bytes, width=Inches(3.1))

        doc.add_page_break()

doc.save(OUTPUT_MAIN)

# ---- Laporan Pelanggaran ----
if daftar_pelanggar:
    doc_p = Document()
    for section in doc_p.sections:
        section.page_height = Inches(11.69)
        section.page_width = Inches(8.27)
        section.top_margin = Inches(0.5)
        section.bottom_margin = Inches(0.5)
        section.left_margin = Inches(0.5)
        section.right_margin = Inches(0.5)

    p_title = doc_p.add_paragraph()
    p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_title.paragraph_format.line_spacing = 1.15
    run_title = p_title.add_run("DAFTAR PELANGGARAN (FOTO KAMERA TERDETEKSI)")
    run_title.bold = True
    run_title.font.size = Pt(14)
    doc_p.add_paragraph()

    kolom_tabel = ['NO'] + [str(label).upper() for label, _ in FIELD_MAPPINGS] + ['FILE KE-', 'ALASAN DITOLAK']
    table_p = doc_p.add_table(rows=1, cols=len(kolom_tabel))
    table_p.style = 'Table Grid'

    for idx, teks in enumerate(kolom_tabel):
        cell = table_p.rows[0].cells[idx]
        cell.text = teks
        cell.paragraphs[0].runs[0].font.bold = True

    no = 1
    for p in daftar_pelanggar:
        for bukti in p['bukti']:
            cells = table_p.add_row().cells
            cells[0].text = str(no)
            no += 1
            for i, (label, kolom) in enumerate(FIELD_MAPPINGS, start=1):
                cells[i].text = nilai_aman(p['data_row'].get(kolom, None)).upper()
            cells[-2].text = str(bukti['nomor_file'])
            cells[-1].text = str(bukti['alasan']).upper()

    doc_p.save(OUTPUT_PELANGGARAN)
    print(f"\\n[!] Laporan pelanggaran dibuat: {OUTPUT_PELANGGARAN}")
else:
    print("\\nTidak ada pelanggaran ditemukan.")

print(f"\\nSELESAI. Dokumen tersimpan di: {OUTPUT_MAIN}")
PYTHON;
    }
}
