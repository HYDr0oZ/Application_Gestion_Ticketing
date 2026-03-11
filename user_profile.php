<?php
session_start();
require_once 'DB.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$firstname = '';
$lastname = '';
$email = '';

try {
  $stmt = $pdo->prepare("SELECT nom, prenom, email FROM users WHERE id = ?");
  $stmt->execute([$user_id]);
  $user = $stmt->fetch();

  if ($user) {
    $firstname = $user['prenom'];
    $lastname = $user['nom'];
    $email = $user['email'];
  }
} catch (PDOException $e) {
  die("Erreur : " . $e->getMessage());
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil Utilisateur - QuickTix</title>
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
      <a href="logout.php" class="logout-button">Se déconnecter</a>
    </div>
  </header>

  <div class="app-container">
    <nav class="sidebar">
      <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="tickets_list.php">Tickets List</a></li>
        <li><a href="project_list.php">Project List</a></li>
        <li>
          <a href="user_profile.php" class="active">Profil Utilisateur</a>
        </li>
        <li><a href="settings.php">Paramètres</a></li>
      </ul>
    </nav>

    <main class="dashboard-content">
      <div class="login-card content-card-large">
        <div class="login-header">
          <h2>Mon Profil</h2>
        </div>
        <form class="login-form">
          <div class="form-row">
            <div class="input-group form-col">
              <label for="firstname">Prénom</label>
              <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>"
                required />
            </div>
            <div class="input-group form-col">
              <label for="lastname">Nom</label>
              <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>"
                required />
            </div>
          </div>

          <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required />
          </div>

          <div class="input-group">
            <label for="role">Rôle</label>
            <input type="text" id="role" name="role" value="Admin" disabled class="input-disabled" />
          </div>

          <div class="input-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" rows="4" class="form-textarea">
Admin QuickTix.</textarea>
          </div>

          <button type="submit" class="login-button">
            Mettre à jour le profil
          </button>
        </form>
      </div>
    </main>
  </div>
  <script src="validation.js"></script>
</body>

</html>