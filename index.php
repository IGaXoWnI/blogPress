<?php
session_start(); 


// $user_id = $_SESSION['user_id'];
// $user_email = $_SESSION['user_email'];
// $user_name = $_SESSION['user_name'];
// $user_role = $_SESSION['user_role'];



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
                <a id="signup/in" class="" href="signup.php"><li>Sign Up</li></a>
                <a id="signout" class="hidden" href="signout.php"><li>Sign Out</li></a>
                <li>Contact</li>
                 <a class="" id="dashboard" href="dashboard.php"><li>Dashboard</li></a>
            </ul>
          
                
          
           
        </nav>
    </header>
    <div class="w-full px-12 py-8 flex justify_between items_center">
    <p class="w-full  text-sm font-extralight text-white">
    Latest News for you <?php echo $_SESSION['user_name']; ?> !!</p>
    <input type="text" name="search" placeholder="Search"
       autocomplete="off"
      class="block w-1/4 border-b border-gray-50 opacity-50 bg-transparent p-0 text-sm file:my-1 file:rounded-full file:border-0 file:bg-accent file:px-4 file:py-2 file:font-medium placeholder:text-muted-foreground/90 focus:outline-none focus:ring-0 sm:leading-7 text-foreground">
      <img src="assets/search.svg" class="h-8 " alt="">
    </div>
    

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


<script>
  
  <?php
if (!empty($_SESSION['user_name'])) {
    echo "document.getElementById('signup/in').classList.add('hidden');";
    echo "document.getElementById('signout').classList.remove('hidden');";
}else{
  echo "document.getElementById('signup/in').classList.remove('hidden');";
  echo "document.getElementById('signout').classList.add('hidden');";

}


if (!empty($_SESSION['user_role'] !== "Author" )) {
  
  echo "document.getElementById('dashboard').classList.add('hidden');";
}else{
  echo "document.getElementById('dashboard').classList.remove('hidden');";

}
?>
</script>