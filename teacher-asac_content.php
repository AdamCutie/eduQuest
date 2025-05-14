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

//shi requires user id duh
if (!isset($_SESSION['type'])) {
  die("No content type selected.");
}

$user_id = $_SESSION['user_id'];
$class_id = $_SESSION['class_id'];
$content_id = $_SESSION['content_id'];
$type = $_SESSION['type'];

// Determine table and ID column
$table = ($type === 'assignment')
  ? 'tbl_assignments'
  : (($type === 'activity')
    ? 'tbl_acts'
    : 'tbl_projects');


$id_column = ($type === 'assignment')
  ? 'ass_id'
  : (($type === 'activity')
    ? 'act_id'
    : 'proj_id');


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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars(ucfirst($type)) ?></title>
</head>

<body>
  <!--title u stewpid ahh baka magkamali ka pa lalagyan ko na comment tae na sinerch ko pa pano mag comment html piset-->
  <h2><?= ucfirst($title) ?></h2>
  <!--user na nag create at datetime ng pagkacreate-->
  <h4><?= htmlspecialchars(ucfirst($content['name'])) ?> ^ <?= htmlspecialchars(ucfirst($content['posted_at'])) ?></h4>
  <!-- score -->
  <h4><?= htmlspecialchars(ucfirst($content['score'])) ?> points</h4>
  <br><br>
  <!--this shi the description-->
  <p>
    <?= htmlspecialchars(ucfirst($content['description'])) ?>
  </p>
  <br>
  <!-- this shi attachemnt -->
  <?php if (!empty($filePath)): ?>
    <p>Attachment:
      <a href="<?= htmlspecialchars($filePath) ?>" download="<?= htmlspecialchars($fileName) ?>">
        <?= htmlspecialchars($fileName) ?>
      </a>
    </p>
  <?php endif; ?>
</body>

</html>