<!DOCTYPE html>
<html lang="en">
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

        <form action="login.php" method="POST">
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

