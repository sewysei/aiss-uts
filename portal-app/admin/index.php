<?php require '../config.php';
$security = getenv('SECURITY_LEVEL') ?: 'low';

if (!isset($_SESSION['username'])) { header('Location: ../index.php'); exit; }

// SENGAJA RENTAN pada security level 'low': tidak ada pengecekan role sama sekali,
// siapa pun yang sudah login (termasuk mahasiswa biasa) bisa mengakses admin panel.
// Pada level 'high': enforcement role dilakukan di sini.
if ($security !== 'low') {
    if (($_SESSION['role'] ?? '') !== 'admin') {
        http_response_code(403);
        die('403 Forbidden - Anda tidak memiliki akses ke halaman ini.');
    }
}

$result = $conn->query("SELECT m.nim, m.nama, n.mata_kuliah, n.nilai, n.id as nilai_id
                         FROM mahasiswa m JOIN nilai n ON n.mahasiswa_id = m.id ORDER BY m.id");
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Admin Panel - Portal Akademik</title>
<style>
    body { font-family: Arial, sans-serif; background:#f5f6fa; }
    nav { background:#c0392b; color:#fff; padding:14px 24px; }
    .container { max-width:700px; margin:32px auto; background:#fff; padding:24px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
    table { width:100%; border-collapse:collapse; margin-top:16px; }
    th, td { text-align:left; padding:8px; border-bottom:1px solid #eee; font-size:14px; }
    a { color:#1560E5; text-decoration:none; }
</style></head>
<body>
    <nav><strong>ADMIN PANEL</strong> — Portal Akademik</nav>
    <div class="container">
        <p><a href="../dashboard.php">&larr; Kembali ke Dashboard</a></p>
        <h2>Seluruh Data Nilai Mahasiswa</h2>
        <p style="font-size:12px;color:#999;">Halaman ini seharusnya hanya bisa diakses role admin. Security level saat ini: <?= htmlspecialchars($security) ?></p>
        <table>
            <tr><th>NIM</th><th>Nama</th><th>Mata Kuliah</th><th>Nilai</th></tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nim']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['mata_kuliah']) ?></td>
                <td><?= htmlspecialchars($row['nilai']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
