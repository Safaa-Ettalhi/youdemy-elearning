<?php
include('./class/db.php');
include('./class/cours.php'); 

// Nombre de cours par page
$perPage = 6;

// Récupérer la page actuelle à partir de l'URL (si non spécifié, la page 1 est utilisée)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Créer une instance de la classe Database
$db = new Database();
$pdo = $db->getPDO();

// Créer une instance de la classe Course
$course = new Course($pdo);

// Récupérer les cours pour la page actuelle
$courses = $course->getCourses($page, $perPage);

// Récupérer le nombre total de cours
$totalCourses = $course->getTotalCourses();

// Calculer le nombre total de pages
$totalPages = ceil($totalCourses / $perPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cours - Youdemy</title>
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
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
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
                    <button id="burger-icon" class="text-gray-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="hidden sm:flex sm:items-center  sm:justify-center sm:space-x-8  text-xl" id="menu">
                    <a href="index.php" class="text-gray-600 hover:text-gray-900">Accueil</a>
                    <a href="cours.php" class="text-gray-600 hover:text-gray-900">Cours</a>
                    <a href="index.php" class="text-gray-600 hover:text-gray-900">Mentors</a>
                    <a href="index.php" class="text-gray-600 hover:text-gray-900">Blog</a>
                    <a href="index.php" class="text-gray-600 hover:text-gray-900">Contact</a>
                    
                    
                </div>
                <div class="hidden sm:flex sm:items-center sm:justify-center sm:gap-2">
                <button class="bg-white hover:bg-orange-500 hover:text-white text-l text-orange-400 border border-orange-400 px-6 py-2 rounded-full transition duration-300">
                    <a href="./login.html">login</a>
                </button>
                <button class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300 ">
                   <a href="./register.html">s'inscrire</a> 
                </button></div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu (Hidden Initially) -->
    <div id="mobile-menu" class="bg-white shadow-lg absolute w-full left-0 top-20 z-50 hidden">
        <div class="px-6 py-4">
            <a href="index.php" class="block text-gray-600 hover:text-gray-900 py-2">Accueil</a>
            <a href="cours.php" class="block text-gray-600 hover:text-gray-900 py-2">Cours</a>
            <a href="index.php" class="block text-gray-600 hover:text-gray-900 py-2">Mentors</a>
            <a href="index.php" class="block text-gray-600 hover:text-gray-900 py-2">Blog</a>
            <a href="index.php" class="block text-gray-600 hover:text-gray-900 py-2">Contact</a>
            
            <button class="w-full bg-white hover:bg-orange-500 hover:text-white text-l text-orange-400 border border-orange-400 px-6 py-2 rounded-full transition duration-300 mt-4">
                <a href="./register.html">s'inscrire</a>
            </button>
            <button class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300 mt-4">
                <a href="./login.html">login</a>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <main class="md:pt-20" >
        <!-- Search Section with Background Image -->
        <div class=" bg-cover bg-center h-96 flex items-center justify-center" style="background-image: url('./cours.png');">
            <div class="bg-white bg-opacity-10 p-2  md:p-8 rounded-lg w-full max-w-3xl  ">
                <h1 class="text-4xl font-bold text-orange-400 mb-4 text-center">Rechercher des cours</h1>
                <form class="flex gap-4">
                    <input type="text" placeholder="Rechercher des cours..." class="flex-grow px-4 py-3 rounded-md focus:outline-none focus:ring-2 focus:ring-youdemy focus:border-transparent text-lg">
                    <button type="submit" class="bg-youdemy text-white px-6 py-3 rounded-md hover:bg-youdemy-hover focus:outline-none focus:ring-2 focus:ring-youdemy focus:ring-offset-2 text-lg font-semibold">
                        Rechercher
                    </button>
                </form>
            </div>
        </div>

        <!-- Courses Section -->
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-8">Cours disponibles</h2>
        
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($courses as $course) : ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="<?= $course['image']; ?>" alt="Course thumbnail" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2"><?= htmlspecialchars($course['titre']); ?></h3>
                        <p class="text-gray-600 mb-4"><?= htmlspecialchars($course['description']); ?></p>
                        <div class="flex items-center mb-4">
                            <img src="<?= $course['avatar']; ?>" alt="Teacher" class="w-10 h-10 rounded-full mr-3">
                            <span class="text-sm text-gray-700">Par <?= htmlspecialchars($course['nom']); ?></span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-gray-500">Catégorie: <?= htmlspecialchars($course['category_name']); ?></span>
                            <span class="text-sm text-gray-500"><?= htmlspecialchars($course['nbr_etudiants']); ?> étudiants</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-youdemy font-bold text-xl"> <?= htmlspecialchars($course['price']); ?>$</span>
                            <button class="bg-youdemy text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-youdemy-hover focus:outline-none focus:ring-2 focus:ring-youdemy focus:ring-offset-2">
                                En savoir plus
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

           
            <!-- Pagination -->
<div class="mt-12 flex justify-center">
    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
        <!-- Lien Précédent -->
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">Précédent</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        <?php endif; ?>

        <!-- Liens des pages -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'bg-youdemy text-white' : 'bg-white text-gray-500' ?> relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <!-- Lien Suivant -->
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">Suivant</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        <?php endif; ?>
    </nav>
</div>

        </div>
    </main>
    <footer class="bg-gray-50 py-20 px-8 pt-16 pb-8">
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