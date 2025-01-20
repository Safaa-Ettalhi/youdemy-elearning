<?php
session_start();
require_once '../class/db.php';
require_once '../class/admin.php';
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Administrateur') {
    header('Location: ../login.php');
    exit();
}
$db = new Database();
$pdo = $db->getPDO();

$admin = new Admin($_SESSION['id'], $_SESSION['nom'], $_SESSION['email'], '', $pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'approveTeacher':
                $admin->approveTeacher($_POST['teacherId']);
                break;
            case 'rejectTeacher':
                $admin->rejectTeacher($_POST['teacherId']);
                break;
            case 'activetUser':
                $admin->activetUser($_POST['userId']);
                break;   
           case 'rejectUser':
                $admin->rejectUser($_POST['userId']);
                break;     
            case 'deleteUser':
                $admin->deleteUser($_POST['userId']);
                break;
            case 'deleteCourse':
                $admin->deleteCourse($_POST['courseId']);
                break;
            case 'addCategory':
                $admin->addCategory($_POST['categoryName']);
                break;
            case 'deleteCategory':
                $admin->deleteCategory($_POST['categoryId']);
                break;
            case 'addTag':
                $admin->addTag($_POST['tagName']);
                break;
            case 'deleteTag':
                $admin->deleteTag($_POST['tagId']);
                break;
            case 'addMultipleTags':
                $tags = explode(',', $_POST['tags']);
                $admin->addMultipleTags($tags);
                break;
        }
    }
}

$coursesCount = $admin->getCoursesCount();
$popularCourse = $admin->getPopularCourse();
$pendingTeachers = $admin->getPendingTeachers();
$topEnseignants =$admin->getTop3Enseignants();
$users = $admin->getAllUsers();
$courses = $admin->getCourses();
$categories = $admin->getCategories();
$tags = $admin->getTags();

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Administrateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'primary': '#4F46E5',
                    'secondary': '#10B981',
                    'accent': '#F59E0B',
                }
            }
        }
    }
    </script>
</head>

