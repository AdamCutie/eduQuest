<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_POST['user_id'];

  if ($user_id) {
    // Prepare the SQL statement to delete the admin
    $stmt = $conn->prepare("DELETE FROM tbl_users WHERE user_id = ? AND user_type = 'admin'");
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
  echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
