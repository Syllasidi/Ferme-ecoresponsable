<?php
require_once __DIR__ . '/../../db/db_connect.php';
require_once __DIR__ . '/../../model/ModeleAtelier.php';
require_once __DIR__ . '/../../model/ModeleParticipant.php';
require_once __DIR__ . '/../../model/Utilisateur.php';

$atelierModel = new ModeleAtelier($pdo);
$participantModel = new ModeleParticipant($pdo);
$utilisateurModel = new Utilisateur($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajouter un atelier
    if (isset($_POST['action']) && $_POST['action'] == 'ajouter_atelier') {
        $nom = $_POST['nom'];
        $thematique = $_POST['thematique'];
        $date = $_POST['date'];
        $woofer_id = $_POST['woofer_id'];
        $categorie = $_POST['categorie'];

        $atelierModel->ajouterAtelier($nom, $thematique, $date, $woofer_id, $categorie);
    }

    // Ajouter un participant à un atelier
    if (isset($_POST['action']) && $_POST['action'] == 'ajouter_participant') {
        $utilisateur_id = $_POST['utilisateur_id'];
        $telephone = $_POST['telephone'];
        $atelier_id = $_POST['atelier_id'];

        // Ajouter le participant
       
        // Associer le participant à l'atelier
        $atelierModel->ajouterParticipantAtelier($atelier_id, $utilisateur_id);
    }
}

header('Location: ../../view/atelier/atelier.php');
exit;