<body class="min-h-screen bg-gray-100">
    <!-- Header -->
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


    <h1 class="text-3xl pt-36 text-center font-bold">Tableau de bord Administrateur</h1>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Global Stats -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Statistiques globales</h2>
            <div class="flex flex-col gap-5 md:grid md:grid-cols-2 lg:grid-cols-4">
                <div
                    class="bg-white overflow-hidden shadow-lg rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-primary rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total des cours</dt>
                                    <dd class="text-3xl font-semibold text-gray-900"><?php echo $coursesCount; ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white overflow-hidden shadow-lg rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-secondary rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Cours le plus populaire</dt>
                                    <dd class="text-lg font-semibold text-gray-900"><?php echo $popularCourse; ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white overflow-hidden shadow-lg rounded-lg transition duration-300 ease-in-out transform hover:scale-105 col-span-2">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-accent rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Top 3 enseignants</dt>
                                    <dd class="text-lg font-semibold text-gray-900">
                                        <ol class="list-decimal list-inside">
                                            <?php
                            
                            foreach ($topEnseignants as $enseignant) {
                                echo "<li>" . htmlspecialchars($enseignant['nom']) . "</li>";
                            }
                            ?>
                                        </ol>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teacher Account Validation -->
        <div class="bg-white shadow-lg rounded-lg mb-8">
            <div class="px-4 py-5 bg-orange-400 text-white">
                <h2 class="text-xl font-semibold">Validation des comptes enseignants</h2>
            </div>
            <div class="p-4">
                <?php foreach($pendingTeachers as $teacher): ?>
                <div class="flex items-center justify-between py-3 border-b">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-12 w-12 rounded-full object-cover"
                                src="../uploads/avatars/<?php echo htmlspecialchars($teacher['avatar']); ?>">
                        </div>
                        <div class="ml-4">
                            <p class="font-semibold"><?php echo htmlspecialchars($teacher['nom']); ?></p>
                            <p class="text-gray-600"><?php echo htmlspecialchars($teacher['email']); ?></p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="approveTeacher">
                            <input type="hidden" name="teacherId" value="<?php echo $teacher['id']; ?>">   
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Confirmer</button>
                        
                        </form>
                        <form method="POST" action="">
                          <input type="hidden" name="action" value="rejectTeacher">
                          <input type="hidden" name="teacherId" value="<?php echo $teacher['id']; ?>">   
                          <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Refuser</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- User Management -->
        <div class="bg-white shadow-lg rounded-lg">
            <div class="px-4 py-5 bg-orange-400 text-white">
                <h2 class="text-xl font-semibold">Gestion des utilisateurs</h2>
            </div>
            <div class="border-t border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-s font-medium text-gray-500 uppercase tracking-wider">
                                    Nom</th>
                                <th
                                    class="px-6 py-3 text-left text-s font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th
                                    class="px-6 py-3 text-left text-s font-medium text-gray-500 uppercase tracking-wider">
                                    Rôle</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut</th>
                                <th
                                    class="px-6 py-3 text-left text-s font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($users as $user): ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="../uploads/avatars/<?php echo htmlspecialchars($user['avatar']); ?>"
                                                alt="User avatar">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($user['nom']); ?></div>
                                        </div>

                                    </div>
                                </td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($user['role']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-s leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <?php echo htmlspecialchars($user['statut']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex justify-between">
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="activetUser">
                                    <input type="hidden" name="userId" value="<?php echo $user['id']; ?>"> 
                                    <button type="submit"
                                        class="text-primary text-2xl hover:text-indigo-900 mr-2 transition duration-150 ease-in-out"><i class="ri-checkbox-circle-line"></i></button>
                                </form>
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="rejectUser">
                                    <input type="hidden" name="userId" value="<?php echo $user['id']; ?>"> 
                                    <button type="submit"
                                        class="text-yellow-600 text-2xl  hover:text-yellow-900 mr-2 transition duration-150 ease-in-out"><i class="ri-error-warning-line"></i></button>
                                </form>
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="deleteUser">
                                    <input type="hidden" name="userId" value="<?php echo $user['id']; ?>"> 
                                    <button type="submit"
                                        class="text-red-600 text-2xl hover:text-red-900 transition duration-150 ease-in-out"><i class="ri-delete-bin-line"></i></button>
                               </form>
                                
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Content Management -->
        <div class="bg-white shadow-lg rounded-lg mb-8 overflow-hidden mt-8">
            <div class="px-4 py-5 sm:px-6 bg-orange-400 text-white">
                <h2 class="text-xl font-semibold">Gestion des contenus</h2>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Catégories</h3>
                        <ul class="space-y-2">
                            <?php foreach($categories as $category): ?>
                            <li
                                class="flex justify-between items-center p-2 hover:bg-gray-50 rounded transition duration-150 ease-in-out">
                                <span><?php echo htmlspecialchars($category['nom']); ?></span>
                                <form method="POST">
                                    <input type="hidden" name="action" value="deleteCategory">
                                    <input type="hidden" name="categoryId" value="<?php echo $category['id']; ?>">
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>

                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <form method="POST">
                            <input type="hidden" name="action" value="addCategory">
                            <input type="text" name="categoryName" placeholder="Nouvelle catégorie"
                                class="mt-4 p-4 shadow-sm focus:ring-orange-400 focus:border-orange-400 block w-full sm:text-sm border-gray-300 rounded-md">
                            <button type="submit"
                                class="mt-2 w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-400 hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400 transition duration-150 ease-in-out">Ajouter
                                une catégorie</button>
                        </form>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tags</h3>
                        <?php 
                                $colors = ['bg-blue-100 text-blue-800', 'bg-green-100 text-green-800', 'bg-yellow-100 text-yellow-800', 'bg-red-100 text-red-800', 'bg-purple-100 text-purple-800'];
                            ?>
                        <div class="flex flex-wrap gap-2">
           
                            <?php foreach($tags as $index => $tag): ?>
                            <?php 
                            
                            $colorClass = $colors[$index % count($colors)];
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-s font-medium <?php echo $colorClass; ?>">
                                <?php echo htmlspecialchars($tag['nom']); ?>
                                

                                <form method="POST" class="inline">
                                    <input type="hidden" name="action" value="deleteTag">
                                    <input type="hidden" name="tagId" value="<?php echo $tag['id']; ?>">
                                    <button type="submit"
                                        class="flex-shrink-0 ml-1 h-4 w-4 rounded-full inline-flex items-center justify-center text-blue-400 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400">
                                        <span class="sr-only">Supprimer le tag</span>
                                        <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                            <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                                        </svg>
                                    </button>
                                </form>
                            </span>
                           
                            <?php endforeach; ?>
                          
                        </div>
                        
                        <form method="POST" class="mb-4">
                            <input type="hidden" name="action" value="addTag">
                            <input type="text" name="tagName" placeholder="Nouveau tag" class="mt-4 py-4 shadow-sm focus:ring-orange-400 focus:border-orange-400 block w-full sm:text-sm border-gray-300 rounded-md">
                            <button type="submit" class="mt-2 w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-400 hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400 transition duration-150 ease-in-out">Ajouter</button>
                        </form>
                    <form method="POST" class="mb-4">
                        <input type="hidden" name="action" value="addMultipleTags">
                        <textarea name="tags" placeholder="Ajouter plusieurs tags (séparés par des virgules)"
                            class="border p-2 w-full mb-2"></textarea>
                        <button type="submit" class="mt-2 w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-400 hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400 transition duration-150 ease-in-out">Ajouter en
                            masse</button>
                    </form>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Cours</h3>
                        <ul class="space-y-2">
                            <?php foreach($courses as $course): ?>
                            <li
                                class="flex justify-between items-center p-2 hover:bg-gray-50 rounded transition duration-150 ease-in-out">
                                <span><?php echo htmlspecialchars($course['titre']); ?></span>     
                                <form method="POST" >
                                        <input type="hidden" name="action" value="deleteCourse">
                                        <input type="hidden" name="courseId" value="<?php echo $course['id']; ?>">
                                        <button type="submit" class="text-primary text-s hover:text-indigo-900 transition duration-150 ease-in-out">Gérer</button>
                                </form>
                            </li>
                            <?php endforeach; ?>
                           
                        </ul>
                    </div>
                </div>
            </div>
        </div>

       
    </main>
</body>

</html>