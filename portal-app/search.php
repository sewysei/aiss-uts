<?php require 'config.php';
if (!isset($_SESSION['username'])) { header('Location: index.php'); exit; }

$security = getenv('SECURITY_LEVEL') ?: 'low';
$id = $_GET['id'] ?? '';
$rows = [];
$error = '';

if ($id !== '') {
    if ($security === 'low') {
        // SENGAJA RENTAN: parameter langsung disambung ke query -> SQL Injection
        // Contoh payload: id = 1 UNION SELECT username, password, 1, 1 FROM admin
        $sql = "SELECT m.nim, m.nama, n.mata_kuliah, n.nilai FROM mahasiswa m
                JOIN nilai n ON n.mahasiswa_id = m.id WHERE m.id = $id";
        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) { $rows[] = $row; }
        } else {
            $error = $conn->error;
        }
    } else {
        // AMAN: prepared statement
        $stmt = $conn->prepare("SELECT m.nim, m.nama, n.mata_kuliah, n.nilai FROM mahasiswa m
                JOIN nilai n ON n.mahasiswa_id = m.id WHERE m.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) { $rows[] = $row; }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Hasil Pencarian - Portal Akademik</title>
<style>
    body { font-family: Arial, sans-serif; background:#f5f6fa; }
    .container { max-width:600px; margin:32px auto; background:#fff; padding:24px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
    table { width:100%; border-collapse:collapse; margin-top:16px; }
    th, td { text-align:left; padding:8px; border-bottom:1px solid #eee; font-size:14px; }
    a { color:#1560E5; text-decoration:none; }
    .error { color:#c0392b; font-size:13px; }
</style></head>
<body>
    <div class="container">
        <p><a href="dashboard.php">&larr; Kembali ke Dashboard</a></p>
        <h2>Hasil Pencarian</h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <table>
            <tr><th>NIM</th><th>Nama</th><th>Mata Kuliah</th><th>Nilai</th></tr>
            <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['nim']) ?></td>
                <td><?= htmlspecialchars($r['nama']) ?></td>
                <td><?= htmlspecialchars($r['mata_kuliah']) ?></td>
                <td><?= htmlspecialchars($r['nilai']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
