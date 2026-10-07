<?php require 'config.php';
$security = getenv('SECURITY_LEVEL') ?: 'low'; // 'low' = rentan (before hardening), 'high' = aman (after hardening)
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $user = null;

    if ($security === 'low') {
        // SENGAJA RENTAN: string concatenation langsung -> SQL Injection
        // Contoh payload: username = admin' OR '1'='1
        $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
        }
    } else {
        // AMAN: prepared statement - parameter tidak lagi disambung langsung ke query,
        // sehingga payload SQL Injection seperti admin' OR '1'='1 tidak lagi berfungsi
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    }

    if ($user) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Portal Akademik - Login</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f0f2f5; display:flex; justify-content:center; align-items:center; height:100vh; margin:0; }
        .box { background:#fff; padding:32px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1); width:320px; }
        h1 { font-size:20px; color:#1560E5; margin-bottom:4px; }
        p.sub { color:#666; font-size:13px; margin-bottom:20px; }
        input { width:100%; padding:10px; margin-bottom:12px; border:1px solid #ccc; border-radius:4px; box-sizing:border-box; }
        button { width:100%; padding:10px; background:#1560E5; color:#fff; border:none; border-radius:4px; cursor:pointer; }
        .error { color:#c0392b; font-size:13px; margin-bottom:12px; }
        .level { font-size:11px; color:#999; margin-top:16px; text-align:center; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Portal Akademik</h1>
        <p class="sub">Silakan login untuk melanjutkan</p>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="level">Security level: <?= htmlspecialchars($security) ?></div>
    </div>
</body>
</html>
