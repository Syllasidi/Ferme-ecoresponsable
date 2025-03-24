<?php
require_once __DIR__ . '/../db/db_connect.php';

class ModeleTache{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter une tâche
    public function creerTache($nom, $description, $statut, $dateEcheance, $wooferId) {
        $sql = "INSERT INTO taches (nom, description, statut, date_echeance, woofer_id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $description, $statut, $dateEcheance, $wooferId]);
    }

    // Récupérer une tâche par ID
    public function getTacheParId($id) {
        $sql = "SELECT * FROM taches WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getTachesParWoofer($wooferId) {
        $sql = "SELECT t.id, t.nom, t.description, t.date_echeance, t.statut
                FROM taches t
                WHERE t.woofer_id = ? 
                ORDER BY t.date_echeance ASC";  
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$wooferId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mettre à jour le statut de la tâche
    public function MajTacheStatut($id, $statut) {
        $sql = "UPDATE taches SET statut = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$statut, $id]);

    }
    public function getAllTaches() {
        $sql = "SELECT t.id, t.nom, t.description, t.date_creation, t.date_echeance, t.statut, 
                       u.prenom AS woofer_nom, u.nom AS woofer_nom
                FROM taches t
                LEFT JOIN utilisateur u ON t.woofer_id = u.id
                ORDER BY t.date_echeance ASC"; 
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterTache($woofer_id, $tache_nom, $description, $date_echeance, $statut) {
        
            // Insérer la tâche dans la table `taches`
            $sql = "INSERT INTO taches (woofer_id, nom, description, date_echeance, statut) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$woofer_id, $tache_nom, $description, $date_echeance, $statut]);

            // Récupérer l'ID de la tâche ajoutée
            $tache_id = $this->pdo->lastInsertId();

            // Ajouter une entrée dans la table `planning` avec la date planifiée
            $sqlPlanning = "INSERT INTO planning (tache_id, woofer_id, date_planifiee)
                            VALUES (?, ?, ?)";
            $stmtPlanning = $this->pdo->prepare($sqlPlanning);
            $stmtPlanning->execute([$tache_id, $woofer_id, $date_echeance]);

            return   $stmtPlanning;

        
    }
}
?>
