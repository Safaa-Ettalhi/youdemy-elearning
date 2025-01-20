<?php
require_once 'user.php';

class Admin extends User {
    public function __construct($id, $nom, $email, $mot_de_passe, $pdo) {
        parent::__construct($id, $nom, $email, $mot_de_passe, 'Administrateur', $pdo);
    }
    public function getPendingTeachers() {
        $query = "SELECT * FROM Utilisateur WHERE role = 'Enseignant' AND statut = 'En cours'";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers() {
        $query = "SELECT * FROM Utilisateur ORDER BY id DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveTeacher($teacherId) {
        $query = "UPDATE Utilisateur SET statut = 'actif' WHERE id = :id AND role = 'Enseignant'";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':id' => $teacherId]);
    }

    public function rejectTeacher($teacherId) {
        $query = "UPDATE Utilisateur SET statut = 'suspendu' WHERE id = :id AND role = 'Enseignant'";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':id' => $teacherId]);
    }

    public function deleteUser($userId) {
        $query = "DELETE FROM Utilisateur WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':id' => $userId]);
    }
    public function rejectUser($userId) {
        $query = "UPDATE Utilisateur SET statut = 'suspendu' WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':id' => $userId]);
    }
    public function activetUser($userId) {
        $query = "UPDATE Utilisateur SET statut = 'actif' WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':id' => $userId]);
    }
    public function getCourses() {
        $query = "SELECT c.*, u.nom as nom_enseignant FROM cours c JOIN Utilisateur u ON c.user_id = u.id ORDER BY c.id DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTop3Enseignants() {
    $query = "
        SELECT u.id, u.nom, COUNT(c.id) as total_cours
        FROM Utilisateur u
        JOIN cours c ON u.id = c.user_id
        WHERE u.role = 'Enseignant'
        GROUP BY u.id, u.nom
        ORDER BY total_cours DESC
        LIMIT 3
    ";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}