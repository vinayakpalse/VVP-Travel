<?php
$host = 'localhost';
$dbname = 'cms';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}
$sql = "SELECT id, date, title, summary, image,name FROM posts";
$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .blog-card {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .blog-card img {
            width: 400px;
            max-height: 200px;
            object-fit: cover;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 100px;
        }
        .header {
            position: fixed;
            width: 100%;
            top: 0; 
            left: 0;
            height: 70px;
            display: flex;
            align-items: center;
            z-index: 1;
            background-color: black;
            padding: 0 10px;
        }
        .logo {
            width: 50px;
            margin-right: 10px;
            border-radius: 10px;
        }
        .nav-brand {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
        }
        .read-more {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        .read-more:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="header">
    <img class="logo" src="logo.jpg" alt="Logo">
    <a class="nav-brand" href="index.html">VVP Travel</a>
</div>
<h1>Blogs</h1>
<?php foreach ($posts as $post): 
    // Fetch likes, dislikes, and comment count for each post
    $likes_sql = "SELECT COUNT(*) AS likes_count FROM post_likes WHERE post_id = :id AND is_like = TRUE";
    $dislikes_sql = "SELECT COUNT(*) AS dislikes_count FROM post_likes WHERE post_id = :id AND is_like = FALSE";
    $comments_sql = "SELECT COUNT(*) AS comments_count FROM post_comments WHERE post_id = :id";
    
    $stmt_likes = $pdo->prepare($likes_sql);
    $stmt_dislikes = $pdo->prepare($dislikes_sql);
    $stmt_comments = $pdo->prepare($comments_sql);
    $stmt_likes->bindParam(':id', $post['id']);
    $stmt_dislikes->bindParam(':id', $post['id']);
    $stmt_comments->bindParam(':id', $post['id']);
    $stmt_likes->execute();
    $stmt_dislikes->execute();
    $stmt_comments->execute();

    $likes = $stmt_likes->fetch(PDO::FETCH_ASSOC)['likes_count'];
    $dislikes = $stmt_dislikes->fetch(PDO::FETCH_ASSOC)['dislikes_count'];
    $comments = $stmt_comments->fetch(PDO::FETCH_ASSOC)['comments_count'];
?>
    <div class="blog-card">
        <?php if (!empty($post['image'])): ?>
            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
        <?php endif; ?>
        <h2><?php echo htmlspecialchars($post['title']); ?></h2>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($post['date']); ?></p>
        <p><i>shared by:</i><?php echo htmlspecialchars($post['name']); ?> </p>
        <p><?php echo htmlspecialchars($post['summary']); ?></p>

        <!-- Display Like, Dislike, and Comment counts -->
        <p>Likes: <?php echo $likes; ?> | Dislikes: <?php echo $dislikes; ?> | Comments: <?php echo $comments; ?></p>

        <a class="read-more" href="fullblog.php?id=<?php echo $post['id']; ?>">Read More</a>
    </div>
<?php endforeach; ?>
</body>
</html>
