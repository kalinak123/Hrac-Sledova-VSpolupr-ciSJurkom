<?php
session_start();
require_once 'db.php'; 


if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: home.php");
    exit;
}

$msg = "";
$msg_class = "";


if (isset($_POST['update_stats'])) {
    $u_id = intval($_POST['user_id']);
    $kills = intval($_POST['kills']);
    $deaths = intval($_POST['deaths']);
    $wins = intval($_POST['wins']);
    $headshots = intval($_POST['headshots']);


    $stmt = $conn->prepare("UPDATE player_stats SET kills = ?, deaths = ?, wins = ?, headshots = ? WHERE user_id = ?");
    $stmt->bind_param("iiiii", $kills, $deaths, $wins, $headshots, $u_id);
    $stmt->execute();


    if ($stmt->affected_rows === 0) {
        $stmt_insert = $conn->prepare("INSERT INTO player_stats (user_id, kills, deaths, wins, headshots) VALUES (?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("iiiii", $u_id, $kills, $deaths, $wins, $headshots);
        $stmt_insert->execute();
    }

    $msg = "Štatistiky pre používateľa ID $u_id boli úspešne uložené.";
    $msg_class = "alert-success";
}


$query = "SELECT u.user_ID, u.nickname, u.email, s.kills, s.deaths, s.wins, s.headshots 
          FROM users u 
          LEFT JOIN player_stats s ON u.user_ID = s.user_id 
          ORDER BY u.user_ID DESC";
$all_players = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | GameTracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index_V1.css">
    <style>
        .admin-card { 
            background: #21262D; 
            border: 1px solid #30363d;
            border-top: 4px solid #ffc107; 
            border-radius: 4px; 
            padding: 25px; 
        }
        .table { color: white; border-color: #333; }
        .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.02); }
        input.form-control-sm { 
            background: #0d1117; 
            color: white; 
            border: 1px solid #30363d; 
            width: 75px; 
            text-align: center;
        }
        input.form-control-sm:focus {
            background: #0d1117;
            color: white;
            border-color: #ffc107; 
            box-shadow: 0 0 10px rgba(255, 193, 7, 0.2);
        }
        .navbar { background: rgba(13, 17, 23, 0.9); border-bottom: 1px solid #30363d; backdrop-filter: blur(10px); }
        .accent-color { color: #F4511E; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">GAME<span class="accent-color">TRACKER</span> <span class="badge bg-warning text-dark ms-2" style="font-size: 0.5em;">ADMIN</span></a>
        <div class="ms-auto">
            <a href="home.php" class="btn btn-outline-light btn-sm me-2">Späť na Dashboard</a>
            <a href="index.php" class="btn btn-danger btn-sm">Odhlásiť sa</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Správa <span class="text-warning">používateľov</span></h2>
            <p class="text-muted">Tu môžeš editovať štatistiky všetkých registrovaných hráčov.</p>
        </div>
    </div>

    <?php if ($msg): ?>
        <div class="alert <?php echo $msg_class; ?> alert-dismissible fade show bg-dark text-white border-success" role="alert">
            <?php echo $msg; ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="admin-card shadow-lg mx-auto" style="max-width: 100%;">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead class="text-muted small text-uppercase">
                    <tr>
                        <th>Hráč (Nickname)</th>
                        <th>Kills</th>
                        <th>Deaths</th>
                        <th>Wins</th>
                        <th>Headshots</th>
                        <th>HS %</th>
                        <th class="text-center">Akcia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($all_players->num_rows > 0): ?>
                        <?php while($player = $all_players->fetch_assoc()): 
                            $k = $player['kills'] ?? 0;
                            $hs = $player['headshots'] ?? 0;
                            $hs_perc = ($k > 0) ? round(($hs / $k) * 100, 1) : 0;
                        ?>
                        <tr>
                            <form method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $player['user_ID']; ?>">
                                <td>
                                    <div class="fw-bold text-white"><?php echo htmlspecialchars($player['nickname']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($player['email']); ?></small>
                                </td>
                                <td>
                                    <input type="number" name="kills" class="form-control form-control-sm" value="<?php echo $k; ?>">
                                </td>
                                <td>
                                    <input type="number" name="deaths" class="form-control form-control-sm" value="<?php echo $player['deaths'] ?? 0; ?>">
                                </td>
                                <td>
                                    <input type="number" name="wins" class="form-control form-control-sm" value="<?php echo $player['wins'] ?? 0; ?>">
                                </td>
                                <td>
                                    <input type="number" name="headshots" class="form-control form-control-sm" value="<?php echo $hs; ?>">
                                </td>
                                <td class="text-warning fw-bold small"><?php echo $hs_perc; ?>%</td>
                                <td class="text-center">
                                    <button type="submit" name="update_stats" class="btn btn-warning btn-sm px-3 fw-bold text-dark border-0">Uložiť</button>
                                </td>
                            </form>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Nenašli sa žiadni používatelia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
