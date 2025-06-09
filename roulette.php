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

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $betColor = $_POST['color'] ?? '';
    $betAmount = intval($_POST['amount'] ?? 0);

    if ($betAmount > 0 && ($betColor === 'red' || $betColor === 'black')) {
        if ($betAmount > $user['balance']) {
            $message = "Solde insuffisant.";
        } else {
            // On laisse le JS gérer le spin, puis on envoie le résultat via AJAX
            // Ici, on ne fait rien, le JS s'occupe de tout
        }
    } else {
        $message = "Mise invalide.";
    }
}
?>

<p class="casinoFont">Nom d'utilisateur : <?= htmlspecialchars($user['username']) ?></p>
<p>Solde : <span id="balance"><?= $user['balance'] ?></span> €</p>
<a href="game.php">retour</a>
<?php if ($message): ?>
    <p style="color:red"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roulette - Light Casino</title>
    <style>
        #roulette { width: 400px; transition: transform 4s cubic-bezier(0.33, 1, 0.68, 1); text_align: center; }
        .casinoFont { font-weight: bold; }
        #arrow {
            width: 0; 
            height: 0; 
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 30px solid #e74c3c; /* flèche vers le bas */
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 50px; /* ajuste selon ta roue */
            z-index: 10;
        }
        body { position: relative; }
        #roulette {
            width: 400px;
            transition: transform 4s cubic-bezier(0.33, 1, 0.68, 1);
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <img id="roulette" src="images/rouletteTexture.png" alt="">
    <div id="arrow"></div>
    <br>
    <form id="betForm" method="post" onsubmit="return false;">
        <label>Miser sur :
            <select name="color" id="color">
                <option value="red">Rouge</option>
                <option value="black">Noir</option>
                <option value="green">Vert</option>
            </select>
        </label>
        <label>Montant :
            <input type="number" name="amount" id="amount" min="1" required>
        </label>
        <button type="submit" id="betBtn">Miser & Tourner</button>
    </form>
    <p id="result"></p>

<script>
// Tableau des numéros et couleurs (roulette européenne)
const numbers = [
    {n:0, c:'green'},
    {n:32, c:'red'}, {n:15, c:'black'}, {n:19, c:'red'}, {n:4, c:'black'},
    {n:21, c:'red'}, {n:2, c:'black'}, {n:25, c:'red'}, {n:17, c:'black'},
    {n:34, c:'red'}, {n:6, c:'black'}, {n:27, c:'red'}, {n:13, c:'black'},
    {n:36, c:'red'}, {n:11, c:'black'}, {n:30, c:'red'}, {n:8, c:'black'},
    {n:23, c:'red'}, {n:10, c:'black'}, {n:5, c:'red'}, {n:24, c:'black'},
    {n:16, c:'red'}, {n:33, c:'black'}, {n:1, c:'red'}, {n:20, c:'black'},
    {n:14, c:'red'}, {n:31, c:'black'}, {n:9, c:'red'}, {n:22, c:'black'},
    {n:18, c:'red'}, {n:29, c:'black'}, {n:7, c:'red'}, {n:28, c:'black'},
    {n:12, c:'red'}, {n:35, c:'black'}, {n:3, c:'red'}, {n:26, c:'black'}
];
let spinning = false;
let lastRotation = 0;

document.getElementById('betForm').onsubmit = function() {
    if (spinning) return false;
    const color = document.getElementById('color').value;
    const amount = parseInt(document.getElementById('amount').value, 10);
    if (!amount || amount < 1) {
        document.getElementById('result').textContent = "Montant invalide.";
        return false;
    }
    spinRoulette(color, amount);
    return false;
};

function spinRoulette(betColor, betAmount) {
    spinning = true;
    document.getElementById('betBtn').disabled = true;
    document.getElementById('result').textContent = "";

    // Tirage du numéro gagnant
    const winningIndex = Math.floor(Math.random() * numbers.length);
    const winning = numbers[winningIndex];

    // Calcul de l'angle cible
    const anglePerSlot = 360 / numbers.length;
    // Pour que le bon numéro soit en haut, on doit tourner jusqu'à ce que ce numéro arrive à 0°
    // On ajoute des tours complets pour l'effet
    const extraTurns = 5;
    const targetAngle = 360 * extraTurns + (360 - winningIndex * anglePerSlot);

    const roulette = document.getElementById('roulette');
    roulette.style.transition = 'none';
    // Remet la roue à la position précédente pour éviter l'accumulation
    roulette.style.transform = `rotate(${lastRotation % 360}deg)`;
    setTimeout(() => {
        roulette.style.transition = `transform 4s cubic-bezier(0.33, 1, 0.68, 1)`;
        roulette.style.transform = `rotate(${targetAngle}deg)`;
        lastRotation = targetAngle;
    }, 20);

    setTimeout(() => {
        spinning = false;
        document.getElementById('betBtn').disabled = false;
        let resultText = `Numéro gagnant : ${winning.n} (${winning.c === 'red' ? 'Rouge' : (winning.c === 'black' ? 'Noir' : 'Vert')})<br>`;
        let win = false;
        if (betColor === 'green' && winning.c === 'green') {
            resultText += "Jackpot ! Vous avez gagné ! + " + (betAmount * 14) + " €";
            win = 'green';
        } else if (winning.c === betColor) {
            resultText += "Bravo, vous avez gagné ! + " + (betAmount * 2) + " €";
            win = true;
        } else {
            resultText += "Dommage, vous avez perdu.";
        }
        document.getElementById('result').innerHTML = resultText;

        // Mise à jour du solde via AJAX
        updateBalance(betAmount, betColor, win);
    }, 4200);
}

function updateBalance(amount, color, win) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'roulette_update.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            if (data.success) {
                document.getElementById('balance').textContent = data.balance;
            } else {
                document.getElementById('result').innerHTML += "<br>" + data.message;
            }
        }
    };
    xhr.send('amount=' + encodeURIComponent(amount) + '&color=' + encodeURIComponent(color) + '&win=' + encodeURIComponent(win));
}
</script>
</body>
</html>