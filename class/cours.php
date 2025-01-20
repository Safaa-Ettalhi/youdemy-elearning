<?php
class Course {
    protected $id;
    protected $titre;
    protected $description;
    protected $contenu;
    protected $image;
    protected $typeContenu;  
    protected $video;
    protected $fichier_document;
    protected $enseignant_id;
    protected $categorie_id;
    protected $price;
    protected $pdo;

    public function __construct($pdo, $id = null, $titre = '', $description = '', $contenu = '', $image = null, $prix = 0, $typeContenu = 'video', $enseignant_id = null, $categorie_id = null) {
        $this->pdo = $pdo;
        $this->titre = $titre;
        $this->description = $description;
        $this->contenu = $contenu;
        $this->image = $image;
        $this->price = $prix;
        $this->typeContenu = $typeContenu;
        $this->enseignant_id = $enseignant_id;
        $this->categorie_id = $categorie_id;
    }

    public function addCourse($title, $description, $filePath, $imagePath, $price, $teacherId, $categoryId, $tags, $contentType) {
        throw new Exception("Cette méthode doit être surchargée par les classes dérivées.");
    }
    public function updateCourse($course_id, $title, $description, $filePath, $imagePath, $price, $teacherId, $category_id, $tags, $content_type, $videoPath) {
        try {
    
            $stmt = $this->pdo->prepare("SELECT user_id FROM cours WHERE id = ?");
            $stmt->execute([$course_id]);
            $course = $stmt->fetch();

            if (!$course) {
                throw new Exception("Cours introuvable.");
            }

            if ((int)$teacherId !== (int)$course['user_id']) {
                throw new Exception("Vous n'êtes pas autorisé à modifier ce cours.");
            }
     
            $stmt = $this->pdo->prepare("
                UPDATE cours
                SET titre = ?, description = ?, fichier_document = ?, image = ?, prix = ?, categorie_id = ?, typeContenu = ?, vedio = ?
                WHERE id = ?
            ");
            $stmt->execute([$title, $description, $filePath, $imagePath, $price, $category_id, $content_type, $videoPath, $course_id]);

            $stmt = $this->pdo->prepare("DELETE FROM cours_tags WHERE cours_id = ?");
            $stmt->execute([$course_id]);

            if (!empty($tags)) {
                $stmt = $this->pdo->prepare("INSERT INTO cours_tags (cours_id, tag_id) VALUES (?, ?)");
                foreach ($tags as $tag_id) {
                    $stmt->execute([$course_id, $tag_id]);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }



    public function getCourses() {
       
    }
    public function getCourseById($course_id) {
    
    $stmt = $this->pdo->prepare('
       SELECT 
        c.*, 
        t.nom AS tag_nom,
        t.id AS tag_id
    FROM 
        Cours c
    JOIN 
        Cours_Tags ct ON c.id = ct.cours_id
    JOIN 
        Tag t ON ct.tag_id = t.id
    WHERE 
        c.id = :course_id
    ');

    $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $stmt->execute();

    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        throw new Exception("Cours introuvable avec l'ID fourni.");
    }

    return $course;
}

    public function searchCourses($searchTerm) {
    $stmt = $this->pdo->prepare('
        SELECT Cours.*, Utilisateur.nom, Utilisateur.avatar AS avatar, Category.nom AS category_name, 
        GROUP_CONCAT(Tag.nom) AS tags, COUNT(Enrollment.user_id) AS nbr_etudiants
        FROM Cours
        LEFT JOIN Category ON Cours.categorie_id = Category.id
        LEFT JOIN Cours_Tags ON Cours.id = Cours_Tags.cours_id
        LEFT JOIN Tag ON Cours_Tags.tag_id = Tag.id
        LEFT JOIN Utilisateur ON Cours.user_id = Utilisateur.id
        LEFT JOIN Enrollment ON Cours.id = Enrollment.cours_id
        WHERE Cours.titre LIKE :searchTerm 
           OR Cours.description LIKE :searchTerm
        GROUP BY Cours.id, Utilisateur.nom, Category.nom
    ');

    $searchTermWithWildcards = '%' . $searchTerm . '%';
    $stmt->bindParam(':searchTerm', $searchTermWithWildcards, PDO::PARAM_STR);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getTotalCourses() {
   
    $sql = "SELECT COUNT(*) FROM Cours";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchColumn();
}

public function getDashboardStats($id_enseignant) {
    
    $stats = [];
    $stmt = $this->pdo->prepare('SELECT COUNT(*) AS total_cours FROM Cours WHERE user_id = :id_enseignant');
    $stmt->bindValue(':id_enseignant', $id_enseignant, PDO::PARAM_INT);
    $stmt->execute();
    $stats['total_cours'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_cours'];
    $stmt = $this->pdo->prepare('
        SELECT COUNT(DISTINCT Enrollment.user_id) AS total_etudiants
        FROM Enrollment
        INNER JOIN Cours ON Cours.id = Enrollment.cours_id
        WHERE Cours.user_id = :id_enseignant
    ');
    $stmt->bindValue(':id_enseignant', $id_enseignant, PDO::PARAM_INT);
    $stmt->execute();
    $stats['total_etudiants'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_etudiants'];

    return $stats;
}

public function getCompletionRate($id_enseignant) {
    $stmt = $this->pdo->prepare('
        SELECT COUNT(*) AS completed_courses 
        FROM Enrollment
        INNER JOIN Cours ON Cours.id = Enrollment.cours_id
        WHERE Enrollment.status = "Complet" AND Cours.user_id = :id_enseignant
    ');
    $stmt->bindValue(':id_enseignant', $id_enseignant, PDO::PARAM_INT);
    $stmt->execute();
    $completed = $stmt->fetch(PDO::FETCH_ASSOC)['completed_courses'];
    $stmt = $this->pdo->prepare('
        SELECT COUNT(*) AS total_courses 
        FROM Enrollment
        INNER JOIN Cours ON Cours.id = Enrollment.cours_id
        WHERE Cours.user_id = :id_enseignant
    ');
    $stmt->bindValue(':id_enseignant', $id_enseignant, PDO::PARAM_INT);
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total_courses'];
    if ($total == 0) {
        return 0; 
    }

    return round(($completed / $total) * 100, 2);
}

public function getCoursesByEnseignant($enseignant_id) {
        
    }


public function deleteCourse($courseId) {
        try {
            
            $deleteStmt = $this->pdo->prepare("DELETE FROM Cours WHERE id = ?");
            $deleteStmt->execute([$courseId]);

            if ($deleteStmt->rowCount() == 0) {
                throw new Exception("Aucun cours trouvé avec l'ID fourni.");
            }
        } catch (Exception $e) {
           
            throw new Exception("Erreur lors de la suppression du cours : " . $e->getMessage());
        }
    }    
}

?>
