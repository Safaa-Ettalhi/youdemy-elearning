<?php
require_once '../../class/db.php';
require_once '../../class/cours.php'; 

$db = new Database();
$pdo = $db->getPDO();
$course = new Course($pdo);

if (isset($_GET['id'])) {
    $coursId = $_GET['id'];

    try {
        
        $course->deleteCourse($coursId);
        header("Location: ../dashbord.php");
        exit;
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Aucun cours à supprimer.";
}

?>
