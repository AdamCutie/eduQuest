<?php
session_start();
include('db_connection.php'); // Use require_once for critical files

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: eduquest.php?error=Please+log+in+first+>:l");
  exit();
}
// Update session if class_id is passed in URL
if (isset($_GET['class_id'])) {
  $_SESSION['class_id'] = (int) $_GET['class_id'];
}

if (isset($_GET['user_id'])) {
  $_SESSION['user_id'] = (int) $_GET['user_id'];
}

// Require class_id in session
if (!isset($_SESSION['class_id'])) {
  die("No class selected.");
}

if (!isset($_SESSION['user_id'])) {
  die("No user logged in.");
}

$user_id = $_SESSION['user_id'];
$class_id = $_SESSION['class_id'];

// Fetch class info
$stmt = $conn->prepare("SELECT class_name, section, class_code FROM tbl_classes WHERE class_id = ?");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result = $stmt->get_result();
$class = $result->fetch_assoc();

$className = $class['class_name'] ?? 'Subject Name';
$section = $class['section'] ?? 'Section Name';
$classCode = $class['class_code'] ?? 'Class Code';

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="EduQuest - Classroom management and learning platform for teachers">
  <title>EduQuest</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    :root {
      --primary-color: #5E4CC2;
      --secondary-color: #2F285B;
      --accent-color: #00B894;
      --text-color: #333;
      --light-bg: #f4f4f4;
      --blue-accent: #3c98ff;
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

    .join-btn {
      background-color: var(--accent-color);
      color: white;
      font-size: 16px;
      padding: 10px 18px;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-left: auto;
      text-align: center;
      text-decoration: none;
      display: inline-block;
    }

    .join-btn:hover {
      background-color: #019174;
    }

    .logo-btn,
    .acc-btn {
      border: none;
      background: none;
      cursor: pointer;
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

    /* Main content area */
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

    /* Subject header */
    .subject-header {
      background: var(--blue-accent);
      color: white;
      padding: 1rem;
      border-radius: 12px 12px 0 0;
      margin: -1rem -1rem 1rem -1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .subject-header h2 {
      margin: 0;
      font-size: 1.5rem;
    }

    .subject-header p {
      margin: 0.25rem 0 0;
      font-size: 1rem;
    }

    /* Content layout */
    .content-container {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .content-wrapper {
      display: flex;
      gap: 1rem;
    }

    .left-panel {
      width: 220px;
      flex-shrink: 0;
    }

    .main-content {
      flex-grow: 1;
    }

    /* Filters */
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

    /* Task list */
    .task-list {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    .task-item {
      background: var(--card-bg);
      padding: 1rem;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      border: 1px solid #ddd;
      transition: transform 0.2s ease;
    }

    .task-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .task-right {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    /* Create button */
    .create-btn {
      background-color: #3182ce;
      color: white;
      border: none;
      padding: 8px 18px;
      font-size: 14px;
      font-weight: bold;
      border-radius: 9999px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 6px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: background 0.2s ease-in-out;
      width: 100%;
      justify-content: center;
    }

    .create-btn:hover {
      background-color: #2563eb;
    }

    /* Dropdown */
    .dropdown {
      position: relative;
      margin-bottom: 1rem;
    }

    .dropdown-content {
      display: none;
      flex-direction: column;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
      padding: 10px 14px;
      margin-top: 5px;
      position: absolute;
      left: 0;
      z-index: 100;
      min-width: 160px;
    }

    .dropdown.show .dropdown-content {
      display: flex;
    }

    .dropdown-content .filter-btn {
      background: #e2e8f0;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 13px;
      text-align: left;
      cursor: pointer;
      margin-bottom: 6px;
    }

    .dropdown-content .filter-btn:hover {
      background-color: #cbd5e1;
    }

    /* Deadline panel */
    .deadline-panel {
      background: white;
      border-radius: 8px;
      padding: 1rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      text-align: center;
    }

    .deadline-panel i {
      font-size: 18px;
      margin-bottom: 4px;
      color: var(--blue-accent);
    }

    .deadline-panel span {
      font-size: 10px;
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
    }

    .deadline-panel select {
      font-size: 12px;
      padding: 5px 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      width: 100%;
    }

    /* Customize button */
    .customize-btn {
      display: flex;
      align-items: center;
      gap: 6px;
      background-color: white;
      color: black;
      font-size: 0.875rem;
      font-weight: 600;
      border-radius: 9999px;
      padding: 0.5rem 1rem;
      cursor: pointer;
      transition: background-color 0.2s ease-in-out;
    }

    .customize-btn:hover {
      background-color: #e0e0e0;
    }

    .edit-input {
      padding: 5px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: inherit;
      font-family: inherit;
      width: 80%;
    }

    /* Responsive adjustments */
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
        margin-top: 80px;
      }

      aside.show~.subject-box {
        margin-left: 220px;
      }

      .content-wrapper {
        flex-direction: column;
      }

      .left-panel {
        width: 100%;
      }
    }

    /* Modal Styles */
    .modal {
      display: none;
      /* Hidden by default */
      position: fixed;
      /* Stay in place */
      z-index: 200;
      /* Sit on top */
      left: 0;
      top: 0;
      width: 100%;
      /* Full width */
      height: 100%;
      /* Full height */
      background-color: rgba(0, 0, 0, 0.5);
      /* Black background with opacity */
    }

    .modal-content {
      background-color: #6f62d2;
      margin: auto;
      /* Center horizontally */
      padding: 20px;
      border-radius: 12px;
      width: 80%;
      max-width: 600px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      position: relative;
      animation: modalopen 0.3s;
      top: 50%;
      /* Position from the top */
      transform: translateY(-50%);
      /* Center vertically */
      display: flex;
      /* Use flexbox */
      flex-direction: column;
      /* Stack children vertically */
      align-items: center;
      /* Center children horizontally */
    }

    @keyframes modalopen {
      from {
        opacity: 0;
        transform: translateY(-50px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .close-modal {
      position: absolute;
      right: 20px;
      top: 10px;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
      color: #aaa;
    }

    .close-modal:hover {
      color: #333;
    }

    /* Quiz Container Styles */
    .quiz-container {
      background-color: #4b3b99;
      padding: 30px;
      border-radius: 10px;
      color: white;
      width: 400px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .quiz-title-input {
      width: 100%;
      padding: 10px;
      font-size: 18px;
      font-weight: bold;
      text-align: center;
      border: none;
      border-radius: 5px;
      background-color: #2e2373;
      color: white;
      outline: none;
    }

    .quiz-title-input::placeholder {
      color: #ccc;
    }


    .question-types {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .question-types button {
      background-color: #2e2373;
      color: white;
      padding: 8px 12px;
      margin: 5px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      flex: 1 1 45%;
    }

    .dropdowns {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    select {
      padding: 8px;
      border-radius: 5px;
      border: none;
      background-color: #2e2373;
      color: white;
      width: 48%;
    }

    .buttons {
      display: flex;
      justify-content: space-between;
    }

    .buttons button {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    .buttons .close-btn {
      background-color: #ff4b5c;
      color: white;
    }

    .buttons .next-btn {
      background-color: #3333cc;
      color: white;
    }

    .question-types button:hover,
    select:hover {
      background-color: #6a5acd;
      /* A lighter purple on hover */
      transform: scale(1.05);
      /* Slight zoom effect */
      transition: 0.2s ease;
    }

    .close-btn:hover {
      transform: scale(1.05);
      /* Slight zoom effect */
      transition: 0.2s ease;
    }

    .next-btn:hover {
      transform: scale(1.05);
      /* Slight zoom effect */
      transition: 0.2s ease;
    }

    #due-date {
      width: 100%;
      padding: 8px;
      margin-bottom: 20px;
      border: none;
      border-radius: 5px;
      background-color: #2e2373;
      color: white;
      font-size: 16px;
    }


    .upload-container {
      background-color: #4b3b99;
      padding: 30px;
      border-radius: 10px;
      color: white;
      width: 400px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .input-field,
    .textarea-field,
    .score-field {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: none;
      border-radius: 5px;
      background-color: #2e2373;
      color: white;
      font-size: 14px;
      box-sizing: border-box;
    }

    .textarea-field {
      height: 80px;
      resize: none;
    }

    .file-input {
      opacity: 0;
      position: absolute;
      z-index: -1;
      width: 0;
      height: 0;
    }

    label.attach-btn {
      background-color: #2e2373;
      color: white;
      padding: 8px 12px;
      border-radius: 5px;
      cursor: pointer;
      display: inline-block;
      text-align: center;
      width: 100%;
      /* Ensure label occupies full width like other buttons */
      box-sizing: border-box;
    }

    input[type="datetime-local"].due-date-picker {
      background-color: #2e2373;
      color: white;
      padding: 8px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 100%;
      font-weight: bold;
    }

    .question-types button.selected {
      background-color: #6a5acd;
      /* Highlight color */
      color: white;
      /* Text color when selected */
      border: 2px solid #fff;
      /* Optional: border to enhance visibility */
    }

    .button-group {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 20px;
    }

    .button-group .btn {
      background-color: #2e2373;
      color: white;
      padding: 8px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      flex: 1 1 45%;
    }

    .score-field {
      width: 45%;
    }

    .action-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }

    .action-buttons .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    .close-btn {
      background-color: #ff4b5c;
      color: white;
    }

    .upload-main-btn {
      background-color: #3333cc;
      color: white;
    }

    /* Responsive adjustments for modal */
    @media (max-width: 768px) {
      .modal-content {
        width: 90%;
        margin: 10% auto;
      }

      .dropdowns {
        flex-direction: column;
      }

      .question-types button {
        flex: 1 0 calc(50% - 8px);
      }
    }

    .class-code {
      background: white;
      border-radius: 8px;
      padding: 1rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      text-align: center;
    }

    .class-code span {
      font-size: 10px;
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
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
    <a href="create_class.php" class="join-btn">Create Class</a>
    <button class="acc-btn">
      <a href="account.html"><img src="account.png" alt="User profile" width="40" height="40" style="height: auto;"></a>
    </button>
  </header>

  <aside id="sidebar" class="hover-active">
    <nav>
      <a href="teacher-home.php">
        <span class="icon">🏠</span><span class="label">Home</span>
      </a>
      <a href="teacher-task.html">
        <span class="icon">📝</span><span class="label">Tasks</span>
      </a>
      <a href="teacher-leadboard.html">
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
      <button class="customize-btn" id="customize-btn">
        <i class="fas fa-pen" id="customize-icon"></i>
        <span>CUSTOMIZE</span>
      </button>
    </div>

    <div class="filters">
      <button class="filter-btn" onclick="window.location.href='teacher-alltopics.php'">All Topics</button>
      <button class="filter-btn" onclick="window.location.href='teacher-act.php'">Activities</button>
      <button class="filter-btn" onclick="window.location.href='teacher-People.php'">People</button>
    </div>

    <div class="content-container">
      <div class="content-wrapper">
        <div class="left-panel">
          <div class="dropdown">
            <button class="create-btn" id="create-btn" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-plus"></i> CREATE ▾
            </button>
            <div id="create-options" class="dropdown-content" role="menu" aria-labelledby="create-btn">
              <button class="filter-btn" onclick="openAnnouncementModal()">Announcements</button>
              <button class="filter-btn" id="ass-modal-btn">Assignment</button>
              <button class="filter-btn" id="quiz-modal-btn">Quizzes</button>
              <button class="filter-btn" id="act-modal-btn" onclick="openActivityModal()">Activities</button>
              <button class="filter-btn" onclick="openLessonModal()">Lessons</button>
              <button class="filter-btn" id="project-modal-btn" onclick="openUploadModal()">Projects</button>
            </div>
          </div>
          <div class="deadline-panel">
            <i class="fas fa-bell" aria-hidden="true"></i>
            <span>DEADLINES</span>
            <select aria-label="View deadlines">
              <option value="all">VIEW ALL</option>
              <option value="today">Today</option>
              <option value="week">This Week</option>
              <option value="month">This Month</option>
            </select>
          </div>
          <div class="class-code">
            <span><strong>CLASS CODE : </strong></span>
            <H1><?= htmlspecialchars($class['class_code']) ?></H1>
          </div>
        </div>
        <div class="main-content">
          <div class="task-list">
            <?php
            $sql = "SELECT ttc.user_id, ttc.name, tt.topic_id, tt.content_id, tt.class_id, tt.user_id, tt.type, tt.title, tt.posted_at 
            FROM tbl_topics tt JOIN tbl_teachers ttc ON tt.user_id = ttc.user_id
            WHERE tt.class_id = ? 
            ORDER BY posted_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $class_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                $type = strtolower($row['type']); // Normalize to lowercase
                $targetPage = in_array($type, ['announcement', 'lesson'])
                  ? 'teacher-anle_dashboard.php'
                  : 'teacher-asac_dashboard.php';
                ?>
                <a href="<?= $targetPage ?>?class_id=<?= $row['class_id'] ?>&content_id=<?= $row['content_id'] ?>&type=<?= $row['type'] ?>"
                  aria-label="View <?= htmlspecialchars($row['title']) ?> details">
                  <div class="task-item" role="listitem">
                    <div><strong><?= ucfirst($row['name']) ?> posted a new <?= ucfirst($row['type']) ?>:
                      </strong><?= htmlspecialchars($row['title']) ?></div>
                    <h4>Posted at: <?= htmlspecialchars($row['posted_at']) ?></h4>
                    <div class="task-right">
                      <a href="discussion.html" aria-label="Discuss lessons">
                        <img src="comment.png" alt="" width="20" height="20" aria-hidden="true" />
                      </a>
                    </div>
                  </div>
                </a>
              <?php endwhile; ?>
            <?php else: ?>
              <div class="task-item" role="listitem">
                <strong>No topics found for this class.</strong>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    </div>
  </section>
  <!-- Quiz Creation Modal -->
  <div id="quizModal" class="modal">
    <div class="modal-content">
      <div class="quiz-container">
        <span class="close-modal">&times;</span>
        <input type="text" class="quiz-title-input" placeholder="Enter Quiz Title..." />

        <div class="question-types">
          <button>Multiple Choice</button>
          <button>True or False</button>
          <button>Enumeration</button>
          <button>Identification</button>
          <button>Checkbox</button>
        </div>

        <div class="dropdowns">
          <select>
            <option selected disabled>Number of Items</option>
            <option>1-10</option>
            <option>1-20</option>
            <option>1-30</option>
            <option>1-40</option>
            <option>1-50</option>
          </select>

          <select>
            <option selected disabled>Points</option>
            <option>1 point per item</option>
            <option>2 point per item</option>
            <option>3 point per item</option>
            <option>4 point per item</option>
            <option>5 point per item</option>
          </select>
        </div>

        <label for="due-date">Due Date:</label>
        <input type="datetime-local" id="due-date" name="due-date" />

        <div class="buttons">
          <button class="close-btn">CLOSE</button>
          <button class="next-btn">NEXT</button>
        </div>
      </div>
    </div>
  </div>

  <div id="uploadModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeUploadModal()">&times;</span>
      <div class="upload-container">
        <form action="create-project.php" method="POST" enctype="multipart/form-data">
          <input type="text" name="title" class="input-field" placeholder="✏️ Title of Project" />
          <textarea class="textarea-field" name="description" placeholder="✏️ Description"></textarea>

          <div class="button-group">
            <!-- File Upload -->
            <input type="file" name="attachment" id="assfile-upload" class="file-input" />
            <label for="assfile-upload" class="btn attach-btn">+ Attach File</label>

            <!-- Score -->
            <input type="number" name="score" class="input-field score-field" placeholder="Score" />

            <!-- Upload & Due Date -->
            <button class="btn upload-btn" type="button">Upload to Students</button>

            <!-- Date & Time Picker -->
            <input type="datetime-local" name="deadline" class="due-date-picker" />
          </div>

          <div class="action-buttons">
            <button class="btn upload-main-btn">Upload</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="activityModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeActivityModal()">&times;</span>
      <div class="upload-container">
        <form action="create-activity.php" method="POST" enctype="multipart/form-data">
          <input type="text" name="title" class="input-field" placeholder="✏️ Title of Activity" />
          <textarea class="textarea-field" name="description" placeholder="✏️ Description"></textarea>

          <div class="button-group">
            <!-- File Upload -->
            <input type="file" name="attachment" id="actfile-upload" class="file-input" />
            <label for="actfile-upload" class="btn attach-btn">+ Attach File</label>

            <!-- Score -->
            <input type="number" name="score" class="input-field score-field" placeholder="Score" />

            <!-- Upload & Due Date -->
            <button class="btn upload-btn" type="button">Upload to Students</button>

            <!-- Date & Time Picker -->
            <input type="datetime-local" name="deadline" class="due-date-picker" />
          </div>
          <div class="action-buttons">
            <button class="btn upload-main-btn">Upload</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="announcementModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeAnnouncementModal()">&times;</span>
      <div class="upload-container">
        <form action="create-announcement.php" method="POST" enctype="multipart/form-data">
          <input type="text" name="title" class="input-field" placeholder="✏️ Title of Announcement" required />
          <textarea class="textarea-field" name="description" placeholder="✏️ Description" required></textarea>
          <div class="button-group">
            <input type="file" name="attachment" id="annfile-upload" class="file-input" />
            <label for="annfile-upload" class="btn attach-btn">+ Attach File</label>
            <button class="btn upload-btn" type="button">Upload to Students</button>
          </div>
          <div class="action-buttons">
            <button type="submit" class="btn upload-main-btn">Upload</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="lessonModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeLessonModal()">&times;</span>
      <div class="upload-container">
        <form action="create-lesson.php" method="POST" enctype="multipart/form-data">
          <input type="text" name="title" class="input-field" placeholder="✏️ Title of Lesson" required />
          <textarea class="textarea-field" name="description" placeholder="✏️ Description" required></textarea>

          <div class="button-group">
            <!-- File Upload -->
            <input type="file" name="attachment" id="lesfile-upload" class="file-input" />
            <label for="lesfile-upload" class="btn attach-btn">+ Attach File</label>

            <!-- Upload & Due Date -->
            <button class="btn upload-btn" type="button">Upload to Students</button>
          </div>

          <div class="action-buttons">
            <button type="submit" class="btn upload-main-btn">Upload</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="uploadModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeUploadModal()">&times;</span>
      <div class="upload-container">
        <input type="text" class="input-field" placeholder="✏️ Title of Project" />
        <textarea class="textarea-field" placeholder="✏️ Description"></textarea>

        <div class="button-group">
          <!-- File Upload -->
          <input type="file" id="file-upload" class="file-input" />
          <label for="file-upload" class="btn attach-btn">+ Attach File</label>

          <!-- Score -->
          <input type="number" class="input-field score-field" placeholder="Score" />

          <!-- Upload & Due Date -->
          <button class="btn upload-btn" type="button">Upload to Students</button>

          <!-- Date & Time Picker -->
          <input type="datetime-local" class="due-date-picker" />
        </div>

        <div class="action-buttons">
          <button class="btn close-btn" onclick="closeUploadModal()">Close</button>
          <button class="btn upload-main-btn">Upload</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Sidebar toggle
      const sidebar = document.getElementById('sidebar');
      const menuBtn = document.querySelector('.menu-btn');

      function toggleSidebar() {
        const isShown = sidebar.classList.toggle('show');
        menuBtn.setAttribute('aria-expanded', isShown);
      }

      menuBtn.addEventListener('click', toggleSidebar);

      // Customize button functionality
      const customizeBtn = document.getElementById('customize-btn');
      const customizeIcon = document.getElementById('customize-icon');
      const subjectTitle = document.getElementById('subject-title');
      const subjectSection = document.getElementById('subject-section');

      customizeBtn.addEventListener('click', function () {
        if (customizeIcon.classList.contains('fa-pen')) {
          // Switch to edit mode
          subjectTitle.innerHTML = `<input type="text" id="edit-subject-title" value="${subjectTitle.innerText}" class="edit-input">`;
          subjectSection.innerHTML = `<input type="text" id="edit-subject-section" value="${subjectSection.innerText}" class="edit-input">`;
          customizeIcon.classList.remove('fa-pen');
          customizeIcon.classList.add('fa-save');
        } else {
          // Save changes
          const newTitle = document.getElementById('edit-subject-title').value.trim();
          const newSection = document.getElementById('edit-subject-section').value.trim();
          subjectTitle.textContent = newTitle || 'Subject Name';
          subjectSection.textContent = newSection || 'Section';
          customizeIcon.classList.remove('fa-save');
          customizeIcon.classList.add('fa-pen');
        }
      });

      // Dropdown handling
      const dropdowns = document.querySelectorAll('.dropdown');

      dropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('button[aria-haspopup]');
        if (button) {
          button.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isOpen);
            dropdown.classList.toggle('show', !isOpen);
          });
        }
      });

      // Close dropdowns when clicking outside
      document.addEventListener('click', function () {
        dropdowns.forEach(dropdown => {
          dropdown.classList.remove('show');
          const button = dropdown.querySelector('button[aria-haspopup]');
          if (button) button.setAttribute('aria-expanded', 'false');
        });
      });

      // Quiz Modal Functionality
      const modal = document.getElementById('quizModal');
      const quizBtn = document.querySelector('#create-options .filter-btn:nth-child(3)'); // Target the Quizzes button
      const closeModalBtn = document.querySelector('.close-modal');
      const closeBtn = document.querySelector('.quiz-container .close-btn');

      // Open modal when Quizzes is clicked in dropdown
      if (quizBtn) {
        quizBtn.addEventListener('click', function (e) {
          e.preventDefault();
          modal.style.display = 'block';
          document.body.style.overflow = 'hidden';

          // Close any open dropdowns
          dropdowns.forEach(dropdown => {
            dropdown.classList.remove('show');
            const button = dropdown.querySelector('button[aria-haspopup]');
            if (button) button.setAttribute('aria-expanded', 'false');
          });
        });
      }

      // Close modal handlers
      function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
      }

      if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
      if (closeBtn) closeBtn.addEventListener('click', closeModal);

      // Close when clicking outside modal
      window.addEventListener('click', function (event) {
        if (event.target === modal) {
          closeModal();
        }
      });

      // Close with ESC key
      document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.style.display === 'block') {
          closeModal();
        }
      });
    });

    document.addEventListener('DOMContentLoaded', function () {
      const nextBtn = document.querySelector('.next-btn');

      nextBtn.addEventListener('click', function () {
        const title = document.querySelector('.quiz-title-input').value.trim();
        const quizTypeBtns = document.querySelectorAll('.question-types button');
        const typeSelected = Array.from(quizTypeBtns).find(btn => btn.classList.contains('selected'));
        const itemDropdown = document.querySelectorAll('.dropdowns select')[0];
        const pointDropdown = document.querySelectorAll('.dropdowns select')[1];
        const dueDate = document.getElementById('due-date').value;

        if (!title || !typeSelected || itemDropdown.selectedIndex === 0 || pointDropdown.selectedIndex === 0 || !dueDate) {
          alert('Please fill in all fields before proceeding.');
          return;
        }

        const quizData = {
          title,
          type: typeSelected.textContent.trim(),
          items: parseInt(itemDropdown.value.split('-')[1]),
          pointsPerItem: parseInt(pointDropdown.value),
          dueDate
        };

        // Option 1: Store in localStorage
        localStorage.setItem('quizSetup', JSON.stringify(quizData));

        // Option 2: Or pass via query string (URL-safe)
        // const params = new URLSearchParams(quizData).toString();
        // window.location.href = 'edit-quiz.html?' + params;

        // Redirect to edit page
        window.location.href = 'editquiz.html';
      });

      // Add selection effect for quiz type buttons
      document.querySelectorAll('.question-types button').forEach(btn => {
        btn.addEventListener('click', () => {
          document.querySelectorAll('.question-types button').forEach(b => b.classList.remove('selected'));
          btn.classList.add('selected');
        });
      });
    });

    // Functions to open and close the Assignment Upload Modal
    function openUploadModal() {
      document.getElementById('uploadModal').style.display = 'block';
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeUploadModal() {
      document.getElementById('uploadModal').style.display = 'none';
      document.body.style.overflow = 'auto'; // Restore background scrolling
    }

    // Open modal when Assignment button is clicked
    const assModalBtn = document.getElementById('ass-modal-btn');
    if (assModalBtn) {
      assModalBtn.addEventListener('click', function (e) {
        e.preventDefault(); // prevent default button behavior if any
        openUploadModal();

        // Also close the dropdown after clicking
        const dropdown = document.getElementById('create-options');
        dropdown.classList.remove('show');
        const createBtn = document.getElementById('create-btn');
        if (createBtn) createBtn.setAttribute('aria-expanded', 'false');
      });
    }

    // Close modal when clicking outside of it
    window.addEventListener('click', function (event) {
      const modal = document.getElementById('uploadModal');
      if (event.target === modal) {
        closeUploadModal();
      }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && document.getElementById('uploadModal').style.display === 'block') {
        closeUploadModal();
      }
    });

    function openActivityModal() {
      document.getElementById("activityModal").style.display = "block";
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeActivityModal() {
      document.getElementById("activityModal").style.display = "none";
      document.body.style.overflow = 'auto'; // Restore background scrolling
    }

    // Close modal when clicking outside of it
    window.addEventListener('click', function (event) {
      const modal = document.getElementById("activityModal");
      if (event.target === modal) {
        closeActivityModal();
      }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && document.getElementById("activityModal").style.display === "block") {
        closeActivityModal();
      }
    });

    function openAnnouncementModal() {
      document.getElementById("announcementModal").style.display = "block";
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeAnnouncementModal() {
      document.getElementById("announcementModal").style.display = "none";
      document.body.style.overflow = 'auto'; // Restore background scrolling
    }

    // Close modal when clicking outside of it
    window.addEventListener('click', function (event) {
      const modal = document.getElementById("announcementModal");
      if (event.target === modal) {
        closeAnnouncementModal();
      }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && document.getElementById("announcementModal").style.display === "block") {
        closeAnnouncementModal();
      }
    });

    function openLessonModal() {
      document.getElementById("lessonModal").style.display = "block";
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeLessonModal() {
      document.getElementById("lessonModal").style.display = "none";
      document.body.style.overflow = 'auto'; // Restore background scrolling
    }

    // Close modal when clicking outside of it
    window.addEventListener('click', function (event) {
      const modal = document.getElementById("lessonModal");
      if (event.target === modal) {
        closeLessonModal();
      }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && document.getElementById("lessonModal").style.display === "block") {
        closeLessonModal();
      }
    });

    function openUploadModal() {
      document.getElementById("uploadModal").style.display = "block";
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeUploadModal() {
      document.getElementById("uploadModal").style.display = "none";
      document.body.style.overflow = 'auto'; // Restore background scrolling
    }

    // Close modal when clicking outside of it
    window.addEventListener('click', function (event) {
      const modal = document.getElementById("uploadModal");
      if (event.target === modal) {
        closeUploadModal();
      }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && document.getElementById("uploadModal").style.display === "block") {
        closeUploadModal();
      }
    });

  </script>

</body>

</html>