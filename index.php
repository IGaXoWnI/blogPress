<?php
session_start(); 


// $user_id = $_SESSION['user_id'];
// $user_email = $_SESSION['user_email'];
// $user_name = $_SESSION['user_name'];
// $user_role = $_SESSION['user_role'];



// echo "User id: " . $user_id . "<br>";
// echo "User email: " . $user_email . "<br>";
// echo "User username: " . $user_name . "<br>";
// echo "User role: " . $user_role . "<br>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <title>Blog</title>
</head>
<body class="font-futura"> 
    <header>
        <a href="index.php"><h1 class="title">Blog</h1></a>
        <nav class="flex gap-6 items-center">
            <ul>
                <li>Explore</li>
                <a href="signup.php"><li>Sign Up</li></a>
                <li>Contact</li>
                 <a href="dashboard.php"><li>Dashboard</li></a>
            </ul>
          
                <img src="assets/search.svg" class="h-10" alt="">
          
           
        </nav>
    </header>
    <p class="w-full p-4 text-xl font-semibold text-gray-600 mt-0">
    Latest News for you <?php echo $_SESSION['user_name']; ?> !!</p>
    <section class="w-5/6 m-auto grid grid-cols-3 gap-y-10 place-items-center">
  <div class="relative w-full max-w-xs h-96 bg-gray-300 hover:brightness-100 transition-all duration-300">
    <img src="assets/img.webp" alt="Background Image" class="absolute inset-0 w-full h-full object-cover filter brightness-50">
    <div class="absolute bottom-0 w-full  text-white text-center py-4">
      <h2 class="text-lg font-semibold">4Freestyle x EA Sports</h2>
    </div>
  </div>

  <div class="relative w-full max-w-xs h-96 bg-gray-300 hover:brightness-100 transition-all duration-300">
    <img src="assets/img1.webp" alt="Background Image" class="absolute inset-0 w-full h-full object-cover filter brightness-50">
    <div class="absolute bottom-0 w-full  text-white text-center py-4">
      <h2 class="text-lg font-semibold">Off-Pitch and Oslo street football
      center</h2>
    </div>
  </div>

  <div class="relative w-full max-w-xs h-96 bg-gray-300 hover:brightness-100 transition-all duration-300">
    <img src="assets/img2.webp" alt="Background Image" class="absolute inset-0 w-full h-full object-cover filter brightness-50">
    <div class="absolute bottom-0 w-full  text-white text-center py-4">
      <h2 class="text-lg font-semibold">We Made it to Barcelona</h2>
    </div>
  </div>


  <div class="relative w-full max-w-xs h-96 bg-gray-300 hover:brightness-100 transition-all duration-300">
    <img src="assets/img2.webp" alt="Background Image" class="absolute inset-0 w-full h-full object-cover filter brightness-50">
    <div class="absolute bottom-0 w-full  text-white text-center py-4">
      <h2 class="text-lg font-semibold">We Made it to Barcelona</h2>
    </div>
  </div>
  
</section>
</body>
</html>