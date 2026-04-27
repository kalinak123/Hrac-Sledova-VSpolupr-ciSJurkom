<?php
require_once 'db.php'; 

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nickname = trim($_POST['nickname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($nickname) || empty($email) || empty($password)) {
        $error = 'Všetky polia sú povinné.';
    } elseif ($password !== $confirm_password) {
        $error = 'Heslá sa nezhodujú.';
    } elseif (strlen($password) < 6) {
        $error = 'Heslo musí mať aspoň 6 znakov.';
    } else {

        $sql = "SELECT user_ID FROM users WHERE nickname = ? OR email = ? LIMIT 1";
        $stmt = mysqli_prepare($pripojenie, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $nickname, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Tento nickname alebo e-mail už niekto používa.';
            mysqli_stmt_close($stmt);
        } else {
            mysqli_stmt_close($stmt);

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql_insert = "INSERT INTO users (nickname, email, password) VALUES (?, ?, ?)";
            $stmt_insert = mysqli_prepare($pripojenie, $sql_insert);
            mysqli_stmt_bind_param($stmt_insert, "sss", $nickname, $email, $hashed_password);

            if (mysqli_stmt_execute($stmt_insert)) {

                header('Location: index.php?status=success');
                exit;
            } else {
                $error = 'Chyba pri zápise, skúste to znovu.';
            }
            mysqli_stmt_close($stmt_insert);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrácia | GameTracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index_V1.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="login-card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Registrácia</h2>
            <p class="text-muted">Vytvor si účet pre GameTracker</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" placeholder="tvoj@email.sk" required 
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Nickname</label>
                <input type="text" name="nickname" class="form-control" placeholder="Hrac123" required
                       value="<?php echo isset($nickname) ? htmlspecialchars($nickname) : ''; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Heslo</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Potvrďte heslo</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Vytvoriť účet</button>
            </div>
        </form>
        
        <div class="text-center mt-3">
            <small>Už si členom? <a href="index.php" class="text-decoration-none">Prihlásiť sa</a></small>
        </div>
    </div>

</body>
</html>
