<?php
require_once __DIR__ . '/../../db/db_connect.php';
require_once __DIR__ . '/../../model/ModeleWoofer.php';
require_once __DIR__ . '/../../model/Utilisateur.php';
require_once __DIR__ . '/../../model/ModeleTache.php';  


$tacheModel = new ModeleTache($pdo);

$wooferModel = new ModeleWoofer($pdo);
$utilisateurModel = new Utilisateur($pdo);

$utilisateursWoofer = $utilisateurModel->getUtilisateursParRole('Woofer');

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ( isset($_POST['action']) && $_POST['action'] == 'ajouter_tache') {
        // Récupérer les données du formulaire
        $woofer_id = $_POST['woofer_id'];
        $tache_nom = $_POST['tache_nom'];
        $description = $_POST['description'];
        $date_echeance = $_POST['date_echeance'];
        $statut = $_POST['statut'];
    
        // Ajouter la tâche
        $tacheModel->ajouterTache($woofer_id, $tache_nom, $description, $date_echeance, $statut);
    }

    if (isset($_POST['action']) && $_POST['action'] === 'ajouter_woofer') {
        $idUtilisateur = $_POST['id_utilisateur'] ?? null;
        $dateArrivee = $_POST['date_arrivee'] ?? '';
        $dateDepart = $_POST['date_depart'] ?? '';
       

        $photo = null;
        if (!empty($_FILES['photo']['name'])) {
            $targetDir = __DIR__ . '/../../public/images/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = basename($_FILES['photo']['name']);
            $targetFilePath = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
                $photo = 'public/images/' . $fileName;
            }
        }

        if ($idUtilisateur && $dateArrivee && $dateDepart) {
            if ($wooferModel->ajouterWoofer($idUtilisateur, $dateArrivee, $dateDepart, $photo)) {
                header("Location: ../../view/woofer/woofer.php?success=1");
                exit();
            } else {
                header("Location: ../../view/woofer/woofer.php?erreur=1");
                exit();
            }
        } else {
            header("Location: ../../view/woofer/woofer.php?erreur=1");
            exit();
        }
    } 
         else {
            header("Location: ../../view/woofer/woofer.php?erreur=1");
            exit();
        }
    }


$woofers = $wooferModel->obtenirTousLesWoofers();
$confirmation = isset($_GET['success']) ? (int) $_GET['success'] : 0;
$erreur = isset($_GET['erreur']);
?>