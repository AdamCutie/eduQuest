<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    // Delete from tbl_students first if there's a foreign key constraint
    mysqli_query($conn, "DELETE FROM tbl_students WHERE user_id = $id");

    // Then delete from tbl_users
    $query = "DELETE FROM tbl_users WHERE user_id = $id AND user_type = 'student'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
}
?>