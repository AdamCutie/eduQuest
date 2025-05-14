<?php
session_start();
include('db_connection.php');


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: eduquest.php?error=Please+log+in+first+>:l");
  exit();
}
// Update session if class_id,contnteid,userid,typeshi is passed in URL
if (isset($_GET['class_id'])) {
  $_SESSION['class_id'] = (int) $_GET['class_id'];
}

if (isset($_GET['user_id'])) {
  $_SESSION['user_id'] = (int) $_GET['user_id'];
}

if (isset($_GET['content_id'])) {
  $_SESSION['content_id'] = (int) $_GET['content_id'];
}

if (isset($_GET['type'])) {
  $_SESSION['type'] = $_GET['type'];
}

// Require class_id in session
if (!isset($_SESSION['class_id'])) {
  die("No class selected.");
}

//shi requires user id duh
if (!isset($_SESSION['content_id'])) {
  die("No content id not found D:");
}

//shi requires user id duh
if (!isset($_SESSION['user_id'])) {
  die("No user logged in.");
}

$user_id = $_SESSION['user_id'];
$class_id = $_SESSION['class_id'];
$content_id = $_SESSION['content_id'];
$type = $_SESSION['type'];


// Determine table and ID column
$table = ($type === 'announcement') ? 'tbl_announcements' : 'tbl_lessons';
$id_column = ($type === 'announcement') ? 'ann_id' : 'lsn_id';

// Prepare query
$stmt = $conn->prepare("
    SELECT t.*, u.name 
    FROM $table t 
    JOIN tbl_users u ON t.user_id = u.user_id 
    WHERE t.$id_column = ? AND t.class_id = ?
");

$stmt->bind_param("ii", $content_id, $class_id);
$stmt->execute();
$result = $stmt->get_result();
$content = $result->fetch_assoc();

$title = $content['title'] ?? '';
$fileName = $content['file_name'];
$filePath = $content['file_path'];
$fileType = $content['file_type'];
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars(ucfirst($type)) ?></title>
  <link rel="stylesheet" href="assignmentdashboard.css">
</head>

<body>
  <header>
    <button class="menu-btn" onclick="toggleSidebar()">&#9776;</button>
    <button class="logo-btn">
      <a href="student-home.php"><img src="eduquest.png" alt="Eduquest Logo" style="height: 70px; width: auto;"></a>
    </button>
    <button class="join-btn">Join Class</button>
    <span class="points">✨ 0.00</span>
    <button class="shop-btn">
      <a href="shop.html"><img src="shopping-cart.png" alt="cart" style="height: 40px; width: auto;"></a>
    </button>
    <button class="acc-btn">
      <a href="account.html"><img src="account.png" alt="profile" style="height: 40px; width: auto;"></a>
    </button>
  </header>

  <aside id="sidebar" class="hover-active">
    <nav>
      <a href="student-home.php">
        <span class="icon">🏠</span><span class="label">Home</span>
      </a>
      <a href="student-task.html">
        <span class="icon">📝</span><span class="label">Tasks</span>
      </a>
      <a href="leaderboards.php">
        <span class="icon">🏆</span><span class="label">Leaderboard</span>
      </a>
      <a href="archive-class.html">
        <span class="icon">🏆</span><span class="label">Archive Class</span>
      </a>

    </nav>
  </aside>

  <div class="assignment-box">
    <div class="left-section">
      <button class="back-btn" onclick="window.location.href='student-alltopics.php'"><img src="back-button.png"
          alt="back_arrow"></button>
      <div class="assignment-header">
        <p><strong><?= htmlspecialchars(ucfirst($type)) ?>: </strong><?= htmlspecialchars($title) ?></p>
        <p><strong>Posted by: <?= htmlspecialchars(ucfirst($content['name'])) ?> ^
            <?= htmlspecialchars(ucfirst($content['posted_at'])) ?></strong></p>
      </div>

      <div class="assignment-description">
        <label>Description</label>
        <p><?= htmlspecialchars(ucfirst($content['description'])) ?></p>
        <?php if (!empty($filePath)): ?>
          <p class="">Attachment:
            <a href="<?= htmlspecialchars($filePath) ?>" download="<?= htmlspecialchars($fileName) ?>">
              <?= htmlspecialchars($fileName) ?>
            </a>
          </p>
        <?php endif; ?>
        <div class="message-box">💬 Public Message</div>
      </div>
    </div>

    <div class="right-panel">
      <div class="message-box">💬 Private Message</div>
    </div>
  </div>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('show');
    }
  </script>
</body>

</html>