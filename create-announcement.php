<?php
session_start();
include('db_connection.php');

// Check if user is logged in
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
    $class_id = $_SESSION['class_id'];
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'] ?? '';
    $desc = $_POST['description'] ?? '';

    if (empty($title) || empty($desc)) {
        die("Title and description are required.");
    }

    $filePath = '';
    $fileName = '';
    $fileType = '';
    $fileSize = 0;

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $file = $_FILES['attachment'];
        $fileTmp = $file['tmp_name'];
        $fileName = basename($file['name']);
        $fileType = mime_content_type($fileTmp);
        $fileSize = $file['size'];

        $uploadDir = 'uploads/';
        $filePath = $uploadDir . uniqid() . '_' . $fileName;

        if (!move_uploaded_file($fileTmp, $filePath)) {
            die("Failed to save the uploaded file.");
        }
    }

    // 1. Insert into tbl_announcements
    $stmt = $conn->prepare("INSERT INTO tbl_announcements (class_id, user_id, title, description, file_name, file_path, file_type, file_size) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssssi", $class_id, $user_id, $title, $desc, $fileName, $filePath, $fileType, $fileSize);

    if (!$stmt->execute()) {
        die("Error saving announcement: " . $stmt->error);
    }

    $ann_id = $conn->insert_id;
    $stmt->close();
    $type = "announcement";
    $posted_at = date('Y-m-d H:i:s');

    // this line insert in the topics
    $stmt = $conn->prepare("INSERT INTO tbl_topics (content_id, class_id, user_id, type, title, posted_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisss", $ann_id, $class_id, $user_id, $type, $title, $posted_at);

    if (!$stmt->execute()) {
        die("Error saving topic: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    header("Location: teacher-alltopics.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement</title>
    <link rel="stylesheet" href="create-announcement.css">
</head>

<body>
    <div class="upload-container">
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" class="input-field" placeholder="✏️ Title of Announcement" required />
            <textarea class="textarea-field" name="description" placeholder="✏️ Description" required></textarea>

            <div class="button-group">
                <!-- File Upload -->
                <input type="file" name="attachment" id="file-upload" class="file-input" />
                <label for="file-upload" class="btn attach-btn">+ Attach File</label>

                <!-- Upload & Due Date -->
                <button class="btn upload-btn" type="button">Upload to Students</button>
            </div>

            <div class="action-buttons">
                <button type="submit" class="btn upload-main-btn">Upload</button>
            </div>
        </form>

        <div class="action-buttons">
            <!--close button aywa gumana mf wag mo balek sa form unless u can ayos ayos -->
            <button class="btn close-btn" onclick="window.location.href='teacher-act.php'"
                style="cursor: pointer;">Close</button>
        </div>
    </div>
</body>

</html>