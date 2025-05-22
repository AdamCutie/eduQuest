<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EduQuest</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: radial-gradient(100% 259.2% at 72.65% 0%, #FFF8A7 16%, #FFA973 34%, #A4E8ED 72%);
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
      padding: 12px;
      text-align: center;
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

    .main-content {
      margin-left: 80px;
      margin-top: 10px;
      padding: 10px;
      transition: margin-left 0.3s ease;
      display: flex;
      gap: 15px;
    }

    aside.show+.main-content {
      margin-left: 310px;
    }

    .user-profile {
      background-color: #7A6ED6;
      border-radius: 15px;
      padding: 15px;
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 180px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .user-image {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background-color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 15px;
      border: 10px solid #2600ff;
      overflow: hidden;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .user-image:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .user-info {
      text-align: center;
      color: white;
      width: 100%;
    }

    .user-name {
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 5px;
    }

    .user-section {
      font-size: 14px;
      margin-bottom: 15px;
      opacity: 0.9;
    }

    .sign-out-btn {
      background-color: #342F6A;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 8px;
      cursor: pointer;
      width: 100%;
      font-weight: bold;
      transition: background-color 0.2s ease;
    }

    .sign-out-btn:hover {
      background-color: #403880;
    }

    .account-panel {
      background-color: #7A6ED6;
      border-radius: 15px;
      padding: 15px;
      display: flex;
      gap: 15px;
      flex: 1;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .panel-left,
    .panel-right {
      display: flex;
      flex-direction: column;
      gap: 15px;
      width: 50%;
    }

    .panel-item {
      background-color: #342F6A;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      padding: 15px;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s ease;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      position: relative;
      overflow: hidden;
    }

    .panel-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      background-color: #403880;
    }

    .panel-item:active {
      transform: translateY(0);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .panel-item::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.1);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .panel-item:hover::after {
      opacity: 1;
    }

    .panel-item.email,
    .panel-item.password,
    .panel-item.leaderboards,
    .panel-item.achievements {
      height: 50px;
    }

    .panel-item.borders {
      height: 70px;
    }

    .panel-item.themes,
    .panel-item.backgrounds {
      height: 70px;
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 100;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .modal.show {
      display: flex;
      opacity: 1;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background-color: white;
      border-radius: 10px;
      padding: 25px;
      width: 80%;
      max-width: 600px;
      box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
      transform: scale(0.7);
      transition: transform 0.3s ease;
      position: relative;
    }

    .modal.show .modal-content {
      transform: scale(1);
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
    }

    .modal-title {
      font-size: 24px;
      color: #5E4CC2;
      margin: 0;
    }

    .close-button {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      color: #888;
      transition: color 0.2s ease;
    }

    .close-button:hover {
      color: #333;
    }

    .modal-body {
      min-height: 100px;
    }

    /* For better mobile responsiveness */
    @media (max-width: 900px) {
      .main-content {
        flex-direction: column;
      }

      .user-profile {
        width: 100%;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
      }

      .user-info {
        text-align: left;
        margin-left: 15px;
        flex: 1;
      }

      .sign-out-btn {
        width: auto;
      }
    }

    @media (max-width: 768px) {
      .account-panel {
        flex-direction: column;
      }

      .panel-left,
      .panel-right {
        width: 100%;
      }

      .modal-content {
        width: 95%;
      }
    }
  </style>
</head>

<body>
  <header>
    <button class="menu-btn" onclick="toggleSidebar()">&#9776;</button>
    <button class="logo-btn">
      <a href="eduquest.html"><img src="eduquest.png" alt="Eduquest Logo" style="height: 70px; width: auto;"></a>
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
      <a href="teacher-task.html">
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

  <div class="main-content">
    <!-- User Profile Section -->
    <div class="user-profile">
      <div class="user-image" onclick="openModal('Profile Picture', 'profile-pic-modal')">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"
            fill="#5E4CC2" />
          <path d="M12 13C7.03 13 3 17.03 3 22H21C21 17.03 16.97 13 12 13Z" fill="#5E4CC2" />
        </svg>
      </div>
      <div class="user-info">
        <div class="user-name">STUDENT NAME</div>
        <button class="sign-out-btn">SIGN OUT</button>
      </div>
    </div>

    <!-- Account Information Panels -->
    <div class="account-panel">
      <div class="panel-left">
        <div class="panel-item email" onclick="openModal('Email Address', 'email-modal')">EMAIL ADDRESS</div>
        <div class="panel-item password" onclick="openModal('Change Password', 'password-modal')">CHANGE PASSWORD</div>
        <div class="panel-item leaderboards" onclick="openModal('Leaderboards', 'leaderboards-modal')">LEADERBOARDS
        </div>
        <div class="panel-item achievements" onclick="openModal('Achievements', 'achievements-modal')">ACHIEVEMENTS
        </div>
      </div>
      <div class="panel-right">
        <div class="panel-item borders" onclick="openModal('Borders', 'borders-modal')">BORDERS</div>
        <div class="panel-item themes" onclick="openModal('Themes', 'themes-modal')">THEMES</div>
        <div class="panel-item backgrounds" onclick="openModal('Backgrounds', 'backgrounds-modal')">BACKGROUNDS</div>
      </div>
    </div>
  </div>

  <!-- Modals -->
  <!-- Profile Picture Modal -->
  <div id="profile-pic-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Profile Picture</h2>
        <button class="close-button" onclick="closeModal('profile-pic-modal')">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Content will be added here -->
      </div>
    </div>
  </div>

  <!-- Other Modals -->
  <div id="email-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Email Address</h2>
        <button class="close-button" onclick="closeModal('email-modal')">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Content will be added here -->
      </div>
    </div>
  </div>

  <div id="password-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Change Password</h2>
        <button class="close-button" onclick="closeModal('password-modal')">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Content will be added here -->
      </div>
    </div>
  </div>

  <div id="leaderboards-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Leaderboards</h2>
        <button class="close-button" onclick="closeModal('leaderboards-modal')">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Content will be added here -->
      </div>
    </div>
  </div>

  <div id="achievements-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Achievements</h2>
        <button class="close-button" onclick="closeModal('achievements-modal')">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Content will be added here -->
      </div>
    </div>
  </div>

  <div id="borders-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Borders</h2>
        <button class="close-button" onclick="closeModal('borders-modal')">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Content will be added here -->
      </div>
    </div>
  </div>

  <div id="themes-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Themes</h2>
        <button class="close-button" onclick="closeModal('themes-modal')">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Content will be added here -->
      </div>
    </div>
  </div>

  <div id="backgrounds-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Backgrounds</h2>
        <button class="close-button" onclick="closeModal('backgrounds-modal')">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Content will be added here -->
      </div>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('show');
    }

    function openModal(title, modalId) {
      const modal = document.getElementById(modalId);
      modal.classList.add('show');

      // Add click event to close when clicking outside modal content
      modal.addEventListener('click', function (event) {
        if (event.target === modal) {
          closeModal(modalId);
        }
      });

      // Add escape key event to close modal
      document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
          closeModal(modalId);
        }
      });
    }

    function closeModal(modalId) {
      const modal = document.getElementById(modalId);
      modal.classList.remove('show');
    }

    const clickableElements = document.querySelectorAll('.panel-item, .sign-out-btn, .user-image');

    clickableElements.forEach(item => {
      item.addEventListener('click', function (event) {
        const ripple = document.createElement('span');
        const rect = item.getBoundingClientRect();

        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        ripple.style.cssText = `
          position: absolute;
          background-color: rgba(255, 255, 255, 0.7);
          border-radius: 50%;
          width: 5px;
          height: 5px;
          top: ${y}px;
          left: ${x}px;
          transform: translate(-50%, -50%);
          animation: ripple 0.6s linear;
          pointer-events: none;
        `;

        item.appendChild(ripple);

        setTimeout(() => {
          ripple.remove();
        }, 600);
      });
    });

    const style = document.createElement('style');
    style.textContent = `
      @keyframes ripple {
        0% {
          width: 0;
          height: 0;
          opacity: 0.5;
        }
        100% {
          width: 500px;
          height: 500px;
          opacity: 0;
        }
      }
    `;
    document.head.appendChild(style);
  </script>
</body>

</html>