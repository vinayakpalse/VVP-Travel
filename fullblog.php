<?php
// Database connection
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

// Get the blog post by id
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch blog post
    $sql = "SELECT * FROM posts WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch likes and dislikes count
    $likes_sql = "SELECT COUNT(*) AS likes_count FROM post_likes WHERE post_id = :id AND is_like = TRUE";
    $dislikes_sql = "SELECT COUNT(*) AS dislikes_count FROM post_likes WHERE post_id = :id AND is_like = FALSE";
    $stmt_likes = $pdo->prepare($likes_sql);
    $stmt_dislikes = $pdo->prepare($dislikes_sql);
    $stmt_likes->bindParam(':id', $id);
    $stmt_dislikes->bindParam(':id', $id);
    $stmt_likes->execute();
    $stmt_dislikes->execute();
    $likes = $stmt_likes->fetch(PDO::FETCH_ASSOC)['likes_count'];
    $dislikes = $stmt_dislikes->fetch(PDO::FETCH_ASSOC)['dislikes_count'];

    // Fetch comments
    $comments_sql = "SELECT commenter_name, comment_text, created_at FROM post_comments WHERE post_id = :id";
    $stmt_comments = $pdo->prepare($comments_sql);
    $stmt_comments->bindParam(':id', $id);
    $stmt_comments->execute();
    $comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);
}

// Handle like and dislike submissions
if (isset($_POST['like'])) {
    $sql = "INSERT INTO post_likes (post_id, is_like) VALUES (:post_id, TRUE)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':post_id', $id);
    $stmt->execute();
    header("Location: fullblog.php?id=$id");
    exit;
}

if (isset($_POST['dislike'])) {
    $sql = "INSERT INTO post_likes (post_id, is_like) VALUES (:post_id, FALSE)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':post_id', $id);
    $stmt->execute();
    header("Location: fullblog.php?id=$id");
    exit;
}

// Handle comment submission
if (isset($_POST['submit_comment'])) {
    $commenter_name = $_POST['commenter_name'];
    $comment_text = $_POST['comment_text'];

    $sql = "INSERT INTO post_comments (post_id, commenter_name, comment_text) VALUES (:post_id, :commenter_name, :comment_text)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':post_id', $id);
    $stmt->bindParam(':commenter_name', $commenter_name);
    $stmt->bindParam(':comment_text', $comment_text);
    $stmt->execute();
    header("Location: fullblog.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #e8e8e8;
            padding-bottom: 10px;
        }
        .post-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow: hidden; /* Prevent overflow */
            max-width: 100%; /* Set a max width for the card */
            word-wrap: break-word; /* Break long words */
        }
        img {
            max-width: auto;
            height: 400px;
            border-radius: 8px;
            margin: 10px 0;
            display: block; /* Center the image */
        }
        .blog-actions {
            margin: 20px 0;
        }
        button {
            background-color: #4682B4;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-right: 10px;
        }
        button:hover {
            background-color: rgb(122, 172, 207);
        }
        .comments-section {
            margin-top: 30px;
        }
        .comment {
            background: #f9f9f9;
            border-left: 4px solid rgb(122, 172, 207);
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .comment p {
            margin: 0;
        }
        .comment small {
            color: #777;
        }
        form {
            margin-top: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.2);
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        input[type="text"]:focus,
        textarea:focus {
            border-color: #4682B4;
            outline: none;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            background-color: #4682B4;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: rgb(122, 172, 207);
        }
    </style>
</head>
<body>
    <div class="post-container">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <?php if (!empty($post['image'])): ?>
            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Blog Image">
        <?php endif; ?>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($post['date']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>

        <div class="blog-actions">
            <form action="fullblog.php?id=<?php echo $id; ?>" method="POST">
                <button type="submit" name="like">Like (<?php echo $likes; ?>)</button>
                <button type="submit" name="dislike">Dislike (<?php echo $dislikes; ?>)</button>
            </form>
        </div>

        <div class="comments-section">
            <h2>Comments</h2>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><strong><?php echo htmlspecialchars($comment['commenter_name']); ?>:</strong> <?php echo htmlspecialchars($comment['comment_text']); ?></p>
                    <p><small><?php echo $comment['created_at']; ?></small></p>
                </div>
            <?php endforeach; ?>
            <h3>Leave a Comment</h3>
            <form action="fullblog.php?id=<?php echo $id; ?>" method="POST">
                <label for="commenter_name">Name:</label>
                <input type="text" name="commenter_name" required>

                <label for="comment_text">Comment:</label>
                <textarea name="comment_text" required></textarea>

                <button type="submit" name="submit_comment">Submit Comment</button>
            </form>
        </div>

        <a href="blog.php" class="back-button">Back to Blog</a>
    </div>
</body>
</html>
