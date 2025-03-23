<?php
class ModeleMouvement {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function enregistrerMouvement($idProduit, $quantite, $idUtilisateur, $type) {
        $sql = "INSERT INTO mouvement (date, quantite, id_produit, id_utilisateur, type) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $date = date('Y-m-d H:i:s');
        $stmt->execute([$date, $quantite, $idProduit, $idUtilisateur, $type]);
        return $this->pdo->lastInsertId();
    }

    public function getDerniersMouvementsParProduit() {
         $sql = " SELECT DISTINCT ON (m.id_produit) m.id_produit, m.date, m.quantite, m.type, u.nom AS utilisateur, p.nom AS produit FROM 
         mouvement m JOIN utilisateur u ON m.id_utilisateur = u.id JOIN produit p ON m.id_produit = p.id ORDER BY 
         m.id_produit, m.date DESC; ";
          $stmt = $this->pdo->prepare($sql); 
          $stmt->execute(); return $stmt->fetchAll(); }
}
?>