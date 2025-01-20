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
public function getCategories() {

    $stmt = $this->pdo->prepare('SELECT * FROM Category ');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}

?>
