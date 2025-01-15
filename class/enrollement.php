<?php

class Enrollment {
    private $pdo;
    private $etudiant_id;
    private $cours_id;
    private $date_inscription;

    // Constructeur pour initialiser les propriétés
    public function __construct($pdo, $etudiant_id = null, $cours_id = null) {
        $this->pdo = $pdo;
        $this->etudiant_id = $etudiant_id;
        $this->cours_id = $cours_id;
        $this->date_inscription = date('Y-m-d');
    }

    // Inscrire un étudiant à un cours
    public function addEnrollment() {
        if ($this->etudiant_id === null || $this->cours_id === null) {
            return false; // Vérification des propriétés
        }

        $stmt = $this->pdo->prepare('
            INSERT INTO Enrollment (etudiant_id, cours_id, date_inscription) 
            VALUES (?, ?, ?)
        ');
        return $stmt->execute([$this->etudiant_id, $this->cours_id, $this->date_inscription]);
    }

    // Récupérer les cours auxquels un étudiant est inscrit
    public function getEnrollmentsByStudent($etudiant_id) {
        $stmt = $this->pdo->prepare('
            SELECT Cours.*, Enrollment.date_inscription 
            FROM Enrollment 
            JOIN Cours ON Enrollment.cours_id = Cours.id 
            WHERE Enrollment.etudiant_id = ?
        ');
        $stmt->execute([$etudiant_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les étudiants inscrits à un cours
    public function getEnrollmentsByCourse($cours_id) {
        $stmt = $this->pdo->prepare('
            SELECT Etudiant.*, Enrollment.date_inscription 
            FROM Enrollment 
            JOIN Etudiant ON Enrollment.etudiant_id = Etudiant.id 
            WHERE Enrollment.cours_id = ?
        ');
        $stmt->execute([$cours_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Définir l'ID de l'étudiant et du cours pour ajouter une inscription
    public function setEnrollmentDetails($etudiant_id, $cours_id) {
        $this->etudiant_id = $etudiant_id;
        $this->cours_id = $cours_id;
    }
}

?>
