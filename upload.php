<?php
$target_dir = "uploads/"; 
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); 
}

$experiences_file = 'experiences.txt'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES["image"]) && isset($_POST["text"])) {
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $experience_text = htmlspecialchars($_POST["text"]);
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            file_put_contents($experiences_file, "$experience_text|$target_file\n", FILE_APPEND);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'File upload failed']);
        }
        exit; 
    } else {
        echo json_encode(['error' => 'No image or text provided']);
    }
}
?>
