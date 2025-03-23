<?php
require_once __DIR__ . '/../../db/db_connect.php';
require_once __DIR__ . '/../../model/ModeleVente.php';
require_once __DIR__ . '/../../model/ModeleProduit.php';
require_once __DIR__ . '/../../model/ModeleWoofer.php';

$venteModel = new ModeleVente($pdo);
$produitModel = new ModeleProduit($pdo);
$wooferModel = new ModeleWoofer($pdo);

$ventes = $venteModel->obtenirToutesLesVentes();
$produits = $produitModel->obtenirTousAvecStock();
$woofers = $wooferModel->obtenirTousLesWoofers();

$confirmation = isset($_GET['success']) && $_GET['success'] === '1';
$erreurStock = isset($_GET['erreur']) && $_GET['erreur'] === 'stock_insuffisant';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des Ventes</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      margin: 0;
      background-color: #eef2f3;
    }
    header, footer {
      background-color: #2a9d8f;
      color: white;
      text-align: center;
      padding: 1rem;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header .logo {
      font-size: 1.5rem;
    }
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
    main {
      flex: 1;
      padding: 1rem;
    }
    .sales-form, .sales-history {
      margin-bottom: 2rem;
    }
    .sales-form input, .sales-form select, .sales-form button {
      width: 100%;
      padding: 0.5rem;
      margin: 0.5rem 0;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .sales-form button {
      background-color: #2a9d8f;
      color: white;
      border: none;
      cursor: pointer;
    }
    .sales-history table {
      width: 100%;
      border-collapse: collapse;
    }
    .sales-history th, .sales-history td {
      border: 1px solid #ddd;
      padding: 0.5rem;
      text-align: left;
    }
    .sales-history th {
      background-color: #2a9d8f;
      color: white;
    }
    .sales-history button {
      padding: 0.5rem;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      background-color: #2a9d8f;
      color: white;
    }
  </style>
</head>
<body>
<header>
  <div class="logo">Ferme Écoresponsable</div>
  <button class="logout-button">Déconnexion</button>
</header>
<nav>
  <a href="dashboard.html">Tableau de Bord</a>
  <a href="stocks.html">Gestion des Stocks</a>
  <a href="ventes.html">Gestion des Ventes</a>
  <a href="ateliers.html">Gestion des Ateliers</a>
  <a href="woofers.html">Gestion des Woofers</a>
</nav>
<main>
  <section class="sales-form">
    <h1>Gestion des Ventes</h1>
    <h2>Enregistrer une Vente</h2>

    <?php if ($confirmation): ?>
      <p style="color: green; font-weight: bold;">✅ Vente enregistrée avec succès !</p>
    <?php endif; ?>

    <?php if ($erreurStock): ?>
      <p style="color: red; font-weight: bold;">❌ Erreur : Stock insuffisant pour cette vente.</p>
    <?php endif; ?>

    <form method="post" action="../../controllers/controleurVente/controleurVente.php">
      <input type="hidden" name="action" value="enregistrer_vente">

      <label>Produit :</label>
      <select name="id_produit" required>
        <option value="">-- Choisir un produit --</option>
        <?php foreach ($produits as $produit): ?>
          <option value="<?= $produit['id'] ?>"><?= htmlspecialchars($produit['nom']) ?></option>
        <?php endforeach; ?>
      </select>

      <label>Quantité :</label>
      <input type="number" name="quantite" required>

      <label>Woofer responsable :</label>
      <select name="id_woofer" required>
        <option value="">-- Choisir un woofer --</option>
        <?php foreach ($woofers as $woofer): ?>
          <option value="<?= $woofer['id'] ?>">
            <?= htmlspecialchars($woofer['prenom'] . ' ' . $woofer['nom']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Mode de paiement :</label>
      <select name="mode_paiement" required>
        <option value="">-- Mode de paiement --</option>
        <option value="cash">Espèces</option>
        <option value="card">Carte</option>
      </select>

      <label>Date de vente :</label>
      <input type="date" name="date_vente" required>

      <button type="submit">Enregistrer</button>
    </form>
  </section>

  <section class="sales-history">
    <h2>Historique des Ventes</h2>
    <table>
      <thead>
        <tr>
          <th>Produit</th>
          <th>Quantité</th>
          <th>Woofer</th>
          <th>Mode de paiement</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($ventes as $vente): ?>
          <tr>
            <td><?= htmlspecialchars($vente['nom_produit']) ?></td>
            <td><?= htmlspecialchars($vente['quantite']) ?></td>
            <td><?= htmlspecialchars($vente['prenom_woofer'] . ' ' . $vente['nom_woofer']) ?></td>
            <td><?= htmlspecialchars($vente['modepaiement']) ?></td>
            <td><?= htmlspecialchars($vente['datevente']) ?></td>
            <td>
              <form method="post" action="../../controllers/controleurVente/controleurVente.php" onsubmit="return confirm('Supprimer cette vente ?');">
                <input type="hidden" name="action" value="supprimer_vente">
                <input type="hidden" name="id_vente" value="<?= $vente['id'] ?>">
                <button type="submit">Supprimer</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</main>
<footer>
  <p>&copy; 2025 Ferme Écoresponsable. Tous droits réservés.</p>
</footer>
</body>
</html>
