<?php
require_once 'user.php';
class Etudiant extends User {

    
    public function __construct($id, $nom, $email, $mot_de_passe, $pdo ) {
        parent::__construct($id, $nom, $email, $mot_de_passe, 'Etudiant', $pdo);

    }

}
?>
