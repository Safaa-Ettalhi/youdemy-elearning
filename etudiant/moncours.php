<?php

session_start();
include('../class/db.php');
include('../class/cours.php');
include('../class/enrollement.php');
include('../class/videoCourse.php');  
include('../class/documentCourse.php'); 
$db = new Database();
$pdo = $db->getPDO();
$enrollment = new Enrollment($pdo);

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Etudiant') {
    
    header('Location: ../login.php');
    exit();
}

if (isset($_POST['complete'])) {
    $coursId = intval($_POST['cours_id']);
    $userId = intval($_SESSION['id']); 

    
    if ($enrollment->updateEnrollmentStatus($coursId, $userId)) {
        
        header("Location: mescours.php?success=1");
        exit();
    } else {
        echo "Erreur lors de la mise à jour du statut.";
    }
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Cours introuvable.";
    exit();
}

$course_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

$videoCourse = new VideoCourse($pdo);
$documentCourse = new DocumentCourse($pdo);
$cours = null;

if ($course_id > 0) {
    
    $cours = $videoCourse->getCourseById($course_id);

    
    if (!$cours) {
        $cours = $documentCourse->getCourseById($course_id);
    }
}
if (!$cours) {
    echo "Cours introuvable.";
    exit;
}


if (!$cours) {
    echo "Cours introuvable.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du cours - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<nav class="fixed w-full bg-white/95 backdrop-blur-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="#" class="flex-shrink-0">
                        <span class="text-3xl font-bold text-orange-400">Youdemy</span>
                    </a>
                </div>
                <div class="flex sm:hidden   items-center">
                    <button id="burger-icon" class="text-gray-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="hidden sm:flex sm:items-center  sm:justify-center sm:space-x-8  text-xl" id="menu">
                    <a href="./catalogecours.php" class="text-gray-600 hover:text-gray-900">Cours</a>
                    <a href="./mescours.php" class="text-gray-600 hover:text-gray-900">Mes cours</a>
                    
                    
                    
                </div>
                <div class="hidden sm:flex sm:items-center sm:justify-center sm:gap-2">
                <button class="bg-white hover:bg-orange-500 hover:text-white text-l text-orange-400 border border-orange-400 px-6 py-2 rounded-full transition duration-300">
                    <a href="../profil.php">Profil</a>
                </button>
                <button class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300 ">
                   <a href="../deconnecter.php">Deconnecter</a> 
                </button></div>
            </div>
        </div>
    </nav>
    <header class="bg-gray-800 text-white py-16 pt-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-8"><?= htmlspecialchars($cours['titre']); ?></h1>
            
            
            <div class="flex items-center">
                
                <div>
                    <p class="font-semibold"><?php echo nl2br(htmlspecialchars($cours['description'])); ?></p>
                    
                </div>
            </div>
        </div>
    </header>
   

    <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
           
            <div class="lg:col-span-2">
                

 <?php if (!empty($cours['vedio'])): ?>
<div class="bg-white rounded-lg shadow-sm p-6 mb-8">
    <h2 class="text-xl font-semibold mb-4 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Contenu du cours
    </h2>
    <div class="aspect-video bg-gray-100 rounded-lg">
        
            <iframe class="w-full h-full rounded-lg" 
                    src="../<?php echo htmlspecialchars($cours['vedio']); ?>" 
                    title="Vidéo du cours" 
                    allowfullscreen>
            </iframe>
        
    </div>
    <div class="flex gap-4 mt-12">
        <form method="POST" action="moncours.php">
         
            <input type="hidden" name="cours_id" value="<?php echo $course_id; ?>">
            
            <button type="submit" name="complete" class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm hover:bg-orange-600">
                Compléter
            </button>
        </form>
        <a href="./catalogecours.php" class="bg-white text-orange-500 px-4 py-2 border border-orange-500 rounded-md text-sm hover:bg-orange-500 hover:text-white transition duration-200">
        Retour au catalogue
        </a>
    </div>
</div>

<?php endif; ?>
<?php if (!empty($cours['fichier_document'])): ?>
                <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
    <h2 class="text-xl font-semibold mb-4 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Documents du cours
    </h2>
    <div class="border border-orange-500 mb-5 rounded-lg p-4">
       
            <a href="../<?php echo htmlspecialchars($cours['fichier_document']); ?>" class="text-gray-600 hover:underline" download>
                <?php echo basename($cours['fichier_document']); ?>
            </a>
       
        
    </div>
    <iframe src="../<?= htmlspecialchars($cours['fichier_document']) ?>" width="100%" height="700px"></iframe>
    <div class="flex gap-4 mt-10">
        <form method="POST" action="moncours.php">
          
            <input type="hidden" name="cours_id" value="<?php echo $course_id; ?>">
         
            <button type="submit" name="complete" class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm hover:bg-orange-600">
                Compléter
            </button>
        </form>
        <a href="./catalogecours.php" class="bg-white text-orange-500 px-4 py-2 border border-orange-500 rounded-md text-sm hover:bg-orange-500 hover:text-white transition duration-200">
        Retour au catalogue
        </a>
    </div>
</div>
<?php endif; ?>
            </div>

          
            <div class="space-y-6">
                
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold mb-4">Enseignant</h2>
                    <div class="flex items-center space-x-4">
                        <img src="../uploads/avatars/<?php echo htmlspecialchars($cours['avatar']) ?? 'simple.png' ; ?>" alt="Enseignant" class="w-16 h-16 rounded-full">
                        <div>
                            <h3 class="font-medium"> <?php echo htmlspecialchars($cours['enseignant']); ?></h3>
                            <?php if (!empty($cours['categorie'])) { ?>
    <p class="text-gray-500 text-sm">Expet en <?php echo htmlspecialchars($cours['categorie']); ?></p>
    <?php } ?>

                        </div>
                    </div>
                    <p class="mt-4 text-gray-600">
                    <?php echo htmlspecialchars($cours['enseignant_description']); ?>
                    </p>
                </div>

               
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            Catégorie
                        </h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
    <?php echo htmlspecialchars($cours['categorie'] ?? 'Aucune catégorie sélectionnée'); ?>
</span>
                    </div>          
                   <div>
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Tags
                    </h2>
    <div class="flex flex-wrap gap-2">
        <?php
        
        if (!empty($cours['tags'])) {
            
            $tags = explode(',', $cours['tags']); 
            $tags = array_unique(array_map('trim', $tags));
            foreach ($tags as $tag) {
                echo '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">'
                    . htmlspecialchars($tag) .
                    '</span>';
        }
        } else {
            echo '<p class="text-gray-500">Aucun tag disponible pour ce cours.</p>';
        }
        ?>
    </div>
    
</div>

                </div>
            </div>
        </div>
    </div>
    <footer class="bg-gray-50 py-20 px-8 pt-16 pb-8">
        <div class="container max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8 md:gap-0 mb-8">

                <div>
                    <a href="#" class="text-2xl font-bold text-orange-400 mb-4 inline-block">Youdemy</a>
                    <p class="text-gray-600">
                        Youdemy est votre passerelle vers l'éducation en ligne, offrant une large gamme de cours pour atteindre vos objectifs d'apprentissage.
                    </p>
                </div>

                <div class="md:ml-40">
                    <h3 class="font-bold text-lg mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Home</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Courses</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Contact</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Blog</a></li>
                    </ul>
                </div>
         
                <div class="md:ml-40">
                    <h3 class="font-bold mb-4">Contact Us</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li>Phone : 06-6666-6666</li>
                        <li>Email : safaeettalhi@gmail.com</li>
                        <li>Address : Safi , Maroc</li>
                    </ul>
                </div>
            </div>

            
        </div>

        <div class="pt-8 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-600 mb-4 md:mb-0 text-xl">&copy; 2025 Youdemy. Tous droits réservés.</</p>
                <div class="flex space-x-4 text-2xl">
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <i class="ri-twitter-fill"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <i class="ri-instagram-fill"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <i class="ri-linkedin-fill"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
