<?php
require_once 'user.php';
class Enseignant extends User {

    public function __construct($id, $nom, $email, $mot_de_passe, $pdo) {
        parent::__construct($id, $nom, $email, $mot_de_passe, 'Enseignant', $pdo);
    } 
    public function getEnseignantPaginated($pageE, $perPage) {
    $offset = ($pageE - 1) * $perPage;

    $stmt = $this->pdo->prepare('
        SELECT Utilisateur.nom AS enseignant_nom, Utilisateur.avatar, 
               COALESCE(Utilisateur.descpription, "") AS enseignant_description
        FROM Utilisateur
        WHERE Utilisateur.role = "Enseignant"
        LIMIT :perPage OFFSET :offset
    ');

    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getTotalEnseignants() {
    $stmt = $this->pdo->prepare('SELECT COUNT(*) as total FROM Utilisateur WHERE role = "Enseignant"');
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}


}
?>
