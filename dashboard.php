<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Author Panel</title>
    <script>
        // Function to toggle between pages
        function showPage(pageId) {
            // Hide all pages
            const pages = document.querySelectorAll('.page');
            pages.forEach(page => page.classList.add('hidden'));

            // Show the selected page
            document.getElementById(pageId).classList.remove('hidden');
        }
    </script>
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
                        <a href="javascript:void(0)" class="text-sm flex items-center hover:text-blue-400 transition-all">
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <main class="flex-1 p-6">
        <!-- Add Article Page -->
        <div id="addArticlePage" class="page hidden max-w-4xl mx-auto p-6 bg-black text-white shadow-md rounded-lg">
            <h2 class="text-2xl font-semibold mb-4">Insert Article</h2>
            <form>
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium">Title</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        class="mt-1 p-3 block w-full rounded-md border-gray-700 bg-gray-900 text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Enter the article title here"
                    />
                </div>

                <div class="mb-4">
                    <label for="subtitle" class="block text-sm font-medium">Subtitle</label>
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
                    ></textarea>
                </div>

                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium">Category</label>
                    <select
                        id="category"
                        name="category"
                        class="mt-1 p-3 block w-full rounded-md border-gray-700 bg-gray-900 text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
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
    </main>
</body>
</html>