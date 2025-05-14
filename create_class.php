<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id'])) {
    die("You need to be logged in to create a class.");
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a class code
function generateClassCode($length = 6)
{
    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $code;
}

// Ensure the class code is unique
function generateUniqueClassCode($conn, $length = 6)
{
    do {
        $code = generateClassCode($length);
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_classes WHERE class_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    } while ($count > 0);
    return $code;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cname = $_POST['subject'] ?? '';
    $section = $_POST['section'] ?? '';
    $user_id = $_POST['assignTeacher'] ?? '';
    $desc = $_POST['description'] ?? '';

    if (empty($cname) || empty($section)) {
        die("Subject and Section are required fields.");
    }

    // Generate a unique class code
    $class_code = generateUniqueClassCode($conn);

    // Insert the class
    $sql = "INSERT INTO tbl_classes (class_name, section, description, user_id, class_code) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $cname, $section, $desc, $user_id, $class_code);

    if ($stmt->execute()) {
        // ✅ Get the new class_id AFTER inserting
        $class_id = $conn->insert_id;

        // Enroll the creator as teacher
        $enrollSql = "INSERT INTO tbl_enrollment (user_id, class_id, type, enrolled_at) VALUES (?, ?, 'teacher', NOW())";
        $enrollStmt = $conn->prepare($enrollSql);
        $enrollStmt->bind_param("ii", $user_id, $class_id);

        if (!$enrollStmt->execute()) {
            echo "Enrollment Error: " . $enrollStmt->error;
            exit();
        }

        $enrollStmt->close();
        $stmt->close();
        $conn->close();

        header("Location: teacher-home.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eduquest - Create Class</title>
</head>

<body>
    <h2>Create Class</h2>
    <form method="POST">
        <label for="subject">Subject:</label><br>
        <input type="text" name="subject" id="subject" placeholder="Subject" required><br><br>

        <label for="section">Section:</label><br>
        <input type="text" name="section" id="section" placeholder="Section Name" required><br><br>

        <label for="description">Description:</label><br>
        <textarea name="description" id="description" placeholder="Description (optional)"></textarea><br><br>

        <button type="submit">Create</button>
        <button type="button" onclick="window.location.href='teacher-home.php'">Close</button>
    </form>
</body>

</html>