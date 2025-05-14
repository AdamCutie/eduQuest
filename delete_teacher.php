<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Make sure user_id is provided
    $user_id = $_POST['user_id'];

    if ($user_id) {
        // Prepare the SQL statement to delete the teacher
        $stmt = $conn->prepare("DELETE FROM tbl_users WHERE user_id = ? AND user_type = 'teacher'");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'DB error']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
