<?php

namespace App\Helpers;

use ZipArchive;

/**
 * SimpleXlsx - Lightweight xlsx generator menggunakan ZipArchive native PHP.
 * Dibuat untuk menghindari bug ZipStream (CRC float) dan PhpSpreadsheet (Epoch overflow)
 * pada PHP 8.2 32-bit.
 */
class SimpleXlsx
{
    private array $sheets = [];
    private array $sharedStrings = [];
    private int $sharedStringIndex = 0;

    /**
     * Tambah sheet baru.
     */
    public function addSheet(string $name, array $headers, array $rows, string $headerBgColor = '059669'): self
    {
        $this->sheets[] = [
            'name' => $name,
            'headers' => $headers,
            'rows' => $rows,
            'headerBgColor' => $headerBgColor,
        ];
        return $this;
    }

    /**
     * Simpan ke file.
     */
    public function save(string $filePath): void
    {
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Reset shared strings
        $this->sharedStrings = [];
        $this->sharedStringIndex = 0;

        $zip = new ZipArchive();
        if ($zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Cannot create xlsx file: {$filePath}");
        }

        $zip->addFromString('[Content_Types].xml', $this->buildContentTypes());
        $zip->addFromString('_rels/.rels', $this->buildRels());
        $zip->addFromString('xl/workbook.xml', $this->buildWorkbook());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->buildWorkbookRels());
        $zip->addFromString('xl/styles.xml', $this->buildStyles());

        foreach ($this->sheets as $index => $sheet) {
            $zip->addFromString("xl/worksheets/sheet" . ($index + 1) . ".xml", $this->buildSheet($sheet));
        }

        $zip->addFromString('xl/sharedStrings.xml', $this->buildSharedStrings());

        $zip->close();
    }

    private function getSharedStringIndex(string $value): int
    {
        if (!isset($this->sharedStrings[$value])) {
            $this->sharedStrings[$value] = $this->sharedStringIndex++;
        }
        return $this->sharedStrings[$value];
    }

    private function colLetter(int $col): string
    {
        $letter = '';
        while ($col >= 0) {
            $letter = chr(65 + ($col % 26)) . $letter;
            $col = intdiv($col, 26) - 1;
        }
        return $letter;
    }

    private function xmlEscape(string $str): string
    {
        return htmlspecialchars($str, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function buildContentTypes(): string
    {
        $sheets = '';
        foreach ($this->sheets as $i => $_) {
            $sheets .= '<Override PartName="/xl/worksheets/sheet' . ($i + 1) . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . $sheets
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>'
            . '</Types>';
    }

    private function buildRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    private function buildWorkbook(): string
    {
        $sheets = '';
        foreach ($this->sheets as $i => $sheet) {
            $sheets .= '<sheet name="' . $this->xmlEscape($sheet['name']) . '" sheetId="' . ($i + 1) . '" r:id="rId' . ($i + 1) . '"/>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets>' . $sheets . '</sheets>'
            . '</workbook>';
    }

    private function buildWorkbookRels(): string
    {
        $rels = '';
        foreach ($this->sheets as $i => $_) {
            $rels .= '<Relationship Id="rId' . ($i + 1) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . ($i + 1) . '.xml"/>';
        }
        $rels .= '<Relationship Id="rId' . (count($this->sheets) + 1) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>';
        $rels .= '<Relationship Id="rId' . (count($this->sheets) + 2) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . $rels
            . '</Relationships>';
    }

    private function buildStyles(): string
    {
        // Kumpulkan semua warna header unik
        $colors = [];
        foreach ($this->sheets as $sheet) {
            $c = strtoupper($sheet['headerBgColor']);
            if (!in_array($c, $colors)) {
                $colors[] = $c;
            }
        }

        $fills = '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>';

        foreach ($colors as $color) {
            $fills .= '<fill><patternFill patternType="solid"><fgColor rgb="FF' . $color . '"/></patternFill></fill>';
        }

        // Buat cellXfs: index 0 = normal, index 1+ = header style per warna
        $cellXfs = '<xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>';
        foreach ($colors as $i => $_) {
            $cellXfs .= '<xf numFmtId="0" fontId="1" fillId="' . ($i + 2) . '" borderId="0" applyFont="1" applyFill="1"/>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="2">'
            . '<font><sz val="11"/><name val="Calibri"/></font>'
            . '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            . '</fonts>'
            . '<fills count="' . (2 + count($colors)) . '">' . $fills . '</fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="' . (1 + count($colors)) . '">' . $cellXfs . '</cellXfs>'
            . '</styleSheet>';
    }

    private function buildSheet(array $sheet): string
    {
        // Tentukan style index untuk header berdasarkan warna
        $colors = [];
        foreach ($this->sheets as $s) {
            $c = strtoupper($s['headerBgColor']);
            if (!in_array($c, $colors)) {
                $colors[] = $c;
            }
        }
        $headerStyleIndex = array_search(strtoupper($sheet['headerBgColor']), $colors) + 1;

        $rows = '';

        // Header row
        $cells = '';
        foreach ($sheet['headers'] as $colIndex => $header) {
            $ref = $this->colLetter($colIndex) . '1';
            $si = $this->getSharedStringIndex((string) $header);
            $cells .= '<c r="' . $ref . '" t="s" s="' . $headerStyleIndex . '"><v>' . $si . '</v></c>';
        }
        $rows .= '<row r="1">' . $cells . '</row>';

        // Data rows
        foreach ($sheet['rows'] as $rowIndex => $row) {
            $rowNum = $rowIndex + 2;
            $cells = '';
            foreach ($row as $colIndex => $value) {
                $ref = $this->colLetter($colIndex) . $rowNum;
                if (is_int($value) || is_float($value)) {
                    $cells .= '<c r="' . $ref . '"><v>' . $value . '</v></c>';
                } else {
                    $si = $this->getSharedStringIndex((string) $value);
                    $cells .= '<c r="' . $ref . '" t="s"><v>' . $si . '</v></c>';
                }
            }
            $rows .= '<row r="' . $rowNum . '">' . $cells . '</row>';
        }

        $colCount = max(count($sheet['headers']), ...array_map('count', $sheet['rows'] ?: [[]]));
        $lastCol = $this->colLetter($colCount - 1);
        $lastRow = count($sheet['rows']) + 1;

        $cols = '';
        for ($i = 0; $i < $colCount; $i++) {
            $width = max(12, mb_strlen($sheet['headers'][$i] ?? '') * 1.3);
            $cols .= '<col min="' . ($i + 1) . '" max="' . ($i + 1) . '" width="' . round($width, 1) . '" bestFit="1" customWidth="1"/>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<dimension ref="A1:' . $lastCol . $lastRow . '"/>'
            . '<cols>' . $cols . '</cols>'
            . '<sheetData>' . $rows . '</sheetData>'
            . '</worksheet>';
    }

    private function buildSharedStrings(): string
    {
        $strings = '';
        // Sort by index to ensure correct order
        $sorted = $this->sharedStrings;
        asort($sorted);
        foreach ($sorted as $value => $index) {
            $strings .= '<si><t>' . $this->xmlEscape((string) $value) . '</t></si>';
        }

        $count = count($this->sharedStrings);
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="' . $count . '" uniqueCount="' . $count . '">'
            . $strings
            . '</sst>';
    }
}
