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

  
   

   
    public function createCategory($nom) {
        $stmt = $this->pdo->prepare('INSERT INTO Category (nom) VALUES (?)');
        return $stmt->execute([$nom]);
    }

  
public function getCategories() {

    $stmt = $this->pdo->prepare('SELECT * FROM Category ');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);}
   

    
    public function deleteCategory($id) {
        $stmt = $this->pdo->prepare('DELETE FROM Category WHERE id = ?');
        return $stmt->execute([$id]);
    }
}

?>
