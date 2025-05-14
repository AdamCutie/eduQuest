<?php
session_start();
include('db_connection.php'); // Use require_once for critical files

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: eduquest.php?error=Please+log+in+first+>:l");
    exit();
}

if (isset($_GET['user_id'])) {
    $_SESSION['user_id'] = (int) $_GET['user_id'];
}


if (!isset($_SESSION['user_id'])) {
    die("No user logged in.");
}

$user_id = $_SESSION['user_id'];

// Fetch class info
$stmt = $conn->prepare("SELECT * FROM tbl_classes");
$stmt->execute();
$result = $stmt->get_result();
$class = $result->fetch_assoc();

$className = $class['class_name'] ?? 'Subject Name';
$section = $class['section'] ?? 'Section Name';
$classCode = $class['class_code'] ?? 'Class Code';

$stmt->close();

// fetch teachers
$stmt = $conn->prepare("SELECT * FROM tbl_teachers");
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();

$teacherName = $teacher['name'] ?? 'Teacher Name';
$teacherId = $teacher['user_id'] ?? 'Teacer Id';

$stmt->close();
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <title>EduQuest/Classroom</title>
    <style>
        header {
            background-color: #5E4CC2;
            padding: 0px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Fredoka', sans-serif;
            background: radial-gradient(100% 259.2% at 72.65% 0%, #FFF8A7 16%, #FFA973 34%, #A4E8ED 72%);
        }

        .panel {
            background-color: #5E4CC2;
            height: 87vh;
            margin: 10px 30px;
            border-radius: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .inner-panel {
            width: 95%;
            height: 80vh;
            background-color: #2F285B;
            border-radius: 20px;
            color: white;
            padding: 30px;
            box-sizing: border-box;
            overflow-y: auto;
        }

        .inner-panel h2 {
            margin-top: 0;
            font-size: 36px;
            color: white;
            text-shadow:
                -1px -1px 0 #000,
                1px -1px 0 #000,
                -1px 1px 0 #000,
                1px 1px 0 #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            table-layout: auto;
        }

        th,
        td {
            padding: 15px;
            border-bottom: 1px solid #444;
            word-wrap: break-word;
        }

        th {
            background-color: #3b3461;
            color: #fff;
        }

        td {
            color: #eee;
        }

        .action-btn {
            padding: 6px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .edit-btn {
            background-color: #00b894;
            color: white;
        }

        .delete-btn {
            background-color: #d63031;
            color: white;
        }

        .action-btn:hover {
            opacity: 0.85;
        }

        .back-btn a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #2F285B;
            color: white;
            text-decoration: none;
            font-size: 20px;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            transition: background-color 0.3s ease;
        }

        .back-btn a:hover {
            background-color: #1d1a3d;
        }

        .add-class-btn {
            background-color: #6c5ce7;
            color: white;
            font-size: 16px;
            padding: 10px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-class-btn:hover {
            background-color: #4b42a5;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
        }

        .modal-content {
            background-color: #2F285B;
            margin: 10% auto;
            padding: 30px;
            border-radius: 15px;
            width: 60%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: white;
            position: relative;
        }

        .close-modal {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
        }

        .close-modal:hover {
            color: white;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #524b8c;
            background-color: #3b3461;
            color: white;
            font-family: 'Fredoka', sans-serif;
        }

        .form-group select {
            height: 42px;
        }

        .modal-footer {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .cancel-btn {
            background-color: #7c7c7c;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            cursor: pointer;
            font-weight: bold;
        }

        .submit-btn {
            background-color: #00b894;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            cursor: pointer;
            font-weight: bold;
        }

        .submit-btn:hover {
            background-color: #00d1a7;
        }

        .cancel-btn:hover {
            background-color: #999;
        }
    </style>
</head>

<body>
    <header>
        <div class="back-btn">
            <a href="admin-home.html" title="Back to Home">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <div>
            <a href="admin-home.html">
                <img src="eduquest.png" alt="Eduquest Logo" style="height: 60px; width: auto;">
            </a>
        </div>
    </header>

    <div class="panel">
        <div class="inner-panel">
            <h2>CLASSES</h2>
            <div style="margin-bottom: 20px;">
                <button class="action-btn add-class-btn" onclick="openModal()">
                    <i class="fas fa-plus"></i> Add Class for Teacher
                </button>
            </div>
            <table>
                <tr>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Description</th>
                    <th>Class ode</th>
                    <th class="actions-column">Actions</th>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($class['class_name']) ?></td>
                    <td><?= htmlspecialchars($class['section']) ?></td>
                    <td><?= htmlspecialchars($class['description']) ?></td>
                    <td><?= htmlspecialchars($class['class_code']) ?></td>
                    <td><button class="action-btn edit-btn">Edit</button><button
                            class="action-btn delete-btn">Delete</button></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Add Class Modal -->
    <div id="addClassModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h2>Add New Class</h2>
            <form id="addClassForm">
                <div class="form-group">
                    <label for="className">Class Name:</label>
                    <input type="text" id="className" name="className" placeholder="Enter class name" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" placeholder="Enter subject" required>
                </div>
                <div class="form-group">
                    <label for="assignTeacher">Assign Teacher:</label>
                    <select id="assignTeacher" name="assignTeacher" required>
                        <option value="" disabled selected>Select a teacher</option>
                        <option><?php
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($teacher = mysqli_fetch_assoc($result)) {
                                echo '<option value="' . htmlspecialchars($teacher['user_id']) . '">' .
                                    htmlspecialchars($teacher['name']) .
                                    '</option>';
                            }
                        } else {
                            echo '<option value="" disabled>No teachers available</option>';
                        }
                        ?>
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" placeholder="Enter a description (Optional)">
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="submit-btn">Create Class</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById("addClassModal");

        function openModal() {
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }

        // Close modal when clicking outside of it
        window.onclick = function (event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        // Form submission
        document.getElementById("addClassForm").addEventListener("submit", function (event) {
            event.preventDefault();

            // Here you would typically send the data to your backend
            const className = document.getElementById("className").value;
            const subject = document.getElementById("subject").value;
            const teacher = document.getElementById("assignTeacher").value;
            const classCode = document.getElementById("description").value;

            // For demonstration purposes, just log the data and close the modal
            console.log("Class created:", {
                className,
                subject,
                teacher,
                description,
            });

            // Success message could be added here
            alert("Class successfully created!");

            // Reset form and close modal
            this.reset();
            closeModal();
        });
    </script>
</body>

</html>