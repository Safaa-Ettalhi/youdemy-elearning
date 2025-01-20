<?php
include('./class/db.php'); 
include('./class/cours.php');
include('./class/categorie.php');
include('./class/enseignent.php');
include('./class/videoCourse.php');  
include('./class/documentCourse.php'); 
$db = new Database();
$pdo = $db->getPDO();
$cours = new Course($pdo);

$videoCourse = new VideoCourse($pdo);
$documentCourse = new DocumentCourse($pdo);
$courses = array_merge($videoCourse->getCourses(), $documentCourse->getCourses());
$coursI = $cours->getCourses() ;
$category = new Category($pdo);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$categories = $category->getCategories();

$enseignant = new Enseignant(0, '', '', '', $pdo);
$pertPage = 4;
$totalEnseignants = $enseignant->getTotalEnseignants();
$totalPages = ceil($totalEnseignants / $pertPage);

$enseignants = $enseignant->getEnseignantPaginated($page, $pertPage);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
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
<body>
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
                    <a href="#Accueil" class="text-gray-600 hover:text-gray-900">Accueil</a>
                    <a href="#Cours" class="text-gray-600 hover:text-gray-900">Cours</a>
                    <a href="#Mentors" class="text-gray-600 hover:text-gray-900">Mentors</a>
                    <a href="#Blog" class="text-gray-600 hover:text-gray-900">Blog</a>
                    <a href="#Contact" class="text-gray-600 hover:text-gray-900">Contact</a>
                    
                    
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
            <a href="#Accueil" class="block text-gray-600 hover:text-gray-900 py-2">Accueil</a>
            <a href="#Cours" class="block text-gray-600 hover:text-gray-900 py-2">Cours</a>
            <a href="#Mentors" class="block text-gray-600 hover:text-gray-900 py-2">Mentors</a>
            <a href="#Blog" class="block text-gray-600 hover:text-gray-900 py-2">Blog</a>
            <a href="#Contact" class="block text-gray-600 hover:text-gray-900 py-2">Contact</a>
            
            <button class="w-full bg-white hover:bg-orange-500 hover:text-white text-l text-orange-400 border border-orange-400 px-6 py-2 rounded-full transition duration-300 mt-4">
                <a href="./register.php">s'inscrire</a>
            </button>
            <button class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300 mt-4">
                <a href="./login.php">login</a>
            </button>
        </div>
    </div>
    <sectuion class="min-h-screen" id="Accueil">
        <div class="grid lg:grid-cols-2 min-h-screen">
            
            <div class="relative h-[50vh] lg:h-screen">
                <img src="hero-img.png" alt="Student learning online" class="w-full h-full object-cover">
            </div>
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 flex items-center px-8 py-16 lg:py-0">
                <div class="max-w-xl mx-auto lg:mx-0">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        Apprenez en ligne avec<span class="text-orange-400"> Youdemy</span>
                    </h1>
                    <p class="text-slate-300 text-lg mb-8 leading-relaxed">
                        Découvrez des milliers de cours en ligne, apprenez à votre rythme et développez vos compétences.
                    </p>
                    <button class="bg-orange-400 text-white hover:bg-orange-500  px-8 py-3 rounded-full font-medium transition duration-300">
                        <a href="./visiteur/cours.php"> Parcourir les cours</a>
                    </button>
                </div>
            </div>
        </div>
    </sectuion>

    <section class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-slate-800 mb-4">Apprendre de nouvelles compétences</h2>
                <p class="text-gray-600 text-xl max-w-2xl mx-auto">
                    Découvrez une variété de compétences qui vous permettront d'évoluer dans votre carrière, d'apprendre à votre rythme et d'acquérir de nouvelles connaissances passionnantes.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg text-center transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="ri-edit-line text-5xl text-orange-400"></i>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-semibold text-slate-800 mb-4">Apprendre n'importe quoi</h3>
                    <p class="text-gray-600 text-xl">
                        Avec une plateforme flexible, vous pouvez explorer divers sujets et maîtriser des compétences variées, allant des langues aux technologies avancées.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg text-center transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="ri-layout-grid-line text-5xl text-orange-400"></i>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-semibold text-slate-800 mb-4">Grande collection</h3>
                    <p class="text-gray-600 text-xl">
                        Accédez à un large éventail de cours couvrant diverses disciplines et apprenez avec des matériaux bien organisés pour vous offrir une expérience optimale.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg text-center transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="ri-user-star-line text-5xl text-orange-400""></i>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-semibold text-slate-800 mb-4">Instructeurs certifiés</h3>
                    <p class="text-gray-600 text-xl">
                        Apprenez auprès d'instructeurs certifiés, des experts dans leur domaine, qui vous guideront tout au long de votre parcours d'apprentissage.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-4" id="Cours">
    <div class="max-w-7xl mx-auto">
        
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-slate-800 mb-4">Cours populaires</h2>
            <p class="text-gray-600 text-xl max-w-2xl mx-auto">
                Explorez nos cours populaires, conçus pour vous aider à développer des compétences pratiques dans des domaines clés du développement et de l'entrepreneuriat.
            </p>
        </div>


        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <?php foreach ($coursI as $course): ?>
                <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 ease-in-out transform hover:scale-105">
                    <div class="p-6">
                        <img src="<?php echo $course['image']; ?>" alt="<?php echo htmlspecialchars($course['titre']); ?>" class="h-48 w-full object-contain mb-6 transform transition duration-300 hover:scale-105 cursor-pointer">
                        <h3 class="text-2xl text-center font-semibold text-slate-800 mb-8 hover:text-orange-400 cursor-pointer"><?php echo htmlspecialchars($course['titre']); ?></h3>
                        <div class="flex justify-center text-xl">
                            <a href="./visiteur/coursdetails.php?id=<?php echo $course['id']; ?>" class="bg-orange-400 text-white py-2 px-6 rounded-lg hover:bg-orange-500 focus:outline-none transition duration-300 transform hover:scale-105">
                                En savoir plus
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>


        <div class="text-center text-2xl">
            <button class="inline-flex items-center justify-center px-8 py-3 border border-orange-400 text-orange-400 hover:bg-orange-400 hover:text-white rounded-full transition duration-300">
                <a href="./visiteur/cours.php">Voir tous les cours</a>  
            </button>
        </div>
    </div>
