<?php

class Category {
    private $id;
    private $nom;
    private $pdo;
    public function __construct($pdo, $id = null, $nom = '') {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->nom = $nom;
    }

    public function addCategory($nom) {
    $checkQuery = "SELECT COUNT(*) FROM category WHERE nom = :nom";
    $checkStmt = $this->pdo->prepare($checkQuery);
    $checkStmt->execute([':nom' => $nom]);
    $categoryExists = $checkStmt->fetchColumn();

    if ($categoryExists) {
        return "La catégorie $nom existe déjà.";
    }
    $query = "INSERT INTO category (nom) VALUES (:nom)";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([':nom' => $nom]);

    return "Catégorie ajoutée avec succès!";
}

public function getCategories() {
        $query = "SELECT * FROM category ORDER BY id DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteCategory($categoryId) {
        $query = "DELETE FROM category WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':id' => $categoryId]);
    }   
}
?>
