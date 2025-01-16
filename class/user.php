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
    
    $sql = "SELECT id FROM Utilisateur WHERE email = :email ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Retourner un message d'erreur si l'email ou le nom d'utilisateur existe déjà
        return "<div class='text-red-500 p-3 mb-4 border border-red-300 bg-red-100 rounded'>L'email existe déjà.</div>";
    } else {
        
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $statut = ($role == 'Etudiant') ? 'actif' : 'En cours';
        
        $sql = "INSERT INTO Utilisateur (nom, email, mot_de_passe, role,statut,avatar) VALUES (:nom, :email, :mot_de_passe, :role,:statut,:avatar)";
        $stmt = $pdo->prepare($sql);

        
        $stmt->bindParam(':nom', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $hashed_password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':avatar', $avatar);
        $stmt->bindParam(':statut', $statut);

        
        if ($stmt->execute()) {
            
            $userId = $pdo->lastInsertId();

            session_start();
            $_SESSION['id'] = $userId;
            $_SESSION['email'] = $email;
            $_SESSION['nom'] = $name;
            $_SESSION['role'] = $role;

            if ($role == 'Etudiant') {
                header('Location: ../etudiant/catalogecours.php');
            } else if($role == 'Enseignant'){
               
                header('Location: ../enseignant/dashbord.php');
            }else{
                header('../admin/dashbord.html');
            }
            exit();
        } else {
            
            return "<div class='text-red-500 p-3 mb-4 border border-red-300 bg-red-100 rounded'>Erreur d'inscription : " . $stmt->errorInfo()[2] . "</div>";
        }
    }
}



    public static function login($email, $mot_de_passe, $pdo) {
        $stmt = $pdo->prepare('SELECT * FROM Utilisateur WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            session_start();
            $_SESSION['id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

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
