<?php
require_once 'excel-client.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['reg_username'], $_POST['signupPassword'])) {
    $spreadsheet = IOFactory::load(EXCEL_FILE);
    $sheet       = $spreadsheet->getActiveSheet();

    // duplicate check
    $max = $sheet->getHighestRow();
    for ($r = 2; $r <= $max; $r++) {
        if ($sheet->getCell('C' . $r)->getValue() === $_POST['reg_username']) {
            die('Username already exists.');
        }
    }

    // append row
    $nextRow = $max + 1;
    $sheet->fromArray([
        $_POST['fullName'],
        $_POST['department'],
        $_POST['reg_username'],
        $_POST['signupPassword'],
        (int)($_POST['initPoints'] ?? 0)
    ], null, 'A' . $nextRow);

    saveSheet($spreadsheet);

    echo "Registration successful! Redirecting to login…";
    echo '<meta http-equiv="refresh" content="2;url=index.html">';
    exit;
}