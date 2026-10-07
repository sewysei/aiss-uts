<?php require 'config.php';
if (!isset($_SESSION['username'])) { header('Location: index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Portal Akademik</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f5f6fa; margin:0; }
        nav { background:#1560E5; color:#fff; padding:14px 24px; display:flex; justify-content:space-between; align-items:center; }
        nav a { color:#fff; text-decoration:none; font-size:13px; margin-left:16px; }
        .container { max-width:600px; margin:32px auto; background:#fff; padding:24px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
        h2 { margin-top:0; }
        input { padding:8px; width:200px; border:1px solid #ccc; border-radius:4px; }
        button { padding:8px 14px; background:#1560E5; color:#fff; border:none; border-radius:4px; cursor:pointer; }
        table { width:100%; border-collapse:collapse; margin-top:16px; }
        th, td { text-align:left; padding:8px; border-bottom:1px solid #eee; font-size:14px; }
    </style>
</head>
<body>
    <nav>
        <strong>Portal Akademik</strong>
        <div>
            Login sebagai <?= htmlspecialchars($_SESSION['username']) ?> (<?= htmlspecialchars($_SESSION['role']) ?>)
            <?php if ($_SESSION['role'] === 'admin'): ?><a href="admin/index.php">Admin Panel</a><?php endif; ?>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <div class="container">
        <h2>Cari Nilai Mahasiswa</h2>
        <form method="GET" action="search.php">
            <input type="text" name="id" placeholder="Masukkan ID Mahasiswa (contoh: 1)">
            <button type="submit">Cari</button>
        </form>
        <p style="font-size:12px;color:#999;">Coba ID 1-5 untuk melihat data nilai.</p>
    </div>
</body>
</html>
