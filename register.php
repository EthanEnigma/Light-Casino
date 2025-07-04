<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed]);
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $error = "Nom d'utilisateur déjà pris ou erreur.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Light Casino</title>
  <link rel="icon" href="images/logo.png"/>
</head>
<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  padding: 15px;
  background: #1abc9c;
  overflow: hidden;
  background-image: url('images/casino2.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

.wrapper {
  max-width: 500px;
  width: 100%;
  background: #fff;
  border-radius: 5px;
  box-shadow: 0px 4px 10px 1px rgba(0, 0, 0, 0.1);
}

.wrapper .title {
  height: 120px;
  background: #16a085;
  border-radius: 5px 5px 0 0;
  color: #fff;
  font-size: 30px;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
}

.wrapper form {
  padding: 40px 35px;
}

.wrapper form .row {
  height: 60px;
  margin-top: 15px;
  position: relative;
}

.wrapper form .row input {
  height: 100%;
  width: 100%;
  outline: none;
  padding-left: 70px;
  border-radius: 5px;
  border: 1px solid lightgrey;
  font-size: 18px;
  transition: all 0.3s ease;
}

form .row input:focus {
  border-color: #16a085;
}

form .row input::placeholder {
  color: #999;
}

.wrapper form .row i {
  position: absolute;
  width: 55px;
  height: 100%;
  color: #fff;
  font-size: 22px;
  background: #16a085;
  border: 1px solid #16a085;
  border-radius: 5px 0 0 5px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.wrapper form .pass {
  margin-top: 12px;
}

.wrapper form .pass a {
  color: #16a085;
  font-size: 17px;
  text-decoration: none;
}

.wrapper form .pass a:hover {
  text-decoration: underline;
}

.wrapper form .button input {
  margin-top: 20px;
  color: #fff;
  font-size: 20px;
  font-weight: 500;
  padding-left: 0px;
  background: #16a085;
  border: 1px solid #16a085;
  cursor: pointer;
}

form .button input:hover {
  background: #12876f;
}

.wrapper form .signup-link {
  text-align: center;
  margin-top: 45px;
  font-size: 17px;
}

.wrapper form .signup-link a {
  color: #16a085;
  text-decoration: none;
}

form .signup-link a:hover {
  text-decoration: underline;
}

.button{
    text-align: center;
}
</style>

<body>
  <div class="wrapper">
    <div class="title"><span>Créer un compte</span></div>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="POST" action="register.php">
      <div class="row">
        <i class="fas fa-user"></i>
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
      </div>
      <div class="row">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Mot de passe" required>
      </div>
      <div class="row button">
        <input type="submit" value="Créer un compte" />
        <a href="index.html" style="margin: 20px; text-align: center; color: #16a085;">Retour</a>
      </div>
      <div class="signup-link">Déjà membre ? <a href="login.php">Se connecter</a></div>
    </form>
  </div>
</body>
</html>