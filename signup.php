<?php
$host = 'localhost';
$db = 'eduquest';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $usertype = $_POST['user-type'] ?? ''; // should be either 'student' or 'teacher'

    if (empty($name) || empty($email) || empty($password) || empty($usertype)) {
        die("All fields are required.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into tbl_users
    $sql = "INSERT INTO tbl_users (name, email, password, user_type) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $usertype);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id; // Get the inserted user's ID
        $Smulah = 0;
        $Tmulah = 9999999999;

        if (strtolower($usertype) === 'student') {
            // Insert into tbl_students
            $sqlStudent = "INSERT INTO tbl_students (user_id, name, mulah) VALUES (?, ?, ?)";
            $stmtStudent = $conn->prepare($sqlStudent);
            $stmtStudent->bind_param("isi", $user_id, $name, $Smulah);

            if (!$stmtStudent->execute()) {
                die("Error inserting into tbl_students: " . $stmtStudent->error);
            }
            $stmtStudent->close();

        } elseif (strtolower($usertype) === 'teacher') {
            // Insert into tbl_tc (teacher table)
            $sqlTeacher = "INSERT INTO tbl_teachers (user_id, name, mulah) VALUES (?, ?, ?)";
            $stmtTeacher = $conn->prepare($sqlTeacher);
            $stmtTeacher->bind_param("isi", $user_id, $name, $Tmulah);

            if (!$stmtTeacher->execute()) {
                die("Error inserting into tbl_tc: " . $stmtTeacher->error);
            }
            $stmtTeacher->close();
        }

        header("Location: eduquest.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}

$conn->close();
?>