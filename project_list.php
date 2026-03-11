<?php
session_start();
require_once 'DB.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle deletion
if (isset($_GET['delete_id'])) {
  $delete_id = $_GET['delete_id'];
  try {
      $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
      $stmt->execute([$delete_id]);
      header("Location: project_list.php");
      exit();
  } catch (PDOException $e) {
      die("Erreur lors de la suppression : " . $e->getMessage());
  }
}

// Fetch projects
try {
    $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
    $projects = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors de la récupération des projets : " . $e->getMessage());
}
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>QuickTix - Liste des projets</title>
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
      <div class="table-container">
        <div class="table-header">
          <h2>Mes Projets</h2>
          <a href="project_form.php" class="add-ticket-button">Ajouter un projet</a>
        </div>
        <table class="tickets-table">
          <thead>
            <tr>
              <th>Projet</th>
              <th>Description</th>
              <th>Statut</th>
              <th>Deadline</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($projects as $project): ?>
              <tr>
                <td>
                  <?php echo $project['title']; ?>
                </td>
                <td>
                  <?php echo $project['description']; ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($project['status']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($project['end_date'] ?? '-'); ?>
                </td>
                <td>
                  <button class="btn-view-details">Voir détails</button>
                  <a href="project_form.php?id=<?php echo $project['id']; ?>" class="action-link">Éditer</a>
                  <a href="project_list.php?delete_id=<?php echo $project['id']; ?>" class="action-link"
                    style="color: #ff4d4d; margin-left: 10px;"
                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">Supprimer</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- Modal -->
  <div id="projectModal" class="modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2 id="modalTitle">Détails du Projet</h2>
      <div class="modal-body">
        <p><strong>Description:</strong> <span id="modalDesc"></span></p>
        <p><strong>Statut:</strong> <span id="modalStatus"></span></p>
        <p><strong>Temps estimé:</strong> <span id="modalTime"></span></p>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const modal = document.getElementById("projectModal");
      const closeBtn = document.querySelector(".close-modal");
      const viewBtns = document.querySelectorAll(".btn-view-details");

      // Elements to populate
      const modalTitle = document.getElementById("modalTitle");
      const modalDesc = document.getElementById("modalDesc");
      const modalStatus = document.getElementById("modalStatus");
      const modalTime = document.getElementById("modalTime");

      viewBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const row = e.target.closest("tr");
          const cells = row.querySelectorAll("td");

          // Cells indices: 0=Projet, 1=Desc, 2=Statut, 3=Time
          modalTitle.textContent = cells[0].textContent;
          modalDesc.textContent = cells[1].textContent;
          modalStatus.textContent = cells[2].textContent;
          modalTime.textContent = cells[3].textContent;

          modal.style.display = "flex";
        });
      });

      closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
      });

      window.addEventListener("click", (e) => {
        if (e.target === modal) {
          modal.style.display = "none";
        }
      });
    });
  </script>
</body>

</html>