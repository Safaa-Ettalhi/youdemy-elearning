<?php

class User {
    protected $id;
    protected $nom;
    protected $email;
    protected $mot_de_passe;
    protected $role;
    protected $pdo;

    public function __construct($id, $nom, $email, $mot_de_passe, $role, $pdo) {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->mot_de_passe = $mot_de_passe;
        $this->role = $role;
        $this->pdo = $pdo;
    }

    public static function register($name, $email, $password, $role,$pdo, $avatar = null) {
    // Vérifier si l'email ou le nom d'utilisateur existe déjà
    $sql = "SELECT id FROM Utilisateur WHERE email = :email ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Retourner un message d'erreur si l'email ou le nom d'utilisateur existe déjà
        return "<div class='text-red-500 p-3 mb-4 border border-red-300 bg-red-100 rounded'>L'email existe déjà.</div>";
    } else {
        // Hacher le mot de passe pour sécurité
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Préparer la requête d'insertion
        $sql = "INSERT INTO Utilisateur (nom, email, mot_de_passe, role,avatar) VALUES (:nom, :email, :mot_de_passe, :role,:avatar)";
        $stmt = $pdo->prepare($sql);

        // Lier les valeurs aux paramètres
        $stmt->bindParam(':nom', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $hashed_password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':avatar', $avatar);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Récupérer l'id de l'utilisateur inséré
            $userId = $pdo->lastInsertId();

            // Démarrer la session et stocker les informations de l'utilisateur
            session_start();
            $_SESSION['id'] = $userId;
            $_SESSION['email'] = $email;
            $_SESSION['nom'] = $name;
            $_SESSION['role'] = $role;

            // Rediriger vers une page spécifique après l'inscription
            if ($role == 'Etudiant') {
                header('Location: ../etudiant/catalogecours.php');
            } else if($role == 'Enseignant'){
                header('Location: ../enseignant/dashbord.php');
            }else{
                header('../admin/dashbord.html');
            }
            exit();
        } else {
            // Retourner un message d'erreur si l'insertion échoue
            return "<div class='text-red-500 p-3 mb-4 border border-red-300 bg-red-100 rounded'>Erreur d'inscription : " . $stmt->errorInfo()[2] . "</div>";
        }
    }
}



    public static function login($email, $mot_de_passe, $pdo) {
        $stmt = $pdo->prepare('SELECT * FROM Utilisateur WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            return new self($user['id'], $user['nom'], $user['email'], $user['mot_de_passe'], $user['role'], $pdo);
        }

        return null;
    }

    public static function logout() {
        
        session_start();
        session_unset();
        session_destroy();
        header('Location: ../login.php');
        exit();
    }

    // Getter methods
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }
}

?>
