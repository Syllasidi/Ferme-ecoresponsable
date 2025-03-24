<?php
require_once __DIR__ . '/../db/db_connect.php';


class ModelePlanning{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter un planning
    public function creerPlanning($tacheId, $wooferId, $datePlanifiee) {
        $sql = "INSERT INTO planning (tache_id, woofer_id, date_planifiee) 
                VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$tacheId, $wooferId, $datePlanifiee]);
    }

    // Récupérer le planning par ID
    public function getPlanningParId($id) {
        $sql = "SELECT * FROM planning WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function getPlanningParWoofer($wooferId) {
        $sql = "SELECT p.id, t.nom AS tache_nom, p.date_planifiee
                FROM planning p
                JOIN taches t ON p.tache_id = t.id
                WHERE p.woofer_id = ?
                ORDER BY p.date_planifiee ASC";  
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$wooferId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllPlannings() {
        $sql = "SELECT p.id, t.nom AS tache_nom, p.date_planifiee
                FROM planning p
                JOIN taches t ON p.tache_id = t.id
                ORDER BY p.date_planifiee ASC";  // Trier les plannings par date
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
