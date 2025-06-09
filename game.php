<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT username, balance FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>


<p>Solde : <?= $user['balance'] ?> €</p>
<a href="logout.php">Se déconnecter</a>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Jeux - Light Casino</title>
  <link rel="icon" href="images/logo.png">
  <link rel="stylesheet" href="index.css">
</head>
<!-- CSS -->
<style>

</style>


<body>

    <!-- HEADER -->
    <div class="navbar">
        <img class="logo" src="images/logo.png" alt="">
        <div class="navbarAuth">
            <a class="navbara" href="index.php">Accueil</a>
            <a class="navbara" href="logout.php">Se déconnecter</a>
        </div>

    </div>

    <div class="header">
        <h1 class="Titre">Bienvenue <span class="casinoFont"><?= htmlspecialchars($user['username']) ?></span> !</h1>
    </div>

    <div class="playerInfosContainer">
        <div class="usernameinfos">
            <h1 class="SousTitre">Nom d'utilisateur :</h1>
            <br>
            <h1 class="casinoFont"><?= htmlspecialchars($user['username']) ?></h1>
        </div>
        <div class="soldeinfos">
            <h1 class="SousTitre">Solde :</h1>
            <br>
            <h1 class="casinoFont"><?= $user['balance'] ?>c</h1>
        </div>
    </div>

    <hr class="customhr">

    <!-- LISTE JEUX -->
    <div class="gamesContainer">
        <div class="game">
            <h1 class="SousTitre">Roulette</h1>
            <a href="roulette.php">
                <img class="gameIMG" src="images/casino3.jpeg" alt="">
            </a>
        </div>
        <div class="game">
            <h1 class="SousTitre">Blackjack</h1>
            <a href="">
                <img class="gameIMG" src="images/blackjack.jpg" alt="">
            </a>
        </div>
    </div>
    <div class="gamesContainer">
        <div class="game">
            <h1 class="SousTitre">Machine à sous</h1>
            <a href="">
                <img class="gameIMG" src="images/slotMachines.jpg" alt="">
            </a>
        </div>
        <div class="game">
            <h1 class="SousTitre">Titre</h1>
            <a href="">
                <img class="gameIMG" src="images/" alt="">
            </a>
        </div>
    </div>

    <div class="footer">
        <div class="footerContainer">
        <div class="footerIMG">
            <img class="footerImage" src="images/logo.png" alt="">
        </div>
        <div class="footerTXT">
            <p class="footerText">Le propriétaire décline tout problème lié à une quelconque addiction.</p>
            <br>
            <p class="footerText">© 2025 Light Casino. Tous droits réservés.</p>
        </div>
        </div>
    </div>

</body>
</html>
