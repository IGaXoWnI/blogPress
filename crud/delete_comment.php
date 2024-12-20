<?php
session_start();
include '../conn/db_connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['comment_id'])) {
    header("Location: ../dashboard.php");
    exit;
}

try {
    $sql = "DELETE FROM comments WHERE comment_id = :comment_id AND EXISTS (
        SELECT 1 FROM articles 
        WHERE articles.article_id = comments.article_id 
        AND articles.author_id = :author_id
    )";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':comment_id', $_POST['comment_id']);
    $stmt->bindParam(':author_id', $_SESSION['user_id']);
    $stmt->execute();
    
    header("Location: ../dashboard.php");
    exit;
    
} catch (PDOException $e) {
    header("Location: ../dashboard.php");
    exit;
}
?> 