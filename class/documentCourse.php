<?php 
class DocumentCourse extends Course {
    public function __construct($pdo, $titre = '', $description = '', $contenu = '', $image = null, $prix = 0, $enseignant_id = null, $categorie_id = null) {
        parent::__construct($pdo, null, $titre, $description, $contenu, $image, $prix, 'document', $enseignant_id, $categorie_id);
    }
    public function addCourse($title, $description, $filePath, $imagePath, $price, $teacherId, $categoryId, $tags, $contentType) {
    $sql = "INSERT INTO cours (titre, description, fichier_document, image, prix, user_id, categorie_id, typeContenu)
            VALUES (:title, :description, :file_path, :image_path, :price, :user_id, :categorie_id, :typeContenu)";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':file_path', $filePath);
    $stmt->bindParam(':image_path', $imagePath);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':user_id', $teacherId);
    $stmt->bindParam(':categorie_id', $categoryId);
    $stmt->bindParam(':typeContenu', $contentType);
    $stmt->execute();
    $courseId = $this->pdo->lastInsertId();

    if (!empty($tags)) {
        foreach ($tags as $tag) {
            $tagQuery = "INSERT INTO cours_tags (cours_id, tag_id) VALUES (:course_id, :tag_id)";
            $tagStmt = $this->pdo->prepare($tagQuery);
            $tagStmt->bindParam(':course_id', $courseId);
            $tagStmt->bindParam(':tag_id', $tag);
            $tagStmt->execute();
        }
    }

    return $courseId;
}


    public function getCourses() {
    $stmt = $this->pdo->prepare("
        SELECT Cours.*, 
            Utilisateur.nom AS nom, 
            Utilisateur.avatar AS avatar, 
            Category.nom AS category_name, 
            GROUP_CONCAT(Tag.nom) AS tags, 
            COUNT(Enrollment.user_id) AS nbr_etudiants
        FROM Cours
        LEFT JOIN Category ON Cours.categorie_id = Category.id
        LEFT JOIN Cours_Tags ON Cours.id = Cours_Tags.cours_id
        LEFT JOIN Tag ON Cours_Tags.tag_id = Tag.id
        LEFT JOIN Utilisateur ON Cours.user_id = Utilisateur.id
        LEFT JOIN Enrollment ON Cours.id = Enrollment.cours_id
        WHERE Cours.typeContenu = 'document'
        GROUP BY Cours.id, Utilisateur.nom, Utilisateur.avatar, Category.nom
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function getCourseById($id) {
    
    $stmt = $this->pdo->prepare("
        SELECT Cours.*, 
            Utilisateur.nom AS enseignant, 
            Utilisateur.avatar AS avatar,
            Utilisateur.bio AS bio, 
            Category.nom AS categorie, 
            COALESCE(Utilisateur.descpription, '') AS enseignant_description, 
            GROUP_CONCAT(Tag.nom) AS tags, 
            COUNT(Enrollment.user_id) AS nbr_etudiants
        FROM Cours
        LEFT JOIN Category ON Cours.categorie_id = Category.id
        LEFT JOIN Cours_Tags ON Cours.id = Cours_Tags.cours_id
        LEFT JOIN Tag ON Cours_Tags.tag_id = Tag.id
        LEFT JOIN Utilisateur ON Cours.user_id = Utilisateur.id
        LEFT JOIN Enrollment ON Cours.id = Enrollment.cours_id
        WHERE Cours.id = :id AND Cours.typeContenu = 'document'
        GROUP BY Cours.id, Utilisateur.nom, Utilisateur.avatar, Utilisateur.bio, Category.nom
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}  
    public function getCoursesByEnseignant($enseignant_id) {
        
        $stmt = $this->pdo->prepare("SELECT c.*, cat.nom AS categorie, 
                (SELECT COUNT(*) FROM Enrollment WHERE cours_id = c.id) AS nbr_etudiants
         FROM Cours c
         LEFT JOIN Category cat ON c.categorie_id = cat.id WHERE user_id = :enseignant_id AND typeContenu = 'document'");
        $stmt->bindParam(':enseignant_id', $enseignant_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>