<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=testdb', 'username', 'password');
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE id = $id";
    $stmt = $pdo->prepare($sql);

    // Bind the parameter


    // Set the value of $id
    $id = 1;

    // Execute the prepared statement
    $stmt->execute();

    // Fetch the results
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Display the result
    print_r($result);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>