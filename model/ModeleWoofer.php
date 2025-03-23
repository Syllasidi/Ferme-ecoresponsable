<?php
require_once __DIR__ . '/../db/db_connect.php';class ModeleWoofer {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenirTousLesWoofers() {
        $sql = "SELECT u.id, u.nom, u.prenom
                FROM Utilisateur u
                JOIN Woofer w ON u.id = w.id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenirWooferParId($id) {
        $sql = "SELECT u.id, u.nom, u.prenom, w.dateArrivee, w.dateDepart, w.photo, w.tachesAttribuees
                FROM Utilisateur u
                JOIN Woofer w ON u.id = w.id
                WHERE u.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ajouterWoofer($idUtilisateur, $dateArrivee, $dateDepart, $photo, $tachesAttribuees) {
        $sql = "INSERT INTO Woofer (id, dateArrivee, dateDepart, photo, tachesAttribuees)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idUtilisateur, $dateArrivee, $dateDepart, $photo, $tachesAttribuees]);
    }
}
?>