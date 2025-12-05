<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

const EXCEL_FILE = __DIR__ . '/scholarsnook.xlsx';

function loadSheet(): \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
{
    return IOFactory::load(EXCEL_FILE)->getActiveSheet();
}

function saveSheet(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet): void
{
    IOFactory::createWriter($spreadsheet, 'Xlsx')->save(EXCEL_FILE);
}