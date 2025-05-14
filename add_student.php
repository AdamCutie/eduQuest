<?php
include('db_connection.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Include PHPMailer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];

    // Check if the email already exists in the database
    $checkEmailSQL = "SELECT * FROM tbl_users WHERE Email = '$email'";
    $emailResult = mysqli_query($conn, $checkEmailSQL);

    if (mysqli_num_rows($emailResult) > 0) {
        // If email already exists, return an error message
        echo json_encode(['success' => false, 'message' => 'This email is already registered.']);
    } else {
        // Generate a temporary password
        $tempPassword = bin2hex(random_bytes(6));  // Generate a 12-character password

        // Hash the password before inserting into the database
        $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

        // Insert student into the database
        $sql = "INSERT INTO tbl_users (Name, Email, Password, user_type) VALUES ('$fullName', '$email', '$hashedPassword', 'student')";
        if (mysqli_query($conn, $sql)) {
            // Get the last inserted user_id
            $user_id = mysqli_insert_id($conn);

            // Insert into tbl_students
            $sql_student = "INSERT INTO tbl_students (user_id, name) VALUES ('$user_id', '$fullName')";
            if (mysqli_query($conn, $sql_student)) {
                // Send email with the temporary password
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp-relay.brevo.com'; // Brevo (Sendinblue) SMTP
                    $mail->SMTPAuth = true;
                    $mail->Username = '8cf1b7001@smtp-brevo.com'; // Brevo SMTP Login
                    $mail->Password = '1TgOZPY6EtHCbV0p'; // Replace with your actual password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipientss
                    $mail->setFrom('dayvoice993@gmail.com', 'EduQuest');
                    $mail->addAddress($email, $fullName);  // Add recipient

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Your Temporary Password for EduQuest';
                    $mail->Body    = "Hello $fullName,<br><br>Your temporary password is: <strong>$tempPassword</strong><br>Please login and change your password immediately.";

                    $mail->send();
                    echo json_encode(['success' => true, 'message' => 'Student added and email sent!', 'name' => $fullName, 'email' => $email, 'id' => $user_id]);
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to insert student into tbl_students.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add student to tbl_users.']);
        }
    }
}
?>
