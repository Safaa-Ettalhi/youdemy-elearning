<?php
include('../class/db.php');

session_start();
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header('Location: ../login.php');
    exit();
}

$utilisateur_id = intval($_SESSION['id']); 

$cours_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : null;
//echo (var_dump($utilisateur_id, $cours_id));
if (!$cours_id) {
    die("Cours non spécifié.");
}

$db = new Database();
$pdo = $db->getPDO();

$stmt = $pdo->prepare("SELECT id FROM Utilisateur WHERE id = ? AND role = 'Etudiant'");
$stmt->execute([$utilisateur_id]);
if ($stmt->rowCount() == 0) {
    die("L'utilisateur avec cet ID n'existe ");
}

try {   
    $stmt = $pdo->prepare("SELECT * FROM Enrollment WHERE user_id = ? AND cours_id = ?");
    $stmt->execute([$utilisateur_id, $cours_id]);

    if ($stmt->rowCount() > 0) {
        header('Location: ./warning.php');
        exit();
    }
    $stmt = $pdo->prepare("INSERT INTO Enrollment (user_id, cours_id, date_inscription) VALUES (?, ?, NOW())");
    $stmt->execute([$utilisateur_id, $cours_id]);

    header('Location: ./succes.php');
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

