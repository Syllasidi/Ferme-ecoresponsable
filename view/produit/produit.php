<?php
require_once __DIR__ . '/../../db/db_connect.php';
require_once __DIR__ . '/../../model/ModeleProduit.php';
require_once __DIR__. '/../../model/ModeleMouvement.php'; 
$mouvementModel = new ModeleMouvement($pdo); 
$derniersMouvements = $mouvementModel->getDerniersMouvementsParProduit();

$produitModel = new ModeleProduit($pdo);
$produits = $produitModel->obtenirTousAvecStock();

if (!isset($produits) || !is_array($produits)) {
    $produits = [];
}

// Messages d’alerte
$confirmation = isset($_GET['success']) && $_GET['success'] === '1';
$erreurStock = isset($_GET['erreur']) && $_GET['erreur'] === 'stock_insuffisant';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Gestion des Stocks - Ferme Écoresponsable</title>
  <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>
<header>
        <div class="logo">Ferme Écoresponsable</div>
        <button class="logout-button">Déconnexion</button>
    </header>
  <nav>
    <a href="produit.php">Gestion de stock</a>
        <a href="../../view/tableauDeBord/tableauDeBord.php">Tableau de Bord</a>
        <a href="../../view/vente/vente.php">Gestion des Ventes</a>
        <a href="../../view/Atelier/atelier.php">Gestion des Ateliers</a>
        <a href="../../view/woofer/woofer.php">Gestion des Woofers</a>
    </nav>

<div class="container">

  <h1>Gestion des Stocks</h1>

  <?php if ($confirmation): ?>
    <p style="color: green; font-weight: bold;">✅ Produit ajouté avec succès !</p>
  <?php endif; ?>

  <?php if ($erreurStock): ?>
    <p style="color: red; font-weight: bold;">❌ Erreur : quantité en stock insuffisante pour cette sortie.</p>
  <?php endif; ?>

  <!-- 1. Liste des produits avec quantité actuelle en stock -->
  <h2>Liste des produits</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Nom</th>
        <th>Catégorie</th>
        <th>Prix (€)</th>
        <th>Quantité en stock</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($produits as $produit): ?>
        <?php
          $alerteStock = $produit['quantiteactuelle'] <= 5;
          $style = $alerteStock ? 'style="color: red; font-weight: bold;"' : '';
        ?>
        <tr <?= $style ?>>
          <td><?= htmlspecialchars($produit['nom']) ?></td>
          <td><?= htmlspecialchars($produit['categorie']) ?></td>
          <td><?= htmlspecialchars(number_format($produit['prix'], 2, '.', '')) ?></td>
          <td><?= htmlspecialchars($produit['quantiteactuelle']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h2>Derniers mouvements par produit</h2> 
  <table class="table"> 
    <thead> <tr> <th>Produit</th> 
    <th>Date</th> 
    <th>Type</th> 
    <th>Quantité</th>
     <th>Utilisateur</th>
     </tr> </thead> <tbody> 
      <?php foreach ($derniersMouvements as $m): ?> 
        <tr> <td><?= htmlspecialchars($m['produit']) ?>
      </td> <td><?= htmlspecialchars($m['date']) ?>
    </td> <td><?= htmlspecialchars($m['type']) ?>
  </td> <td><?= htmlspecialchars($m['quantite']) ?>
</td> <td><?= htmlspecialchars($m['utilisateur']) ?>
</td> </tr> <?php endforeach; ?> </tbody> </table>
  

  <!-- 2. Formulaire d'ajout d'un nouveau produit -->
  <h2>Ajouter un nouveau produit</h2>
  <form method="post" action="../../controllers/controleurStock/controleurStock.php">
    <input type="hidden" name="action" value="ajouter_produit">
    <div class="form-group">
      <label for="name">Nom du produit :</label>
      <input type="text" name="nom" id="name" required />
    </div>
    <div class="form-group">
      <label for="category">Catégorie :</label>
      <input type="text" name="categorie" id="category" required />
    </div>
    <div class="form-group">
      <label for="price">Prix (EUR) :</label>
      <input type="number" step="0.01" name="prix" id="price" required />
    </div>
    <div class="form-group">
      <label for="initialQty">Quantité initiale :</label>
      <input type="number" name="quantite" id="initialQty" required />
    </div>
    <button type="submit" class="btn">Ajouter</button>
  </form>

  <!-- 3. Formulaire d'entrée/sortie de stock -->
  <h2>Entrée / Sortie de stock</h2>
  <form method="post" action="../../controllers/controleurStock/controleurStock.php">
    <input type="hidden" name="action" value="mouvement_stock">
    <div class="form-group">
      <label for="product_id">Produit :</label>
      <select name="id_produit" id="product_id" required>
        <option value="">-- Choisissez un produit --</option>
        <?php foreach ($produits as $produit): ?>
          <option value="<?= $produit['id'] ?>"><?= htmlspecialchars($produit['nom']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label for="quantity">Quantité :</label>
      <input type="number" name="quantite" id="quantity" required />
    </div>
    <div class="form-group">
      <label for="type">Type de mouvement :</label>
      <select name="type" id="type">
        <option value="entree">Entrée (ajout)</option>
        <option value="sortie">Sortie (retrait)</option>
      </select>
    </div>
    <button type="submit" class="btn">Valider</button>
  </form>

</div>
<footer>
  <p>&copy; 2025 Ferme Écoresponsable. Tous droits réservés.</p>
</footer>
</body>
</html>
