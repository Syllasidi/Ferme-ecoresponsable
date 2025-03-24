<?php
require_once __DIR__ . '/../db/db_connect.php';
class ModeleAtelier {
    
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter un atelier
    public function ajouterAtelier($nom, $thematique, $date, $woofer_id, $produit_id) {
        $sql = "INSERT INTO atelier (nom, thematique, date, woofer_id, produit_id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $thematique, $date, $woofer_id, $produit_id]);
    }

    // Récupérer tous les ateliers
    public function getAllAteliers() {
        $sql = "SELECT a.id, a.nom, a.thematique, a.date, u.prenom AS woofer_prenom, u.nom AS woofer_nom, p.categorie AS produit_categorie
                FROM atelier a
                JOIN woofer w ON a.woofer_id = w.id
                JOIN utilisateur u ON w.id = u.id
                JOIN produit p ON a.produit_id = p.id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
