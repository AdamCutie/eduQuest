<?php
include('db_connection.php'); // Make sure this connects correctly

// Fetch all student
$sql = "SELECT user_id, Name, Email FROM tbl_users WHERE user_type = 'teacher'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <title>EduQuest/Admins</title>
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
        -1px  1px 0 #000,
         1px  1px 0 #000;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      table-layout: auto;
    }

    th, td {
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

    .add-btn {
      padding: 10px 30px;
      margin-top: 0;
      margin-left: auto;
      background-color: #00b894;
      color: white;
      border: none;
      border-radius: 20px;
      font-weight: bold;
      cursor: pointer;
      width: fit-content;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
      background-color: #2F285B;
      margin: 15% auto;
      padding: 20px;
      border-radius: 15px;
      width: 80%;
      max-width: 500px;
      position: relative;
      color: white;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover {
      color: white;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
    }

    .form-group input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
      font-size: 16px;
    }

    .submit-btn {
      background-color: #00b894;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
    }

    .submit-btn:hover {
      opacity: 0.85;
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
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>TEACHERS</h2>
        <button class="add-btn">Add Account</button>
      </div>
      <table>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th class="actions-column">Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
          <td><?= htmlspecialchars($row['Name']) ?></td>
          <td><?= htmlspecialchars($row['Email']) ?></td>
          <td>
            <button class="action-btn edit-btn" data-id="<?= $row['user_id'] ?>" data-name="<?= $row['Name'] ?>" data-email="<?= $row['Email'] ?>">Edit</button>
            <button class="action-btn delete-btn" data-id="<?= $row['user_id'] ?>">Delete</button>
          </td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </div>

  <div id="addModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Add New Teacher</h2>
      <form id="addAdminForm">
        <div class="form-group">
          <label for="fullName">Full Name:</label>
          <input type="text" id="fullName" name="fullName" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
        </div>
        <button type="submit" class="submit-btn">Add Teacher</button>
      </form>
    </div>
  </div>

  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Edit Teacher</h2>
      <form id="editAdminForm">
        <input type="hidden" id="editUserId" name="user_id">
        <div class="form-group">
          <label for="editFullName">Full Name:</label>
          <input type="text" id="editFullName" name="fullName" required>
        </div>
        <div class="form-group">
          <label for="editEmail">Email:</label>
          <input type="email" id="editEmail" name="email" required>
        </div>
        <button type="submit" class="submit-btn">Update Teacher</button>
      </form>
    </div>
  </div>

  <script>
    const modal = document.getElementById("addModal");
    const editModal = document.getElementById("editModal");
    const addBtn = document.querySelector(".add-btn");
    const spanAdd = document.getElementsByClassName("close")[0]; // Close button for add modal
    const spanEdit = document.getElementsByClassName("close")[1]; // Close button for edit modal
    const form = document.getElementById("addAdminForm");
    const editForm = document.getElementById("editAdminForm");

    // Add Admin Modal
    addBtn.onclick = function() {
      modal.style.display = "block";
    }

    // Close modal
    spanAdd.onclick = function() {
      modal.style.display = "none";
    }

    spanEdit.onclick = function() {
      editModal.style.display = "none"; // Close the edit modal
    }

    window.onclick = function(event) {
      if (event.target == modal || event.target == editModal) {
        modal.style.display = "none";
        editModal.style.display = "none";
      }
    }

    // Add new admin
 form.onsubmit = function(e) {
  e.preventDefault();
  const fullName = document.getElementById("fullName").value;
  const email = document.getElementById("email").value;

  fetch('add_teacher.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ fullName, email })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("Teacher added successfully! Name: " + data.name + ", Email: " + data.email);
      location.reload(); // refresh to show updated data
    } else {
      alert("Error: " + data.message);
    }
  })
  .catch(error => {
    console.error('Request failed:', error);
    alert("Something went wrong.");
  });
};


    // Edit Admin
    document.querySelectorAll('.edit-btn').forEach(button => {
      button.addEventListener('click', (e) => {
        const userId = e.target.dataset.id;
        const userName = e.target.dataset.name;
        const userEmail = e.target.dataset.email;

        // Prefill the edit modal form
        document.getElementById("editUserId").value = userId;
        document.getElementById("editFullName").value = userName;
        document.getElementById("editEmail").value = userEmail;

        editModal.style.display = "block";
      });
    });

    // Update Admin
    editForm.onsubmit = function(e) {
      e.preventDefault();
      const userId = document.getElementById("editUserId").value;
      const fullName = document.getElementById("editFullName").value;
      const email = document.getElementById("editEmail").value;

      fetch('edit_teacher.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ user_id: userId, fullName, email })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload(); // refresh to show updated data
        } else {
          alert("Error: " + data.error);
        }
      })
      .catch(error => {
        console.error('Request failed:', error);
        alert("Something went wrong.");
      });
    };

    // Delete Teacher
document.querySelectorAll('.delete-btn').forEach(button => {
  button.addEventListener('click', (e) => {
    const userId = e.target.dataset.id;

    // Confirm before deleting
    if (confirm("Are you sure you want to delete this teacher?")) {
      fetch('delete_teacher.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ user_id: userId })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload(); // Refresh the page to reflect the changes
        } else {
          alert("Error: " + data.error);
        }
      })
      .catch(error => {
        console.error('Request failed:', error);
        alert("Something went wrong.");
      });
    }
  });
});


</script>
</body>
</html>
