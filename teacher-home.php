<?php
session_start();
include('db_connection.php'); // Use require_once for critical files

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: eduquest.php?error=Please+log+in+first+>:l");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="EduQuest - Classroom management system for teachers">
  <title>EduQuest - Classrooms</title>
  <style>
    :root {
      --primary-color: #5E4CC2;
      --secondary-color: #2F285B;
      --accent-color: #00B894;
      --text-color: #333;
      --light-bg: #f4f4f4;
      --card-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
      --card-hover-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
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
      padding: 0;
      transition: transform 0.2s;
    }

    .acc-btn:hover {
      transform: scale(1.05);
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

    .panel {
      background: white;
      border-radius: 15px;
      padding: 1rem;
      margin-left: 80px;
      margin-right: 20px;
      margin-top: 20px;
      transition: margin-left 0.3s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    aside.hover-active:hover~.panel,
    aside.show~.panel {
      margin-left: 320px;
    }

    .class-panel {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
      padding: 20px 0;
    }

    .class-card {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: var(--card-shadow);
      transition: all 0.3s ease;
      border: 1px solid #eee;
    }

    .class-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--card-hover-shadow);
    }

    .class-card h3 {
      margin: 0 0 10px;
      font-size: 1.5rem;
      color: var(--primary-color);
    }

    .class-card h4 {
      margin: 0 0 10px;
      font-size: 1.1rem;
      color: #555;
      font-weight: normal;
    }

    .class-card p {
      font-size: 1rem;
      color: #777;
      margin: 10px 0 0;
    }

    .panel h1 {
      color: var(--primary-color);
      margin: 0 0 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    button:focus-visible {
      outline: 2px solid var(--accent-color);
      outline-offset: 2px;
    }

    .account {
      display: none;
      position: fixed;
      top: 85px;
      right: 25px;
      width: 320px;
      background: white;
      border-radius: 12px;
      box-shadow: var(--modal-shadow);
      z-index: 1000;
      overflow: hidden;
      opacity: 0;
      transform: translateY(-10px);
      transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .account.show {
      opacity: 1;
      transform: translateY(0);
    }

    .modal-backdrop.show {
      opacity: 1;
    }

    .account-header {
      padding: 20px;
      border-bottom: 1px solid #f0f0f0;
      display: flex;
      align-items: center;
      gap: 15px;
      background: linear-gradient(135deg, #f9f9ff 0%, #f0f2ff 100%);
    }

    .account-header img {
      border-radius: 50%;
      border: 2px solid var(--primary-color);
    }

    .account-header-content {
      flex: 1;
    }

    .account-header h3 {
      margin: 0;
      font-size: 17px;
      color: var(--secondary-color);
      font-weight: 600;
    }

    .account-header p {
      margin: 4px 0 0;
      font-size: 14px;
      color: #666;
    }

    .modal-content {
      padding: 8px 0;
    }

    .modal-item {
      padding: 14px 20px;
      display: flex;
      align-items: center;
      cursor: pointer;
      transition: all 0.2s;
      font-size: 15px;
      color: #555;
    }

    .modal-item:hover {
      background-color: #f8f8ff;
      color: var(--primary-color);
      padding-left: 22px;
    }

    .modal-item i {
      margin-right: 15px;
      color: var(--primary-color);
      width: 20px;
      text-align: center;
      font-size: 16px;
    }

    .modal-footer {
      padding: 15px;
      border-top: 1px solid #f0f0f0;
      text-align: center;
      background-color: #fafaff;
    }

    .logout-btn {
      color: #d32f2f;
      font-weight: 600;
      cursor: pointer;
      padding: 8px 16px;
      border-radius: 6px;
      transition: all 0.2s;
      display: inline-block;
    }

    .logout-btn:hover {
      background-color: rgba(211, 47, 47, 0.1);
      text-decoration: none;
    }

    /* Backdrop for modal */
    .modal-backdrop {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: 999;
      opacity: 0;
      transition: opacity 0.25s ease;
    }

    .modal-backdrop.show {
      opacity: 1;
    }

    /* Animation for modal items */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateX(10px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .modal-item {
      animation: fadeIn 0.3s ease forwards;
      opacity: 0;
    }

    .modal-item:nth-child(1) {
      animation-delay: 0.1s;
    }

    .modal-item:nth-child(2) {
      animation-delay: 0.15s;
    }

    .modal-item:nth-child(3) {
      animation-delay: 0.2s;
    }

    .modal-item:nth-child(4) {
      animation-delay: 0.25s;
    }

    .modal-item:nth-child(5) {
      animation-delay: 0.3s;
    }

    .modal-item:nth-child(6) {
      animation-delay: 0.35s;
    }

    @media (max-width: 768px) {
      .class-panel {
        grid-template-columns: 1fr;
        gap: 15px;
      }

      .modal {
        width: 90%;
        right: 5%;
        top: 75px;
      }

      header {
        padding: 0 15px;
        height: 60px;
      }

      .panel {
        margin: 15px;
        padding: 1rem;
      }
    }

    @media (max-width: 768px) {
      aside {
        width: 60px;
      }

      aside.show {
        width: 200px;
      }

      .panel {
        margin-left: 0;
        width: calc(100% - 40px);
      }

      aside.show~.panel {
        margin-left: 200px;
      }

      .class-panel {
        grid-template-columns: 1fr;
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
    <a href="create_class.php" class="join-btn">Create Class</a>
    <button class="acc-btn" id="accountBtn">
      <img src="account.png" alt="User profile" width="40" height="40" style="height: auto; border-radius: 50%;">
    </button>
  </header>
  <!-- Account Modal -->
  <div class="account" id="accountModal">
    <div class="account-backdrop" id="modalBackdrop"></div>

    <div class="account-modal-content">
      <div class="account-header">
        <img src="account.png" alt="User profile" width="50" height="50" style="height: auto;">
        <div class="account-header-content">
          <h3>Torio, Vincent</h3>
          <p>vincenttorio262@gmail.com</p>
        </div>
      </div>

      <div class="account-content">
        <div class="modal-item" onclick="window.location.href='profile.html'">
          <i>👤</i><span>Teacher Profile</span>
        </div>
        <div class="modal-item" onclick="window.location.href='change-password.html'">
          <i>🔒</i><span>Account Settings</span>
        </div>
      </div>

      <div class="modal-footer">
        <div class="logout-btn" id="logoutBtn">Log Out</div>
      </div>
    </div>
  </div>

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

  <div class="panel">
    <h1>Classrooms</h1>
    <?php
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT class_id, class_name, section, description, user_id FROM tbl_classes WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id); // Bind user_id as integer
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    <div class="class-panel">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="class-card" role="article">
            <a href="teacher-alltopics.php?class_id=<?= $row['class_id'] ?>&user_id=<?= $row['user_id'] ?>"
              aria-label="View <?= htmlspecialchars($row['class_name']) ?> classroom">
              <h3><?= htmlspecialchars($row['class_name']) ?></h3>
              <h4><?= htmlspecialchars($row['section']) ?></h4>
              <p><?= htmlspecialchars($row['description']) ?></p>
            </a>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No classes found.</p>
      <?php endif; ?>
    </div>
    <?php
    $conn->close();
    ?>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const btn = document.querySelector('.menu-btn');
      const isShown = sidebar.classList.toggle('show');
      btn.setAttribute('aria-expanded', isShown);
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function () {

      const button = dropdown.querySelector('button[aria-haspopup]');
      if (button) button.setAttribute('aria-expanded', 'false');
    });

    /**
 * Initialize all dropdown menus
 */
    function initDropdowns() {
      document.querySelectorAll('.dropdown').forEach(dropdown => {
        const button = dropdown.querySelector('button[aria-haspopup]');
        if (!button) return;

        button.addEventListener('click', function (e) {
          e.stopPropagation();
          const isOpen = this.getAttribute('aria-expanded') === 'true';
          this.setAttribute('aria-expanded', !isOpen);
          dropdown.classList.toggle('show', !isOpen);
        });
      });

      // Close dropdowns when clicking outside
      document.addEventListener('click', function () {
        document.querySelectorAll('.dropdown').forEach(dropdown => {
          dropdown.classList.remove('show');
          const button = dropdown.querySelector('button[aria-haspopup]');
          if (button) button.setAttribute('aria-expanded', 'false');
        });
      });
    }


    /**
     * Setup header functionality including account modal
     */
    function setupHeader() {
      const accountBtn = document.getElementById('accountBtn');
      const accountModal = document.getElementById('accountModal');
      const modalBackdrop = document.getElementById('modalBackdrop');
      const logoutBtn = document.getElementById('logoutBtn');

      if (!accountBtn || !accountModal || !modalBackdrop || !logoutBtn) return;

      function openModal() {
        accountModal.style.display = 'block';
        modalBackdrop.style.display = 'block';
        setTimeout(() => {
          accountModal.classList.add('show');
          modalBackdrop.classList.add('show');
        }, 10);
      }

      function closeModal() {
        accountModal.classList.remove('show');
        modalBackdrop.classList.remove('show');
        setTimeout(() => {
          accountModal.style.display = 'none';
          modalBackdrop.style.display = 'none';
        }, 250);
      }

      function toggleModal() {
        accountModal.classList.contains('show') ? closeModal() : openModal();
      }

      accountBtn.addEventListener('click', e => {
        e.stopPropagation();
        toggleModal();
      });

      modalBackdrop.addEventListener('click', closeModal);
      accountModal.addEventListener('click', e => e.stopPropagation());

      document.addEventListener('click', e => {
        if (!accountModal.contains(e.target) && e.target !== accountBtn) {
          closeModal();
        }
      });

      logoutBtn.addEventListener('click', () => alert('Logging out...'));
    }

    // Initialize after DOM is fully loaded
    document.addEventListener('DOMContentLoaded', setupHeader);
  </script>


</body>

</html>