<?php

class Category {
    private $id;
    private $nom;
    private $pdo;

    // Constructeur
    public function __construct($pdo, $id = null, $nom = '') {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->nom = $nom;
    }

    // Getter et Setter pour id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter et Setter pour nom
    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    // Créer une catégorie
    public function createCategory($nom) {
        $stmt = $this->pdo->prepare('INSERT INTO Category (nom) VALUES (?)');
        return $stmt->execute([$nom]);
    }

    // Récupérer les catégories
    public function getCategories($page = 1, $perPage = 6) {
    $offset = ($page - 1) * $perPage;
    // Assurez-vous que limit et offset sont des entiers
    $stmt = $this->pdo->prepare('SELECT * FROM Category ORDER BY RAND() LIMIT :perPage OFFSET :offset');
    $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Obtenir le nombre total de catégories
    public function getTotalCategories() {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM Category');
        return $stmt->fetchColumn();
    }

    // Supprimer une catégorie
    public function deleteCategory($id) {
        $stmt = $this->pdo->prepare('DELETE FROM Category WHERE id = ?');
        return $stmt->execute([$id]);
    }
}

?>
