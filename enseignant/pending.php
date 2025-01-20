
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue des cours - Youdemy</title>
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

<body>

    <div class="min-h-screen flex flex-col">

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
                    <a href="../index.php" class="text-gray-600 hover:text-gray-900">Accueil</a>
                    <a href="../cours.php" class="text-gray-600 hover:text-gray-900">Cours</a>
                    <a href="../index.php" class="text-gray-600 hover:text-gray-900">Mentors</a>
                    <a href="../index.php" class="text-gray-600 hover:text-gray-900">Blog</a>
                    <a href="../index.php" class="text-gray-600 hover:text-gray-900">Contact</a>
                    
                    
                </div>
               
            </div>
        </div>
    </nav>

    
    <div id="mobile-menu" class="bg-white shadow-lg absolute w-full left-0 top-20 z-50 hidden">
        <div class="px-6 py-4">
            <a href="../index.php" class="block text-gray-600 hover:text-gray-900 py-2">Accueil</a>
            <a href="../cours.php" class="block text-gray-600 hover:text-gray-900 py-2">Cours</a>
            <a href="../index.php" class="block text-gray-600 hover:text-gray-900 py-2">Mentors</a>
            <a href="../index.php" class="block text-gray-600 hover:text-gray-900 py-2">Blog</a>
            <a href="../index.php" class="block text-gray-600 hover:text-gray-900 py-2">Contact</a>

        </div>
    </div>

        <section class="hero flex pt-20 flex-row items-center justify-center mt-24">
            <div class="mb-6">
                <img src="./pending-approval_6cdu.png" alt="Access Denied" height="400px" width="400px"
                    class="mx-auto ">
            </div>
            <div class="flex flex-col justify-start">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Pending Account</h1>
                <p class="text-lg text-gray-600 mb-6">
                    We're sorry, but your account has been suspended. <br>Please contact support for more details.
                </p>
                <div class="flex space-x-4">
                    <a href="../index.php"
                        class="px-6 py-3 bg-orange-400 text-white font-semibold rounded-full hover:bg-orange-500 transition">
                        Go Back Home
                    </a>
                    <a href="../index.php"
                        class="px-6 py-3 bg-white border border-orange-400 text-orange-400 font-semibold rounded-full  hover:bg-orange-400 hover:text-white transition">
                        Contact Support
                    </a>
                </div>
            </div>
        </section>

    </div>


    <footer class="bg-gray-100 py-20 px-8 pt-16 pb-8">
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