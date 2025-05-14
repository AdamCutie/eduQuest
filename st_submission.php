<?php
session_start();
include('db_connection.php');

/// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: eduquest.php?error=Please+log+in+first+>:l");
    exit();
}

// Update session if class_id is passed in URL
if (isset($_GET['class_id'])) {
    $_SESSION['class_id'] = (int) $_GET['class_id'];
}

// Require session values
if (!isset($_SESSION['class_id']) || !isset($_SESSION['user_id'])) {
    die("Missing class or user information.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class_id'] ?? '';
    $content_id = $_POST['content_id'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $posted_at = date('Y-m-d H:i:s');

    if (empty($class_id) || empty($user_id)) {
        die("class and user are required.");
    }

    $filePath = '';
    $fileName = '';
    $fileType = '';
    $fileSize = 0;

    // If file uploaded
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $file = $_FILES['attachment'];
        $fileTmp = $file['tmp_name'];
        $fileName = basename($file['name']);
        $fileType = mime_content_type($fileTmp);
        $fileSize = $file['size'];

        $uploadDir = 'studentSubmissions/';
        $filePath = $uploadDir . uniqid() . '_' . $fileName;

        // Move the file
        if (!move_uploaded_file($fileTmp, $filePath)) {
            die("Failed to save the uploaded file.");
        }
    }

    // 1. Insert into tbl_announcements
    $stmt = $conn->prepare("INSERT INTO tbl_st_submissions (class_id, content_id, user_id, file_name, file_path, file_type, file_size) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisssi", $class_id, $content_id, $user_id, $fileName, $filePath, $fileType, $fileSize);

    if (!$stmt->execute()) {
        die("Error submitting file " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    header("Location: student-asac_dashboard.php");
    exit();
}
?>