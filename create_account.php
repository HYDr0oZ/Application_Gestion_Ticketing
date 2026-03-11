<?php
require_once 'DB.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $lastname = trim($_POST['lastname']);
  $firstname = trim($_POST['firstname']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  if (empty($lastname) || empty($firstname) || empty($email) || empty($password) || empty($confirm_password)) {
    $error = "Tous les champs sont obligatoires.";
  } elseif ($password !== $confirm_password) {
    $error = "Les mots de passe ne correspondent pas.";
  } else {
    try {
      // Check if email already exists
      $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
      $stmt->execute([$email]);
      if ($stmt->fetch()) {
        $error = "Cet email est déjà utilisé.";
      } else {
        // Hash password and insert user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Using `nom` and `prenom` as per user description
        $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$lastname, $firstname, $email, $hashed_password])) {
          $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
          // Optional: redirect to login page after a delay or show link
          header("refresh:2;url=index.php");
        } else {
          $error = "Une erreur est survenue lors de l'inscription.";
        }
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
  <title>Inscription - QuickTix</title>
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
      <a href="index.php" class="logout-button">Se connecter</a>
    </div>
  </header>
  <div class="create-account-container">
    <div class="create-account-card">
      <div class="create-account-header">
        <h2>Création de compte</h2>
      </div>

      <?php if ($error): ?>
        <div style="color: red; text-align: center; margin-bottom: 15px;">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>
      <?php if ($success): ?>
        <div style="color: green; text-align: center; margin-bottom: 15px;">
          <?php echo $success; ?>
        </div>
      <?php endif; ?>

      <form action="create_account.php" method="POST" class="create-account-form">
        <div class="input-group">
          <label for="lastname">Nom</label>
          <input type="text" id="lastname" name="lastname" placeholder="Entrez votre nom" required
            value="<?php echo htmlspecialchars($lastname ?? ''); ?>" />
        </div>
        <div class="input-group">
          <label for="firstname">Prénom</label>
          <input type="text" id="firstname" name="firstname" placeholder="Entrez votre prénom" required
            value="<?php echo htmlspecialchars($firstname ?? ''); ?>" />
        </div>
        <div class="input-group">
          <label for="email">Identifiant/Email</label>
          <input type="email" id="email" name="email" placeholder="Entrez votre identifiant" required
            value="<?php echo htmlspecialchars($email ?? ''); ?>" />
        </div>
        <div class="input-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required />
        </div>
        <div class="input-group">
          <label for="confirm_password">Confirmer le mot de passe</label>
          <input type="password" id="confirm_password" name="confirm_password"
            placeholder="Confirmez votre mot de passe" required />
        </div>

        <button type="submit" class="create-account-button">
          Créer un compte
        </button>
      </form>
    </div>
  </div>
  <script src="validation.js"></script>
</body>

</html>