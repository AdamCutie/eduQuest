<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_POST['user_id'];
  $name = trim($_POST['fullName']);
  $email = trim($_POST['email']);

  if ($user_id && $name && $email) {
    // Prepare the SQL statement to update admin details
    $stmt = $conn->prepare("UPDATE tbl_users SET Name = ?, Email = ? WHERE user_id = ? AND user_type = 'teacher'");
    $stmt->bind_param("ssi", $name, $email, $user_id);

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
