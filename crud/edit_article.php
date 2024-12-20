<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

include '../conn/db_connection.php';

// Get article data
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE article_id = :id AND author_id = :author_id");
    $stmt->execute([':id' => $article_id, ':author_id' => $_SESSION['user_id']]);
    $article = $stmt->fetch();

    if (!$article) {
        header("Location: ../dashboard.php");
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $subtitle = isset($_POST['subtitle']) ? $_POST['subtitle'] : null;
    $content = $_POST['content'];
    $category = $_POST['category'];
    $featuredImageUrl = $_POST['featuredImageUrl'];
    
    try {
        $sql = "UPDATE articles SET 
                article_title = :title,
                article_subtitle = :subtitle,
                article_content = :content,
                category = :category,
                featured_image_url = :featuredImageUrl
                WHERE article_id = :id AND author_id = :author_id";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':subtitle' => $subtitle,
            ':content' => $content,
            ':category' => $category,
            ':featuredImageUrl' => $featuredImageUrl,
            ':id' => $article_id,
            ':author_id' => $_SESSION['user_id']
        ]);

        header("Location: ../dashboard.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error updating article: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-black text-white">
    <div class="max-w-4xl mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Edit Article</h2>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-sm font-medium">Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($article['article_title']); ?>" 
                       class="mt-1 p-3 w-full rounded-md border-gray-700 bg-gray-900 text-white" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Subtitle</label>
                <input type="text" name="subtitle" value="<?php echo htmlspecialchars($article['article_subtitle']); ?>"
                       class="mt-1 p-3 w-full rounded-md border-gray-700 bg-gray-900 text-white">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Content</label>
                <textarea name="content" rows="6" 
                          class="mt-1 p-3 w-full rounded-md border-gray-700 bg-gray-900 text-white" required><?php echo htmlspecialchars($article['article_content']); ?></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Category</label>
                <select name="category" class="mt-1 p-3 w-full rounded-md border-gray-700 bg-gray-900 text-white" required>
                    <?php
                    $categories = ['news', 'opinion', 'technology', 'lifestyle'];
                    foreach ($categories as $cat) {
                        $selected = ($cat == $article['category']) ? 'selected' : '';
                        echo "<option value=\"$cat\" $selected>" . ucfirst($cat) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Featured Image URL</label>
                <input type="url" name="featuredImageUrl" value="<?php echo htmlspecialchars($article['featured_image_url']); ?>"
                       class="mt-1 p-3 w-full rounded-md border-gray-700 bg-gray-900 text-white" required>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                    Update Article
                </button>
                <a href="../dashboard.php" class="bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</body>
</html> 