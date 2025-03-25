<?php
session_start();

require_once __DIR__ . '/../../db/db_connect.php';
require_once __DIR__ . '/../../model/ModeleProduit.php';
require_once __DIR__ . '/../../model/ModeleStock.php';
require_once __DIR__ . '/../../model/ModeleMouvement.php';

$produitModel = new ModeleProduit($pdo);
$stockModel = new ModeleStock($pdo);
$mouvementModel = new ModeleMouvement($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'ajouter_produit') {
        $nom = $_POST['nom'] ?? '';
        $categorie = $_POST['categorie'] ?? '';
        $prix = $_POST['prix'] ?? 0;
        $quantite = $_POST['quantite'] ?? 0;

        if ($nom && $categorie && $prix >= 0 && $quantite >= 0) {
            $produitModel->creerProduit($nom, $categorie, $prix, $quantite);
           header("Location: ../../view/produit/produit.php?success=1");;
            exit();
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'mouvement_stock') {
        $idProduit = $_POST['id_produit'] ?? null;
        $quantite = $_POST['quantite'] ?? 0;
        $type = $_POST['type'] ?? '';
        $idUtilisateur = $_POST['id_utilisateur'] ?? 1;
    
        if ($idProduit && $quantite > 0 && in_array($type, ['entree', 'sortie'])) {
    
            // Récupérer la quantité actuelle
            $quantiteActuelle = $stockModel->obtenirQuantiteActuelle($idProduit); 
    
            if ($type === 'sortie' && $quantite > $quantiteActuelle) {
                // Trop de sortie demandée → redirection avec erreur
                header("Location: ../../view/produit/produit.php?erreur=stock_insuffisant");
                exit();
            }
    
            $variation = ($type === 'sortie') ? -$quantite : $quantite;
    
            try {
                $pdo->beginTransaction();
                $mouvementModel->enregistrerMouvement($idProduit, $quantite, $idUtilisateur, $type);
                $stockModel->mettreAJourQuantite($idProduit, $variation);
                $pdo->commit();
            } catch (Exception $e) {
                $pdo->rollBack();
                echo "Erreur : " . $e->getMessage();
                exit();
            }
    
            header("Location: ../../view/produit/produit.php?success=1");
            exit();
        }
    }
}    

$produits = $produitModel->obtenirTousAvecStock();


