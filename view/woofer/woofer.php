<?php
require_once __DIR__ . '/../../db/db_connect.php';  // Connexion à la base de données
require_once __DIR__ . '/../../model/ModeleWoofer.php';  // Modèle Woofer
require_once __DIR__ . '/../../model/ModeleTache.php';  // Modèle Tâche
require_once __DIR__ . '/../../model/ModelePlanning.php';  // Modèle Planning
require_once __DIR__ . '/../../model/utilisateur.php';

$utilisateurModel = new Utilisateur($pdo);
$utilisateursWoofer = $utilisateurModel->getUtilisateursParRole('Woofer');

$wooferModel = new ModeleWoofer($pdo);
$tacheModel = new ModeleTache($pdo);
$planningModel = new ModelePlanning($pdo);

$woofers = $wooferModel->obtenirTousLesWoofers();  // Récupérer la liste des woofers
$confirmation = isset($_GET['success']) ? (int) $_GET['success'] : 0;
$erreur = isset($_GET['erreur']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../public/css/styleWoofer.css">
  <title>Gestion des Woofers</title>
  <style>
   
  </style>
</head>
<body>
  <header>
    <div class="logo">Ferme Écoresponsable</div>
    <button class="logout-button">Déconnexion</button>
  </header>

  <nav>
    <a href="dashboard.php">Tableau de Bord</a>
    <a href="stocks.php">Gestion des Stocks</a>
    <a href="ventes.php">Gestion des Ventes</a>
    <a href="ateliers.php">Gestion des Ateliers</a>
    <a href="woofer.php">Gestion des Woofers</a>
  </nav>

  <main>
    <section class="form-container">
      <h2>Ajouter un Woofer</h2>
      <form action="../../controllers/controleurWoofer/controleurWoofer.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="ajouter_woofer">
        <label>Woofer (utilisateur existant) :</label>
        <select name="id_utilisateur" required>
          <option value="">-- Choisir un woofer --</option>
          <?php foreach ($utilisateursWoofer as $util): ?>
            <option value="<?= $util['id'] ?>">
              <?= htmlspecialchars($util['prenom'] . ' ' . $util['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <input type="date" name="date_arrivee" placeholder="Date d'arrivée" required>
        <input type="date" name="date_depart" placeholder="Date de départ" required>

        <input type="file" name="photo" accept="image/*">
        <button type="submit">Ajouter</button>
      </form>



      <br><br><h1>Ajouter une Tâche</h1>
    <form action="../../controllers/controleurWoofer/controleurWoofer.php" method="POST">
        <input type="hidden" name="action" value="ajouter_tache">
        <label for="woofer_id">Choisir un Woofer:</label>
        <select name="woofer_id" required>
            <option value="">-- Choisir un woofer --</option>
            <?php foreach ($woofers as $woofer): ?>
                <option value="<?= $woofer['id'] ?>"><?= htmlspecialchars($woofer['prenom'] . ' ' . $woofer['nom']) ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        
        <label for="tache_nom">Nom de la tâche:</label>
        <input type="text" name="tache_nom" required>
        <br>
        
        <label for="description">Description:</label>
        <textarea name="description" required></textarea>
        <br>
        
        <label for="date_echeance">Date d'échéance:</label>
        <input type="date" name="date_echeance" required>
        <br>
        
        <label for="statut">Statut de la tâche:</label>
        <select name="statut" required>
            <option value="Non commencée">Non commencée</option>
            <option value="En cours">En cours</option>
            <option value="Terminée">Terminée</option>
            <option value="Annulée">Annulée</option>
        </select>
        <br>

        <button type="submit">Ajouter la tâche</button>
    </form>
    </section>

    <h2>Liste des Woofers</h2>
    <!-- Tableau des Woofers -->
    <table class="woofer-table">
      <thead>
        <tr>
          <th>Photo</th>
          <th>Nom</th>
          <th>Arrivée</th>
          <th>Départ</th>
         
        </tr>
      </thead>
      <tbody>
        <?php foreach ($woofers as $woofer): ?>
          <tr>
            <td><img src="../../<?= htmlspecialchars($woofer['photo']) ?>" alt="Photo de <?= htmlspecialchars($woofer['nom']) ?>"></td>
            <td><?= htmlspecialchars($woofer['prenom'] . ' ' . $woofer['nom']) ?></td>
            <td><?= htmlspecialchars($woofer['datearrivee']) ?></td>
            <td><?= htmlspecialchars($woofer['datedepart']) ?></td>
      
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h2>Tâches des Woofers</h2>
    <!-- Tableau des Tâches -->
    <table class="tasks-table">
      <thead>
        <tr>
          <th>Nom du Woofer</th>
          <th>Nom de la Tâche</th>
          <th>Description</th>
          <th>date de création</th>
          <th>Date d'échéance</th>
          <th>Statut</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Récupérer les tâches attribuées aux woofers
        $taches = $tacheModel->getAllTaches(); // Suppose que getAllTaches retourne toutes les tâches
        if (!empty($taches)) {
          foreach ($taches as $tache) {
            ?>
            <tr>
              <td><?= htmlspecialchars($tache['woofer_nom']) ?></td>
              <td><?= htmlspecialchars($tache['nom']) ?></td>
              <td><?= htmlspecialchars($tache['description']) ?></td>
              <td><?= htmlspecialchars($tache['date_creation']) ?></td>
              <td><?= htmlspecialchars($tache['date_echeance']) ?></td>
              <td><?= $statut = ($tache['statut'] == 0) ? 'Non commencée' : (($tache['statut'] == 1) ? 'En cours' : (($tache['statut'] == 2) ? 'Terminée' : 'Annulée')) ?></td>
            </tr>
          <?php } ?>
        <?php } else { ?>
          <tr><td colspan="5">Aucune tâche attribuée</td></tr>
        <?php } ?>
      </tbody>
    </table>

    <h2>Planning des Tâches</h2>
    <!-- Tableau des Plannings -->
    <table class="planning-table">
      <thead>
        <tr>
          <th>Nom de la Tâche</th>
          <th>Date Planifiée</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Récupérer le planning des tâches
        $plannings = $planningModel->getAllPlannings(); // Suppose que getAllPlannings retourne tous les plannings
        if (!empty($plannings)) {
          foreach ($plannings as $planning) {
            ?>
            <tr>
              <td><?= htmlspecialchars($planning['tache_nom']) ?></td>
              <td><?= htmlspecialchars($planning['date_planifiee']) ?></td>
            </tr>
          <?php } ?>
        <?php } else { ?>
          <tr><td colspan="2">Aucun planning disponible</td></tr>
        <?php } ?>
      </tbody>
    </table>

  </main>

  <footer>
    <p>&copy; 2025 Ferme Écoresponsable. Tous droits réservés.</p>
  </footer>
</body>
</html>
