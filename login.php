<?php
session_start();
require_once 'excel-client.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['login_username'], $_POST['login_password'])) {
    $sheet = loadSheet();
    $max   = $sheet->getHighestRow();

    for ($r = 2; $r <= $max; $r++) {                // row 1 = headers
        $user = $sheet->getCell('C' . $r)->getValue();
        $pass = $sheet->getCell('D' . $r)->getValue();
        if ($user === $_POST['login_username'] && $pass === $_POST['login_password']) {
            $_SESSION['username'] = $user;
            header('Location: dashboard.php');
            exit;
        }
    }
    echo "Invalid username or password.";
}