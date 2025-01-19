<?php

class Enrollment {
    private $pdo;
    private $etudiant_id;
    private $cours_id;
    private $date_inscription;

    public function __construct($pdo, $etudiant_id = null, $cours_id = null) {
        $this->pdo = $pdo;
        $this->etudiant_id = $etudiant_id;
        $this->cours_id = $cours_id;
        $this->date_inscription = date('Y-m-d');
    }

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


public function updateEnrollmentStatus($coursId, $userId, $newStatus = 'Complet') {
        try {
          
            $query = "UPDATE Enrollment SET status = :status WHERE cours_id = :coursId AND user_id = :userId";
            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
            $stmt->bindParam(':coursId', $coursId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            
            error_log("Erreur lors de la mise à jour du statut : " . $e->getMessage());
            return false;
        }
    }
  
}

?>
