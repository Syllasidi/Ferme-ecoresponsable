<?php
require_once __DIR__ . '/../../db/db_connect.php';
require_once __DIR__ . '/../../model/ModeleWoofer.php';

$wooferModel = new ModeleWoofer($pdo);
$woofers = $wooferModel->obtenirTousLesWoofers();
$confirmation = isset($_GET['success']) ? (int) $_GET['success'] : 0;
$erreur = isset($_GET['erreur']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des Woofers</title>
  
</head>
<body>
  <header>
    <div class="logo">Ferme Écoresponsable</div>
    <button class="logout-button">Déconnexion</button>
  </header>
  <nav>
    <a href="../dashboard.php">Dashboard</a>
    <a href="../produit/produit.php">Stocks</a>
    <a href="../vente/vente.php">Ventes</a>
    <a href="../atelier/atelier.php">Ateliers</a>
    <a href="woofer.php">Woofers</a>
  </nav>
  <main>
    <h1>Gestion des Woofers</h1>

    <?php if ($confirmation === 1): ?>
      <p style="color: green;">Woofer ajouté avec succès !</p>
    <?php elseif ($confirmation === 2): ?>
      <p style="color: green;">Woofer supprimé avec succès !</p>
    <?php elseif ($erreur): ?>
      <p style="color: red;">Une erreur est survenue.</p>
    <?php endif; ?>

    <section class="form-container">
      <h2>Ajouter un Woofer</h2>
      <form action="../../controllers/controleurWoofer/controleurWoofer.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="ajouter_woofer">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenom" placeholder="Prénom" required>
        <input type="date" name="date_arrivee" placeholder="Date d'arrivée" required>
        <input type="date" name="date_depart" placeholder="Date de départ" required>
        <input type="text" name="taches" placeholder="Tâches assignées" required>
        <input type="file" name="photo" accept="image/*">
        <button type="submit">Ajouter</button>
      </form>
    </section>

    <section>
      <h2>Liste des Woofers</h2>
      <?php foreach ($woofers as $woofer): ?>
        <div class="woofer-profile">
          <img src="../../<?= htmlspecialchars($woofer['photo'] ?? 'public/images/placeholder.jpg') ?>" alt="Photo">
          <div>
            <h3><?= htmlspecialchars($woofer['prenom']) . ' ' . htmlspecialchars($woofer['nom']) ?></h3>
            <p>Arrivée : <?= htmlspecialchars($woofer['datearrivee']) ?></p>
            <p>Départ : <?= htmlspecialchars($woofer['datedepart']) ?></p>
            <p>Tâches : <?= htmlspecialchars($woofer['tachesattribuees']) ?></p>
          </div>
          <form method="POST" action="../../controllers/controleurWoofer/controleurWoofer.php">
            <input type="hidden" name="action" value="supprimer_woofer">
            <input type="hidden" name="id" value="<?= $woofer['id'] ?>">
            <button type="submit" class="delete-button" onclick="return confirm('Supprimer ce woofer ?')">Supprimer</button>
          </form>
        </div>
      <?php endforeach; ?>
    </section>
  </main>
  <footer>
    <p>&copy; 2025 Ferme Écoresponsable. Tous droits réservés.</p>
  </footer>
</body>
</html>
