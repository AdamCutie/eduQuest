<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        die("Both email and password are required.");
    }

    $sql = "SELECT user_id, password, user_type FROM tbl_users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();


    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $user_type);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['user_type'] = $user_type;

            if ($user_type == 'student') {
                header("Location: student-home.php");
            } elseif ($user_type == 'teacher') {
                header("Location: teacher-home.php");
            } elseif ($user_type == 'admin') {
                header("Location: admin-home.html");
            } else {
                header("Location: (troll shit lagay kayo ng kung ano dafuq this shi will never happen unless...)"); // Default fallback if user type is unknown
            }
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with that email.";
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}

$conn->close();
?>