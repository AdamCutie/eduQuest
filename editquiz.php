<?php
session_start();
include('db_connection.php');
/// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: eduquest.php?error=Please+log+in+first+>:l");
    exit();
}

// Update session if class_id is passed in URL
if (isset($_GET['class_id'])) {
    $_SESSION['class_id'] = (int) $_GET['class_id'];
}

// Require session values
if (!isset($_SESSION['class_id']) || !isset($_SESSION['user_id'])) {
    die("Missing class or user information.");
}

$class_id = $_SESSION['class_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Quiz</title>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Fredoka', sans-serif;
      background-color: #2e2373;
      color: white;
      margin: 0;
      padding: 20px;
      display: flex;
      justify-content: center;
    }
  
    .quiz-wrapper {
      width: 100%;
      max-width: 900px;
    }
  
    h2 {
      text-align: center;
      color: #ffffff;
    }
  
    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
      color: #ddd;
    }
  
    select,
    input[type="number"],
    input[type="text"] {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      margin-bottom: 10px;
      border-radius: 5px;
      border: none;
      background-color: #4b3b99;
      color: white;
      box-sizing: border-box;
    }
  
    select:focus,
    input:focus {
      outline: 2px solid #6f62d2;
    }
  
    button {
      padding: 10px 20px;
      background-color: #6f62d2;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.2s;
    }
  
    button:hover {
      background-color: #574bc1;
    }
  
    #quiz-form {
      margin-top: 20px;
    }
    
    /* Container for quiz type and number of questions */
    .input-container {
      display: flex;
      justify-content: space-between;
      gap: 20px; /* Space between the inputs */
      margin-bottom: 20px; /* Space after the inputs */
    }
    
    .input-container label,
    .input-container div {
      flex: 1; /* Make labels take equal space */
    }
    
    select, input[type="number"] {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      margin-bottom: 10px;
      border-radius: 5px;
      border: none;
      background-color: #4b3b99;
      color: white;
      box-sizing: border-box;
    }
    
    select:focus,
    input:focus {
      outline: 2px solid #6f62d2;
    }
    
    #quiz-container {
      display: grid;
      grid-template-columns: repeat(2, 1fr); /* Two columns */
      gap: 20px;
    }
    
    .question-container {
      background-color: #3b2f83;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
  
    .option-input {
      width: 100%;
      margin-top: 6px;
      padding: 8px;
      border-radius: 5px;
      border: none;
      background-color: #4b3b99;
      color: white;
    }
  
    input::placeholder {
      color: #bbb;
    }
  
    form button[type="submit"] {
      display: block;
      margin: 30px auto 0;
      width: 200px;
      background-color: #6f62d2;
    }
    
    .message {
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      text-align: center;
    }
    
    .success {
      background-color: #2d7e32;
    }
    
    .error {
      background-color: #c62828;
    }
    
    @media (max-width: 600px) {
      #quiz-container {
        grid-template-columns: 1fr;
      }
      
      .input-container {
        flex-direction: column;
        gap: 0;
      }
    }
  </style>
</head>
<body>
 <div class="quiz-wrapper">
  <h2>Edit Quiz</h2>

  <div class="input-container">
    <div>
      <label for="quiz-title">Quiz Title:</label>
      <input type="text" id="quiz-title" required>
    </div>
    
    <div>
    <label for="class-id">Class:</label>
    <input type="text" id="class-id" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>" />
</div>

  </div>

  <div class="input-container">
    <div>
      <label for="quiz-type">Quiz Type:</label>
      <select id="quiz-type">
        <option value="multiple">Multiple Choice</option>
        <option value="truefalse">True/False</option>
        <option value="identification">Identification</option>
        <option value="enumeration">Enumeration</option>
        <option value="checkbox">Checkbox</option>
      </select>
    </div>
    
    <div>
      <label for="num-questions">Number of Questions:</label>
      <input type="number" id="num-questions" min="1" max="50" value="5" />
    </div>
  </div>

  <button id="generate-btn">Create</button>

  <div id="message-container"></div>

  <form id="quiz-form">
    <div id="quiz-container"></div>
    <button type="submit">Save Quiz</button>
  </form>
