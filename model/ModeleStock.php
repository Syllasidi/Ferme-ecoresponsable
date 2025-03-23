<?php

require_once __DIR__ . '/../db/db_connect.php';
class ModeleStock {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function mettreAJourQuantite($idProduit, $variation) {
        $sql = "SELECT s.id, s.quantiteactuelle 
                FROM stock s 
                JOIN stock_produit sp ON s.id = sp.id_stock 
                WHERE sp.id_produit = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idProduit]);
        $stock = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$stock) return false;

        $nouvelleQuantite = $stock['quantiteactuelle'] + $variation;
        if ($nouvelleQuantite < 0) return false;

        $date = date('Y-m-d H:i:s');
        $updateSql = "UPDATE stock SET quantiteactuelle = ?, dateDerniereMaj = ? WHERE id = ?";
        $stmt2 = $this->pdo->prepare($updateSql);
        $stmt2->execute([$nouvelleQuantite, $date, $stock['id']]);
        return true;
    }

    public function obtenirQuantiteActuelle($idProduit) {
        $sql = "SELECT quantiteactuelle FROM stock WHERE id = (
            SELECT id_stock FROM stock_produit WHERE id_produit = ?
        )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idProduit]);
        return (int) $stmt->fetchColumn();
    }
    
}
?>