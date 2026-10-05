<?php
session_start();

if (
    !isset($_SESSION["autenticado"]) ||
    $_SESSION["autenticado"] !== true
) {
    header("Location: sesion.html");
    exit;
}

require_once '../Config/Conexion.php';
$database = new Database();
$db = $database->getConnection();

try {
    $stmt = $db->query("SELECT id_deporte, nombre FROM deportes");
    $deportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $deportes = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SportX - The Sports Platform for You</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../Public/CSS/oficial.css">

</head>


<body>

<div class="bg-overlay"></div>
<!-- HEADER -->

<header>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">

<div class="container">


<div class="navbar-brand d-flex align-items-center" >

<img src="../Public/img/IMG-20260529-WA0008(2).jpg"
alt="Logo SportX"
class="logoimg"
height="70">

</div>

<a href="intusuario.php" class="user-profile d-flex align-items-center text-decoration-none">
    <img src="../Public/img/fotoperfil.jpg" id="avatar-inicio" class="rounded-circle user-avatar me-2" alt="" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #FDB913;">
    <div class="user-info text-white me-3">
        <span id ="Cartel2"class="Cartel">SportX</span>
        <small id="user-sports-inicio" class="d-block text-white-50">Welcome back</small>
    </div>
    <span class="btn btn-sm" style="background: #FDB913; color: #0D2860; font-weight: bold; border-radius: 8px;">
        <i class="fa-solid fa-user me-1" ></i> View Profile
    </span>
</a>
</nav>

</header>


<main class="container my-5">


<!-- ABOUT SPORTX -->


<!-- EXPLORE SPORTS -->


<section id="deportes">

<h2 class="fw-bold mb-4" style="color: #FDB913;">
Explore Our Sports
</h2>


            <div class="row g-4">
                <?php foreach ($deportes as $deporte):
                    $img_map = [
                        'Soccer' => 'img futbol.jpg',
                        'Volleyball' => 'voleibol img.jpg',
                        'Basketball' => 'basketball img.jpg',
                        'Ballet' => 'Chopiniana_Baku.jpg',
                        'Olympic Wrestling' => 'LUCHA-OLIMPICA.jpg'
                    ];
                    $img = isset($img_map[$deporte['nombre']]) ? $img_map[$deporte['nombre']] : 'default.jpg';
                ?>
                    <div class="col-md-4 col-lg">
                        <a href="deporte.php?id=<?php echo $deporte['id_deporte']; ?>" class="gallery-card">
                            <img src="../Public/img/<?php echo $img; ?>" alt="<?php echo htmlspecialchars($deporte['nombre']); ?>">
                            <div class="gallery-overlay">
                                <h3><?php echo htmlspecialchars($deporte['nombre']); ?></h3>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

</section>

<!-- FEATURED ACTIVITIES -->

<section class="mb-5">
     <div class="sportx-banner">

   Discover. Train. Push Your Limits.

</div>
<div class="d-flex justify-content-between align-items-center mb-4">

<h2 class="fw-bold" style="color: #FDB913;" >
Featured Activities
</h2>


</div>


<div class="row g-4">

<div class="col-md-4">

<div class="activity-card p-4">

<i class="fas fa-running"></i>

<h4>
Speed Training
</h4>

<p>
Improve Your Speed and Agility.
</p>

</div>

</div>


<div class="col-md-4">

<div class="activity-card p-4">

<i class="fas fa-road"></i>

<h4>
Long-Distance Running
</h4>

<p>
Improve Your Cardiovascular Endurance.
</p>

</div>

</div>


<div class="col-md-4">

<div class="activity-card p-4">

<i class="fas fa-dumbbell"></i>

<h4>
Strength Training
</h4>

<p>
Improve Your Muscle Strength.
</p>

</div>

</div>


<div class="col-md-4">

<div class="activity-card p-4">

<i class="fas fa-heartbeat"></i>

<h4>
Endurance Training
</h4>
<p>
Increase Your Physical Capacity and Performance.
</p>

</div>

</div>


<div class="col-md-4">

<div class="activity-card p-4">

<i class="fas fa-futbol"></i>

<h4>
Sports Training
</h4>

<p>
Develop Your Athletic Skills.
</p>

</div>

</div>


<div class="col-md-4">

<div class="activity-card p-4">

<i class="fas fa-medal"></i>

<h4>
Physical Conditioning
</h4>

<p>
Improve Your Performance and Discipline.
</p>

</div>

</div>

</div>

</section>


</main>


<!-- FOOTER -->


<footer class="py-4">

<div class="container">

<div class="row align-items-center">

<div class="col-md-4 text-center">

<p class="mb-0">

© 2026 SportX. All Rights Reserved.

</p>

</div>


<div class="col-md-4 text-center text-md-end social-icons">

<a href="#">
<i class="fab fa-facebook"></i>
</a>

<a href="#">
<i class="fab fa-instagram"></i>
</a>

<a href="#">
<i class="fab fa-twitter"></i>
</a>

</div>

</div>

</div>

</footer>

<script src="JS/inicio.js"></script>
</body>

</html>