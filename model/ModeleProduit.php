<?php
// ModeleProduit.php
require_once __DIR__ . '/../db/db_connect.php';

class ModeleProduit{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenirTousAvecStock() {
        $sql = "SELECT p.id, p.nom, p.categorie, p.prix, s.quantiteActuelle 
                FROM produit p
                JOIN stock_produit sp ON p.id = sp.id_produit
                JOIN stock s ON sp.id_stock = s.id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creerProduit($nom, $categorie, $prix, $quantiteInitiale) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO produit (nom, categorie, prix) VALUES (?, ?, ?)"
        );
        $stmt->execute([$nom, $categorie, $prix]);
        $idProduit = $this->pdo->lastInsertId();

        $date = date('Y-m-d H:i:s');
        $stmt2 = $this->pdo->prepare(
            "INSERT INTO stock (quantiteActuelle, dateDerniereMaj) VALUES (?, ?)"
        );
        $stmt2->execute([$quantiteInitiale, $date]);
        $idStock = $this->pdo->lastInsertId();

        $stmt3 = $this->pdo->prepare(
            "INSERT INTO stock_produit (id_stock, id_produit) VALUES (?, ?)"
        );
        $stmt3->execute([$idStock, $idProduit]);

        return $idProduit;
    }
    public function obtenirProduitParId($idProduit) {
        $sql = "SELECT * FROM produit WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idProduit]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function obtenirQuantiteProduit($idProduit) {
        $sql = "SELECT quantiteactuelle FROM stock WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idProduit]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['quantiteactuelle'] : 0;
    }

}
?>