<?php
session_start();
require 'db.php';

// Récupérer le leaderboard (top 25)
$leaderboard = [];
$stmt = $pdo->query("SELECT username, balance FROM users ORDER BY balance DESC LIMIT 25");
if ($stmt) {
    $leaderboard = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil - Light Casino</title>
  <link rel="icon" href="images/logo.png">
  <link rel="stylesheet" href="index.css">
  <style>
    .leaderboard-container {
      width: 350px;
      max-width: 90vw;
      margin: 30px auto 30px auto;
      background: #222;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
      padding: 20px;
    }
    .leaderboard-title {
      color: #ffd700;
      text-align: center;
      font-size: 1.5em;
      margin-bottom: 10px;
      font-family: 'Arial Black', Arial, sans-serif;
    }
    .leaderboard-list {
      max-height: 350px;
      overflow-y: auto;
      padding: 0;
      margin: 0;
      list-style: none;
    }
    .leaderboard-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 0;
      border-bottom: 1px solid #444;
      color: #fff;
      font-family: 'Consolas', monospace;
      font-size: 1.1em;
    }
    .leaderboard-item:last-child {
      border-bottom: none;
    }
    .leaderboard-rank {
      color: #ffd700;
      font-weight: bold;
      margin-right: 10px;
      width: 28px;
      text-align: right;
      display: inline-block;
    }
    .leaderboard-username {
      flex: 1;
      margin-left: 5px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .leaderboard-balance {
      color: #7fff7f;
      font-weight: bold;
      margin-left: 10px;
      min-width: 60px;
      text-align: right;
      font-family: 'Consolas', monospace;
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <div class="navbar">
    <img class="logo" src="images/logo.png" alt="">
    <div class="navbarAuth">
      <?php if (isset($_SESSION['user_id'])): ?>
        <a class="navbara" href="game.php">Jouer</a>
        <a class="navbara" href="logout.php">Se déconnecter</a>
      <?php else: ?>
        <a class="navbara" href="login.php">Se connecter</a>
        <a class="navbara" href="register.php">Créer un compte</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="header">
    <h1 class="Titre">Bienvenue au <span class="casinoFont">Light Casino</span> !</h1>
  </div>

  <!-- LEADERBOARD -->
  <div class="leaderboard-container">
    <div class="leaderboard-title">🏆 Top 25 Joueurs</div>
    <ul class="leaderboard-list">
      <?php foreach ($leaderboard as $i => $player): ?>
        <li class="leaderboard-item">
          <span class="leaderboard-rank"><?= $i+1 ?>.</span>
          <span class="leaderboard-username"><?= htmlspecialchars($player['username']) ?></span>
          <span class="leaderboard-balance"><?= number_format($player['balance'], 0, ',', ' ') ?> €</span>
        </li>
      <?php endforeach; ?>
      <?php if (empty($leaderboard)): ?>
        <li class="leaderboard-item">Aucun joueur pour le moment.</li>
      <?php endif; ?>
    </ul>
  </div>

  <div class="infosContainer">
    <div class="infosIMG">
      <img class="infosImage" src="images/slotMachines.jpg" alt="">
    </div>
    <div class="infosTXT">
      <h1 class="infosTitle">Qu'est-ce que le Light Casino ?</h1>
      <br>
      <p class="infosDescription">Le Light Casino est un casino en ligne où vous pouvez miser de l'argent fictif afin de devenir
        le meilleur parieur de la plateforme. Vous pouvez jouer à différents jeux de casino tels que les machines à sous, le 
        blackjack, la roulette et bien plus encore.</p>
      <br>
      <p class="infosDescription">Inscrivez-vous dès maintenant pour profiter pleinement du casino et commencer à jouer !</p>
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