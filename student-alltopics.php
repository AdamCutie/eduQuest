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
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500&display=swap" rel="stylesheet">
  <title>EduQuest/Classroom</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Fredoka', sans-serif;
      background-color: #6f62d2;
    }

    header {
      background-color: #5E4CC2;
      padding: 0px 20px;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .points {
      background-color: white;
      border-radius: 50px;
      padding: 10px 8px;
      text-align: center;
      display: flex;
      align-items: center;
      gap: 5px;
      cursor: pointer;
      transition: transform 0.2s ease;
    }

    .points:hover {
      transform: scale(1.05);
    }

    .points-icon {
      font-size: 20px;
    }

    .points-modal {
      display: none;
      position: fixed;
      top: 80px;
      right: 20px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      width: 300px;
      padding: 20px;
      z-index: 1100;
      animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .points-category {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      padding: 10px;
      background-color: #f4f4f4;
      border-radius: 5px;
    }

    .points-progress {
      width: 100%;
      background-color: #e0e0e0;
      border-radius: 10px;
      margin-top: 10px;
      overflow: hidden;
    }

    .points-progress-bar {
      width: 0;
      height: 10px;
      background-color: #5E4CC2;
      transition: width 0.5s ease;
    }

    .menu-btn {
      background-color: #5E4CC2;
      color: white;
      font-size: 25px;
      border: none;
      cursor: pointer;
      padding: 8px 12px;
      border-radius: 5px;
    }

    .join-btn {
      background-color: #00B894;
      color: white;
      font-size: 16px;
      padding: 10px 18px;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-left: auto;
      text-align: center;
    }

    .join-btn:hover {
      background-color: #019174;
    }

    .shop-btn,
    .logo-btn,
    .acc-btn {
      border: none;
      background: none;
      cursor: pointer;
    }

    aside {
      width: 60px;
      background-color: #2F285B;
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

    aside.hover-active:hover~.subject-box,
    aside.show~.subject-box {
      margin-left: 300px;
      transition: margin-left 0.3s ease;
    }

    aside nav a {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 12px;
      text-decoration: none;
      color: white;
      font-size: 18px;
      background-color: #5E4CC2;
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
    }

    aside.hover-active:hover .label,
    aside.show .label {
      display: inline;
    }

    .subject-box {
      background: white;
      border-radius: 15px;
      padding: 1rem;
      /* Padding for content */
      position: relative;
      margin-left: 60px;
      transition: margin-left 0.3s ease;
    }

    aside.hover-active:hover~.subject-box,
    aside.show~.subject-box {
      margin-left: 300px;
      transition: margin-left 0.3s ease;
    }

    .subject-header {
      background: #3c98ff;
      color: white;
      padding: 0.5rem 1rem;
      margin: -1rem -1rem 1rem -1rem;
      /* To remove spacing around the header */
    }

    .filters {
      margin: 1rem 0;
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .filter-btn {
      padding: 0.4rem 0.8rem;
      background-color: white;
      color: black;
      border: black;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.2s ease;
    }

    .filter-btn:hover {
      background-color: gray;
    }


    .task-list .task-item {
      background: #f0f0f0;
      margin-bottom: 0.5rem;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 1rem;
    }

    /* Icon container */
    .task-item .icons {
      display: flex;
      gap: 0.5rem;
      /* Space between icons */
    }

    /* Icon images */
    .task-item .icons img {
      width: 24px;
      height: 24px;
      cursor: pointer;
      transition: transform 0.2s;
    }

    .task-item .icons img:hover {
      transform: scale(1.1);
    }
  </style>

<body>
  <header>
    <button class="menu-btn" onclick="toggleSidebar()">&#9776;</button>
    <button class="logo-btn">
      <a href="student-home.php"><img src="eduquest.png" alt="Eduquest Logo" style="height: 70px; width: auto;"></a>
    </button>
    <button class="join-btn">Join Class</button>
    <span class="points" onclick="togglePointsModal()">
      <span class="points-icon">✨</span>
      <span id="total-points">0.00</span>
    </span>
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
      <a href="student-tasks.html">
        <span class="icon">📝</span><span class="label">Tasks</span>
      </a>
      <a href="leaderboards.html">
        <span class="icon">🏆</span><span class="label">Leaderboard</span>
      </a>
      <a href="archive-class.html">
        <span class="icon">🏆</span><span class="label">Archive Class</span>
      </a>

    </nav>
  </aside>

  <div id="points-modal" class="points-modal">
    <h3>Points Breakdown</h3>
    <div id="points-categories">
      <!-- Dynamically populated points categories -->
    </div>
    <div class="points-progress">
      <div id="points-progress-bar" class="points-progress-bar"></div>
    </div>
    <p id="points-next-level">Next Level: 50 points</p>
  </div>

  <section class="subject-box">
    <div class="subject-header">
      <h2 id="subject-title"><?= htmlspecialchars($className) ?></h2>
      <p id="subject-section"><?= htmlspecialchars($section) ?></p>
    </div>
    <div class="filters">
      <button class="filter-btn" onclick="window.location.href='student-alltopics.php'">All Topics</button>
      <button class="filter-btn" onclick="window.location.href='student-activities.html'">Activities</button>
      <button class="filter-btn" onclick="window.location.href='student-people.php'">People</button>

    </div>
    <div class="task-list">
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
            $targetPage = in_array($type, ['announcement', 'lesson']) ? 'student-anle_dashboard.php' : 'student-asac_dashboard.php';
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
  </section>

  <script>
    class PointSystem {
      constructor() {
        // Initialize point categories
        this.points = {
          quiz: 0,
          activity: 0,
          assignment: 0,
          participation: 0
        };

        // Point levels and rewards
        this.levels = [
          { name: 'Beginner', threshold: 50, reward: 'Basic Badge' },
          { name: 'Learner', threshold: 100, reward: 'Learning Badge' },
          { name: 'Scholar', threshold: 250, reward: 'Scholar Badge' },
          { name: 'Master', threshold: 500, reward: 'Master Badge' }
        ];

        // Load saved points from local storage
        this.loadPoints();
      }

      // Add points to a specific category
      addPoints(category, points) {
        if (this.points.hasOwnProperty(category)) {
          this.points[category] += points;
          this.updatePointDisplay();
          this.savePoints();
        } else {
          console.error(`Invalid point category: ${category}`);
        }
      }

      // Calculate total points
      getTotalPoints() {
        return Object.values(this.points).reduce((a, b) => a + b, 0);
      }

      // Update point display in UI
      updatePointDisplay() {
        const totalPoints = this.getTotalPoints();

        // Update total points in header
        document.getElementById('total-points').textContent = totalPoints.toFixed(2);

        // Update points categories in modal
        const categoriesContainer = document.getElementById('points-categories');
        categoriesContainer.innerHTML = ''; // Clear existing categories

        // Create category breakdown
        Object.entries(this.points).forEach(([category, points]) => {
          const categoryElement = document.createElement('div');
          categoryElement.classList.add('points-category');
          categoryElement.innerHTML = `
            <span>${this.formatCategoryName(category)}</span>
            <span>${points.toFixed(2)}</span>
          `;
          categoriesContainer.appendChild(categoryElement);
        });

        // Update progress bar
        this.updateProgressBar(totalPoints);
      }

      // Format category name (convert snake_case to Title Case)
      formatCategoryName(category) {
        return category.split('_')
          .map(word => word.charAt(0).toUpperCase() + word.slice(1))
          .join(' ');
      }

      // Update progress bar and next level info
      updateProgressBar(totalPoints) {
        const progressBar = document.getElementById('points-progress-bar');
        const nextLevelText = document.getElementById('points-next-level');

        // Find current and next level
        const currentLevel = this.levels.findLast(level => totalPoints >= level.threshold) || this.levels[0];
        const nextLevel = this.levels.find(level => level.threshold > totalPoints) || this.levels[this.levels.length - 1];

        // Calculate progress percentage
        const prevLevelThreshold = this.levels[this.levels.indexOf(currentLevel) - 1]?.threshold || 0;
        const progressPercentage = nextLevel
          ? ((totalPoints - prevLevelThreshold) / (nextLevel.threshold - prevLevelThreshold)) * 100
          : 100;

        // Update progress bar
        progressBar.style.width = `${Math.min(progressPercentage, 100)}%`;

        // Update next level text
        if (nextLevel) {
          nextLevelText.textContent = `Next Level: ${nextLevel.name} (${nextLevel.threshold} points)`;
        } else {
          nextLevelText.textContent = 'Max Level Reached!';
        }
      }

      // Save points to local storage
      savePoints() {
        localStorage.setItem('eduquest-points', JSON.stringify(this.points));
      }

      // Load points from local storage
      loadPoints() {
        const savedPoints = localStorage.getItem('eduquest-points');

        if (savedPoints) {
          this.points = JSON.parse(savedPoints);
          this.updatePointDisplay();
        }
      }

      // Methods to earn points
      completeQuiz(score) {
        // Award points based on quiz performance
        const points = score * 10; // 10 points per point of score
        this.addPoints('quiz', points);
      }

      completeActivity(activityType) {
        // Different point values for different activity types
        const activityPoints = {
          'group-project': 50,
          'discussion': 20,
          'presentation': 40
        };

        const points = activityPoints[activityType] || 10;
        this.addPoints('activity', points);
      }

      submitAssignment(grade) {
        // Award points based on assignment grade
        const points = grade * 5; // 5 points per point of grade
        this.addPoints('assignment', points);
      }

      participateInClass() {
        // Small points for class participation
        this.addPoints('participation', 10);
      }
    }

    // Global point system instance
    const pointSystem = new PointSystem();

    // Toggle points modal
    function togglePointsModal() {
      const modal = document.getElementById('points-modal');
      modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
    }

    // Close modal when clicking outside
    // Close modal when clicking outside or on a close button
    window.addEventListener('click', function (event) {
      const modal = document.getElementById('points-modal');

      // Close if clicking outside the modal or on a close element
      if (event.target === modal ||
        (modal.style.display === 'block' &&
          !modal.contains(event.target) &&
          !document.querySelector('.points').contains(event.target))) {
        modal.style.display = 'none';
      }
    });

    // Sidebar toggle function (from original code)
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('show');
    }

    // Simulate point earning for demonstration
    function simulatePointEarning() {
      pointSystem.completeQuiz(8.5);  // Quiz score of 8.5
      pointSystem.completeActivity('discussion');
      pointSystem.submitAssignment(9);
      pointSystem.participateInClass();
    }
  </script>
</body>

</html>