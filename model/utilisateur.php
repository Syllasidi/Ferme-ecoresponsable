<?php
require_once __DIR__ . '/../db/db_connect.php';

class Utilisateur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function creerUtilisateur($nom, $prenom, $email, $motDePasse, $role) {
        $hashedPassword = password_hash($motDePasse, PASSWORD_DEFAULT);
        $sql = "INSERT INTO utilisateur(nom, prenom, email, motDePasse, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $prenom, $email, $hashedPassword, $role]);
    }

    public function getUtilisateurParEmail($email) {
        $sql = "SELECT * FROM utilisateur WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();
  
        return  $user;
    }
    public function getUtilisateursParRole($role) {
         $sql = "SELECT * FROM utilisateur WHERE role = ?"; 
         $stmt = $this->pdo->prepare($sql); 
         $stmt->execute([$role]); 
         return $stmt->fetchAll(PDO::FETCH_ASSOC); }
}
?>
