<?php
session_start();

require_once '../../class/db.php';  
require_once '../../class/cours.php'; 
include('../../class/videoCourse.php');  
include('../../class/documentCourse.php');
$db = new Database();
$pdo = $db->getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $categoryId = $_POST['category'];
    $contentType = $_POST['content-type'];
    $price = $_POST['price']; // Prix du cours
    $tags = isset($_POST['tags']) ? explode(',', $_POST['tags'][0]) : [];  // Transforme la chaîne en tableau
    $teacherId = $_SESSION['id'];
var_dump($tags);
    
    if (isset($_FILES['file-upload-image'])) {
        $image = $_FILES['file-upload-image'];
        $imagePath = '../../uploads/avatars/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);
    } else {
        $imagePath = null;
    }

    if ($contentType === 'video' && isset($_FILES['file-upload-video'])) {
        $file = $_FILES['file-upload-video'];
        $filePath = 'uploads/vedio/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $filePath);

        $course = new VideoCourse($pdo);
        $courseId = $course->addCourse($title, $description, $filePath, $imagePath, $price, $teacherId, $categoryId, $tags, $contentType);
        header('Location: ../dashbord.php');
    } elseif ($contentType === 'document' && isset($_FILES['file-upload-document'])) {
        $file = $_FILES['file-upload-document'];
        $filePath = 'uploads/document/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $filePath);

        $course = new DocumentCourse($pdo);
        $courseId = $course->addCourse($title, $description, $filePath, $imagePath, $price, $teacherId, $categoryId, $tags, $contentType);
        header('Location: ../dashbord.php');
        
    } else {
        echo "Erreur : Veuillez télécharger un fichier valide.";
    }
}
?>