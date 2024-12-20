<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'conn/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $subtitle = isset($_POST['subtitle']) ? $_POST['subtitle'] : null; 
    $content = $_POST['content'];
    $category = $_POST['category'];
    $featuredImageUrl = $_POST['featuredImageUrl'];
    $author_id = $_SESSION['user_id'];

    try {
        $sql = "INSERT INTO articles (article_title, article_content, author_id, view_count, like_count, create_at, article_subtitle, category, featured_image_url)
                VALUES (:title, :content, :author_id, 0, 0, NOW()::timestamp(0), :subtitle, :category, :featuredImageUrl)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':author_id' => $author_id,
            ':subtitle' => $subtitle,
            ':category' => $category,
            ':featuredImageUrl' => $featuredImageUrl
        ]);

        header("Location: dashboard.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$statsQuery = "SELECT  
    SUM(view_count) as total_views,
    SUM(like_count) as total_likes,
    COUNT(DISTINCT c.comment_id) as total_comments
FROM articles a 
LEFT JOIN comments c ON a.article_id = c.article_id
WHERE a.author_id = :user_id";

$stmt = $pdo->prepare($statsQuery);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

$articlesQuery = "SELECT a.article_id, a.category, a.article_title, a.create_at, u.username 
                 FROM articles a 
                 JOIN users u ON a.author_id = u.user_id 
                 WHERE a.author_id = :user_id
                 ORDER BY a.create_at DESC";
$stmt = $pdo->prepare($articlesQuery);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$commentsQuery = "SELECT c.comment_id, c.comment_content, created_at, c.article_id, 
                        a.article_title, u.username as commenter_username
                 FROM comments c
                 JOIN articles a ON c.article_id = a.article_id
                 JOIN users u ON c.user_id = u.user_id
                 WHERE a.author_id = :user_id
                 ORDER BY created_at DESC";

$stmt = $pdo->prepare($commentsQuery);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Author Panel</title>
    <script>
        function showPage(pageId) {
            const pages = document.querySelectorAll('.page');
            pages.forEach(page => page.classList.add('hidden'));

            document.getElementById(pageId).classList.remove('hidden');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="flex bg-black text-white">
    <nav class="bg-gray-800 shadow-xl h-screen sticky top-0 left-0 min-w-[250px] py-6 px-4 font-[sans-serif] overflow-auto">
        <div class="relative flex flex-col h-full">
            <div class="flex flex-wrap items-center cursor-pointer relative">
                <div class="ml-4">
                    <p class="text-sm font-semibold">Author Panel</p>
                </div>
            </div>
            <hr class="my-6 border-gray-700" />
            <div>
                <h4 class="text-sm text-gray-400 mb-4">Insights</h4>
                <ul class="space-y-4 px-2 flex-1">
                    <li>
                        <a href="index.php" class="text-sm flex items-center hover:text-blue-400 transition-all">
                            <span id="home">Home</span>
                        </a>
                    </li>
                    <li>
                        <a onclick="showPage('addArticlePage')" href="#" class="text-sm flex items-center hover:text-blue-400 transition-all">
                            <span id="add_article_side">Add an article</span>
                        </a>
                    </li>
                    <li>
                        <a onclick="showPage('activeInactivePage')" href="javascript:void(0)" class="text-sm flex items-center hover:text-blue-400 transition-all">
                            <span id="active_inactive">Article Management</span>
                        </a>
                    </li>
                    <li>
                        <a onclick="showPage('statistics')" href="javascript:void(0)" class="text-sm flex items-center hover:text-blue-400 transition-all">
                            <span id="statistic">Statistics</span>
                        </a>
                    </li>
                    <li>
                        <a onclick="showPage('commentsPage')" href="javascript:void(0)" class="text-sm flex items-center hover:text-blue-400 transition-all">
                            <span id="comments">Comments Management</span>
                        </a>
                    </li>
                </ul>
            </div>
            <hr class="my-6 border-gray-700" />
            <div class="mt-4">
                <ul class="space-y-4 px-2">
                    <li>
                        <a href="javascript:void(0)" class="text-sm flex items-center hover:text-blue-400 transition-all">
                            <span>Settings</span>
                        </a>
                    </li>
                    <li>
                        <a id="signout/in" class="" href="index.php"><li>sign out</li></a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>
    
    <main class="flex-1 p-6">
        <div id="addArticlePage" class="page hidden max-w-4xl mx-auto p-6 bg-black text-white shadow-md rounded-lg">
            <h2 class="text-2xl font-semibold mb-4">Insert Article</h2>
            <form method="POST" action="dashboard.php">
    <div class="mb-4">
        <label for="title" class="block text-sm font-medium">Title</label>
        <input
            type="text"
            id="title"
            name="title"
            class="mt-1 p-3 block w-full rounded-md border-gray-700 bg-gray-900 text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Enter the article title here"
            required
        />
    </div>

    <div class="mb-4">
        <label for="subtitle" class="block text-sm font-medium">Subtitle (Optional)</label>
        <input
            type="text"
            id="subtitle"
            name="subtitle"
            class="mt-1 p-3 block w-full rounded-md border-gray-700 bg-gray-900 text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Enter a subtitle or brief summary"
        />
    </div>

    <div class="mb-4">
        <label for="content" class="block text-sm font-medium">Content</label>
        <textarea
            id="content"
            name="content"
            rows="6"
            class="mt-1 p-3 block w-full rounded-md border-gray-700 bg-gray-900 text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Write your article here..."
            required
        ></textarea>
    </div>

    <div class="mb-4">
        <label for="category" class="block text-sm font-medium">Category</label>
        <select
            id="category"
            name="category"
            class="mt-1 p-3 block w-full rounded-md border-gray-700 bg-gray-900 text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required
        >
            <option value="news">News</option>
            <option value="opinion">Opinion</option>
            <option value="technology">Technology</option>
            <option value="lifestyle">Lifestyle</option>
        </select>
    </div>

    <div class="mb-4">
        <label for="featuredImageUrl" class="block text-sm font-medium text-white">Featured Image URL</label>
        <input
            type="url"
            id="featuredImageUrl"
            name="featuredImageUrl"
            placeholder="Enter the URL of the featured image"
            class="mt-1 p-3 block w-full bg-black text-white border border-gray-600 rounded-md shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500"
            required
        />
    </div>

    <div>
        <button
            type="submit"
            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
            Submit Article
        </button>
    </div>
</form>
        </div>

        <div id="activeInactivePage" class="page hidden">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-2xl font-semibold mb-6">Article Management</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-900 rounded-lg overflow-hidden">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Article Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Author</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            <?php foreach ($articles as $article): ?>
                            <tr class="hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300"><?php echo htmlspecialchars($article['article_id']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300"><?php echo htmlspecialchars($article['category']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300"><?php echo htmlspecialchars($article['article_title']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300"><?php echo htmlspecialchars($article['username']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300"><?php echo htmlspecialchars($article['create_at']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    <a href="crud/edit_article.php?id=<?php echo $article['article_id']; ?>" 
                                       class="text-blue-400 hover:text-blue-300 mr-4">Update</a>
                                    <form method="POST" action="crud/delete_article.php" style="display: inline;">
                                        <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                                        <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="commentsPage" class="page hidden">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-2xl font-semibold mb-6">Comments Management</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-900 rounded-lg overflow-hidden">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Comment ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Article Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Commenter</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Comment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            <?php foreach ($comments as $comment): ?>
                            <tr class="hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300"><?php echo htmlspecialchars($comment['comment_id']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300"><?php echo htmlspecialchars($comment['article_title']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300"><?php echo htmlspecialchars($comment['commenter_username']); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-300"><?php echo htmlspecialchars($comment['comment_content']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300"><?php echo htmlspecialchars($comment['created_at']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    <form method="POST" action="crud/delete_comment.php" style="display: inline;">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this comment?')" 
                                                class="text-red-400 hover:text-red-300">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="statistics" class="page hidden">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-2xl font-semibold mb-6">Statistics Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="bg-gray-900 p-6 rounded-lg">
                        <div class="flex flex-col space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Total Views</span>
                                <span class="text-2xl font-bold"><?php echo number_format($stats['total_views'] ?? 0); ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Total Likes</span>
                                <span class="text-2xl font-bold"><?php echo number_format($stats['total_likes'] ?? 0); ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Total Comments</span>
                                <span class="text-2xl font-bold"><?php echo number_format($stats['total_comments'] ?? 0); ?></span>
                            </div>
                        </div>
                    </div>
                    
 
                    <div class="bg-gray-900 p-6 rounded-lg">
                        <canvas id="statsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('statsChart').getContext('2d');
            
            const statsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Views', 'Likes', 'Comments'],
                    datasets: [{
                        data: [
                            <?php echo $stats['total_views'] ?? 0; ?>,
                            <?php echo $stats['total_likes'] ?? 0; ?>,
                            <?php echo $stats['total_comments'] ?? 0; ?>
                        ],
                        backgroundColor: '#4F46E5'
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
    <script>
        // Show statistics page when dashboard loads
        window.onload = function() {
            showPage('statistics');
        }
    </script>
</body>
</html>