<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit;
}
require_once 'excel-client.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$sheet = loadSheet();
$max   = $sheet->getHighestRow();

// current user
$myRow = null;
for ($r = 2; $r <= $max; $r++) {
    if ($sheet->getCell('C' . $r)->getValue() === $_SESSION['username']) {
        $myRow = $r;
        break;
    }
}
$fullName   = $myRow ? $sheet->getCell('A' . $myRow)->getValue() : $_SESSION['username'];
$userPoints = $myRow ? (int)$sheet->getCell('E' . $myRow)->getValue() : 0;

// top-3 leaderboard
$leaderboard = [];
for ($r = 2; $r <= $max; $r++) {
    $leaderboard[] = [
        'name'   => $sheet->getCell('A' . $r)->getValue(),
        'points' => (int)$sheet->getCell('E' . $r)->getValue()
    ];
}
usort($leaderboard, fn($a, $b) => $b['points'] - $a['points']);
$top3 = array_slice($leaderboard, 0, 3);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ScholarsNook — Dashboard</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        .hero{
            margin: 0 -20px 20px -20px;
            padding: 60px 20px;
            text-align: center;
            background: linear-gradient(135deg, var(--ths-green) 0%, var(--ths-violet) 100%);
            color: #fff;
        }
        .hero h1{ margin: 0; font-size: 2.4rem; }
        .hero p{ margin: 8px 0 0 0; opacity: .9; }
        .leaderboard{ margin: 12px 0; padding-left: 24px; font-size: 1.1rem; }
        .leaderboard li{ margin: 8px 0; color: var(--ths-violet); }
        .leaderboard .points{ font-weight: 700; color: var(--ths-green); }
    </style>
</head>
<body>
<main class="container">
    <header class="brand">
        <h1>ScholarsNook</h1>
        <p class="tag">Track your points • Flex your wins</p>
    </header>

    <section class="hero">
        <h1>Welcome, <?=htmlspecialchars($fullName) ?>!</h1>
        <p>Your current balance</p>
        <div class="points" style="font-size: 3rem; margin-top: 12px;">
            <?=number_format($userPoints) ?> pts
        </div>
    </section>

    <section class="card">
        <h2>Leaderboard – Top 3</h2>
        <ol class="leaderboard">
        <?php foreach ($top3 as $user): ?>
            <li><?=htmlspecialchars($user['name']) ?> – <span class="points"><?=number_format($user['points']) ?> pts</span></li>
        <?php endforeach; ?>
        </ol>
        <p class="muted">More features coming soon…</p>
        <div class="actions">
            <a href="logout.php" class="alt-btn" style="background: var(--muted);">Log out</a>
        </div>
    </section>
</main>
</body>
</html>