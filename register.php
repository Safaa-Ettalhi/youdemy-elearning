<?php
require_once './class/db.php'; 
require_once './class/user.php'; 
require_once './class/enrollement.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['name'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['password'];
    $role = $_POST['role'];
    $message = '';

    
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
    $fileName = $_FILES['avatar']['name'];
    if (in_array(pathinfo($fileName, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']) &&
        move_uploaded_file($_FILES['avatar']['tmp_name'], './uploads/avatars/' . $fileName)) {
        $avatar = $fileName;
    } else {
        $message = "Erreur ou format incorrect.";
    }
}

    $database = new Database();
    $pdo = $database->getPDO();

    $message = User::register($nom, $email, $mot_de_passe, $role, $pdo, $avatar);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Youdemy</title>
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
                    <img src="./SAFAA BB.svg" alt="Safaa Ettalhi" >
                    </a>
                </div>
                <div class="flex sm:hidden items-center">
                    <button id="burger-icon" class="text-gray-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="hidden sm:flex sm:items-center sm:justify-center sm:space-x-8 text-xl" id="menu">
                    <a href="convertision.html" class="text-gray-600 hover:text-gray-900">Accueil</a>
                    <a href="convertision.html" class="text-gray-600 hover:text-gray-900">Cours</a>
                    <a href="convertision.html" class="text-gray-600 hover:text-gray-900">Mentors</a>
                    <a href="convertision.html" class="text-gray-600 hover:text-gray-900">Blog</a>
                    <a href="convertision.html" class="text-gray-600 hover:text-gray-900">Contact</a>
                </div>
                <div class="hidden sm:flex sm:items-center sm:justify-center sm:gap-2">
                    <button class="bg-white hover:bg-orange-500 hover:text-white text-l text-orange-400 border border-orange-400 px-6 py-2 rounded-full transition duration-300">
                        <a href="./login.php">login</a>
                    </button>
                    <button class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300 ">
                       <a href="./register.php">s'inscrire</a> 
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu (Hidden Initially) -->
    <div id="mobile-menu" class="bg-white shadow-lg absolute w-full left-0 top-20 z-50 hidden">
        <div class="px-6 py-4">
            <a href="convertision.html" class="block text-gray-600 hover:text-gray-900 py-2">Accueil</a>
            <a href="convertision.html" class="block text-gray-600 hover:text-gray-900 py-2">Cours</a>
            <a href="convertision.html" class="block text-gray-600 hover:text-gray-900 py-2">Mentors</a>
            <a href="convertision.html" class="block text-gray-600 hover:text-gray-900 py-2">Blog</a>
            <a href="convertision.html" class="block text-gray-600 hover:text-gray-900 py-2">Contact</a>
            <button class="w-full bg-white hover:bg-orange-500 hover:text-white text-l text-orange-400 border border-orange-400 px-6 py-2 rounded-full transition duration-300 mt-4">
                <a href="./register.php">s'inscrire</a>
            </button>
            <button class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300 mt-4">
                <a href="./login.php">login</a>
            </button>
        </div>
    </div>

    <!-- Register Form -->
    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full mt-16 space-y-8 bg-white p-8 rounded-xl shadow-lg">
            <div>
                <h2 class="mt-6 text-center text-4xl font-extrabold text-gray-900">Créer un compte</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Ou
                    <a href="./login.html" class="font-medium text-youdemy hover:text-youdemy-hover">
                        connectez-vous à votre compte existant
                    </a>
                </p>
            </div>
            <?php if (!empty($message)): ?>
            <div class="mb-4">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>
            <form class="mt-8 space-y-6" action="" method="POST" enctype="multipart/form-data">
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label for="name" class="block text-m font-medium text-gray-700">Nom complet</label>
                        <input id="name" name="name" type="text" required
                            class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-youdemy focus:border-youdemy"
                            placeholder="Nom complet">
                    </div>
                    <div>
                        <label for="email" class="block text-m font-medium text-gray-700">Adresse email</label>
                        <input id="email" name="email" type="email" required
                            class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-youdemy focus:border-youdemy"
                            placeholder="vous@exemple.com">
                    </div>
                    <div>
                        <label for="password" class="block text-m font-medium text-gray-700">Mot de passe</label>
                        <input id="password" name="password" type="password" required
                            class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-youdemy focus:border-youdemy">
                    </div>
                    <div>
                        <label for="avatar" class="block text-m font-medium text-gray-700">Avatar/Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <div class="flex text-sm text-gray-600">
                                    <label for="avatar" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                        <span>Choisir une image</span>
                                        <input id="avatar" name="avatar" accept="image/*" type="file" class="sr-only">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-l font-medium text-gray-700">Je suis un</label>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input id="student" name="role" type="radio" value="Etudiant" checked
                                    class="h-4 w-4 text-youdemy focus:ring-youdemy border-gray-300">
                                <label for="student" class="ml-2 block text-sm font-medium text-gray-700">
                                    Etudiant
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="teacher" name="role" type="radio" value="Enseignant"
                                    class="h-4 w-4 text-youdemy focus:ring-youdemy border-gray-300">
                                <label for="teacher" class="ml-2 block text-sm font-medium text-gray-700">
                                    Enseignant
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300">
                        S'inscrire
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
       
        document.getElementById('burger-icon').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
