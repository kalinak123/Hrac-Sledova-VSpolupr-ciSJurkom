<?php
session_start();
require_once 'db.php'; 

$error = "";
$success = "";

if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $success = "Registrácia úspešná! Teraz sa môžeš prihlásiť.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $nickname = trim($_POST['nickname']);
    $password = $_POST['password'];

    if (empty($email) || empty($nickname) || empty($password)) {
        $error = "Vyplňte všetky polia.";
    } else {
        $sql = "SELECT user_ID, nickname, password FROM users WHERE email = ? AND nickname = ? LIMIT 1";
        $stmt = mysqli_prepare($pripojenie, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $nickname);
        mysqli_stmt_execute($stmt);
        $vysledok = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($vysledok)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_ID'];
                $_SESSION['nickname'] = $user['nickname'];
                
                header("Location: home.php");
                exit;
            } else {
                $error = "Nesprávne heslo.";
            }
        } else {
            $error = "Používateľ s týmito údajmi neexistuje.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Secure Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index_V1.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="login-card p-4 shadow-lg">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Prihlásenie</h2>
            <p class="text-muted">Pre pozeranie statov sa prihláste.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success py-2"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="index.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
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
            <div class="d-grid mt-4">
                <button type="submit" name="submit" class="btn btn-primary btn-login">Login</button>
            </div>
        </form>
        
        <div class="text-center mt-3">
            <small>Nemáte účet? <a href="register.php" class="text-decoration-none">Registrovať sa</a></small>
        </div>
    </div>

</body>
</html>
