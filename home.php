<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}


require_once 'db.php'; 

$current_user_id = $_SESSION['user_id'];


$sql = "SELECT u.nickname, u.balance, s.kills, s.deaths, s.wins, s.headshots 
        FROM users u 
        LEFT JOIN player_stats s ON u.user_ID = s.user_id 
        WHERE u.user_ID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();


$kills = $user_data['kills'] ?? 0;
$deaths = $user_data['deaths'] ?? 0;
$wins = $user_data['wins'] ?? 0;
$headshots = $user_data['headshots'] ?? 0;

$kd_ratio = ($deaths > 0) ? round($kills / $deaths, 2) : $kills;
$hs_percent = ($kills > 0) ? round(($headshots / $kills) * 100, 1) : 0;
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | GameTracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index_V1.css"> 
    <style>
        .stat-card {
            background: #21262D;
            border: 1px solid #30363d;
            border-top: 4px solid #F4511E;
            border-radius: 4px;
            padding: 20px;
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 0 15px rgba(244, 81, 30, 0.2); }
        .navbar { background: rgba(13, 17, 23, 0.9); border-bottom: 1px solid #30363d; backdrop-filter: blur(10px); }
        .table-dark { background-color: transparent !important; }
        .accent-color { color: #F4511E; }
        .btn-outline-custom { border: 1px solid #F4511E; color: #F4511E; background: transparent; }
        .btn-outline-custom:hover { background: #F4511E; color: white; box-shadow: 0 0 10px rgba(244, 81, 30, 0.4); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">GAME<span class="accent-color">TRACKER</span></a>
        <div class="ms-auto d-flex align-items-center">
            <span class="me-3 text-muted">Vitaj, <strong class="text-white"><?php echo htmlspecialchars($user_data['nickname']); ?></strong></span>
            <span class="badge me-3 p-2" style="background-color: #F4511E; color: white;">💰 <?php echo $user_data['balance'] ?? 1000; ?> pts</span>
            <a href="index.php" class="btn btn-outline-danger btn-sm">Odhlásiť sa</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Tvoje <span class="accent-color">Štatistiky</span></h2>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="stat-card text-center">
                <p class="text-muted mb-1">K/D Ratio</p>
                <h3 class="fw-bold m-0 text-white"><?php echo $kd_ratio; ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <p class="text-muted mb-1">Výhry</p>
                <h3 class="fw-bold m-0 text-white"><?php echo $wins; ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <p class="text-muted mb-1">Kills</p>
                <h3 class="fw-bold m-0 text-white"><?php echo $kills; ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <p class="text-muted mb-1">Headshot %</p>
                <h3 class="fw-bold m-0 accent-color"><?php echo $hs_percent; ?>%</h3>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-8">
            <div class="login-card p-4 shadow-lg mx-auto" style="max-width: 100%;">
                <h4 class="mb-4 fw-bold text-white">Top Hráči</h4>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th>#</th>
                                <th>Hráč</th>
                                <th>Kills</th>
                                <th>Wins</th>
                                <th>K/D</th>
                                <th>HS %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rank = 1;
                            $leaderboard = $conn->query("SELECT u.nickname, s.kills, s.wins, s.deaths, s.headshots FROM users u JOIN player_stats s ON u.user_ID = s.user_id ORDER BY s.kills DESC LIMIT 5");
                            while($row = $leaderboard->fetch_assoc()):
                                $row_kills = $row['kills'] ?? 0;
                                $row_kd = ($row['deaths'] > 0) ? round($row_kills / $row['deaths'], 2) : $row_kills;
                                $row_hs = ($row_kills > 0) ? round(($row['headshots'] / $row_kills) * 100, 1) : 0;
                            ?>
                            <tr>
                                <td><span class="text-muted"><?php echo $rank++; ?></span></td>
                                <td class="fw-bold text-white"><?php echo htmlspecialchars($row['nickname']); ?></td>
                                <td><?php echo $row_kills; ?></td>
                                <td><?php echo $row['wins']; ?></td>
                                <td class="accent-color fw-bold"><?php echo $row_kd; ?></td>
                                <td class="text-warning small"><?php echo $row_hs; ?>%</td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="login-card p-4 shadow-lg mx-auto" style="max-width: 100%;">
                <h4 class="mb-4 fw-bold text-white">Akcie</h4>
                <div class="d-grid gap-3">
                    <?php 

                    if(isset($_SESSION['role']) && $_SESSION['role'] == 1): 
                    ?>
                    <a href="admin.php" class="btn btn-warning text-start p-3 text-dark fw-bold" style="border: none;">🛠 Admin Panel</a>
                    <?php else: ?>
                    <p class="text-muted small">Žiadne dostupné akcie.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>  
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
