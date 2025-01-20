<?php

require_once './class/db.php';
require_once './class/user.php';

$database = new Database();
$pdo = $database->getPDO();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];
    if (!empty($email) && !empty($password)) {

    $user = User::login($email, $password, $pdo);

    if ($user) {
        $_SESSION['id'] = $user->getId();
        $_SESSION['nom'] = $user->getNom();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['role'] = $user->getRole();

        if ($_SESSION['role'] == 'Etudiant') {
            header('Location: ../etudiant/catalogecours.php');
            exit();
        } elseif ($_SESSION['role'] == 'Enseignant') {
            header('Location: ../enseignant/dashbord.php');
            exit();
        } else {
            header('Location: ../admin/dashbord.php');
            exit();
        }
    } else {
        
        $message = "<div class='text-red-500 p-3 mb-4 border border-red-300 bg-red-100 rounded'>Identifiants incorrects.</div>";
    }
}else{
    $message = "<div class='text-red-500 p-3 mb-4 border border-red-300 bg-red-100 rounded'>Veuillez remplir tous les champs.</div>";
}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Youdemy</title>
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
    <nav class="fixed w-full bg-white/95 backdrop-blur-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="#" class="flex-shrink-0">
                        <span class="text-3xl font-bold text-orange-400">Youdemy</span>
                    </a>
                </div>
                <div class="flex sm:hidden items-center">
                   
                    <button id="burger-icon" class="text-gray-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="hidden sm:flex sm:items-center  sm:justify-center sm:space-x-8  text-xl" id="menu">
                    <a href="index.php" class="text-gray-600 hover:text-gray-900">Accueil</a>
                    <a href="index.php" class="text-gray-600 hover:text-gray-900">Cours</a>
                    <a href="index.php" class="text-gray-600 hover:text-gray-900">Mentors</a>
                    <a href="index.php" class="text-gray-600 hover:text-gray-900">Blog</a>
                    <a href="index.php" class="text-gray-600 hover:text-gray-900">Contact</a>
                    
                    
                </div>
                <div class="hidden sm:flex sm:items-center sm:justify-center sm:gap-2">
                <button class="bg-white hover:bg-orange-500 hover:text-white text-l text-orange-400 border border-orange-400 px-6 py-2 rounded-full transition duration-300">
                    <a href="./login.php">login</a>
                </button>
                <button class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300 ">
                   <a href="./register.php">s'inscrire</a> 
                </button></div>
            </div>
        </div>
    </nav>

    <div id="mobile-menu" class="bg-white shadow-lg absolute w-full left-0 top-20 z-50 hidden">
        <div class="px-6 py-4">
            <a href="index.php" class="block text-gray-600 hover:text-gray-900 py-2">Accueil</a>
            <a href="index.php" class="block text-gray-600 hover:text-gray-900 py-2">Cours</a>
            <a href="index.php" class="block text-gray-600 hover:text-gray-900 py-2">Mentors</a>
            <a href="index.php" class="block text-gray-600 hover:text-gray-900 py-2">Blog</a>
            <a href="index.php" class="block text-gray-600 hover:text-gray-900 py-2">Contact</a>
            
            <button class="w-full bg-white hover:bg-orange-500 hover:text-white text-l text-orange-400 border border-orange-400 px-6 py-2 rounded-full transition duration-300 mt-4">
                <a href="./register.php">s'inscrire</a>
            </button>
            <button class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300 mt-4">
                <a href="./login.php">login</a>
            </button>
        </div>
    </div>

    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full mt-16 space-y-8 bg-white p-8 rounded-xl shadow-lg">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Connexion à votre compte
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Ou
                    <a href="./register.php" class="font-medium text-youdemy hover:text-youdemy-hover">
                        créez un nouveau compte
                    </a>
                </p>
            </div>
            <?php if ($message): ?>
                <div ><?php echo $message; ?></div>
            <?php endif; ?>
            <form class="mt-8 space-y-6" action="#" method="POST">
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label for="email" class="block text-m font-medium text-gray-700">
                            Adresse email
                        </label>
                        <input id="email" name="email" type="email" required 
                            class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-youdemy focus:border-youdemy"
                            placeholder="vous@exemple.com">
                    </div>
                    <div>
                        <label for="password" class="block text-m font-medium text-gray-700">
                            Mot de passe
                        </label>
                        <input id="password" name="password" type="password" required
                        placeholder="***********"   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-youdemy focus:border-youdemy">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                            class="h-4 w-4 text-youdemy focus:ring-youdemy border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-m text-gray-900">
                            Se souvenir de moi
                        </label>
                    </div>

                    <div class="text-m">
                        <a href="#" class="font-medium text-youdemy hover:text-youdemy-hover">
                            Mot de passe oublié?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-m font-medium rounded-md text-white bg-youdemy hover:bg-youdemy-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-youdemy">
                        Se connecter
                    </button>
                </div>
            </form>
        </div>
    </div>
    <footer class="bg-white/95 py-20 px-8 pt-16 pb-8">
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
                        <li><a href="index.php" class="text-gray-600 hover:text-gray-900">Home</a></li>
                        <li><a href="index.php" class="text-gray-600 hover:text-gray-900">Courses</a></li>
                        <li><a href="index.php" class="text-gray-600 hover:text-gray-900">Contact</a></li>
                        <li><a href="index.php" class="text-gray-600 hover:text-gray-900">Blog</a></li>
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
    <script src="scriptF.js"></script>
</body>
