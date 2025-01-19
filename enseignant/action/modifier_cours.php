<?php
session_start();
require_once '../../class/db.php'; 
require_once '../../class/cours.php';
require_once '../../class/tag.php';
require_once '../../class/categorie.php';
$db = new Database();
$pdo = $db->getPDO();

$course_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

$Coure = new Course($pdo);

if ($course_id > 0) {
    $courses = $Coure->getCourseById($course_id); 
    if ($courses) { 
        $title = $courses['titre'];
        $price = $courses['prix'];
        $description = $courses['description'];
        $category_id = $courses['categorie_id'];
        $image = $courses['image']; 
        $content_type = $courses['typeContenu']; 
        $video_url = $courses['vedio']; 
        $document_url = $courses['fichier_document']; 
    }
}

if (!$courses) {
    echo "Cours introuvable.";
    exit;
}

$tag = new Tag($pdo);

$selected_tags = [];
$tags = $tag->getTagsByCourseId($course_id);

$all_tags = $tag->getTags(); 

foreach ($tags as $row) {
    $selected_tags[] = $row['tag_id'];
}
$tag = new Category($pdo);
$category = new Category($pdo);
$categories = $category->getCategories();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category'];
    $content_type = $_POST['content_type'];
    $price = $_POST['price'];
    $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
    $teacherId = $_SESSION['id']; 

    // Gestion des fichiers (image, vidéo ou document)
    $imagePath = $image; // Par défaut, conserver l'image existante
    if (isset($_FILES['file-upload-image']) && $_FILES['file-upload-image']['error'] === 0) {
        $image = $_FILES['file-upload-image'];
        $imagePath = '../../uploads/avatars/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);
    }

    $filePath = ($content_type === 'video') ? $video_url : $document_url; // Par défaut, conserver le contenu existant
    if ($content_type === 'video' && isset($_FILES['video_file']) && $_FILES['video_file']['error'] === 0) {
        $file = $_FILES['video_file'];
        $filePath = '../../uploads/video/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $filePath);
    } elseif ($content_type === 'document' && isset($_FILES['document_file']) && $_FILES['document_file']['error'] === 0) {
        $file = $_FILES['document_file'];
        $filePath = '../../uploads/document/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $filePath);
    }

    // Mise à jour du cours
    try {
        $Coure->updateCourse($course_id, $title, $description, $filePath, $imagePath, $price, $teacherId, $category_id, $tags, $content_type);
        header('Location: ../dashbord.php');
        exit();
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.svg">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet" />
    <script src="../scripts/articles.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
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
                <div class="flex sm:hidden items-center">
                    <button id="burger-icon"
                        class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300">
                        <a href="../deconnecter.php">Deconnecter</a>
                    </button>
                </div>
                <div class="hidden sm:flex sm:items-center sm:justify-center sm:gap-2">
                    <button
                        class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full transition duration-300">
                        <a href="../deconnecter.php">Deconnecter</a>
                    </button>
                </div>
            </div>
        </div>
    </nav>
<div class=" pb-28"></div>
    <div class="bg-white shadow rounded-lg mx-2 md:mx-20 my-4 border border-orange-300 width-1/2" id="update">
    <div class="relative px-4  py-5 sm:px-6 flex justify-center items-center">
        <h3 class="text-2xl font-bold leading-6 text-gray-900">Modifier Cours</h3>
    </div>

    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <form class="space-y-6" method="POST" action="./modifier_cours.php" enctype="multipart/form-data">
            <!-- Titre du cours -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>

            <!-- Description du cours -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="w-full p-2 border border-gray-300 rounded-md" required><?= htmlspecialchars($description) ?></textarea>
            </div>

            <!-- Prix du cours -->
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Prix</label>
                <input type="number" id="price" name="price" value="<?= htmlspecialchars($price) ?>" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>

            <!-- Sélection de la catégorie -->
            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
                <select id="category" name="category" class="w-full p-2 border border-gray-300 rounded-md" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= ($category['id'] == $category_id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tags du cours -->
            <div class="mb-4">
                <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                <div id="tags-container" class="space-y-2 space-x-1">
                    <?php
                    // Afficher tous les tags disponibles
                    foreach ($all_tags as $tag) {
                        $selected = (in_array($tag['id'], $selected_tags)) ? 'bg-orange-300' : 'bg-gray-200';
                        echo "<div class='tag-item $selected space-x-2 inline-block p-2 border border-orange-300 rounded-full cursor-pointer hover:bg-orange-300' data-tag-id=\"" . $tag['id'] . "\">" . htmlspecialchars($tag['nom']) . "</div>";
                    }
                    ?>
                </div>
                <input type="hidden" name="tags[]" id="selected-tags-hidden" value="<?php echo htmlspecialchars(implode(',', $selected_tags)); ?>">
                <p class="text-xs text-gray-500 mt-1">Cliquez sur un tag pour le sélectionner ou le retirer.</p>
            </div>

            <!-- Type de contenu -->
            <div class="mb-4">
                <label for="content_type" class="block text-sm font-medium text-gray-700">Type de contenu</label>
                <select id="content_type" name="content_type" class="w-full p-2 border border-gray-300 rounded-md" required>
                    <option value="video" <?= ($content_type == 'video') ? 'selected' : '' ?>>Vidéo</option>
                    <option value="document" <?= ($content_type == 'document') ? 'selected' : '' ?>>Document</option>
                </select>
            </div>

            <!-- Sections vidéo et document dynamiques -->
            <div id="video-section" class="mb-4" style="<?= $content_type == 'video' ? '' : 'display: none;' ?>">
                <label for="video_url" class="block text-sm font-medium text-gray-700">URL de la vidéo</label>
                <?php if (empty($video_url)): ?>
                    <input type="url" id="video_url" name="video_url" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Entrez l'URL de la vidéo">
                <?php else: ?>
                    <input type="url" id="video_url" name="video_url" value="<?= htmlspecialchars($video_url) ?>" class="w-full p-2 border border-gray-300 rounded-md" readonly>
                <?php endif; ?>
                <div class="mb-4">
                    <label for="video_file" class="block text-sm font-medium text-gray-700">Télécharger une nouvelle vidéo</label>
                    <input type="file" id="video_file" name="video_file" accept="video/*" class="w-full p-2 border border-gray-300 rounded-md">
                </div>
            </div>

            <div id="document-section" class="mb-4" style="<?= $content_type == 'document' ? '' : 'display: none;' ?>">
                <label for="document_url" class="block text-sm font-medium text-gray-700">URL du document</label>
                <?php if (empty($document_url)): ?>
                    <input type="url" id="document_url" name="document_url" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Entrez l'URL du document">
                <?php else: ?>
                    <input type="url" id="document_url" name="document_url" value="<?= htmlspecialchars($document_url) ?>" class="w-full p-2 border border-gray-300 rounded-md" readonly>
                <?php endif; ?>
                <div class="mb-4">
                    <label for="document_file" class="block text-sm font-medium text-gray-700">Télécharger un nouveau document</label>
                    <input type="file" id="document_file" name="document_file" accept=".pdf, .doc, .docx" class="w-full p-2 border border-gray-300 rounded-md">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full bg-orange-400 hover:bg-orange-500 text-white px-6 py-3 rounded-full transition duration-300">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

<script>
  // JavaScript pour gérer la sélection/désélection des tags
document.querySelectorAll('.tag-item').forEach(function(tagItem) {
    tagItem.addEventListener('click', function() {
        const tagId = this.getAttribute('data-tag-id');
        const selectedTagsInput = document.getElementById('selected-tags-hidden');
        let selectedTags = selectedTagsInput.value ? selectedTagsInput.value.split(',') : [];

        // Vérifier si le tag est déjà sélectionné
        if (selectedTags.includes(tagId)) {
            // Si sélectionné, le retirer de la liste
            selectedTags = selectedTags.filter(id => id !== tagId);
            this.classList.remove('bg-orange-300'); // Retirer la classe de sélection
        } else {
            // Si non sélectionné, l'ajouter à la liste
            selectedTags.push(tagId);
            this.classList.add('bg-orange-300'); // Ajouter la classe de sélection
        }

        // Mettre à jour la valeur cachée avec les tags sélectionnés
        selectedTagsInput.value = selectedTags.join(',');
    });
});


</script>
</body>

</html>