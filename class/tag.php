<?php

class Tag {
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

    // Créer un tag
    public function createTag($nom) {
        $stmt = $this->pdo->prepare('INSERT INTO Tag (nom) VALUES (?)');
        return $stmt->execute([$nom]);
    }

    // Récupérer les tags
    public function getTags() {
        $stmt = $this->pdo->query('SELECT * FROM Tag');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprimer un tag
    public function deleteTag($id) {
        $stmt = $this->pdo->prepare('DELETE FROM Tag WHERE id = ?');
        return $stmt->execute([$id]);
    }
}

?>
