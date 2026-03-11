<?php
session_start();
require_once 'DB.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = trim($_POST['title']);
  $description = trim($_POST['description']);
  $price = !empty($_POST['price']) ? floatval($_POST['price']) : 0.00;
  $duration = trim($_POST['duration'] ?? '');
  $estimated_time = trim($_POST['estimated_time'] ?? '');
  $status = 'Ouvert'; // Default status

  try {
      $stmt = $pdo->prepare("INSERT INTO tickets (title, description, status, price, duration, estimated_time) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->execute([$title, $description, $status, $price, $duration, $estimated_time]);
      
      header("Location: tickets_list.php");
      exit();
  } catch (PDOException $e) {
      $error = "Erreur SQL : " . $e->getMessage();
      // Normally we would display this error, but for now we just log/die or redirect
      // For simplicity, we can just echo it if there's a problem, though redirecting is better UI
      die("Erreur lors de l'ajout du ticket : " . $e->getMessage());
  }
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter un ticket</title>
  <link rel="stylesheet" href="style.css" />
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
        <li><a href="settings.php">Paramètres</a></li>
      </ul>
    </nav>
    <main class="dashboard-content">
      <div class="mb-20">
        <a href="tickets_list.php" class="add-ticket-button">← Retour a la liste des tickets</a>
      </div>

      <div class="login-card content-card-medium">
        <div class="login-header">
          <h2>Nouveau Ticket</h2>
        </div>
        <form action="Add_ticket.php" method="POST" class="login-form">
          <div class="input-group">
            <label for="title">Titre du ticket</label>
            <input type="text" id="title" name="title" placeholder="Ex: Problème connexion" required />
          </div>

          <div class="input-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" class="form-textarea"
              placeholder="Détaillez le problème..."></textarea>
          </div>

          <div class="input-group">
            <label for="price">Prix estimé (€)</label>
            <input type="number" id="price" name="price" placeholder="0.00" step="0.01" />
          </div>

          <div class="input-group">
            <label for="duration">Durée du ticket</label>
            <input type="text" id="duration" name="duration" placeholder="Ex: 2 heures" />
          </div>

          <div class="input-group">
            <label for="estimated_time">Temps estimé de résolution</label>
            <input type="text" id="estimated_time" name="estimated_time" placeholder="Ex: 1 jour" />
          </div>

          <button type="submit" class="login-button">Créer le ticket</button>
        </form>
      </div>
    </main>
  </div>
  <script src="validation.js"></script>
</body>

</html>