</div>

<script>
  document.getElementById('generate-btn').addEventListener('click', function() {
    generateQuizEditor();
  });

  function generateQuizEditor(existingItems = null) {
    const container = document.getElementById('quiz-container');
    const type = document.getElementById('quiz-type').value;
    const num = parseInt(document.getElementById('num-questions').value) || 5;
    container.innerHTML = '';

    for (let i = 1; i <= num; i++) {
      const questionDiv = document.createElement('div');
      questionDiv.classList.add('question-container');

      const label = document.createElement('label');
      label.textContent = `Question ${i}: `;
      const input = document.createElement('input');
      input.type = 'text';
      input.name = `question_${i}`;
      input.required = true;
      
      // If editing, populate with existing data
      if (existingItems && existingItems[i-1]) {
        input.value = existingItems[i-1].question;
      }

      questionDiv.appendChild(label);
      questionDiv.appendChild(input);

      // Add fields based on type
      if (type === 'multiple') {
        for (let j = 1; j <= 4; j++) {
          const opt = document.createElement('input');
          opt.type = 'text';
          opt.className = 'option-input';
          opt.placeholder = `Option ${j}`;
          opt.name = `question_${i}_option_${j}`;
          opt.required = true;
          
          if (existingItems && existingItems[i-1] && existingItems[i-1].options) {
            opt.value = existingItems[i-1].options[j-1] || '';
          }
          
          questionDiv.appendChild(opt);
        }
        const answer = document.createElement('input');
        answer.type = 'text';
        answer.name = `answer_${i}`;
        answer.placeholder = 'Correct Option (e.g. Option 1)';
        answer.required = true;
        
        if (existingItems && existingItems[i-1]) {
          answer.value = existingItems[i-1].answer || '';
        }
        
        questionDiv.appendChild(answer);

      } else if (type === 'truefalse') {
        const select = document.createElement('select');
        select.name = `answer_${i}`;
        select.required = true;
        ['True', 'False'].forEach(val => {
          const opt = document.createElement('option');
          opt.value = val;
          opt.textContent = val;
          select.appendChild(opt);
        });
        
        if (existingItems && existingItems[i-1]) {
          select.value = existingItems[i-1].answer || 'True';
        }
        
        questionDiv.appendChild(select);

      } else if (type === 'identification') {
        const answer = document.createElement('input');
        answer.type = 'text';
        answer.name = `answer_${i}`;
        answer.placeholder = 'Correct Answer';
        answer.required = true;
        
        if (existingItems && existingItems[i-1]) {
          answer.value = existingItems[i-1].answer || '';
        }
        
        questionDiv.appendChild(answer);

      } else if (type === 'enumeration') {
        for (let j = 1; j <= 4; j++) {
          const enumInput = document.createElement('input');
          enumInput.type = 'text';
          enumInput.className = 'option-input';
          enumInput.placeholder = `Answer ${j}`;
          enumInput.name = `answer_${i}_enum_${j}`;
          enumInput.required = j === 1; // require at least 1
          
          if (existingItems && existingItems[i-1] && existingItems[i-1].answers) {
            enumInput.value = existingItems[i-1].answers[j-1] || '';
          }
          
          questionDiv.appendChild(enumInput);
        }

      } else if (type === 'checkbox') {
        for (let j = 1; j <= 4; j++) {
          const checkbox = document.createElement('input');
          checkbox.type = 'text';
          checkbox.className = 'option-input';
          checkbox.placeholder = `Option ${j}`;
          checkbox.name = `question_${i}_option_${j}`;
          checkbox.required = true;
          
          if (existingItems && existingItems[i-1] && existingItems[i-1].options) {
            checkbox.value = existingItems[i-1].options[j-1] || '';
          }
          
          questionDiv.appendChild(checkbox);
        }

        const correctBox = document.createElement('input');
        correctBox.type = 'text';
        correctBox.name = `answer_${i}`;
        correctBox.placeholder = 'Correct Options (e.g. Option 1, Option 3)';
        correctBox.required = true;
        
        if (existingItems && existingItems[i-1]) {
          correctBox.value = existingItems[i-1].answer || '';
        }
        
        questionDiv.appendChild(correctBox);
      }

      container.appendChild(questionDiv);
    }
  }

  // First, let's fix the form submission handler
