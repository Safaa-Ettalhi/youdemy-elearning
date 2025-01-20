<?php
require_once 'user.php';

class Admin extends User {
    public function __construct($id, $nom, $email, $mot_de_passe, $pdo) {
        parent::__construct($id, $nom, $email, $mot_de_passe, 'Administrateur', $pdo);
    }

    public function getCoursesCount() {
        $query = "SELECT COUNT(*) as total FROM cours";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getPopularCourse() {
        $query = "SELECT titre FROM cours ORDER BY id DESC LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['titre'];
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

    public function deleteCourse($courseId) {
        $query = "DELETE FROM cours WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':id' => $courseId]);
    }

    public function getCategories() {
        $query = "SELECT * FROM category ORDER BY id DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCategory($nom) {
    $checkQuery = "SELECT COUNT(*) FROM category WHERE nom = :nom";
    $checkStmt = $this->pdo->prepare($checkQuery);
    $checkStmt->execute([':nom' => $nom]);
    $categoryExists = $checkStmt->fetchColumn();

    if ($categoryExists) {
        return "La catégorie $nom existe déjà.";
    }
    $query = "INSERT INTO category (nom) VALUES (:nom)";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([':nom' => $nom]);

    return "Catégorie ajoutée avec succès!";
}



    public function deleteCategory($categoryId) {
        $query = "DELETE FROM category WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':id' => $categoryId]);
    }

    public function getTags() {
        $query = "SELECT * FROM tag ORDER BY id DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTag($nom) {
    $checkQuery = "SELECT COUNT(*) FROM tag WHERE nom = :nom";
    $checkStmt = $this->pdo->prepare($checkQuery);
    $checkStmt->execute([':nom' => $nom]);
    $tagExists = $checkStmt->fetchColumn();

    if ($tagExists) {
        return "Le tag $nom existe déjà.";
    }
    $query = "INSERT INTO tag (nom) VALUES (:nom)";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([':nom' => $nom]);

    return "Tag ajouté avec succès!";
}


    public function deleteTag($tagId) {
        $query = "DELETE FROM tag WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':id' => $tagId]);
    }

    public function addMultipleTags($tags) {
    $query = "INSERT INTO tag (nom) VALUES (:nom)";
    $stmt = $this->pdo->prepare($query);

    $addedTags = 0;
    $duplicateTags = 0;

    foreach ($tags as $tag) {
        $messageF = '';
        $tag = trim($tag);

        if (!empty($tag)) {
            $checkQuery = "SELECT COUNT(*) FROM tag WHERE nom = :nom";
            $checkStmt = $this->pdo->prepare($checkQuery);
            $checkStmt->execute([':nom' => $tag]);
            $exists = $checkStmt->fetchColumn();

            if ($exists == 0) {
                $stmt->execute([':nom' => $tag]);
                $addedTags++;
            } else {
                $duplicateTags++;
            }
        }
    }
    if ($addedTags > 0) {
        $messageF = "$addedTags tag(s) ajouté(s) avec succès.";
    }

    if ($duplicateTags > 0) {
        $messageF .= " $duplicateTags tag(s) étaient déjà présents et ont été ignorés.";
    }

    return $messageF;
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