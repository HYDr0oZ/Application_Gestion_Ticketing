<?php session_start(); ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Paramètres - QuickTix</title>
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
        <li><a href="user_profile.php">Profil Utilisateur</a></li>
        <li><a href="settings.php" class="active">Paramètres</a></li>
      </ul>
    </nav>

    <main class="dashboard-content">
      <div class="login-card content-card-large">
        <div class="login-header">
          <h2>Paramètres de l'application</h2>
        </div>
        <form class="login-form">
          <div class="input-group">
            <h3>Notifications</h3>
            <div class="mt-10">
              <label class="checkbox-label">
                <input type="checkbox" checked /> Recevoir des notifications
                par email
              </label>
              <label class="checkbox-label">
                <input type="checkbox" checked /> Notifications de bureau
              </label>
            </div>
          </div>

          <div class="input-group mt-20">
            <h3>Apparence</h3>
            <label for="theme">Thème</label>
            <select id="theme" name="theme" class="form-select mt-5">
              <option value="light">Clair</option>
              <option value="dark">Sombre</option>
              <option value="system">Système</option>
            </select>
          </div>

          <div class="input-group mt-20">
            <h3>Sécurité</h3>
            <button type="button" class="login-button btn-secondary">
              Changer de mot de passe
            </button>
          </div>

          <hr class="divider" />

          <button type="submit" class="login-button">
            Enregistrer les préférences
          </button>
        </form>
      </div>
    </main>
  </div>
  <script src="validation.js"></script>
</body>

</html>