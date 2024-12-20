<?php
session_start();
include '../conn/db_connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['article_id'])) {
    header("Location: ../dashboard.php");
    exit;
}

try {
    // Delete the article
    $sql = "DELETE FROM articles WHERE article_id = :article_id AND author_id = :author_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':article_id' => $_POST['article_id'],
        ':author_id' => $_SESSION['user_id']
    ]);
    
    header("Location: ../dashboard.php");
    exit;
    
} catch (PDOException $e) {
    header("Location: ../dashboard.php");
    exit;
}
?> 