document.getElementById('quiz-form').addEventListener('submit', function (e) {
  e.preventDefault();
  
  // Validate form
  const title = document.getElementById('quiz-title').value.trim();
  const classId = document.getElementById('class-id').value; // Fixed: Use class-id instead of class-select
  
  if (!title) {
    showMessage('Please enter a quiz title', 'error');
    return;
  }
  
  if (!classId) {
    showMessage('Please select a class', 'error');
    return;
  }
  
  // Collect form data
  const formData = new FormData(this);
  const quizType = document.getElementById('quiz-type').value;
  const num = parseInt(document.getElementById('num-questions').value);
  const quizQuestions = [];

  for (let i = 1; i <= num; i++) {
    const questionText = formData.get(`question_${i}`);
    
    if (!questionText || questionText.trim() === '') {
      showMessage(`Question ${i} cannot be empty`, 'error');
      return;
    }
    
    const q = {
      question: questionText,
      type: quizType,
    };

    if (quizType === 'multiple' || quizType === 'checkbox') {
      q.options = [];
      for (let j = 1; j <= 4; j++) {
        const optionText = formData.get(`question_${i}_option_${j}`);
        if (!optionText || optionText.trim() === '') {
          showMessage(`Option ${j} for Question ${i} cannot be empty`, 'error');
          return;
        }
        q.options.push(optionText);
      }
    }

    if (quizType === 'enumeration') {
      q.answers = [];
      for (let j = 1; j <= 4; j++) {
        const ans = formData.get(`answer_${i}_enum_${j}`);
        if (ans && ans.trim() !== '') {
          q.answers.push(ans);
        }
      }
      
      if (q.answers.length === 0) {
        showMessage(`At least one answer is required for enumeration Question ${i}`, 'error');
        return;
      }
    } else {
      const answer = formData.get(`answer_${i}`);
      if (!answer || answer.trim() === '') {
        showMessage(`Answer for Question ${i} cannot be empty`, 'error');
        return;
      }
      q.answer = answer;
    }

    quizQuestions.push(q);
  }

  // Get quiz ID if editing an existing quiz
  const urlParams = new URLSearchParams(window.location.search);
  const quizId = urlParams.get('quiz_id');
  
  // Prepare data to send
  const quizData = {
    title: title,
    class_id: classId,
    type: quizType,
    items: quizQuestions
  };
  
  if (quizId) {
    quizData.quiz_id = quizId;
  }

  console.log('Sending quiz data:', quizData); // Add this for debugging

  // Send to server
  fetch('savequiz.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(quizData)
  })
  .then(response => response.json())
  .then(result => {
    console.log('Server response:', result); // Add this for debugging
    if (result.status === 'success') {
      showMessage('Quiz saved successfully!', 'success');
      // Redirect to quiz list after 2 seconds
      setTimeout(() => {
        window.location.href = 'quizzes.php';
      }, 2000);
    } else {
      showMessage('Error: ' + result.message, 'error');
    }
  })
  .catch(error => {
    console.error('Error saving quiz:', error);
    showMessage('Failed to save quiz. Please try again.', 'error');
  });
});
  
  function showMessage(message, type) {
    const container = document.getElementById('message-container');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.textContent = message;
    
    // Clear existing messages
    container.innerHTML = '';
    container.appendChild(messageDiv);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
      if (container.contains(messageDiv)) {
        container.removeChild(messageDiv);
      }
    }, 5000);
  }
</script>

</body>
</html>