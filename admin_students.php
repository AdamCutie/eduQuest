<?php
include('db_connection.php');

// Fetch all students
$sql = "SELECT user_id, Name, Email FROM tbl_users WHERE user_type = 'student'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <title>EduQuest | Students</title>
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
        <img src="eduquest.png" alt="Eduquest Logo" style="height: 60px;">
      </a>
    </div>
  </header>

  <div class="panel">
    <div class="inner-panel">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>STUDENTS</h2>
        <button class="add-btn">Add Account</button>
      </div>
      <table id="studentsTable">
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th class="actions-column">Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
          <tr data-id="<?= $row['user_id'] ?>">
            <td><?= htmlspecialchars($row['Name']) ?></td>
            <td><?= htmlspecialchars($row['Email']) ?></td>
            <td>
              <button class="action-btn edit-btn">Edit</button>
              <button class="action-btn delete-btn">Delete</button>
            </td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </div>

  <!-- Add Modal -->
  <div id="addModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Add New Student</h2>
      <form id="addStudentForm">
        <div class="form-group">
          <label for="fullName">Full Name:</label>
          <input type="text" id="fullName" name="fullName" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
        </div>
        <button type="submit" class="submit-btn">Add Student</button>
      </form>
    </div>
  </div>

  <!-- Edit Modal -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close edit-close">&times;</span>
      <h2>Edit Student</h2>
      <form id="editStudentForm">
        <input type="hidden" id="editUserId" name="id">
        <div class="form-group">
          <label for="editFullName">Full Name:</label>
          <input type="text" id="editFullName" name="name" required>
        </div>
        <div class="form-group">
          <label for="editEmail">Email:</label>
          <input type="email" id="editEmail" name="email" required>
        </div>
        <button type="submit" class="submit-btn">Update Student</button>
      </form>
    </div>
  </div>

  <script>
    const modal = document.getElementById("addModal");
    const addBtn = document.querySelector(".add-btn");
    const span = document.getElementsByClassName("close")[0];
    const form = document.getElementById("addStudentForm");

    addBtn.onclick = () => modal.style.display = "block";
    span.onclick = () => modal.style.display = "none";

    // Add student
    form.onsubmit = function(e) {
  e.preventDefault();
  const fullName = document.getElementById("fullName").value;
  const email = document.getElementById("email").value;

  fetch('add_student.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `fullName=${encodeURIComponent(fullName)}&email=${encodeURIComponent(email)}`
  }).then(res => res.json()).then(data => {
    if (data.success) {
      alert("Admin added successfully! Name: " + data.name + ", Email: " + data.email);
      location.reload();  // Reload the page to show the new student
    } else {
      alert("Error: " + data.message);  // Error message
    }
  });
};

    // Edit modal logic
    const editModal = document.getElementById("editModal");
    const editForm = document.getElementById("editStudentForm");
    const editCloseBtn = document.querySelector(".edit-close");

    document.querySelectorAll('.edit-btn').forEach(button => {
      button.addEventListener('click', () => {
        const row = button.closest('tr');
        const id = row.dataset.id;
        const name = row.cells[0].innerText;
        const email = row.cells[1].innerText;

        document.getElementById("editUserId").value = id;
        document.getElementById("editFullName").value = name;
        document.getElementById("editEmail").value = email;

        editModal.style.display = "block";
      });
    });

    editCloseBtn.onclick = () => editModal.style.display = "none";

    window.onclick = (event) => {
      if (event.target === modal) modal.style.display = "none";
      if (event.target === editModal) editModal.style.display = "none";
    };

    editForm.onsubmit = function(e) {
      e.preventDefault();
      const formData = new FormData(editForm);

      fetch('edit_student.php', {
        method: 'POST',
        body: new URLSearchParams(formData)
      }).then(res => res.json()).then(data => {
        if (data.success) location.reload();
        else alert("Error updating student");
      });
    };

    // Delete student
    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', () => {
        const row = button.closest('tr');
        const id = row.dataset.id;
        if (confirm("Are you sure you want to delete this student?")) {
          fetch('delete_student.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
          }).then(res => res.json()).then(data => {
            if (data.success) location.reload();
            else alert("Error deleting student");
          });
        }
      });
    });
  </script>
</body>
</html>
