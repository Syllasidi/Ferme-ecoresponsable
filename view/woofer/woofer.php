<?php
require_once __DIR__ . '/../../db/db_connect.php';
require_once __DIR__ . '/../../model/ModeleWoofer.php';
require_once __DIR__ . '/../../model/Utilisateur.php';
$utilisateurModel = new Utilisateur($pdo);
$utilisateursWoofer = $utilisateurModel->getUtilisateursParRole('Woofer');


$wooferModel = new ModeleWoofer($pdo);
$woofers = $wooferModel->obtenirTousLesWoofers();
$confirmation = isset($_GET['success']) ? (int) $_GET['success'] : 0;
$erreur = isset($_GET['erreur']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Woofers</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; background-color: #eef2f3; }
    header, footer { background-color: #2a9d8f; color: white; text-align: center; padding: 1rem; }
    header { display: flex; justify-content: space-between; align-items: center; }
    header .logo { font-size: 1.5rem; }
    header .logout-button {
      background-color: #e76f51;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      cursor: pointer;
    }
    nav {
      display: flex;
      justify-content: center;
      background-color: #2a9d8f;
      padding: 1rem;
    }
    nav a {
      margin: 0 1rem;
      color: white;
      text-decoration: none;
      font-weight: bold;
    }
    main { padding: 1rem; }
    .form-container input, .form-container select, .form-container button {
      width: 100%;
      padding: 0.5rem;
      margin: 0.5rem 0;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .form-container button {
      background-color: #2a9d8f;
      color: white;
      border: none;
      cursor: pointer;
    }
    .woofer-profile {
      display: flex;
      align-items: center;
      margin: 1rem 0;
      border: 1px solid #ccc;
      background: white;
      padding: 1rem;
      border-radius: 8px;
    }
    .woofer-profile img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 50%;
      margin-right: 1rem;
      background-color: #ddd;
    }
    .woofer-profile h3 { margin: 0; color: #2a9d8f; }
    .woofer-profile p { margin: 0.2rem 0; }
    .delete-button {
      background-color: #e76f51;
      color: white;
      border: none;
      padding: 0.3rem 0.7rem;
      border-radius: 4px;
      cursor: pointer;
      margin-left: auto;
    }
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
      <input type="text" name="taches" placeholder="Tâches assignées" required>
      <input type="file" name="photo" accept="image/*">
      <button type="submit">Ajouter</button>
    </form>
  </section>

  <h2>Liste des Woofers</h2>
  <?php foreach ($woofers as $woofer): ?>
    <div class="woofer-profile">
      <img src="../../<?= htmlspecialchars($woofer['photo']) ?>" alt="Photo de <?= htmlspecialchars($woofer['nom']) ?>">
      <div>
        <h3><?= htmlspecialchars($woofer['prenom'] . ' ' . $woofer['nom']) ?></h3>
        <p>Arrivée : <?= htmlspecialchars($woofer['datearrivee']) ?></p>
        <p>Départ : <?= htmlspecialchars($woofer['datedepart']) ?></p>
        <p>Tâches : <?= htmlspecialchars($woofer['tachesattribuees']) ?></p>
      </div>
      
    </div>
  <?php endforeach; ?>
</main>
<footer>
  <p>&copy; 2025 Ferme Écoresponsable. Tous droits réservés.</p>
</footer>
</body>
</html>
