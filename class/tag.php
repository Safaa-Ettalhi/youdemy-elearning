<?php

class Tag {
    private $id;
    private $nom;
    private $pdo;

    public function __construct($pdo, $id = null, $nom = '') {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->nom = $nom;
    }
    public function getTags() {
        $stmt = $this->pdo->query('SELECT * FROM Tag');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagsByCourseId($course_id) {
        $stmt = $this->pdo->prepare("SELECT t.nom AS tag_nom, t.id AS tag_id 
                                     FROM Cours_Tags ct 
                                     JOIN Tag t ON ct.tag_id = t.id 
                                     WHERE ct.cours_id = :course_id");
        $stmt->execute(['course_id' => $course_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
