<?php
class Course {
    protected $id;
    protected $titre;
    protected $description;
    protected $contenu;
    protected $image;
    protected $typeContenu;  // video ou document
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
    public function getCourses() {
       
    }
    public function getCourseById($id) {
        
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
    // Calculer le nombre total de cours
    $sql = "SELECT COUNT(*) FROM Cours";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchColumn();
}

public function getDashboardStats($id_enseignant) {
    // Exemple de requêtes pour récupérer les statistiques
    $stats = [];

    // Nombre total de cours pour l'enseignant spécifique
    $stmt = $this->pdo->prepare('SELECT COUNT(*) AS total_cours FROM Cours WHERE user_id = :id_enseignant');
    $stmt->bindValue(':id_enseignant', $id_enseignant, PDO::PARAM_INT);
    $stmt->execute();
    $stats['total_cours'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_cours'];

    // Nombre total d'étudiants inscrits dans les cours de cet enseignant
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
    // Requête pour compter le nombre de cours complétés par l'enseignant spécifique
    $stmt = $this->pdo->prepare('
        SELECT COUNT(*) AS completed_courses 
        FROM Enrollment
        INNER JOIN Cours ON Cours.id = Enrollment.cours_id
        WHERE Enrollment.status = "Complet" AND Cours.user_id = :id_enseignant
    ');
    $stmt->bindValue(':id_enseignant', $id_enseignant, PDO::PARAM_INT);
    $stmt->execute();
    $completed = $stmt->fetch(PDO::FETCH_ASSOC)['completed_courses'];

    // Requête pour compter le nombre total de cours pour l'enseignant spécifique
    $stmt = $this->pdo->prepare('
        SELECT COUNT(*) AS total_courses 
        FROM Enrollment
        INNER JOIN Cours ON Cours.id = Enrollment.cours_id
        WHERE Cours.user_id = :id_enseignant
    ');
    $stmt->bindValue(':id_enseignant', $id_enseignant, PDO::PARAM_INT);
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total_courses'];

    // Éviter la division par zéro
    if ($total == 0) {
        return 0; // Aucun cours pour cet enseignant
    }

    // Calcul du taux de complétion
    return round(($completed / $total) * 100, 2);
}




public function getCoursesByEnseignant($enseignant_id) {
        throw new Exception("Cette méthode doit être redéfinie dans les sous-classes.");
    }
}






?>
