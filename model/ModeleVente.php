<?php
require_once __DIR__ . '/../db/db_connect.php';

class ModeleVente {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function enregistrerVente($idProduit, $idWoofer, $quantite, $prixTotal, $dateVente, $modePaiement) {
        $sql = "INSERT INTO Vente (id_produit, id_woofer, quantite, prixTotal, dateVente, modePaiement)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idProduit, $idWoofer, $quantite, $prixTotal, $dateVente, $modePaiement]);
    }

    public function obtenirToutesLesVentes() {
        $sql = "SELECT v.*, p.nom AS nom_produit, u.nom AS nom_woofer, u.prenom AS prenom_woofer
                FROM Vente v
                JOIN Produit p ON v.id_produit = p.id
                JOIN Utilisateur u ON v.id_woofer = u.id
                ORDER BY v.dateVente DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function supprimerVente($idVente) {
        $sql = "DELETE FROM Vente WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idVente]);
    }
}
?>