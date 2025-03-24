<?php
class ModeleParticipant {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter un participant
    public function ajouterParticipant($utilisateur_id, $telephone) {
        $sql = "INSERT INTO participant (utilisateur_id, telephone) 
                VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$utilisateur_id, $telephone]);
    }

    // Récupérer tous les participants
    public function getAllParticipants() {
        $sql = "SELECT p.id, u.nom, u.prenom, u.email, p.telephone
                FROM participant p
                JOIN utilisateur u ON p.utilisateur_id = u.id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un participant par ID
    public function getParticipantById($id) {
        $sql = "SELECT p.id, u.nom, u.prenom, u.email, p.telephone
                FROM participant p
                JOIN utilisateur u ON p.utilisateur_id = u.id
                WHERE p.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
