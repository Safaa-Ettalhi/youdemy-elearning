<?php 
session_start();
include('../class/db.php');
include('../class/cours.php'); 
include ('../class/categorie.php');
include ('../class/tag.php');
include('../class/videoCourse.php');  
include('../class/documentCourse.php');
require_once '../class/enrollement.php';
$enseignant_id = $_SESSION['id'];
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit();
}
$status = $_SESSION['statut']; 
$id_enseignant=$_SESSION['id'];
if ($status === 'En cours') {
    header('Location: pending.php');
    exit();
} elseif ($status === 'suspendu') {
    header('Location: suspenssed.php');
    exit();
}
$db = new Database();
$pdo = $db->getPDO();
$videoCourse = new VideoCourse($pdo);
$documentCourse = new DocumentCourse($pdo);

$videoCourses = $videoCourse->getCoursesByEnseignant($enseignant_id);
$documentCourses = $documentCourse->getCoursesByEnseignant($enseignant_id);
$course = new Course($pdo);

$stats = $course->getDashboardStats($id_enseignant);
$completionRate = $course->getCompletionRate($id_enseignant);


$category = new Category($pdo);
$tag = new Tag($pdo);

$categories = $category->getCategories();
$tags = $tag->getTags();
$enrollment = new Enrollment($pdo);
$enrollments = $enrollment->getEnrollmentsByTeacher($id_enseignant);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Enseignant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body class="min-h-screen bg-gray-100">

    <nav class="fixed w-full bg-white/95 backdrop-blur-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="#" class="flex-shrink-0">
                        <span class="text-3xl font-bold text-orange-400">Youdemy</span>
                    </a>
                </div>
                <div class="flex sm:hidden items-center">
                    <!-- Burger Icon for Mobile -->
                    <button id="burger-icon"
                        class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300 ">
                        <a href="../deconnecter.php">Deconnecter</a>
                    </button>
                </div>

                <div class="hidden sm:flex sm:items-center sm:justify-center sm:gap-2">

                    <button
                        class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300 ">
                        <a href="../deconnecter.php">Deconnecter</a>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <h1 class="text-3xl pt-36 text-center font-bold">Tableau de bord Enseignant</h1>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total des cours</dt>
                                <dd class="text-3xl font-semibold text-gray-900">
                                    <?php echo htmlspecialchars($stats['total_cours']); ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Étudiants inscrits</dt>
                                <dd class="text-3xl font-semibold text-gray-900">
                                    <?php echo htmlspecialchars($stats['total_etudiants']); ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Taux de complétion</dt>
                                <dd class="text-3xl font-semibold text-gray-900">
                                    <?php echo htmlspecialchars($completionRate); ?>%</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">Gestion des cours</h2>
                <a href="#add" onclick="toggleForm()">
                    <button
                        class="inline-flex items-center px-2 md:px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Nouveau cours
                    </button></a>
            </div>


            <div class="border-t border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Titre</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Catégorie</th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Étudiants</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($videoCourses as $coursItem): 
                            $courseDetails = $videoCourse->getCourseById($coursItem['id']); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($coursItem['titre']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($coursItem['description']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?= htmlspecialchars($coursItem['categorie'] ?? 'Aucun') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($coursItem['nbr_etudiants']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Publié
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-orange-600 hover:text-orange-900 mr-4">
                                    <a href="./action/modifier_cours.php?id=<?= $coursItem['id'] ?>">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        </a>
                                    </button>
                                    
                                    <button class="text-red-600 hover:text-red-900">
                                        <a href="./action/supprimer_cours.php?id=<?= $coursItem['id'] ?>">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </a>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>

                            
                            <?php foreach ($documentCourses as $coursItem): 
                            $courseDetails = $documentCourse->getCourseById($coursItem['id']); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($coursItem['titre']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($coursItem['description']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?= htmlspecialchars($coursItem['categorie']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($coursItem['nbr_etudiants']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Publié
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                  
                                     
                                    <button onclick='openUpdateModal()' class="text-orange-600 hover:text-orange-900 mr-4">
                                    <a href="./action/modifier_cours.php?id=<?= $coursItem['id'] ?>">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        </a>
                                    </button>
                                    
                                    <button class="text-red-600 hover:text-red-900">
                                        <a href="./action/supprimer_cours.php?id=<?= $coursItem['id'] ?>">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </a>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

      
        <div class="bg-white shadow rounded-lg hidden mb-8" id="add">

            <div class="relative px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-xl font-medium leading-6 text-gray-900">Ajouter un nouveau cours</h3>
                <i onclick="closeModal()"
                    class="ri-arrow-down-s-line text-2xl text-gray-500 transition-transform duration-300"
                    id="toggleIcon"></i>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <form class="space-y-6" method="POST" action="./action/add.php" enctype="multipart/form-data">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" name="title" id="title" required
                            class="mt-1 block w-full rounded-md p-2 border border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="prix" class="block text-sm font-medium text-gray-700">Prix</label>
                        <input type="number" name="price" id="price" required
                            class="mt-1 block w-full rounded-md p-2 border border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="3" required
                            class="mt-1 block w-full rounded-md p-2 border border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"></textarea>
                    </div>

                    <div id="image-upload">
                        <label class="block my-4 text-sm font-medium text-gray-700">Image :</label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-orange-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <div class="flex text-sm text-gray-600 justify-center text-center">
                                    <label for="file-upload-image"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                        <span>Télécharger une Image</span>
                                        <input type="file" name="file-upload-image" id="file-upload-image" required
                                            class="sr-only">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">Image jusqu'à 10MB</p>
                            </div>
                        </div>
                        <div id="file-name" class="mt-4 text-center text-sm text-gray-700"></div>
                    </div>

                    <div class="mb-4">
                        <label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
                        <select id="category" name="category" required
                            class="mt-1 block w-full rounded-md p-2 border border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            <?php
            
            foreach ($categories as $cat) {
                echo "<option value=\"" . $cat['id'] . "\">" . htmlspecialchars($cat['nom']) . "</option>";
            }
            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                        <div id="tags-container" class="space-y-2 space-x-1">
                            <?php
           
            foreach ($tags as $tag) {
                echo "<div class='tag-item bg-gray-200 space-x-2 inline-block p-2 border border-orange-300 rounded-full cursor-pointer hover:bg-orange-300' data-tag-id=\"" . $tag['id'] . "\">" . htmlspecialchars($tag['nom']) . "</div>";
            }
            ?>
                        </div>
                        <input type="hidden" name="tags[]" id="selected-tags-hidden">
                        <p class="text-xs text-gray-500 mt-1">Cliquez sur un tag pour le sélectionner ou le retirer.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contenu du cours</label>
                        <div>
                            <select id="content-type" name="content-type" required
                                class="mt-1 block w-full rounded-md p-2 border border-orange-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                <option value="">-- Sélectionner un type de contenu --</option>
                                <option value="video">Vidéo</option>
                                <option value="document">Document</option>
                            </select>
                        </div>

                        <div id="video-upload" class="hidden">
                            <label class="block my-4 text-sm font-medium text-gray-700">Vidéo :</label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-orange-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file-upload-video"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                            <span>Télécharger une vidéo</span>
                                            <input id="file-upload-video" name="file-upload-video" type="file"
                                                class="sr-only">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">Vidéo</p>
                                </div>
                            </div>

                            <div id="video-name" class="mt-4 text-center text-sm text-gray-700"></div>
                        </div>


                        <div id="document-upload" class="hidden">
                            <label class="block my-4 text-sm font-medium text-gray-700">Document :</label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-orange-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file-upload-document"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                            <span>Télécharger un fichier</span>
                                            <input id="file-upload-document" name="file-upload-document" type="file"
                                                class="sr-only">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">Document PDF jusqu'à 10MB</p>
                                </div>
                            </div>

                            <div id="document-name" class="mt-4 text-center text-sm text-gray-700"></div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            Créer le cours
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <div class="bg-white shadow-lg rounded-lg ">
        <div class="px-6 py-5 flex justify-between items-center border-b">
            <h2 class="text-2xl font-semibold text-gray-800">Consultation des inscriptions</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase">Étudiant</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase">Cours</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase">Date d'inscription</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase">Statut</th>
                       
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($enrollments)) : ?>
                <?php foreach ($enrollments as $enrollment) : ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap flex items-center">
                            <div class="h-10 w-10 rounded-full overflow-hidden border border-gray-200 flex-shrink-0">
                                <img src="../uploads/avatars/<?= htmlspecialchars($enrollment['etudiant_avatar']) ?>" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-800"><?= htmlspecialchars($enrollment['etudiant_nom']) ?></div>
                                <div class="text-sm text-gray-500"><?= htmlspecialchars($enrollment['etudiant_email']) ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800"><?= htmlspecialchars($enrollment['cours_nom']) ?></div>
                            <div class="text-sm text-gray-500"><?= htmlspecialchars($enrollment['cours_description']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= htmlspecialchars($enrollment['date_inscription']) ?></td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                        <?php if ($enrollment['status'] === 'nonComplet') : ?>
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            <?= htmlspecialchars($enrollment['status']) ?>
                        </span>
                        <?php else : ?>
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <?= htmlspecialchars($enrollment['status']) ?>
                            </span>
                        <?php endif; ?>
                        </td>
                        
                    </tr>
                    <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                Aucun étudiant inscrit à vos cours pour le moment.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    </main>
    <script src="../scriptF.js"></script>

</body>


</html>