<?php
include('db_connection.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];

    // Check if the email already exists in the database
    $checkEmailSQL = "SELECT * FROM tbl_users WHERE Email = '$email'";
    $emailResult = mysqli_query($conn, $checkEmailSQL);

    if (mysqli_num_rows($emailResult) > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists.']);
        exit;
    }

    // Generate a temporary password
    $tempPassword = bin2hex(random_bytes(6));  // Generate a 12-character password

    // Hash the temporary password
    $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO tbl_users (Name, Email, Password, user_type) VALUES (?, ?, ?, ?)");
    $userType = 'admin'; // Admin user type

    // Bind parameters to the SQL query
    $stmt->bind_param("ssss", $fullName, $email, $hashedPassword, $userType);

    // Execute the query
    if ($stmt->execute()) {
        // Get the last inserted user_id
        $user_id = $stmt->insert_id;

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

            // Recipients
            $mail->setFrom('dayvoice993@gmail.com', 'EduQuest'); // Use your Brevo-approved sender
            $mail->addAddress($email, $fullName);  // Add recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Temporary Password for EduQuest';
            $mail->Body    = "Hello $fullName,<br><br>Your temporary password is: <strong>$tempPassword</strong><br>Please login and change your password immediately.";

            // Send the email
            $mail->send();

            // Return success message with the new admin's name and email
            echo json_encode(['success' => true, 'message' => 'Admin added and email sent!', 'name' => $fullName, 'email' => $email, 'id' => $user_id]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to insert admin into tbl_users.']);
    }

    // Close the statement
    $stmt->close();
}
?>
