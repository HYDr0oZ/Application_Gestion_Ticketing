<?php
session_start();
require_once 'DB.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_name = $_SESSION['user_name'] ?? 'Utilisateur';

try {
    $stmt = $pdo->query("SELECT * FROM tickets ORDER BY created_at DESC LIMIT 5"); // Get 5 most recent for dashboard
    $tickets = $stmt->fetchAll();
} catch (PDOException $e) {
    // Graceful degradation
    $tickets = [];
}

try {
    $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 5"); // Get 5 most recent for dashboard
    $projects = $stmt->fetchAll();
} catch (PDOException $e) {
    // Graceful degradation
    $projects = [];
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>QuickTix - Dashboard</title>
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
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="tickets_list.php">Tickets List</a></li>
        <li><a href="project_list.php">Project List</a></li>
        <li><a href="user_profile.php">Profil Utilisateur</a></li>
        <li><a href="settings.php">Paramètres</a></li>
      </ul>
    </nav>

    <main class="dashboard-content">
      <span class="header-title">Bonjour,
        <?php echo htmlspecialchars($user_name); ?>
      </span>
      <div class="stats-container">
        <div class="stat-card">
          <span class="stat-label">Tickets Ouverts</span>
          <span class="stat-value">24</span>
        </div>
        <div class="stat-card urgent">
          <span class="stat-label">Urgents</span>
          <span class="stat-value">5</span>
        </div>
        <div class="stat-card closed">
          <span class="stat-label">Fermés Aujourd'hui</span>
          <span class="stat-value">12</span>
        </div>
      </div>
      <div class="table-container">
        <div class="table-header">
          <h2><a href="tickets_list.php">Liste des tickets</a></h2>
        </div>
        <table class="tickets-table">
          <thead>
            <tr>
              <th>Titre</th>
              <th>Description</th>
              <th>Statut</th>
              <th>Durée</th>
              <th>Temps Estimé</th>
              <th>Prix</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tickets as $ticket): ?>
              <tr>
                <td>
                  <?php echo htmlspecialchars($ticket['title']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($ticket['description']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($ticket['status']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($ticket['duration'] ?? '-'); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($ticket['estimated_time'] ?? '-'); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($ticket['price']); ?> €
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="table-container mt-20">
        <div class="table-header">
          <h2><a href="project_list.php">Derniers projets</a></h2>
        </div>
        <table class="tickets-table">
          <thead>
            <tr>
              <th>Projet</th>
              <th>Description</th>
              <th>Statut</th>
              <th>Date de fin estimée</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($projects as $project): ?>
              <tr>
                <td>
                  <?php echo htmlspecialchars($project['title']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($project['description']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($project['status']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($project['end_date'] ?? '-'); ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>

</html>