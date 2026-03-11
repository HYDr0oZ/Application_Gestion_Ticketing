<?php
session_start();
require_once 'DB.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if (empty($email) || empty($password)) {
    $error = "Veuillez remplir tous les champs.";
  } else {
    try {
      $stmt = $pdo->prepare("SELECT id, nom, prenom, password FROM users WHERE email = ?");
      $stmt->execute([$email]);
      $user = $stmt->fetch();

      if ($user && password_verify($password, $user['password'])) {
        // Login success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];

        header("Location: dashboard.php");
        exit();
      } else {
        $error = "Identifiants incorrects.";
      }
    } catch (PDOException $e) {
      $error = "Erreur SQL : " . $e->getMessage();
    }
  }
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexion - QuickTix</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet" />
</head>

<body>
  <header class="main-header">
    <div class="header-content">
      <img src="logo.png" alt="QuickTix Logo" class="logo" />
      <h1 class="header-title">QuickTix</h1>
    </div>
  </header>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h2>Connexion</h2>
      </div>

      <?php if ($error): ?>
        <div style="color: red; text-align: center; margin-bottom: 15px;">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <form action="index.php" method="POST" class="login-form">
        <div class="input-group">
          <label for="email">Identifiant/Email</label>
          <input type="email" id="email" name="email" placeholder="Entrez votre identifiant" required
            value="<?php echo htmlspecialchars($email ?? ''); ?>" />
        </div>
        <div class="input-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" placeholder="••••••••" required />
          <div class="text-right mt-5">
            <a href="forgot_password.php" class="forgot-password-link">Mot de passe oublié ?</a>
          </div>
        </div>

        <button type="submit" class="login-button">Se connecter</button>
      </form>
      <div class="login-footer">
        <p>
          Vous n'avez pas de compte ?
          <a href="create_account.php">Créer un compte</a>
        </p>
      </div>
    </div>
  </div>
  <script src="validation.js"></script>
</body>

</html>