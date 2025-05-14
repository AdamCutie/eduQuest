<?php
session_start();
require_once('db_connection.php'); // Use require_once for essential includes

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: eduquest.php?error=Please+log+in+first+>:l");
  exit();
}

// Set class_id from URL if provided
if (isset($_GET['class_id'])) {
  $_SESSION['class_id'] = (int) $_GET['class_id'];
}

if (isset($_GET['user_id'])) {
  $_SESSION['user_id'] = (int) $_GET['user_id'];
}

// Validate required session values
if (!isset($_SESSION['class_id']) || !isset($_SESSION['user_id'])) {
  die("Missing class or user session information.");
}

$user_id = $_SESSION['user_id'];
$class_id = $_SESSION['class_id'];

// Fetch class info
$stmt = $conn->prepare("SELECT class_name, section, class_code FROM tbl_classes WHERE class_id = ?");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result = $stmt->get_result();
$class = $result->fetch_assoc();
$stmt->close();

$className = $class['class_name'] ?? 'Subject Name';
$section = $class['section'] ?? 'Section Name';
$classCode = $class['class_code'] ?? 'Class Code';

// --- Fetch Teachers ---
$teachers = [];
$stmt = $conn->prepare("
    SELECT tu.user_id, tu.name 
    FROM tbl_users tu 
    JOIN tbl_enrollment te ON tu.user_id = te.user_id 
    WHERE te.class_id = ? AND te.type = 'teacher'
");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $teachers[] = $row;
}
$stmt->close();

// --- Fetch Students ---
$students = [];
$stmt = $conn->prepare("
    SELECT tu.user_id, tu.name 
    FROM tbl_users tu 
    JOIN tbl_enrollment te ON tu.user_id = te.user_id 
    WHERE te.class_id = ? AND te.type = 'student'
");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $students[] = $row;
}
$stmt->close();

