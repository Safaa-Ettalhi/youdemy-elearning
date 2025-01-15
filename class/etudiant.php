<?php

class Etudiant extends User {

    public function __construct($id, $nom, $email, $mot_de_passe, $pdo) {
        parent::__construct($id, $nom, $email, $mot_de_passe, 'Etudiant', $pdo);
    }

    public function enrollInCourse($cours_id) {
        $stmt = $this->pdo->prepare('INSERT INTO Enrollment (etudiant_id, cours_id, date_inscription) VALUES (?, ?, NOW())');
        return $stmt->execute([$this->id, $cours_id]);
    }

    public function getEnrolledCourses() {
        $stmt = $this->pdo->prepare('SELECT Cours.* FROM Cours INNER JOIN Enrollment ON Cours.id = Enrollment.cours_id WHERE Enrollment.etudiant_id = ?');
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
