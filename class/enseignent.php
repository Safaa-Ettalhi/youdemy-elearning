<?php
require_once 'user.php';
class Enseignant extends User {

    public function __construct($id, $nom, $email, $mot_de_passe, $pdo) {
        parent::__construct($id, $nom, $email, $mot_de_passe, 'Enseignant', $pdo);
    }

    public function addCourse($titre, $description, $contenu, $image, $video, $fichier_document, $categorie_id) {
        $stmt = $this->pdo->prepare('INSERT INTO Cours (titre, description, contenu, image, video, fichier_document, enseignant_id, categorie_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$titre, $description, $contenu, $image, $video, $fichier_document, $this->id, $categorie_id]);
    }

    public function getCourses() {
        $stmt = $this->pdo->prepare('SELECT * FROM Cours WHERE enseignant_id = ?');
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getEnseignentPaginated($page, $perPage) {
    $offset = ($page - 1) * $perPage;

    $stmt = $this->pdo->prepare('
        SELECT Utilisateur.nom AS enseignant_nom, Utilisateur.avatar, 
               COALESCE(Enseignant.descpription, "") AS enseignant_description
        FROM Enseignant
        JOIN Utilisateur ON Enseignant.id = Utilisateur.id
        LIMIT :perPage OFFSET :offset
    ');

    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getTotalEnseignants() {
    $stmt = $this->pdo->prepare('SELECT COUNT(*) as total FROM Enseignant');
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}


}
?>
