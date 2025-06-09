<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non connecté.']);
    exit;
}

$amount = intval($_POST['amount'] ?? 0);
$color = $_POST['color'] ?? '';
$win = $_POST['win'] ?? false;

$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
    exit;
}

if ($amount < 1 || $amount > $user['balance']) {
    echo json_encode(['success' => false, 'message' => 'Mise invalide ou solde insuffisant.']);
    exit;
}

$newBalance = $user['balance'] - $amount;

if ($win === 'green') {
    $newBalance += $amount * 35;
} elseif ($win == 1 || $win === true || $win === 'true') {
    $newBalance += $amount * 2;
}

$stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
$stmt->execute([$newBalance, $_SESSION['user_id']]);

echo json_encode(['success' => true, 'balance' => $newBalance]);