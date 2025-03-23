<?php
require_once __DIR__ . '/../../db/db_connect.php';
require_once __DIR__ . '/../../model/ModeleVente.php';
require_once __DIR__ . '/../../model/ModeleProduit.php';
require_once __DIR__ . '/../../model/ModeleWoofer.php';
require_once __DIR__ . '/../../model/ModeleStock.php';

$venteModel = new ModeleVente($pdo);
$produitModel = new ModeleProduit($pdo);
$wooferModel = new ModeleWoofer($pdo);
$stockModel = new ModeleStock($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($_POST['action'] === 'enregistrer_vente') {
    $idProduit = $_POST['id_produit'];
    $quantite = (int) $_POST['quantite'];
    $idWoofer = $_POST['id_woofer'];
    $modePaiement = $_POST['mode_paiement'];
    $dateVente = $_POST['date_vente'];

    $quantiteDispo = $produitModel->obtenirQuantiteProduit($idProduit);
    $produit = $produitModel->obtenirProduitParId($idProduit);

    if ($quantiteDispo < $quantite) {
      header("Location: ../../view/vente/vente.php?erreur=stock_insuffisant");
      exit();
    }

    $prixTotal = $produit['prix'] * $quantite;

    try {
      $pdo->beginTransaction();
      $venteModel->enregistrerVente($idProduit, $idWoofer, $quantite, $prixTotal, $dateVente, $modePaiement);
      $stockModel->mettreAJourQuantite($idProduit, -$quantite);
      $pdo->commit();
      header("Location: ../../view/vente/vente.php?success=1");
      exit();
    } catch (Exception $e) {
      $pdo->rollBack();
      echo "Erreur : " . $e->getMessage();
      exit();
    }
  }

  if ($_POST['action'] === 'supprimer_vente') {
    $idVente = $_POST['id_vente'];
    $venteModel->supprimerVente($idVente);
    header("Location: ../../view/vente/vente.php");
    exit();
  }
}

$ventes = $venteModel->obtenirToutesLesVentes();
$produits = $produitModel->obtenirTousAvecStock();
$woofers = $wooferModel->obtenirTousLesWoofers();

$confirmation = isset($_GET['success']) && $_GET['success'] === '1';
$erreurStock = isset($_GET['erreur']) && $_GET['erreur'] === 'stock_insuffisant';
?>

<!-- Reste du HTML de la vue identique à ton code actuel -->
