<?php

class Tag {
    private $id;
    private $nom;
    private $pdo;

    public function __construct($pdo, $id = null, $nom = '') {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->nom = $nom;
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
public function deleteTag($tagId) {
        $query = "DELETE FROM tag WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':id' => $tagId]);
    }

    public function getTagsByCourseId($course_id) {
        $stmt = $this->pdo->prepare("SELECT t.nom AS tag_nom, t.id AS tag_id 
                                     FROM Cours_Tags ct 
                                     JOIN Tag t ON ct.tag_id = t.id 
                                     WHERE ct.cours_id = :course_id");
        $stmt->execute(['course_id' => $course_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
