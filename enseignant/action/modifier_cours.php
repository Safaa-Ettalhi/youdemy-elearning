<?php
session_start();
include('../../class/db.php');
include('../../class/cours.php');
include('../../class/categorie.php');
include('../../class/tag.php');
include('../../class/videoCourse.php');
include('../../class/documentCourse.php');

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Enseignant') {
    header('Location: ../login.php');
    exit();
}

$enseignant_id = $_SESSION['id'];

if (!isset($_GET['id'])) {
    header('Location: enseignant.php');
    exit();
}

$course_id = $_GET['id'];

$db = new Database();
$pdo = $db->getPDO();

$course = new Course($pdo);
$category = new Category($pdo);
$tag = new Tag($pdo);

try {
    $courseInfo = $course->getCourseById($course_id);

    if ($courseInfo['user_id'] != $enseignant_id) {
        throw new Exception("Vous n'êtes pas autorisé à modifier ce cours.");
    }
    
    $categories = $category->getCategories();
    $tags = $tag->getTags();
    $courseTags = $tag->getTagsByCourseId($course_id);
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: enseignant.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category_id = $_POST['category'];
        $selectedTags = isset($_POST['tags']) ? $_POST['tags'] : [];

        $filePath = $courseInfo['fichier_document']; 
        $videoPath = $courseInfo['vedio']; 
        $newContentType = $courseInfo['typeContenu']; 

        if (isset($_FILES['file-upload-document']) && $_FILES['file-upload-document']['error'] == 0) {
            $uploadDir = '../../uploads/document/';
            $filePath = $uploadDir . basename($_FILES['file-upload-document']['name']);
            move_uploaded_file($_FILES['file-upload-document']['tmp_name'], $filePath);
            $videoPath = null; 
            $newContentType = 'document';
        }

        if (isset($_FILES['file-upload-video']) && $_FILES['file-upload-video']['error'] == 0) {
            $uploadDir = '../../uploads/vedio/';
            $videoPath = $uploadDir . basename($_FILES['file-upload-video']['name']);
            move_uploaded_file($_FILES['file-upload-video']['tmp_name'], $videoPath);
            $filePath = null; 
            $newContentType = 'video';
        }

        $imagePath = $courseInfo['image']; 
        if (isset($_FILES['file-upload-image']) && $_FILES['file-upload-image']['error'] == 0) {
            $uploadDir = '../../uploads/avatars/';
            $imagePath = $uploadDir . basename($_FILES['file-upload-image']['name']);
            move_uploaded_file($_FILES['file-upload-image']['tmp_name'], $imagePath);
        }

        $course->updateCourse($course_id, $title, $description, $filePath, $imagePath, $price, $enseignant_id, $category_id, $selectedTags, $newContentType, $videoPath);

        $_SESSION['success'] = "Le cours a été mis à jour avec succès.";
        header('Location: ../dashbord.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    
    <nav class="fixed w-full bg-white/95 backdrop-blur-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="../dashbord.php" class="flex-shrink-0">
                        <img src="../../SAFAA BB.svg" alt="">
                    </a>
                </div>
                <div class="flex items-center">
                    <button class="bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300">
                        <a href="../deconnecter.php">Déconnecter</a>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8 pt-24">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Modifier le cours</h1>
            
          

            <form action="" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                        Titre
                    </label>
                    <input class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-orange-500" 
                           id="title" 
                           type="text" 
                           name="title" 
                           value="<?php echo htmlspecialchars($courseInfo['titre']); ?>" 
                           required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Description
                    </label>
                    <textarea class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-orange-500" 
                              id="description" 
                              name="description" 
                              rows="4" 
                              required><?php echo htmlspecialchars($courseInfo['description']); ?></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                        Prix
                    </label>
                    <input class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-orange-500" 
                           id="price" 
                           type="number" 
                           name="price" 
                           value="<?php echo htmlspecialchars($courseInfo['prix']); ?>" 
                           required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                        Catégorie
                    </label>
                    <select class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-orange-500" 
                            id="category" 
                            name="category" 
                            required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" 
                                    <?php echo ($cat['id'] == $courseInfo['categorie_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Tags
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($tags as $tag): ?>
                            <label class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 hover:bg-orange-100 cursor-pointer">
                                <input type="checkbox" 
                                       name="tags[]" 
                                       value="<?php echo $tag['id']; ?>" 
                                       <?php echo in_array($tag['id'], array_column($courseTags, 'tag_id')) ? 'checked' : ''; ?>
                                       class="form-checkbox h-4 w-4 text-orange-500">
                                <span class="ml-2"><?php echo htmlspecialchars($tag['nom']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

               
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Image du cours
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-orange-300 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload-image" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                    <span>Télécharger une image</span>
                                    <input id="file-upload-image" name="file-upload-image" type="file" accept="image/*" class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG jusqu'à 10MB</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <?php if (!empty($courseInfo['image']) && file_exists($courseInfo['image'])): ?>
                            <p class="text-sm text-gray-500">Image actuelle :</p>
                            <img src="<?php echo htmlspecialchars($courseInfo['image']); ?>" alt="Image du cours" class="mt-2 h-32 w-auto rounded-lg">
                        <?php else: ?>
                            <p class="text-sm text-gray-500">Aucune image sélectionnée</p>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Vidéo du cours
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-orange-300 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h3m-3 4h3m-3 4h3M13 8h3m-3 4h3m-3 4h3" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload-video" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                    <span>Télécharger une vidéo</span>
                                    <input id="file-upload-video" name="file-upload-video" type="file" accept="video/*" class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">MP4, AVI jusqu'à 500MB</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <?php if ($courseInfo['typeContenu'] == 'video' && !empty($courseInfo['vedio'])): ?>
                            <p class="text-sm text-gray-500">Vidéo actuelle :</p>
                            <div class="mt-2">
                                <video controls class="w-full rounded-lg">
                                    <source src="<?php echo htmlspecialchars($courseInfo['vedio']); ?>" type="video/mp4">
                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                </video>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">Aucune vidéo sélectionnée</p>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Document du cours
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-orange-300 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload-document" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                    <span>Télécharger un document</span>
                                    <input id="file-upload-document" name="file-upload-document" type="file" accept=".pdf,.doc,.docx" class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC jusqu'à 10MB</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <?php if ($courseInfo['typeContenu'] == 'document' && !empty($courseInfo['fichier_document'])): ?>
                            <p class="text-sm text-gray-500">Document actuel : <?php echo basename($courseInfo['fichier_document']); ?></p>
                            <div class="mt-2">
                                <a href="<?php echo htmlspecialchars($courseInfo['fichier_document']); ?>" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                                   target="_blank">
                                    <i class="ri-file-download-line mr-2"></i>
                                    Voir le document
                                </a>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">Aucun document sélectionné</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                        Mettre à jour le cours
                    </button>
                    <a href="../dashbord.php" class="inline-block align-baseline font-bold text-sm text-orange-500 hover:text-orange-800">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        
        document.getElementById('file-upload-image').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const fileInfo = e.target.parentElement.parentElement.nextElementSibling;
                fileInfo.textContent = `Fichier sélectionné : ${fileName}`;
            }
        });

        
        document.getElementById('file-upload-video').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const fileInfo = e.target.parentElement.parentElement.nextElementSibling;
                fileInfo.textContent = `Fichier sélectionné : ${fileName}`;
            }
        });

        
        document.getElementById('file-upload-document').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const fileInfo = e.target.parentElement.parentElement.nextElementSibling;
                fileInfo.textContent = `Fichier sélectionné : ${fileName}`;
            }
        });
    </script>
</body>
</html>

