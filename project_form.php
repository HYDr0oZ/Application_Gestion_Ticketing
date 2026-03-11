<?php
session_start();
require_once 'DB.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = trim($_POST['project-name']);
  $description = trim($_POST['description']);
  $start_date = !empty($_POST['start-date']) ? $_POST['start-date'] : null;
  $end_date = !empty($_POST['end-date']) ? $_POST['end-date'] : null;
  $statusRaw = $_POST['status'] ?? 'draft';

  // Map status values to display values
  $statusMapping = [
    'draft' => 'Brouillon',
    'active' => 'Actif',
    'completed' => 'Terminé',
    'archived' => 'Archivé'
  ];
  $status = $statusMapping[$statusRaw] ?? 'Brouillon';

  try {
      $stmt = $pdo->prepare("INSERT INTO projects (title, description, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$title, $description, $start_date, $end_date, $status]);
      
      header("Location: project_list.php");
      exit();
  } catch (PDOException $e) {
      die("Erreur lors de la création du projet : " . $e->getMessage());
  }
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Créer / Éditer un projet - QuickTix</title>
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
        <li><a href="project_list.php" class="active">Project List</a></li>
        <li><a href="user_profile.php">Profil Utilisateur</a></li>
        <li><a href="settings.php">Paramètres</a></li>
      </ul>
    </nav>

    <main class="dashboard-content">
      <div class="mb-20">
        <a href="project_list.php" class="add-ticket-button">← Retour à la liste des projets</a>
      </div>

      <div class="login-card content-card-large">
        <div class="login-header">
          <h2>Nouveau Projet</h2>
        </div>
        <form action="project_form.php" method="POST" class="login-form">
          <div class="input-group">
            <label for="project-name">Nom du projet</label>
            <input type="text" id="project-name" name="project-name" placeholder="Ex: Refonte Site Web" required />
          </div>

          <div class="input-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" class="form-textarea"
              placeholder="Détaillez les objectifs du projet..."></textarea>
          </div>

          <div class="form-row">
            <div class="input-group form-col-small">
              <label for="start-date">Date de début</label>
              <input type="date" id="start-date" name="start-date" />
            </div>
            <div class="input-group form-col-small">
              <label for="end-date">Date de fin estimée</label>
              <input type="date" id="end-date" name="end-date" />
            </div>
          </div>

          <div class="input-group">
            <label for="status">Statut</label>
            <select id="status" name="status" class="form-select">
              <option value="draft">Brouillon</option>
              <option value="active" selected>Actif</option>
              <option value="completed">Terminé</option>
              <option value="archived">Archivé</option>
            </select>
          </div>

          <button type="submit" class="login-button">
            Enregistrer le projet
          </button>
        </form>
      </div>
    </main>
  </div>
  <script src="validation.js"></script>
</body>

</html>