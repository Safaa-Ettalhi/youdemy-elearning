<?php
include('../class/db.php'); 
include('../class/cours.php');
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit();
}

// Récupérez l'ID de l'utilisateur connecté
$user_id = $_SESSION['id'];
// Initialisation
$db = new Database();
$pdo = $db->getPDO();
$courseModel = new Course($pdo);

// Récupération du cours par ID (exemple ID=1, peut être dynamique via GET)
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$course = $courseModel->getCourseById($course_id);

if (!$course) {
    echo "Cours introuvable.";
    exit;
}

// Génération dynamique du HTML
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Introduction au développement web - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'youdemy': '#ff7900',
                        'youdemy-hover': '#ff9533',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white/95 backdrop-blur-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="#" class="flex-shrink-0">
                         <img src="./SAFAA BB.svg" alt="Safaa Ettalhi" >
                    </a>
                </div>
                <div class="flex sm:hidden   items-center">
                    <!-- Burger Icon for Mobile -->
                    <button id="burger-icon" class="text-gray-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="hidden sm:flex sm:items-center  sm:justify-center sm:space-x-8  text-xl" id="menu">
                    <!-- <a href="#Accueil" class="text-gray-600 hover:text-gray-900">Accueil</a> -->
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

    <!-- Course Header -->
    <header class="bg-gray-800 text-white py-16 pt-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($course['titre']); ?></h1>
            <p class="text-xl mb-6"><?= htmlspecialchars($course['description']); ?></p>
            
            <div class="flex items-center">
                <img src="<?= htmlspecialchars($course['avatar']); ?>" alt="<?= htmlspecialchars($course['enseignant']); ?>" class="w-12 h-12 rounded-full mr-4">
                <div>
                    <p class="font-semibold">Créé par <?= htmlspecialchars($course['enseignant']); ?></p>
                    <p class="text-sm">Expert en <?= htmlspecialchars($course['categorie']); ?></p>
                </div>
            </div>
        </div>
    </header>


    <!-- Course Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">Contenu du cours</h2>
                    <p><?= nl2br(htmlspecialchars($course['contenu'])); ?></p>
                </div>
            </div>
            
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="mb-6">
                        <img src="<?= htmlspecialchars($course['image']); ?>" alt="Course thumbnail" class="w-full rounded-lg">
                    </div>
                    <div class="text-3xl font-bold mb-4"><?= htmlspecialchars($course['prix']); ?>  $</div>
                    <p class="text-sm text-gray-500 mb-4">Garantie de remboursement de 30 jours</p>
                    <form action="inscription.php" method="POST">
    <input type="hidden" name="course_id" value="<?= htmlspecialchars($course['id']); ?>">
    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id); ?>">

    <button type="submit" class="w-full bg-youdemy hover:bg-youdemy-hover text-white font-bold py-2 px-4 rounded">
        S'inscrire maintenant
    </button>
</form>

                </div>
            </div>
        </div>
        <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">À propos de l'instructeur</h2>
                    <div class="flex items-start mb-6">
                    <img src="<?= htmlspecialchars($course['avatar']); ?>" alt="<?= htmlspecialchars($course['enseignant']); ?>" class="w-24 h-24 rounded-full mr-6">
                        <div>
                            <h3 class="text-xl text-orange-400 font-semibold mb-2"><?= htmlspecialchars($course['enseignant']); ?></h3>
                            <p class="text-gray-600 mb-4">Expert(e) en <?= htmlspecialchars($course['categorie']); ?></p>
                            
                        </div>
                    </div>
                    <p class="mb-4">
                    <?= htmlspecialchars($course['bio']); ?>
                    </p>
                </div>
    </main>
        <!-- Footer -->
        <footer class="bg-gray-100 py-20 px-8 pt-16 pb-8">
        <div class="container max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8 md:gap-0 mb-8">
                <!-- Brand -->
                <div>
                    <a href="#" class="text-2xl font-bold text-orange-400 mb-4 inline-block">Youdemy</a>
                    <p class="text-gray-600">
                        Youdemy est votre passerelle vers l'éducation en ligne, offrant une large gamme de cours pour atteindre vos objectifs d'apprentissage.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="md:ml-40">
                    <h3 class="font-bold text-lg mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Home</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Courses</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Contact</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Blog</a></li>
                    </ul>
                </div>


                <!-- Contact Us -->
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
        <!-- Footer Bottom -->
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
   
    
    <script src="scriptF.js"></script>
</body>
</html>