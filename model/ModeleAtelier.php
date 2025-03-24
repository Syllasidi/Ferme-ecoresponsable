<?php
class ModeleAtelier {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter un atelier
    public function ajouterAtelier($nom, $thematique, $date, $woofer_id, $categorie) {
        $sql = "INSERT INTO atelier (nom, thematique, date, woofer_id, categorie) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $thematique, $date, $woofer_id, $categorie]);
    }

    // Récupérer tous les ateliers
    public function getAllAteliers() {
        // Mettre à jour la requête pour utiliser 'categorie' et obtenir le nom du produit
        $sql = "SELECT a.id, a.nom, a.thematique, a.date, 
                       u.prenom AS woofer_prenom, u.nom AS woofer_nom, 
                       a.categorie AS produit_categorie, 
                       p.nom AS produit_nom  
                FROM atelier a
                JOIN woofer w ON a.woofer_id = w.id
                JOIN utilisateur u ON w.id = u.id
                LEFT JOIN produit p ON a.categorie = p.categorie";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Ajouter un participant à un atelier
    public function ajouterParticipantAtelier($atelier_id, $participant_id) {
        // Ajouter un participant à l'atelier (table de jointure atelier_participant)
        $sql = "INSERT INTO atelier_participant (atelier_id, utilisateur_id) 
                VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$atelier_id, $participant_id]);
    }
    
    // Récupérer les participants d'un atelier
    public function getParticipantsAtelier($atelier_id) {
        $sql = "SELECT u.nom, u.prenom, u.email
                FROM atelier_participant ap
                JOIN utilisateur u ON u.id = ap.utilisateur_id
                WHERE ap.atelier_id = ? AND u.role = 'Participant'"; // S'assurer que l'utilisateur est un participant
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$atelier_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCategories() {
        $sql = "SELECT DISTINCT categorie FROM produit";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
