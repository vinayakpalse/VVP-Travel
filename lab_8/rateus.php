<?php
// Database connection (modify the credentials as per your setup)
$host = 'localhost';
$dbname = 'cms';
$username = 'root';  // Your MySQL username
$password = '';      // Your MySQL password

// Connect to MySQL
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

// Form submission logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $title = $_POST['title'];
    $summary = $_POST['summary'];
    $content = $_POST['content'];

    // Handle image upload
    $image = $_FILES['image'];
    $imagePath = 'uploads/' . basename($image['name']); // Set the upload directory

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($image['tmp_name'], $imagePath)) {
        // Prepare SQL statement to insert the data into the database
        $sql = "INSERT INTO posts (name,date, title, summary, content, image) VALUES (:name,:date, :title, :summary, :content, :image)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':summary', $summary);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image', $imagePath); // Bind the image path

        // Execute the query
        if ($stmt->execute()) {
            echo "<div class='success'>Blog post submitted successfully!</div>";
        } else {
            echo "<div class='error'>Error submitting post.</div>";
        }
    } else {
        echo "<div class='error'>Error uploading image.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Blog Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 20px;
            margin-top: 80px; /* Adjusted margin to provide space below the header */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 1100px; /* Increased width for the container */
            display: flex; /* Use flexbox for layout */
            align-items: flex-start; /* Align items at the start */
        }
        .form-container {
            flex: 1; /* Allow the form to grow */
            margin-right: 20px; /* Space between form and image */
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }
        input[type="text"],
        input[type="date"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        .success {
            color: green;
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
        }
        .header {
            position: fixed;
            width: 100%;
            top: 0; /* Fixed position to the top */
            left: 0; /* Align to the left */
            height: 70px;
            display: flex;
            align-items: center;
            z-index: 1;
            background-color: black;
            padding: 0 10px; /* Added padding for logo and nav link */
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
        .preview-image {
            width: 450px; /* Set a width for the image */
            height: auto; /* Maintain aspect ratio */
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            height:600px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img class="logo" src="logo.jpg" alt="Logo">
        <a class="nav-brand" href="index.html">VVP Travel</a>
    </div>
    <div class="container">
        <div class="form-container">
            <h1>Submit a Blog Post</h1>
            <form action="rateus.php" method="POST" enctype="multipart/form-data">
                <label for="name">Your Name:</label>
                <input type="text" name="name" required>

                <label for="date">Date:</label>
                <input type="date" name="date" required>

                <label for="title">Blog Title:</label>
                <input type="text" name="title" required>

                <label for="summary">Summary:</label>
                <textarea name="summary" required></textarea>

                <label for="content">Content:</label>
                <textarea name="content" required></textarea>

                <label for="image">Upload Image:</label>
                <input type="file" name="image" accept="image/*" required>

                <button type="submit">Submit</button>
            </form>
        </div>
        <div>
            <img src="back3.jpg" alt="Preview" class="preview-image"> <!-- Replace 'placeholder-image.jpg' with your desired image -->
        </div>
    </div>
</body>
</html>
