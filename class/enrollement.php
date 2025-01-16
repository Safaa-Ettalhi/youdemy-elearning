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
    public function addEnrollment($course_id, $user_id) {
        $stmt = $this->pdo->prepare("INSERT INTO Enrollment (etudiant_id, cours_id) VALUES (:user_id,:course_id)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':course_id', $course_id);
        
        $stmt->execute();
    }
    public function getUserEnrollments($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT c.* 
            FROM enrollment e 
            JOIN cours c ON e.cours_id = c.id 
            WHERE e.etudiant_id = :user_id
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les cours auxquels un étudiant est inscrit
    public function getEnrollmentsByStudent($etudiant_id) {
    $stmt = $this->pdo->prepare('
        SELECT 
            Cours.*, 
            Enrollment.date_inscription,
            Utilisateur.nom AS enseignant_nom,
            Utilisateur.avatar AS enseignant_avatar
        FROM Enrollment
        JOIN Cours ON Enrollment.cours_id = Cours.id
        JOIN Utilisateur ON Cours.user_id = Utilisateur.id
        WHERE Enrollment.user_id = :etudiant_id
    ');

    $stmt->bindParam(':etudiant_id', $etudiant_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Récupérer les étudiants inscrits à un cours
    public function getEnrollmentsByCourse($cours_id) {
    $stmt = $this->pdo->prepare('
        SELECT Utilisateur.*, Enrollment.date_inscription
        FROM Enrollment
        JOIN Utilisateur ON Enrollment.user_id = Utilisateur.id
        WHERE Enrollment.cours_id = :cours_id AND Utilisateur.role = "Etudiant"
    ');

    $stmt->bindParam(':cours_id', $cours_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Définir l'ID de l'étudiant et du cours pour ajouter une inscription
    public function setEnrollmentDetails($etudiant_id, $cours_id) {
        $this->etudiant_id = $etudiant_id;
        $this->cours_id = $cours_id;
    }
}

?>
