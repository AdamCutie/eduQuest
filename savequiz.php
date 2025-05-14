<?php
session_start();
header('Content-Type: application/json');
include 'db_connection.php'; // Make sure you include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Get the input data
$data = json_decode(file_get_contents('php://input'), true);

// Validate incoming data
if (empty($data['title']) || !isset($data['class_id']) || empty($data['type']) || empty($data['items'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields: ' . 
                     (empty($data['title']) ? 'title ' : '') . 
                     (!isset($data['class_id']) ? 'class_id ' : '') . 
                     (empty($data['type']) ? 'type ' : '') . 
                     (empty($data['items']) ? 'items' : '')]);
    exit;
}

$title = $data['title'];
$class_id = (int)$data['class_id'];
$type = $data['type'];
$items = $data['items'];

// Start transaction
$conn->begin_transaction();

try {
    // Check if we're updating an existing quiz
    if (isset($data['quiz_id'])) {
        // Update existing quiz
        $quiz_id = (int)$data['quiz_id'];
        
        // Update quiz details
        $stmt = $conn->prepare("UPDATE tbl_quizzes SET class_id = ?, title = ?, quiz_type = ? WHERE quiz_id = ? AND user_id= ?");
        $stmt->bind_param("issii", $class_id, $title, $type, $quiz_id, $_SESSION['user_id']);
        $stmt->execute();
        
        // If no rows were affected, the quiz might not exist or belong to this user
        if ($stmt->affected_rows == 0) {
            throw new Exception("Quiz not found or you don't have permission to edit it");
        }
        
        // Delete existing questions and answers for this quiz
        $stmt = $conn->prepare("DELETE q, a FROM tbl_quiz_questions q LEFT JOIN tbl_quiz_answers a ON q.question_id = a.question_id WHERE q.quiz_id = ?");
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
    } else {
        // Insert new quiz
        $stmt = $conn->prepare("INSERT INTO tbl_quizzes (class_id, title, quiz_type, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $class_id, $title, $type, $_SESSION['user_id']);
        $stmt->execute();
        $quiz_id = $conn->insert_id;  // Get the newly generated quiz_id
    }
    $stmt->close();

    // Insert questions
    foreach ($items as $index => $item) {
        $question = $item['question_text'];
        $question_type = $item['question_type'];

        // Insert question
        $stmt = $conn->prepare("INSERT INTO tbl_quiz_questions (quiz_id, question_text, question_type) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $quiz_id, $question, $question_type);
        $stmt->execute();
        $question_id = $conn->insert_id;  // Get the question_id
        $stmt->close();

        // Insert options or answers (based on question type)
        if ($question_type == 'multiple' || $question_type == 'checkbox') {
            // Insert options
            if (isset($item['options']) && is_array($item['options'])) {
                foreach ($item['options'] as $option) {
                    $stmt = $conn->prepare("INSERT INTO tbl_quiz_answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
                    
                    // Determine if this option is correct
                    $is_correct = 0;
                    if ($question_type == 'multiple') {
                        // For multiple choice, check if this option matches the correct answer
                        $is_correct = (isset($item['answer']) && 
                                     ("Option " . (array_search($option, $item['options']) + 1) == $item['answer'])) ? 1 : 0;
                    } else if ($question_type == 'checkbox') {
                        // For checkbox, split the correct answers and check if this option is in the list
                        if (isset($item['answer'])) {
                            $correct_options = explode(', ', $item['answer']);
                            $option_number = "Option " . (array_search($option, $item['options']) + 1);
                            $is_correct = in_array($option_number, $correct_options) ? 1 : 0;
                        }
                    }
                    
                    $stmt->bind_param("isi", $question_id, $option, $is_correct);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        } elseif ($question_type == 'enumeration') {
            // Insert enumeration answers
            if (isset($item['answers']) && is_array($item['answers'])) {
                foreach ($item['answers'] as $answer) {
                    if (!empty($answer)) {
                        $stmt = $conn->prepare("INSERT INTO tbl_quiz_answers (question_id, answer_text, is_correct) VALUES (?, ?, 1)");
                        $stmt->bind_param("is", $question_id, $answer);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        } else {
            // For identification and true/false
            if (isset($item['answer'])) {
                $answer = $item['answer'];
                $stmt = $conn->prepare("INSERT INTO tbl_quiz_answers (question_id, answer_text, is_correct) VALUES (?, ?, 1)");
                $stmt->bind_param("is", $question_id, $answer);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    // Commit the transaction
    $conn->commit();
    echo json_encode(['status' => 'success', 'message' => 'Quiz saved successfully', 'quiz_id' => $quiz_id]);
    
} catch (Exception $e) {
    // Roll back the transaction in case of error
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>