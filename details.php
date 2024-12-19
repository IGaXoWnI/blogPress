<?php
session_start();

require_once 'db_connection.php';

// Get article ID from URL and validate
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM articles JOIN users ON articles.author_id = user_id WHERE article_id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$article) {
        header('Location: index.php');
        exit();
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['like']) && !empty($_SESSION['user_id'])) {
        try {
            // Check if user has already liked this article
            $checkLike = $pdo->prepare("SELECT * FROM likes WHERE article_id = ? AND user_id = ?");
            $checkLike->execute([$article_id, $_SESSION['user_id']]);
            
            if ($checkLike->rowCount() == 0) {
                $addLike = $pdo->prepare("INSERT INTO likes (article_id, user_id) VALUES (?, ?)");
                $addLike->execute([$article_id, $_SESSION['user_id']]);
                
                $updateCount = $pdo->prepare("UPDATE articles SET like_count = like_count + 1 WHERE article_id = ?");
                $updateCount->execute([$article_id]);
            } else {
                $removeLike = $pdo->prepare("DELETE FROM likes WHERE article_id = ? AND user_id = ?");
                $removeLike->execute([$article_id, $_SESSION['user_id']]);
                
                $updateCount = $pdo->prepare("UPDATE articles SET like_count = like_count - 1 WHERE article_id = ?");
                $updateCount->execute([$article_id]);
            }
            
            header("Location: details.php?id=" . $article_id);
            exit();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    // Handle comment submission
    if (isset($_POST['submit_comment']) && !empty($_SESSION['user_id'])) {
        $comment_content = trim($_POST['comment']);
        
        if (!empty($comment_content)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO comments (article_id, user_id, comment_content) VALUES (?, ?, ?)");
                $stmt->execute([$article_id, $_SESSION['user_id'], $comment_content]);
                
                header("Location: details.php?id=" . $article_id);
                exit();
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}

try {
    $commentStmt = $pdo->prepare("
        SELECT comments.*, users.username 
        FROM comments 
        JOIN users ON comments.user_id = users.user_id 
        WHERE article_id = ? 
        ORDER BY comment_id DESC
    ");
    $commentStmt->execute([$article_id]);
    $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$hasLiked = false;
if (!empty($_SESSION['user_id'])) {
    try {
        $checkLike = $pdo->prepare("SELECT * FROM likes WHERE article_id = ? AND user_id = ?");
        $checkLike->execute([$article_id, $_SESSION['user_id']]);
        $hasLiked = $checkLike->rowCount() > 0;
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <title><?php echo htmlspecialchars($article['article_title']); ?> - Blog</title>
</head>
<body class="font-futura">
    <header>
        <a href="index.php"><h1 class="title">Blog</h1></a>
        <nav class="flex gap-6 items-center">
            <ul>
                <li>Explore</li>
                <a id="signup/in" class="" href="signup.php"><li>Sign Up</li></a>
                <a id="signout" class="hidden" href="signout.php"><li>Sign Out</li></a>
                <li>Contact</li>
                <a class="" id="dashboard" href="dashboard.php"><li>Dashboard</li></a>
            </ul>
        </nav>
    </header>

    <main class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="mb-8">
            <h1 class="text-4xl font-bold mb-2"><?php echo htmlspecialchars($article['article_title']); ?></h1>
            <h2 class="text-xl text-gray-400 mb-4"><?php echo htmlspecialchars($article['article_subtitle']); ?></h2>
            <div class="flex items-center text-sm text-gray-500">
                <span>By <?php echo htmlspecialchars($article['username']); ?></span>
                <span class="mx-2">•</span>
                <span><?php echo date('F j, Y', strtotime($article['create_at'])); ?></span>
            </div>
        </div>

        <div class="relative w-full h-[500px] my-8 flex justify-center">
            <img src="<?php echo htmlspecialchars($article['featured_image_url']); ?>" 
                 alt="<?php echo htmlspecialchars($article['article_title']); ?>" 
                 class="h-full object-contain">
        </div>

        <div class="prose prose-lg prose-invert max-w-none">
            <p class="text-lg leading-relaxed">
                <?php echo nl2br(htmlspecialchars($article['article_content'])); ?>
            </p>
        </div>

        <div class="flex items-center space-x-4 my-8">
            <form method="POST" class="flex items-center">
                <button type="submit" name="like" class="flex items-center space-x-2 <?php echo $hasLiked ? 'bg-blue-600' : 'bg-blue-500 hover:bg-blue-600'; ?> text-white px-4 py-2 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="<?php echo $hasLiked ? 'currentColor' : 'none'; ?>" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                    <span><?php echo $hasLiked ? 'Liked' : 'Like'; ?></span>
                </button>
            </form>
            <span class="text-gray-400"><?php echo $article['like_count']; ?> likes</span>
        </div>

        <div class="mt-12 border-t border-gray-700 pt-8">
            <h3 class="text-2xl font-bold mb-6">Comments</h3>
            
            <?php if (!empty($_SESSION['user_name'])): ?>
                <form method="POST" class="mb-8">
                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium mb-2">Add a comment</label>
                        <textarea id="comment" name="comment" rows="4" 
                                class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                required></textarea>
                    </div>
                    <button type="submit" name="submit_comment" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Post Comment
                    </button>
                </form>
            <?php else: ?>
                <p class="text-gray-400 mb-8">Please <a href="login.php" class="text-blue-500 hover:underline">sign in</a> to leave a comment.</p>
            <?php endif; ?>

            <div class="space-y-6">
                <?php foreach ($comments as $comment): ?>
                <div class="bg-gray-800 p-4 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <div class="font-medium"><?php echo htmlspecialchars($comment['username']); ?></div>
                        <div class="text-sm text-gray-400">
                            <?php echo date('F j, Y', strtotime($comment['created_at'])); ?>
                        </div>
                    </div>
                    <p class="text-gray-300"><?php echo nl2br(htmlspecialchars($comment['comment_content'])); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>
</html>

<script>
<?php
if (!empty($_SESSION['user_name'])) {
    echo "document.getElementById('signup/in').classList.add('hidden');";
    echo "document.getElementById('signout').classList.remove('hidden');";
} else {
    echo "document.getElementById('signup/in').classList.remove('hidden');";
    echo "document.getElementById('signout').classList.add('hidden');";
}

if (!empty($_SESSION['user_role'] !== "Author" )) {
    echo "document.getElementById('dashboard').classList.add('hidden');";
} else {
    echo "document.getElementById('dashboard').classList.remove('hidden');";
}
?>
</script> 