<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Secure Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index_V1.css">
</head>
<body >
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nickname = $_POST['nickname'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $error = '';
    if (empty($nickname) || empty($password) || empty($confirm_password)) {
        $error = 'Vyplňte všetky polia.';
    } elseif ($password !== $confirm_password) {
        $error = 'Heslá sa nezhodujú.';
    } elseif (strlen($password) < 6) {
        $error = 'Heslo musí mať aspoň 6 znakov.';
    } else {
        $conn = new mysqli('localhost', 'root', '', 'gametracker');
        if ($conn->connect_error) {
            $error = 'Chyba pripojenia k databáze.';
        } else {
            $stmt = $conn->prepare('SELECT user_ID FROM users WHERE nickname = ?');
            $stmt->bind_param('s', $nickname);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $error = 'Tento nickname už existuje.';
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                // Insert new user
                $stmt = $conn->prepare('INSERT INTO users (nickname, password) VALUES (?, ?)');
                $stmt->bind_param('ss', $nickname, $hashed_password);
                if ($stmt->execute()) {
                    // Success, redirect to login
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Registrácia zlyhala.';
                }
            }
            $stmt->close();
            $conn->close();
        }
    }
}
?>
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="login-card p-4 shadow-lg">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Registrácia</h2>
            <p class="text-muted">Vytvorte si nový účet.</p>
        </div>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control custom-input" placeholder="name@example.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nickname</label>
                <input type="text" name="nickname" class="form-control custom-input" placeholder="robofico" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Heslo</label>
                <input type="password" name="password" class="form-control custom-input" placeholder="••••••••" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Potvrďte heslo</label>
                <input type="password" name="confirm_password" class="form-control custom-input" placeholder="••••••••" required>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-login">Registrovať</button>
            </div>
        </form>
        
        <div class="text-center mt-3">
            <small>Máte už účet? <a href="index.php" class="text-decoration-none">Prihlásiť sa</a></small>
        </div>
    </div>
</div>
</body>
</html>