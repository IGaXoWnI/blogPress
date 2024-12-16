<?php
require 'db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';


    if (empty($username) || empty($email) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (username, user_email, user_password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
        ]);
        header("Location: signin.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <title>SignUp</title>
</head>
<body>
<header>
        <a href="index.php"><h1 class = "title">Blog</h1></a>
        <nav>
            <ul>
                <li>Explore</li>
                <a href="signup.php"><li>Sign Up</li></a>
                <li>Contact</li>
            </ul>
        </nav>
    </header>
    <div class="bg-black text-white flex h-[80vh] flex-col items-center pt-16 sm:justify-center sm:pt-0">
   
        <div class="relative mt-12 w-full max-w-lg sm:mt-10">
            <div class="relative -mb-px h-px w-full bg-gradient-to-r from-transparent via-sky-300 to-transparent"
                bis_skin_checked="1"></div>
            <div
                class="mx-5 border dark:border-b-white/50 dark:border-t-white/50 border-b-white/20 sm:border-t-white/20 shadow-[20px_0_20px_20px] shadow-slate-500/10 dark:shadow-white/20 rounded-lg border-white/20 border-l-white/20 border-r-white/20 sm:shadow-sm lg:rounded-xl lg:shadow-none">
                <div class="flex flex-col p-6">
                    <h3 class="text-xl font-semibold leading-6 tracking-tighter">Sign Up</h3>
                    <p class="mt-1.5 text-sm font-medium text-white/50">Create an account to gain full access!
                    </p>
                </div>
                <div class="p-6 pt-0">
                <form action="signup.php" method="POST">
                        <div>
                            <div class="group relative rounded-lg border focus-within:border-sky-200 px-3 pb-1.5 pt-2.5 duration-200 focus-within:ring focus-within:ring-sky-300/30">
                                <div class="flex justify-between">
                                    <label class="text-xs font-medium text-muted-foreground group-focus-within:text-white text-gray-400">Username</label>
                                </div>
                                <input type="text" name="username" placeholder="Username" autocomplete="off"
                                    class="block w-full border-0 bg-transparent p-0 text-sm file:my-1 file:rounded-full file:border-0 file:bg-accent file:px-4 file:py-2 file:font-medium placeholder:text-muted-foreground/90 focus:outline-none focus:ring-0 sm:leading-7 text-foreground">
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="group relative rounded-lg border focus-within:border-sky-200 px-3 pb-1.5 pt-2.5 duration-200 focus-within:ring focus-within:ring-sky-300/30">
                                <div class="flex justify-between">
                                    <label class="text-xs font-medium text-muted-foreground group-focus-within:text-white text-gray-400">Email</label>
                                </div>
                                <input type="email" name="email" placeholder="Email" autocomplete="off"
                                    class="block w-full border-0 bg-transparent p-0 text-sm file:my-1 file:rounded-full file:border-0 file:bg-accent file:px-4 file:py-2 file:font-medium placeholder:text-muted-foreground/90 focus:outline-none focus:ring-0 sm:leading-7 text-foreground">
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="group relative rounded-lg border focus-within:border-sky-200 px-3 pb-1.5 pt-2.5 duration-200 focus-within:ring focus-within:ring-sky-300/30">
                                <div class="flex justify-between">
                                    <label class="text-xs font-medium text-muted-foreground group-focus-within:text-white text-gray-400">Password</label>
                                </div>
                                <input type="password" name="password"
                                    class="block w-full border-0 bg-transparent p-0 text-sm file:my-1 placeholder:text-muted-foreground/90 focus:outline-none focus:ring-0 focus:ring-teal-500 sm:leading-7 text-foreground">
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="remember" class="outline-none focus:outline focus:outline-sky-300">
                                <span class="text-xs">Remember me</span>
                            </label>
                            <a class="text-sm font-medium text-foreground underline" href="signin.php">I have already an account</a>
                        </div>

                        <div class="mt-4 flex items-center justify-center gap-x-2">
                            <button
                                class="font-semibold hover:bg-black hover:text-white hover:ring hover:ring-white transition duration-300 inline-flex items-center justify-center rounded-md text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-white text-black h-10 px-4 py-2"
                                type="submit">Register</button>
                        </div>
</form>
                </div>
            </div>
        </div>
    </div>
    </section>

</body>
</html>