// Process form submissions for adding teachers/students if needed here
// This would handle POST requests from the modals
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="EduQuest - Classroom People Management">
  <title>EduQuest - People</title>
  <style>
    :root {
      --primary-color: #5E4CC2;
      --secondary-color: #2F285B;
      --accent-color: #00B894;
      --blue-accent: #3c98ff;
      --text-color: #333;
      --light-bg: #f4f4f4;
      --card-bg: #f0f0f0;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: #6f62d2;
      color: var(--text-color);
    }

    header {
      background-color: var(--primary-color);
      padding: 0 20px;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .menu-btn {
      background-color: var(--primary-color);
      color: white;
      font-size: 25px;
      border: none;
      cursor: pointer;
      padding: 8px 12px;
      border-radius: 5px;
    }

    .logo-btn,
    .acc-btn {
      border: none;
      background: none;
      cursor: pointer;
    }

    .acc-btn {
      margin-left: auto;
    }

    aside {
      width: 60px;
      background-color: var(--secondary-color);
      color: white;
      padding: 15px 10px;
      height: 100vh;
      box-sizing: border-box;
      position: fixed;
      left: 0;
      overflow: hidden;
      transition: width 0.3s ease;
      z-index: 1;
    }

    aside.hover-active:hover,
    aside.show {
      width: 300px;
    }

    aside nav a {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 12px;
      text-decoration: none;
      color: white;
      font-size: 18px;
      background-color: var(--primary-color);
      border-radius: 8px;
      margin-bottom: 10px;
      transition: background-color 0.3s ease;
    }

    aside nav a:hover {
      background-color: black;
    }

    .icon {
      font-size: 22px;
      min-width: 30px;
      text-align: center;
    }

    .label {
      display: none;
      margin-left: 15px;
      white-space: nowrap;
      text-align: center;
    }

    aside.hover-active:hover .label,
    aside.show .label {
      display: inline;
    }

    .subject-box {
      background: white;
      border-radius: 15px;
      padding: 1rem;
      margin-left: 80px;
      margin-right: 20px;
      margin-top: 20px;
      transition: margin-left 0.3s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    aside.hover-active:hover~.subject-box,
    aside.show~.subject-box {
      margin-left: 320px;
    }

    .subject-header {
      background: var(--blue-accent);
      color: white;
      padding: 1rem;
      border-radius: 12px 12px 0 0;
      margin: -1rem -1rem 1rem -1rem;
    }

    .subject-header h2 {
      margin: 0;
      font-size: 1.5rem;
    }

    .subject-header p {
      margin: 0.25rem 0 0;
      font-size: 1rem;
    }

    .filters {
      margin: 1rem 0;
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    .filter-btn {
      padding: 0.5rem 1rem;
      background-color: white;
      color: black;
      border: 1px solid #ddd;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: all 0.2s ease;
    }

    .filter-btn:hover,
    .filter-btn:focus {
      background-color: #e0e0e0;
      border-color: #bbb;
    }

    .filter-btn:active {
      transform: scale(0.98);
    }

    .task-list {
      margin-top: 1.5rem;
    }

    .task-item {
      background: var(--card-bg);
      margin-bottom: 0.75rem;
      padding: 1rem;
      border-radius: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: transform 0.2s ease;
      border: 1px solid #ddd;
    }

    .task-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .task-item.category-header {
      background-color: var(--primary-color);
      color: white;
      font-weight: bold;
      border: none;
    }

    .profile-info {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .profile-info img {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid white;
    }

    .add-count {
      background-color: var(--accent-color);
      color: white;
      padding: 0.25rem 0.5rem;
      border-radius: 12px;
      font-size: 0.85rem;
      cursor: pointer;
    }

    button:focus-visible {
      outline: 2px solid var(--accent-color);
      outline-offset: 2px;
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1100;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      max-width: 500px;
      width: 90%;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      position: relative;
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
    }

    .modal-header h2 {
      margin: 0;
      color: var(--primary-color);
    }

    .close {
      color: #aaa;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
      transition: color 0.2s;
    }

    .close:hover {
      color: #333;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
      box-sizing: border-box;
    }

    .modal-footer {
      text-align: right;
      margin-top: 20px;
      padding-top: 15px;
      border-top: 1px solid #eee;
    }

    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s;
    }

    .btn-cancel {
      background-color: #f4f4f4;
      color: #333;
      margin-right: 10px;
    }

    .btn-invite {
      background-color: var(--primary-color);
      color: white;
    }

    .btn-cancel:hover {
      background-color: #e0e0e0;
    }

    .btn-invite:hover {
      background-color: #4a3da5;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table tr {
      border-bottom: 1px solid #e0e0e0;
    }

    table tr:last-child {
      border-bottom: none;
    }

    table th,
    table td {
      padding: 12px 8px;
      text-align: left;
    }

    table th {
      font-weight: bold;
      color: #555;
    }

    table td img {
      vertical-align: middle;
      border-radius: 50%;
    }

    @media (max-width: 768px) {
      aside {
        width: 60px;
      }

      aside.show {
        width: 200px;
      }

      .subject-box {
        margin-left: 20px;
        margin-right: 20px;
      }

      aside.show~.subject-box {
        margin-left: 220px;
      }

      .modal-content {
        width: 95%;
        padding: 15px;
      }
    }
  </style>
</head>

<body>
  <header>
    <button class="menu-btn" onclick="toggleSidebar()" aria-label="Toggle sidebar" aria-expanded="false"
      aria-controls="sidebar">&#9776;</button>
    <button class="logo-btn">
      <a href="teacher-home.php"><img src="eduquest.png" alt="EduQuest Logo" width="70" height="70"
          style="height: auto;"></a>
    </button>
    <button class="acc-btn">
      <a href="account.html"><img src="account.png" alt="User profile" width="40" height="40" style="height: auto;"></a>
    </button>
  </header>

  <aside id="sidebar" class="hover-active">
    <nav>
      <a href="teacher-home.php">
        <span class="icon">🏠</span><span class="label">Home</span>
      </a>
      <a href="teacher-task.php">
        <span class="icon">📝</span><span class="label">Tasks</span>
      </a>
      <a href="teacher-leadboard.php">
        <span class="icon">🏆</span><span class="label">Leaderboard</span>
      </a>
    </nav>
  </aside>

  <section class="subject-box" aria-labelledby="subject-title">
    <div class="subject-header">
      <div>
        <h2 id="subject-title"><?= htmlspecialchars($className) ?></h2>
        <p id="subject-section"><?= htmlspecialchars($section) ?></p>
      </div>
    </div>

    <div class="filters">
      <button class="filter-btn" onclick="window.location.href='teacher-alltopics.php'">All Topics</button>
      <button class="filter-btn" onclick="window.location.href='teacher-act.php'">Activities</button>
      <button class="filter-btn" onclick="window.location.href='teacher-People.php'">People</button>
    </div>

    <div class="task-list" role="list">
      <div class="task-item category-header" role="listitem">
        Teachers
        <span class="add-count" onclick="openModal('teacherModal')">👤➕</span>
      </div>

      <table class="task-item">
        <tr>
          <th></th>
          <th>Name</th>
        </tr>
        <?php if (!empty($teachers)): ?>
          <?php foreach ($teachers as $teacher): ?>
            <tr>
              <td><img src="account.png" alt="Teacher profile" width="40" height="40" /></td>
              <td><?= htmlspecialchars($teacher['name']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="2">No teachers assigned.</td>
          </tr>
        <?php endif; ?>
      </table>
    </div>

    <div class="task-list" role="list">
      <div class="task-item category-header" role="listitem">
        Students
        <span class="add-count" onclick="openModal('studentModal')">👤➕</span>
      </div>

      <table class="task-item">
        <tr>
          <th></th>
          <th>Name</th>
        </tr>
        <?php if (!empty($students)): ?>
          <?php foreach ($students as $student): ?>
            <tr>
              <td><img src="account.png" alt="Student profile" width="40" height="40" /></td>
              <td><?= htmlspecialchars($student['name']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="2">No students enrolled.</td>
          </tr>
        <?php endif; ?>
      </table>
    </div>
  </section>

  <!-- Modal for adding a teacher -->
  <div id="teacherModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Add Teacher</h2>
        <span class="close" onclick="closeModal('teacherModal')">&times;</span>
      </div>
      <form action="add_teacher.php" method="post">
        <input type="hidden" name="class_id" value="<?= $class_id ?>">

        <div class="form-group">
          <label for="teacherEmail">Email address</label>
          <input type="email" id="teacherEmail" name="email" placeholder="Enter teacher's email" required>
        </div>

        <div class="form-group">
          <p>Class code: <strong><?= htmlspecialchars($classCode) ?></strong></p>
          <p>Teachers can also join using this class code</p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-cancel" onclick="closeModal('teacherModal')">Cancel</button>
          <button type="submit" class="btn btn-invite">Invite</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal for adding a student -->
  <div id="studentModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Add Student</h2>
        <span class="close" onclick="closeModal('studentModal')">&times;</span>
      </div>
      <form action="add_student.php" method="post">
        <input type="hidden" name="class_id" value="<?= $class_id ?>">

        <div class="form-group">
          <label for="studentEmail">Email address</label>
          <input type="email" id="studentEmail" name="email" placeholder="Enter student's email" required>
        </div>

        <div class="form-group">
          <p>Class code: <strong><?= htmlspecialchars($classCode) ?></strong></p>
          <p>Students can also join using this class code</p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-cancel" onclick="closeModal('studentModal')">Cancel</button>
          <button type="submit" class="btn btn-invite">Invite</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const btn = document.querySelector('.menu-btn');
      const isShown = sidebar.classList.toggle('show');
      btn.setAttribute('aria-expanded', isShown);
    }

    // Modal functions
    function openModal(modalId) {
      document.getElementById(modalId).style.display = 'flex';
      document.body.style.overflow = 'hidden'; // Prevent scrolling behind modal
    }

    function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
      document.body.style.overflow = 'auto'; // Re-enable scrolling
    }

    // Close modals when clicking outside
    window.addEventListener('click', function (event) {
      const teacherModal = document.getElementById('teacherModal');
      const studentModal = document.getElementById('studentModal');

      if (event.target === teacherModal) {
        closeModal('teacherModal');
      }

      if (event.target === studentModal) {
        closeModal('studentModal');
      }
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (event) {
      const sidebar = document.getElementById('sidebar');
      const menuBtn = document.querySelector('.menu-btn');

      if (window.innerWidth <= 768 &&
        !sidebar.contains(event.target) &&
        !menuBtn.contains(event.target)) {
        sidebar.classList.remove('show');
        menuBtn.setAttribute('aria-expanded', 'false');
      }
    });
  </script>
</body>

</html>