</section>

<section class="py-20 px-4">
    <div class="max-w-7xl mx-auto">

        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-slate-800 mb-4">Top Categories</h2>
            <p class="text-gray-600 text-xl max-w-3xl mx-auto">
                Découvrez nos principales catégories de cours, adaptées à tous les niveaux et domaines d'expertise, pour vous aider à développer des compétences essentielles dans des domaines clés.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <?php foreach ($categories as $index => $cat): ?>
              
                <div class="p-8 text-center hover:shadow-lg transition duration-300 <?php echo $index % 2 == 0 ? 'bg-white' : 'bg-orange-400'; ?> rounded-lg shadow-md">
                    <div class="inline-block mb-4">
                        
                        <i class="ri-article-line text-3xl <?php echo $index % 2 == 0 ? 'text-orange-400' : 'text-white'; ?>"></i>
                    </div>
                    <h3 class="text-3xl font-semibold <?php echo $index % 2 == 0 ? 'text-slate-800' : 'text-white'; ?>"><?php echo htmlspecialchars($cat['nom']); ?></h3>
                </div>
            <?php endforeach; ?>
        </div>

        
    </div>
</section>

    <section class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 text-center mb-16">
                Pourquoi choisir Youdemy?
            </h2>

            <div class="grid md:grid-cols-3 gap-8">
            
                <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-2xl transition duration-300 ease-in-out transform hover:scale-105">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-orange-100 mb-6">
                        <i class="ri-book-open-line text-2xl text-orange-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">Instructeurs experts</h3>
                    <p class="text-gray-600">
                        Apprenez auprès de professionnels de l'industrie et d'éducateurs expérimentés.
                    </p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-2xl transition duration-300 ease-in-out transform hover:scale-105">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-orange-100 mb-6">
                        <i class="ri-flashlight-line text-2xl text-orange-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">Apprentissage flexible</h3>
                    <p class="text-gray-600">
                        Étudiez à votre rythme, à tout moment et en tout lieu.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-md text-center hover:shadow-2xl transition duration-300 ease-in-out transform hover:scale-105">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-orange-100 mb-6">
                        <i class="ri-check-line text-2xl text-orange-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">Cours certifiés</h3>
                    <p class="text-gray-600">
                        Obtenez des certificats reconnus à la fin du cours.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-4 bg-white" id="Mentors">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                Nos instructeurs experts
            </h2>
            <p class="text-gray-600 text-xl max-w-3xl mx-auto">
                Nos instructeurs sont des experts dans leurs domaines respectifs. Avec des années d'expérience, ils sont passionnés par le partage de leurs connaissances et vous guideront dans votre apprentissage pour atteindre vos objectifs.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($enseignants as $enseignant) : ?>
                <div class="text-center rounded-lg shadow-md hover:shadow-2xl transition duration-300 ease-in-out transform hover:scale-105">
                    <div class="relative mb-4 aspect-square">
                        <img src="../uploads/avatars/<?php echo $enseignant['avatar'] ?? 'simple.png' ; ?>" alt="<?php echo htmlspecialchars($enseignant['enseignant_nom']); ?>"
                             class="w-full h-full object-cover rounded-lg">
                    </div>
                    <h3 class="text-lg font-semibold text-slate-800"><?php echo htmlspecialchars($enseignant['enseignant_nom']); ?></h3>
                    <p class="text-gray-600 mb-8"><?php if(!empty($enseignant['enseignant_description'])) {echo htmlspecialchars($enseignant['enseignant_description']);} ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-12 flex justify-center">
            <div class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
               
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Précédent</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif; ?>

               
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'bg-youdemy text-white' : 'bg-white text-gray-500' ?> relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

               
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Suivant</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

    <!-- Blog Section -->
    <section class="py-20 px-4 bg-white" id="Blog">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Depuis le blog</h2>
            <p class="text-gray-600 text-xl mb-8">
                Découvrez nos derniers articles sur les tendances du design, les outils web et bien plus encore.
            </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Blog Card 1 -->
                <div class="rounded-lg shadow-md text-center hover:shadow-2xl transition duration-300 ease-in-out transform hover:scale-105">
                    <img src="data:image/png;base64,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" alt="Modern website design tools" class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Outils de design web modernes</h3>
                    <p class="text-gray-600 mb-6">Explorez les derniers outils pour concevoir des sites web modernes, conviviaux, rapides et esthétiques. Découvrez les meilleurs logiciels pour optimiser votre processus de design.</p>
                </div>

                <!-- Blog Card 2 -->
                <div class="rounded-lg shadow-md text-center hover:shadow-2xl transition duration-300 ease-in-out transform hover:scale-105">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASIAAACuCAMAAAClZfCTAAAA51BMVEX////6/NuDySf5+9iM0Cej2EJ2syvb7sp7xgeEzQDo9djg8c9+xxeBxij9/t+535N9ui2SyjZtrRzQ5p/z+M///+O94IjT6qac1Svn87+PzjyHyy6b0UDu9+SGzhak2Vqg1zjQ6p2S0jWVyz3w98t9vymb1SiNxi7H4pPl8di94net3GafyGrY6Mv6/ff1++6azUmr1Wdsrgugz1Sl1mLf77Sp2lDT5arW7L+PzkK31Yje67eo2HLG5abD5Y664XqWw12FukXG5obf8LDQ6rWu0H5/tjvD25arznmj1mqu2ny94Y+X0VQl19zgAAAKm0lEQVR4nO2d+1/aPBvGafvgopYMCwUqHjgURdnLdIKi4mns2Z45//+/502whyRND0hHSsm1Hzqdn5F+ve8rd9IkLRSkpKSkpKSkpKSkpKSkpKSkpKSkpKSkpKQITSr1Q9FtyLR2rxutVuNxKLodmdXheaOI1WrVRTclmxrWGq2io1ZxIro5GVTdB4TVON4R3aKMaVKkAM0hnX8V3aoMaee6wQLC2XYifdvR13MeIOnbnoaPJ4EcIyAVP4luoHDVWxGA5pY02Gzf5rg0B9LT5vr2znHQhA4ODoLZ1uiLbqoYcV26u4f+cCC1bkQ3d/XiuvTB3sWsatdOeZCuN823eS590N2rQKgopn152uVY0mCTpgB4Lo0A1TQECMu8vzoNBlLx5Pum+PbOgGdCp1PbVFxBWO/u8SypLbrxq9Dh0wkP0MjyAb1D6ne5vv1D9A38bQ37Db5Lm1BhZFa5vt243hV9E39VNzxA3e7cpYPaQN/eveWUit1Tz6U5kJBv8yDV8jkFcDgCajMI6NI2wwBhSzLrFzzfblRE3076Gr4CXVVV+k6DLs2BpPS59XYxb779MAdEM+pyXZqTbdoG+PZEBaqjjp9j3TbfpTmQsG9zIJ3nxbd3bz1AXhhFu3Qw25Bv7wV9u5UT334lAalzx0aAniJdmgcpxLdzMOG2SxPCqdY9vYp1aQ4kpc/LtqLoG1xen3UGUS+hSweFfDvYubUmZ0iHhzup6BBLMKKeYdwldWkOJE69/Q0LgO2UBABYde5SiHpbxn/VDwNSuL5d3kIqqeo/aUkkotKW8WxBTdOWYBScJ3ERpQZpWxyisvFyDxVtSUSBettHlBIjcYi2jF/vgJZmhHx76jMiEKXDaHvVVbuLqIQIaZ6WZKSYV10eolQYCUPUMzQtPUSwv8dFlAajjCBalhGshCBKgVFWEC1r2aGIlmeUf0RLM8oMouUYRSGiGB0Rkog4jI463+uWo/pjJxmk7CBarsaOROQyOqIm7SCsJGKUIUSJGXEGdTGIHEYWpD8QWvlEBO1xEFIcIsxoewbZT4TjBHGUJUSJGEGr0bgOzMDFIlL/ORpA+kPwF+YgntHaIVLm82ZthlE8IvXIwlAUu9J+lz2fY7C21wtRAkbmYP6IuzWgHwYkQKTOP2C27Xb523WMDK5ZFMUzMvvOIoBWkUq2BIj2TZxYB/6tH+Awgtc5QwTH/iqARpvswBMgmhPp+Ld+NP/G8bohimYEq8TsYrFFhFFiRPvEva8pokhG5jGJ6PgjiIgoyiMi8zu5GqllfyTR8hBF4YzgjFyO1JgtatfviIhBbe4QQZuKoUeqMloAkc9obRGFMYLXJKEBs1p0AUQeo/VFxGdknlPLIpkfSlQXQQhNjMhlNP9GfhCZbdKITiy4MKJmDaup+owGWJ1oPhlFxGEELTKGGuwQLRZRE8n7C1KH0hoiCjLSyCRrnQeW2sQhqrIfAAmZ0eO09UDkDF4dXQfn1OIQRXwWGritYxQxjMxHumbkJOLmIFKcx/z0/Y/Da8aNQwTrTxYzN4gHrxE146YhUupAB08as6CGGryyNeNCiBRFIa/OV2uFSMMds67OqGVZ9OCVrRkXQKRo4xmOUUWxZniStmrb1XVDBGvv/wZGVegxMunBK1szJkekjFUAwKWpWPvo+qbAGgB1Zb0QKba73lgHfZcEM3gN1IwLRBH64H0VjGFTV29V0DZrur5uiMyRvwwSXDjhQg9egzVjYkTKGOg1E4CaDfRLswlGa4hImVGL1kENP1CkB6+cmjE5ohnQX81pbWYBfWS+vlbWEdEtfVd6c2ya7bia0UXUjk20KlD1MV4Dr6t6HV1h5hGVDGbcpOyz6/r1ATXuaIzDd0GELuTzESF7VkEdarCNrn2orQMiW6ERWSrLqFMkY4hbM7qIavF2DZ8cNo/oWoMEIj1biB48RPcKE0bVK3YLDUloELXK33zyEGFCWyoHERrqITaoIjIrOJ4IL9IjV7KtHNEPF4Pxi0GEfs99hhG5dzb68dHVQSyiqqUgKiNYtbQK6vcpRFGMVo7I22xl/BtYy6JBizEkL9XCakZHWjcOEerRgAWBum8DMDObKqARRTBaOaJDF1HvJYgIRcolHUhumoXVjI4VWacuoQNMqMxBhOqhGiofbyHQp9BFNJ9YmCMKZ7RyRAUPEdulOYFU18lA6sTUjA4iv0Pbw4h6nESDt/g/BjVUoaIrKh/RcKfZVCuKgyiU0eoRvbnNNm4CZjRnZF+QjGJqRseKRvQWEB4ixcZjs0uoVS/QdTTv9HUdtD1EYYxWj8jbJ1P+wsk0fC+4gvHUjK4Z31U95e6SoRChJLbH83VX0LlWsbxEC2W0ekS7fqZxCeF7GBMlUieyZnzPM3/4wXdrb77IvVLx6yHiM1o9oqHXIGovER1I2sgPpNb3uL3FRJfPt6KYWUf/95ENRIXfXqbx+jQ3kLwSST+OPSNj7PVnYQ8akyLiMRKAaOLefCnEsN8ZeSVS7Oo+eNmNybPkiDiMBCDyuv3IMEJgpvgH8YghhpBfFIXlmaqF/y4YREFGIhD9cZsUGUao6XXcKcfuLzZH3bg8U+1qhDS6os8CokM/jH5GIUK9c60OtRhEpBPxS2skPVLMD2cAkW/YqnEXkWr+k8dIRhfc3dXLKAOIdrww6rGzRmGswtPMnykKD6KlGAlBVHjzwmgryrETMEJenXoQMYzEIDrzwqgUXj8mYqRddNMPIhqSGESFqRdGvcDs4yKITKIkSjGIKEaCEH31xxflrciiJZKR2fd7s/eaKK0gIhgJQlR48BkZzx9NNdTfEweqhBTWSzMShYhw7JIRMisShwja5JEz/ImiFBgJQ+TXj4hRdHUUwgiSVp2uV1OMhCHyH4Vgy07UrbFRdEWe7ZR6mnmMVn44jy+/xkaMbhZmBEd7RSbNUuvNKEYCERWafksWZ0QTChvhp8Fo5QeFESLsKCGjMEId54y5vyORB7NOKEZJ/MgFpFCErv+H9elvSSChQuEzyehbAkYOoeoVSaiV67cUTHUyjjiPsHmMoE329sWTHJyZGqU3glHJeI4fi6BRx5g6ybGR+3em3FKMXqpxjBSzQh0IepK3o8A5IhmpW1v30cmmKK/UW1Mam/BSuSG1XqZs/DKjCFVfysVNI8Qy6hlfwg0J3hhldfMIIUZUrpWMn+xRTF6S/Wf0yKVrJxv0CrA36jlNmThalQwh68WYj8LcpWub4NS+flNr03rGs80GkgJ/Ge5ch5NleXpjQwLRJ/KXDDaQoP1ieMPUeRi1cl4xBvWZXuPYM16IQFKUO6NMTHXg84tyPerga0IzKm0Zd+4OO3j/06BnOlqDXLzNYlGdMav3e8bPG5xt0P7yjZlxBX9EN1aQhm/MyvSy8XxvaneGwUwngg1+dTxjSDjbkJjZRH0/L+/V+ZAmgFnJ0iuz061gKrqRgvX1lt0qwxLaqHqRLzbZZJIFtRvYl+aH0KvoxmVEw9/8QNLV3E8wJtcksP5w7tMbWS6GaThlA0lvyhBi9IkOJFAT3aAs6o8fSODtTHRrsqkzp0bSwYPopmRXP1Sg6+BV2nSUHqavGzd1JiUlJSUlJSUlJSUlJSUlJSUlJSUlJSUlJSUlJYX1fwC/ewCe5vGlAAAAAElFTkSuQmCC" alt="How To install SSL" class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Comment installer SSL</h3>
                    <p class="text-gray-600 mb-6">L'installation de SSL est essentielle pour sécuriser votre site web. Ce guide vous montre les étapes à suivre pour installer SSL sur votre site et protéger vos utilisateurs ainsi que votre entreprise.</p>
                </div>

                <!-- Blog Card 3 -->
                <div class="rounded-lg shadow-md text-center hover:shadow-2xl transition duration-300 ease-in-out transform hover:scale-105"> 
                    <img src="data:image/png;base64,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" alt="Getting started with Figma" class="w-full h-48 object-cover rounded-lg mb-4">
                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Commencer avec Figma</h3>
                    <p class="text-gray-600 mb-6">Figma est l'un des outils les plus puissants pour la conception d'applications web et mobiles. Cet article couvre les bases de Figma et vous guide dans la création de votre premier projet.</p>
                </div>
            </div>
        </div>
        
    </section>

    <!-- Contact Section -->
    <section class="py-20 px-4 bg-white relative overflow-hidden" id="Contact">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute right-0 top-0 w-1/2 h-1/2 bg-repeat" 
                 style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Ccircle cx=\'1\' cy=\'1\' r=\'1\' fill=\'%23000000\'/%3E%3C/svg%3E');
                        background-size: 20px 20px;">
            </div>
        </div>

        <div class="max-w-7xl mx-auto relative">
            <div class="grid lg:grid-cols-2 gap-12 items-start">
                <!-- Left Column -->
                <div>
                    <h2 class="text-4xl font-bold text-slate-800 mb-4">Contactez-nous</h2>
                    <p class="text-gray-600 text-xl mb-8 max-w-lg">
                        Nous sommes toujours prêts à répondre à vos questions et préoccupations. N'hésitez pas à nous contacter pour toute information ou assistance supplémentaire. 
                        Notre équipe est disponible 24/7 pour vous aider.
                    </p>
                    
                    <div class="space-y-4 text-xl">
                        <h3 class="text-2xl font-bold text-slate-800">Appelez notre ligne directe 24/7</h3>
                        <p class="text-xl text-slate-800">06-6666-6666</p>
                        <p class="text-slate-800">Safaeettalhi@gmail.com</p>
                    </div>
                </div>

                <!-- Right Column - Form -->
                <div class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <input 
                            type="text" 
                            placeholder="Nom" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                        >
                        <input 
                            type="email" 
                            placeholder="Email" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                        >
                    </div>
                    <input 
                        type="text" 
                        placeholder="Sujet" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                    >
                    <textarea 
                        placeholder="Message" 
                        rows="6" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-400 focus:border-transparent resize-none"
                    ></textarea>
                    <div class="text-right">
                        <button 
                            type="submit" 
                            class="px-8 py-3 bg-orange-400 hover:bg-orange-500 text-white rounded-lg transition duration-300"
                        >
                        Envoyer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
