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
      $stmt = $pdo->prepare("DELETE FROM tickets WHERE id = ?");
      $stmt->execute([$delete_id]);
      header("Location: tickets_list.php");
      exit();
  } catch (PDOException $e) {
      die("Erreur lors de la suppression : " . $e->getMessage());
  }
}

// Fetch tickets
try {
    $stmt = $pdo->query("SELECT * FROM tickets ORDER BY created_at DESC");
    $tickets = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors de la récupération des tickets : " . $e->getMessage());
}
?>



<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>QuickTix - Liste des tickets</title>
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
        <li><a href="tickets_list.php" class="active">Tickets List</a></li>
        <li><a href="project_list.php">Project List</a></li>
        <li><a href="user_profile.php">Profil Utilisateur</a></li>
        <li><a href="settings.php">Paramètres</a></li>
      </ul>
    </nav>

    <main class="dashboard-content">
      <div class="table-container">
        <div class="table-header">
          <div style="display: flex; align-items: center; gap: 15px">
            <h2>Tous les tickets</h2>
            <select id="statusFilter" class="form-select" style="width: auto; padding: 5px">
              <option value="all">Tous</option>
              <option value="Ouvert">Ouvert</option>
              <option value="En cours">En cours</option>
              <option value="Fermé">Fermé</option>
            </select>
          </div>
          <a href="Add_ticket.php" class="add-ticket-button">Ajouter un ticket</a>
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
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tickets as $ticket): ?>
              <tr>
                <td>
                  <?php echo $ticket['title']; ?>
                </td>
                <td>
                  <?php echo $ticket['description']; ?>
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
                <td>
                  <button class="btn-view-details">Voir détails</button>
                  <a href="tickets_list.php?delete_id=<?php echo $ticket['id']; ?>" class="action-link"
                    style="color: #ff4d4d; margin-left: 10px;"
                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce ticket ?');">Supprimer</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
  <!-- Modal -->
  <div id="ticketModal" class="modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2 id="modalTitle">Détails du Ticket</h2>
      <div class="modal-body">
        <p><strong>Description:</strong> <span id="modalDesc"></span></p>
        <p><strong>Statut:</strong> <span id="modalStatus"></span></p>
        <p><strong>Durée:</strong> <span id="modalDuration"></span></p>
        <p><strong>Temps Estimé:</strong> <span id="modalEstimatedTime"></span></p>
        <p><strong>Prix:</strong> <span id="modalPrice"></span></p>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const modal = document.getElementById("ticketModal");
      const closeBtn = document.querySelector(".close-modal");
      const viewBtns = document.querySelectorAll(".btn-view-details");

      // Elements to populate
      const modalTitle = document.getElementById("modalTitle");
      const modalDesc = document.getElementById("modalDesc");
      const modalStatus = document.getElementById("modalStatus");
      const modalDuration = document.getElementById("modalDuration");
      const modalEstimatedTime = document.getElementById("modalEstimatedTime");
      const modalPrice = document.getElementById("modalPrice");

      viewBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const row = e.target.closest("tr");
          const cells = row.querySelectorAll("td");

          // Cells indices: 0=Title, 1=Desc, 2=Status, 3=Duration, 4=EstimatedTime, 5=Price, 6=Action
          modalTitle.textContent = cells[0].textContent.trim();
          modalDesc.textContent = cells[1].textContent.trim();
          modalStatus.textContent = cells[2].textContent.trim();
          modalDuration.textContent = cells[3].textContent.trim();
          modalEstimatedTime.textContent = cells[4].textContent.trim();
          modalPrice.textContent = cells[5].textContent.trim();

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

      // Filter logic
      const statusFilter = document.getElementById("statusFilter");
      const tableRows = document.querySelectorAll(".tickets-table tbody tr");

      statusFilter.addEventListener("change", () => {
        const selectedStatus = statusFilter.value;

        tableRows.forEach((row) => {
          const statusCell = row.cells[2]; // cellule de status
          const statusText = statusCell.textContent.trim();

          if (selectedStatus === "all" || statusText === selectedStatus) {
            row.style.display = "";
          } else {
            row.style.display = "none";
          }
        });
      });
    });
  </script>
</body>

</html>