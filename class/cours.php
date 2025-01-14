<?php

class Course {
    private $id;
    private $titre;
    private $description;
    private $contenu;
    private $image;
    private $video;
    private $enseignant_id;
    private $categorie_id;
    private $pdo;

    // Constructeur
    public function __construct($pdo, $id = null, $titre = '', $description = '', $contenu = '', $image = null, $video = null, $enseignant_id = null, $categorie_id = null) {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->contenu = $contenu;
        $this->image = $image;
        $this->video = $video;
        $this->enseignant_id = $enseignant_id;
        $this->categorie_id = $categorie_id;
    }

    // Getters et Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getContenu() {
        return $this->contenu;
    }

    public function setContenu($contenu) {
        $this->contenu = $contenu;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getVideo() {
        return $this->video;
    }

    public function setVideo($video) {
        $this->video = $video;
    }

    public function getEnseignantId() {
        return $this->enseignant_id;
    }

    public function setEnseignantId($enseignant_id) {
        $this->enseignant_id = $enseignant_id;
    }

    public function getCategorieId() {
        return $this->categorie_id;
    }

    public function setCategorieId($categorie_id) {
        $this->categorie_id = $categorie_id;
    }

    // Ajouter un cours
    public function addCourse($tags) {
        $stmt = $this->pdo->prepare('INSERT INTO Cours (titre, description, contenu, image, video, enseignant_id, categorie_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$this->titre, $this->description, $this->contenu, $this->image, $this->video, $this->enseignant_id, $this->categorie_id]);

        // Récupérer l'ID du dernier cours inséré
        $cours_id = $this->pdo->lastInsertId();

        // Associer les tags au cours
        foreach ($tags as $tag_id) {
            $stmt = $this->pdo->prepare('INSERT INTO Cours_Tags (cours_id, tag_id) VALUES (?, ?)');
            $stmt->execute([$cours_id, $tag_id]);
        }

        return true;
    }

    // Récupérer les cours
    public function getCoursesRand() {
    $stmt = $this->pdo->prepare('
        SELECT Cours.*, Utilisateur.nom, Category.nom AS category_name, GROUP_CONCAT(Tag.nom) AS tags
        FROM Cours
        LEFT JOIN Category ON Cours.categorie_id = Category.id
        LEFT JOIN Cours_Tags ON Cours.id = Cours_Tags.cours_id
        LEFT JOIN Tag ON Cours_Tags.tag_id = Tag.id
        LEFT JOIN Utilisateur ON Cours.enseignant_id = Utilisateur.id
        GROUP BY Cours.id, Utilisateur.nom, Category.nom
        ORDER BY RAND() LIMIT 6
    ');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getCourses($page = 1, $perPage = 6) {
    // Calcul de l'offset basé sur la page actuelle
    $offset = ($page - 1) * $perPage;

    $stmt = $this->pdo->prepare('
        SELECT Cours.*, Utilisateur.nom, Utilisateur.avatar AS avatar, Category.nom AS category_name, 
        GROUP_CONCAT(Tag.nom) AS tags, COUNT(Enrollment.etudiant_id) AS nbr_etudiants
        FROM Cours
        LEFT JOIN Category ON Cours.categorie_id = Category.id
        LEFT JOIN Cours_Tags ON Cours.id = Cours_Tags.cours_id
        LEFT JOIN Tag ON Cours_Tags.tag_id = Tag.id
        LEFT JOIN Utilisateur ON Cours.enseignant_id = Utilisateur.id
        LEFT JOIN Enrollment ON Cours.id = Enrollment.cours_id
        GROUP BY Cours.id, Utilisateur.nom, Category.nom
        LIMIT :perPage OFFSET :offset
    ');

    // Liaison des paramètres
    $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getTotalCourses() {
    // Calculer le nombre total de cours
    $sql = "SELECT COUNT(*) FROM Cours";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchColumn();
}
// Récupérer un cours par son ID
public function getCourseById($course_id) {
    $stmt = $this->pdo->prepare('
        SELECT 
            Cours.*, 
            Utilisateur.nom AS enseignant, 
            Utilisateur.avatar, 
            Category.nom AS categorie, 
            COALESCE(Enseignant.descpription, "") AS enseignant_description, 
            GROUP_CONCAT(Tag.nom) AS tags, 
            COUNT(Enrollment.etudiant_id) AS nbr_etudiants
        FROM Cours
        LEFT JOIN Category ON Cours.categorie_id = Category.id
        LEFT JOIN Cours_Tags ON Cours.id = Cours_Tags.cours_id
        LEFT JOIN Tag ON Cours_Tags.tag_id = Tag.id
        LEFT JOIN Enseignant ON Cours.enseignant_id = Enseignant.id
        LEFT JOIN Utilisateur ON Cours.enseignant_id = Utilisateur.id
        LEFT JOIN Enrollment ON Cours.id = Enrollment.cours_id
        WHERE Cours.id = :id
        GROUP BY Cours.id, Utilisateur.nom, Category.nom
    ');
    $stmt->bindParam(':id', $course_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}



    // Supprimer un cours
    public function deleteCourse($cours_id) {
        $stmt = $this->pdo->prepare('DELETE FROM Cours WHERE id = ?');
        return $stmt->execute([$cours_id]);
    }
}

?>
