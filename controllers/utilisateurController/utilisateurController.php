<?php
require_once __DIR__ . '/../../model/utilisateur.php';

$utilisateurModel = new Utilisateur($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'inscription') {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $motDePasse = $_POST['motDePasse'];
        $role = $_POST['role'];; 

        if ($utilisateurModel->creerUtilisateur($nom, $prenom, $email, $motDePasse, $role)) {
            header("Location: ../../view/compte/connexion.php");
            
            exit();
        } else {
            echo "Erreur lors de l'inscription.";
        }
    } elseif ($_POST['action'] === 'connexion') {
        $email = $_POST['email'];
        $motDePasse = $_POST['motDePasse'];

        $user = $utilisateurModel->getUtilisateurParEmail($email);

        if ($user && password_verify($motDePasse, $user['motdepasse'])){
           
            header("Location: ../../view/tableauDeBord/tableauDeBord.php");
            exit();
        } else {
            echo "Email ou mot de passe incorrect.";
        }
    }
}
?>
