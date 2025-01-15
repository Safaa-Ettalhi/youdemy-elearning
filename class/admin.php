<?php

class Admin extends User {

    public function __construct($id, $nom, $email, $mot_de_passe, $pdo) {
        parent::__construct($id, $nom, $email, $mot_de_passe, 'Administrateur', $pdo);
    }

    public function validateTeacherAccount($enseignantId) {
        $stmt = $this->pdo->prepare('UPDATE Utilisateur SET statut = "validé" WHERE id = ?');
        return $stmt->execute([$enseignantId]);
    }

    public function suspendUser($userId) {
        $stmt = $this->pdo->prepare('UPDATE Utilisateur SET statut = "suspendu" WHERE id = ?');
        return $stmt->execute([$userId]);
    }

    public function deleteUser($userId) {
        $stmt = $this->pdo->prepare('DELETE FROM Utilisateur WHERE id = ?');
        return $stmt->execute([$userId]);
    }
}

?>
