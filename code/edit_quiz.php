<?php
session_start();
if (!isset($_SESSION["instructorloggedin"]) || $_SESSION["instructorloggedin"] !== true) {
    header("location: instructorlogin.php");
    exit;
}

include "database.php";

$quiz_id_to_edit = null;
$quiz_data = null;
$feedback_message = '';
$page_title = "Edit Quiz";

if (isset($_GET['quiz_id'])) {
    $quiz_id_to_edit = intval($_GET['quiz_id']);
    $stmt = $conn->prepare("SELECT * FROM quizconfig WHERE quiznumber = ?");
    $stmt->bind_param("i", $quiz_id_to_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $quiz_data = $result->fetch_assoc();
    } else {
        $_SESSION['error_message'] = "Quiz not found.";
        header("Location: manage_quizzes.php");
        exit;
    }
    $stmt->close();
} else if (!isset($_POST['quiz_id_to_edit'])) { // If not GET and not POST with id, redirect
    $_SESSION['error_message'] = "No quiz ID provided.";
    header("Location: manage_quizzes.php");
    exit;
}

// Fetch subjects for dropdown
$subjects = [];
$sql_subjects = "SELECT subject_id, subject_name FROM subjects ORDER BY subject_name ASC";
$result_subjects = $conn->query($sql_subjects);
if ($result_subjects && $result_subjects->num_rows > 0) {
    while ($row_subject = $result_subjects->fetch_assoc()) {
        $subjects[] = $row_subject;
    }
}

// Fetch classes for dropdown
$classes = [];
$sql_classes = "SELECT class_id, class_name FROM classes ORDER BY class_name ASC";
$result_classes = $conn->query($sql_classes);
if ($result_classes && $result_classes->num_rows > 0) {
    while ($row_class = $result_classes->fetch_assoc()) {
        $classes[] = $row_class;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quiz_id_to_edit'])) {
    $quiz_id_to_edit = intval($_POST['quiz_id_to_edit']); // Get ID from hidden field

    // Fetch existing data again to be sure, or use $quiz_data if already fetched and this is the same request context
    // If $quiz_data is not set (e.g. direct POST attempt or refresh after error), re-fetch.
    if (!$quiz_data && $quiz_id_to_edit) {
        $stmt_refetch = $conn->prepare("SELECT * FROM quizconfig WHERE quiznumber = ?");
        $stmt_refetch->bind_param("i", $quiz_id_to_edit);
        $stmt_refetch->execute();
        $result_refetch = $stmt_refetch->get_result();
        if ($result_refetch->num_rows > 0) {
            $quiz_data = $result_refetch->fetch_assoc(); // Update $quiz_data with current db state before applying POST
        }
        $stmt_refetch->close();
    }

    // Validate chapter selection
    $selected_chapters_value = NULL;
    if(isset($_POST['chapter_id'])){
        if(is_array($_POST['chapter_id'])){
            $filtered_chapters = array_filter($_POST['chapter_id'], function($value) { 
                return $value !== 'all' && $value !== '' && is_numeric($value); 
            });
            if(!empty($filtered_chapters)){
                // Make sure all values are integers
                $filtered_chapters = array_map('intval', $filtered_chapters);
                $selected_chapters_value = implode(',', $filtered_chapters);
            } else if (in_array('all', $_POST['chapter_id'])){
                $selected_chapters_value = 'all';
            }
        } else if ($_POST['chapter_id'] === 'all'){
            $selected_chapters_value = 'all';
        } else if(is_numeric($_POST['chapter_id'])){
            $selected_chapters_value = intval($_POST['chapter_id']);
        }
    }

    // If no chapters are selected despite the field being present, set as 'all'
    if (empty($selected_chapters_value) && isset($_POST['chapter_id'])) {
        $selected_chapters_value = 'all';
    }

    if (empty($selected_chapters_value) && $selected_chapters_value !== 'all') {
        $feedback_message = '<p class="h6 text-center" style="color:red;">Error: Please select at least one chapter for the quiz.</p>';
    } else {
        $typeamarks = intval($_POST["typeamarks"]);
        $typea = intval($_POST["typea"]);
        $typebmarks = intval($_POST["typebmarks"]);
        $typeb = intval($_POST["typeb"]);
        $typecmarks = intval($_POST["typecmarks"]);
        $typec = intval($_POST["typec"]);
        $typedmarks = intval($_POST["typedmarks"]);
        $typed = intval($_POST["typed"]);
        $typeemarks = intval($_POST["typeemarks"]);
        $typee = intval($_POST["typee"]);
        $typefmarks = intval($_POST["typefmarks"]);
        $typef = intval($_POST["typef"]);
        $maxmarks = $typeamarks * $typea + $typebmarks * $typeb + $typecmarks * $typec + $typedmarks * $typed + $typeemarks * $typee + $typefmarks * $typef;
        
        $duration = intval($_POST["duration"]);
        $starttime = $_POST["starttime"]; // Format expected: DD/MM/YYYY hh:mm A
        $endtime = isset($_POST["endtime"]) ? $_POST["endtime"] : ''; // Format expected: DD/MM/YYYY hh:mm A
        
        // Debug the input datetime value
        error_log("EDIT_QUIZ RAW STARTTIME: " . $starttime);
        error_log("EDIT_QUIZ RAW ENDTIME: " . $endtime);
        
        // Convert date format from DD/MM/YYYY hh:mm A to YYYY-MM-DD HH:MM:SS for database
        if (!empty($starttime)) {
            // First try the expected format
            $dateObj = DateTime::createFromFormat('d/m/Y h:i A', $starttime);
            
            // If that fails, try other common formats
            if (!$dateObj) {
                $dateObj = DateTime::createFromFormat('d/m/Y H:i', $starttime);
            }
            if (!$dateObj) {
                $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $starttime);
            }
            if (!$dateObj) {
                $dateObj = DateTime::createFromFormat('Y-m-d h:i A', $starttime);
            }
            
            // If we have a valid date object, format it for the database
            if ($dateObj) {
                $starttime = $dateObj->format('Y-m-d H:i:s');
                error_log("EDIT_QUIZ CONVERTED STARTTIME: " . $starttime);
            } else {
                // If all parsing attempts failed, log the error and use a default
                error_log("EDIT_QUIZ ERROR: Failed to parse date: " . $starttime . ". DateTime errors: " . print_r(DateTime::getLastErrors(), true));
                
                // Set a valid default date/time
                $starttime = date('Y-m-d H:i:s');
                error_log("EDIT_QUIZ USING DEFAULT STARTTIME: " . $starttime);
            }
        } else {
            // If starttime is empty, use current date/time
            $starttime = date('Y-m-d H:i:s');
            error_log("EDIT_QUIZ EMPTY STARTTIME, USING CURRENT: " . $starttime);
        }

        // Process end time
        $endtime_sql = "NULL";
        if (!empty($endtime)) {
            // First try the expected format
            $dateObj = DateTime::createFromFormat('d/m/Y h:i A', $endtime);
            
            // If that fails, try other common formats
            if (!$dateObj) {
                $dateObj = DateTime::createFromFormat('d/m/Y H:i', $endtime);
            }
            if (!$dateObj) {
                $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $endtime);
            }
            if (!$dateObj) {
                $dateObj = DateTime::createFromFormat('Y-m-d h:i A', $endtime);
            }
            
            // If we have a valid date object, format it for the database
            if ($dateObj) {
                $endtime = $dateObj->format('Y-m-d H:i:s');
                $endtime_sql = "'" . $conn->real_escape_string($endtime) . "'";
                error_log("EDIT_QUIZ CONVERTED ENDTIME: " . $endtime);
            } else {
                // If all parsing attempts failed, calculate based on duration
                error_log("EDIT_QUIZ ERROR: Failed to parse end date: " . $endtime . ". DateTime errors: " . print_r(DateTime::getLastErrors(), true));
                
                // Calculate end time based on start time + duration
                $endtime = date('Y-m-d H:i:s', strtotime($starttime . ' +' . $duration . ' minutes'));
                $endtime_sql = "'" . $conn->real_escape_string($endtime) . "'";
                error_log("EDIT_QUIZ CALCULATED ENDTIME FROM DURATION: " . $endtime);
            }
        } else {
            // If endtime is empty, calculate based on start time + duration
            $endtime = date('Y-m-d H:i:s', strtotime($starttime . ' +' . $duration . ' minutes'));
            $endtime_sql = "'" . $conn->real_escape_string($endtime) . "'";
            error_log("EDIT_QUIZ EMPTY ENDTIME, CALCULATED FROM DURATION: " . $endtime);
        }
        
        // Escape the date string properly for SQL
        $starttime_sql = "'" . $conn->real_escape_string($starttime) . "'";
        error_log("EDIT_QUIZ ESCAPED STARTTIME FOR SQL: " . $starttime_sql);
        error_log("EDIT_QUIZ ESCAPED ENDTIME FOR SQL: " . $endtime_sql);
        
        $subject_id = !empty($_POST["subject_id"]) ? intval($_POST["subject_id"]) : NULL;
        $class_id = !empty($_POST["class_id"]) ? intval($_POST["class_id"]) : NULL;
        $total_questions = isset($_POST["total_questions"]) ? intval($_POST["total_questions"]) : 10;
        $random_quiz = isset($_POST["random_quiz"]) ? 1 : 0;
        $quiz_name = isset($_POST["quizname"]) ? $conn->real_escape_string(trim($_POST["quizname"])) : ($quiz_data['quizname'] ?? 'Quiz');
        $attempts = isset($_POST["attempts"]) ? intval($_POST["attempts"]) : ($quiz_data['attempts'] ?? 1);
        $section = !empty($_POST['section']) ? $conn->real_escape_string(trim($_POST['section'])) : NULL;

        // Make sure all integer values are properly converted to integers
        $attempts = intval($attempts);
        $subject_id = $subject_id !== NULL ? intval($subject_id) : NULL;
        $class_id = $class_id !== NULL ? intval($class_id) : NULL;
        $duration = intval($duration);
        $maxmarks = intval($maxmarks);
        $typea = intval($typea);
        $typeamarks = intval($typeamarks);
        $typeb = intval($typeb);
        $typebmarks = intval($typebmarks);
        $typec = intval($typec);
        $typecmarks = intval($typecmarks);
        $typed = intval($typed);
        $typedmarks = intval($typedmarks);
        $typee = intval($typee);
        $typeemarks = intval($typeemarks);
        $typef = intval($typef);
        $typefmarks = intval($typefmarks);
        $total_questions = intval($total_questions);
        $random_quiz = intval($random_quiz);
        $quiz_id_to_edit = intval($quiz_id_to_edit);
        
        // Properly escape string values for SQL
        $quiz_name_sql = "'" . $conn->real_escape_string($quiz_name) . "'";
        $starttime_sql = "'" . $conn->real_escape_string($starttime) . "'";
        $selected_chapters_sql = "'" . $conn->real_escape_string($selected_chapters_value) . "'";
        $section_sql = $section !== NULL ? "'" . $conn->real_escape_string($section) . "'" : "NULL";
        $subject_id_sql = $subject_id !== NULL ? $subject_id : "NULL";
        $class_id_sql = $class_id !== NULL ? $class_id : "NULL";
        
        // Add debug logging to verify parameter values before binding
        error_log("EDIT_QUIZ DEBUG: Data for quiz #" . $quiz_id_to_edit);
        error_log("quiz_name: " . $quiz_name);
        error_log("attempts: " . $attempts);
        error_log("subject_id: " . ($subject_id ?? 'NULL'));
        error_log("class_id: " . ($class_id ?? 'NULL'));
        error_log("starttime: " . $starttime);
        error_log("starttime_sql: " . $starttime_sql);
        error_log("duration: " . $duration);
        error_log("maxmarks: " . $maxmarks);
        error_log("typea: " . $typea . ", typeamarks: " . $typeamarks);
        error_log("typeb: " . $typeb . ", typebmarks: " . $typebmarks);
        error_log("typec: " . $typec . ", typecmarks: " . $typecmarks);
        error_log("typed: " . $typed . ", typedmarks: " . $typedmarks);
        error_log("typee: " . $typee . ", typeemarks: " . $typeemarks);
        error_log("typef: " . $typef . ", typefmarks: " . $typefmarks);
        error_log("total_questions: " . $total_questions);
        error_log("is_random: " . $random_quiz);
        error_log("chapter_ids: " . $selected_chapters_value);
        error_log("section: " . ($section ?? 'NULL'));
        
        // Build a direct SQL query instead of using prepared statements
        $sql_update = "UPDATE quizconfig SET 
                quizname = $quiz_name_sql, 
                attempts = $attempts, 
                subject_id = $subject_id_sql, 
                class_id = $class_id_sql, 
                starttime = $starttime_sql,
                endtime = $endtime_sql, 
                duration = $duration, 
                maxmarks = $maxmarks,
                typea = $typea, 
                typeamarks = $typeamarks, 
                typeb = $typeb, 
                typebmarks = $typebmarks, 
                typec = $typec, 
                typecmarks = $typecmarks,
                typed = $typed, 
                typedmarks = $typedmarks, 
                typee = $typee, 
                typeemarks = $typeemarks, 
                typef = $typef, 
                typefmarks = $typefmarks,
                total_questions = $total_questions, 
                is_random = $random_quiz, 
                chapter_ids = $selected_chapters_sql, 
                section = $section_sql
            WHERE quiznumber = $quiz_id_to_edit";
        
        error_log("EDIT_QUIZ DIRECT SQL: " . $sql_update);
        
        try {
            // Execute the SQL directly
            if ($conn->query($sql_update)) {
                $feedback_message = '<p class="h6 text-center" style="color:green;">Quiz #' . $quiz_id_to_edit . ' updated successfully!</p>';
                // Refresh $quiz_data with new values
                $stmt_refresh = $conn->prepare("SELECT * FROM quizconfig WHERE quiznumber = ?");
                $stmt_refresh->bind_param("i", $quiz_id_to_edit);
                $stmt_refresh->execute();
                $result_refresh = $stmt_refresh->get_result();
                $quiz_data = $result_refresh->fetch_assoc();
                $stmt_refresh->close();
                echo '<script>setTimeout(function(){ window.location.href = "manage_quizzes.php"; }, 2000);</script>'; // Redirect after 2 seconds
            } else {
                $feedback_message = '<p class="h6 text-center" style="color:red;">Error updating quiz: ' . $conn->error . '</p>';
                error_log("EDIT_QUIZ ERROR (execute): " . $conn->error);
            }
        } catch (Exception $e) {
            $feedback_message = '<p class="h6 text-center" style="color:red;">Error updating quiz: ' . $e->getMessage() . '</p>';
            error_log("EDIT_QUIZ EXCEPTION: " . $e->getMessage());
            error_log("EDIT_QUIZ STACK TRACE: " . $e->getTraceAsString());
        }
    }
}

// Default values if $quiz_data is not set (e.g. new quiz, though this page is for edit)
// Or if some fields are nullable and not set in DB, provide defaults for form display.
$q_num = $quiz_data['quiznumber'] ?? $quiz_id_to_edit ?? '';
$q_duration = $quiz_data['duration'] ?? 10;
$q_starttime = $quiz_data['starttime'] ?? '01/01/2024 10:00 AM'; // Default format

// Convert DB format to display format if needed
if (!empty($q_starttime)) {
    // If it's in YYYY-MM-DD HH:MM:SS format, convert to DD/MM/YYYY hh:mm A
    $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $q_starttime);
    if ($dateObj) {
        $q_starttime = $dateObj->format('d/m/Y h:i A');
    }
}

$q_subject_id = $quiz_data['subject_id'] ?? '';
$q_class_id = $quiz_data['class_id'] ?? '';
$q_chapter_ids_str = $quiz_data['chapter_ids'] ?? '';
$q_total_questions = $quiz_data['total_questions'] ?? 10;
$q_is_random = $quiz_data['is_random'] ?? 0;
$q_section = $quiz_data['section'] ?? '';

// Fetch pre-selected chapter names for display
$preselected_chapters = [];
if (!empty($q_chapter_ids_str) && $q_chapter_ids_str !== 'all') {
    $chapter_ids_array = explode(',', $q_chapter_ids_str);
    $chapter_ids_placeholders = str_repeat('?,', count($chapter_ids_array) - 1) . '?';
    $stmt_chapters = $conn->prepare("SELECT chapter_id, chapter_name FROM chapters WHERE chapter_id IN ($chapter_ids_placeholders)");
    $param_types = str_repeat('i', count($chapter_ids_array));
    $stmt_chapters->bind_param($param_types, ...$chapter_ids_array);
    $stmt_chapters->execute();
    $result_chapters = $stmt_chapters->get_result();
    while ($row_chapter = $result_chapters->fetch_assoc()) {
        $preselected_chapters[$row_chapter['chapter_id']] = $row_chapter['chapter_name'];
    }
    $stmt_chapters->close();
}

$q_typea = $quiz_data['typea'] ?? 0;
$q_typeamarks = $quiz_data['typeamarks'] ?? 0;
$q_typeb = $quiz_data['typeb'] ?? 0;
$q_typebmarks = $quiz_data['typebmarks'] ?? 0;
$q_typec = $quiz_data['typec'] ?? 0;
$q_typecmarks = $quiz_data['typecmarks'] ?? 0;
$q_typed = $quiz_data['typed'] ?? 0;
$q_typedmarks = $quiz_data['typedmarks'] ?? 0;
$q_typee = $quiz_data['typee'] ?? 0;
$q_typeemarks = $quiz_data['typeemarks'] ?? 0;
$q_typef = $quiz_data['typef'] ?? 0;
$q_typefmarks = $quiz_data['typefmarks'] ?? 0;

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title><?php echo $page_title; ?> - Quiz Portal</title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <link href="./assets/css/material-kit.css?v=2.0.4" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <style>
    /* Fixed Navbar Styles */
    .navbar {
        background-color: #fff !important;
        box-shadow: 0 4px 18px 0px rgba(0, 0, 0, 0.12), 0 7px 10px -5px rgba(0, 0, 0, 0.15);
        z-index: 1040;
    }
    
    .navbar-brand {
        color: #555 !important;
        font-weight: 500;
        font-size: 1.25rem;
    }
    
    .navbar-toggler {
        border: none;
        background: transparent !important;
    }
    
    .navbar-toggler-icon {
        background-color: #555;
        width: 22px;
        height: 2px;
        margin: 4px 0;
        display: block;
        transition: all 0.2s;
    }
    
    .navbar .navbar-nav {
        align-items: center;
    }
    
    .navbar .nav-item {
        margin: 0 0.3rem;
    }
    
    .navbar.scrolled {
        background-color: #fff !important;
        box-shadow: 0 4px 18px 0px rgba(0, 0, 0, 0.12), 0 7px 10px -5px rgba(0, 0, 0, 0.15);
    }
    
    .navbar.scrolled .nav-link {
        color: #555 !important;
    }
    
    .navbar.scrolled .navbar-brand {
        color: #555 !important;
    }
    
    .page-header {
        background-color: #f5f5f5;
        background-size: cover;
        margin: 0;
        padding: 0;
        border: 0;
        min-height: auto;
        height: auto;
        padding-top: 90px; /* Add space for fixed navbar */
    }
    
    /* For better spacing */
    .form-row-mobile {
        margin-bottom: 15px;
    }
    
    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .mobile-text-center {
            text-align: center !important;
        }
        
        .mobile-full-width {
            width: 100% !important;
        }
        
        .form-row-mobile {
            margin-bottom: 20px;
        }
        
        .navbar .nav-link {
            padding: 0.5rem 0;
        }
        
        .page-header {
            padding-top: 60px;
        }
        
        .card-body {
            padding: 15px 10px;
        }
    }
  </style>
  <script>
    function marks() {
        var xa = parseInt(document.getElementById("typea").value) || 0;
        var ya = parseInt(document.getElementById("typeamarks").value) || 0;
        var ta = xa * ya;  
        document.getElementById("totala").innerHTML = ta;
        
        var xb = parseInt(document.getElementById("typeb").value) || 0;
        var yb = parseInt(document.getElementById("typebmarks").value) || 0; 
        var tb = xb * yb;     
        document.getElementById("totalb").innerHTML = tb;
        
        var xc = parseInt(document.getElementById("typec").value) || 0;
        var yc = parseInt(document.getElementById("typecmarks").value) || 0; 
        var tc = xc * yc;     
        document.getElementById("totalc").innerHTML = tc;
        
        var xd = parseInt(document.getElementById("typed").value) || 0;
        var yd = parseInt(document.getElementById("typedmarks").value) || 0; 
        var td = xd * yd;
        document.getElementById("totald").innerHTML = td;
        
        var xe = parseInt(document.getElementById("typee").value) || 0;
        var ye = parseInt(document.getElementById("typeemarks").value) || 0;
        var te = xe * ye;
        document.getElementById("totale").innerHTML = te;
        
        var xf = parseInt(document.getElementById("typef").value) || 0;
        var yf = parseInt(document.getElementById("typefmarks").value) || 0;
        var tf = xf * yf;     
        document.getElementById("totalf").innerHTML = tf;
        
        var totalMarks = ta + tb + tc + td + te + tf;
        document.getElementById("total").innerHTML = totalMarks;
    }
    
    // Add form validation function
    function validateQuizForm() {
        // Validate date field
        var starttimeField = document.getElementById('starttime');
        var starttime = starttimeField.value;
        
        if (!starttime || starttime.trim() === '') {
            alert('Please select a start date and time for the quiz');
            starttimeField.focus();
            return false;
        }
        
        // Try to parse the date to make sure it's valid
        var dateParts = starttime.match(/(\d+)\/(\d+)\/(\d+)\s+(\d+):(\d+)\s+([AP]M)/i);
        if (!dateParts) {
            alert('The date format should be DD/MM/YYYY hh:mm AM/PM. Please use the date picker.');
            starttimeField.focus();
            return false;
        }
        
        // Make sure at least one chapter is selected
        var chapterField = document.getElementById('chapter_id');
        if (chapterField && chapterField.selectedOptions.length === 0) {
            alert('Please select at least one chapter or "All Chapters"');
            return false;
        }
        
        return true;
    }
  </script>
</head>
<body class="login-page sidebar-collapse">
  <nav class="navbar fixed-top navbar-expand-lg">
    <div class="container">
      <div class="navbar-translate">
        <a class="navbar-brand" href="instructorhome.php">Quiz Portal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="sr-only">Toggle navigation</span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a href="manage_classes_subjects.php" class="nav-link">
              <i class="material-icons">school</i> Manage Classes & Subjects
            </a>
          </li>
          <li class="nav-item">
            <a href="questionfeed.php" class="nav-link">
              <i class="material-icons">input</i> Feed Questions
            </a>
          </li>
          <li class="nav-item">
            <a href="view_questions.php" class="nav-link">
              <i class="material-icons">list_alt</i> Questions Bank
            </a>
          </li>
          <li class="nav-item">
            <a href="quizconfig.php" class="nav-link">
              <i class="material-icons">layers</i> Set Quiz
            </a>
          </li>
          <li class="nav-item">
            <a href="manage_quizzes.php" class="nav-link">
              <i class="material-icons">settings</i> Manage Quizzes
            </a>
          </li>
          <li class="nav-item">
            <a href="view_quiz_results.php" class="nav-link">
              <i class="material-icons">assessment</i> View Results
            </a>
          </li>
          <li class="nav-item">
            <a href="manage_instructors.php" class="nav-link">
              <i class="material-icons">people</i> Manage Instructors
            </a>
          </li>
          <li class="nav-item">
            <a href="manage_students.php" class="nav-link">
              <i class="material-icons">group</i> Manage Students
            </a>
          </li>
          <li class="nav-item">
            <a href="my_profile.php" class="nav-link">
              <i class="material-icons">person</i> My Profile
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" rel="tooltip" title="Logout" data-placement="bottom" href="instructorlogout.php">
              <i class="material-icons">power_settings_new</i> Log Out
            </a>
          </li>
        </ul>
      </div>      
    </div>
  </nav>
  <div class="page-header">
    <div class="container">
      <div class="row justify-content-center" style="margin-top: 20px">
        <div class="col-lg-9 col-md-9 ml-auto mr-auto" >
          <div class="card card-login" >
            <form class="form" name="editQuizForm" action="edit_quiz.php?quiz_id=<?php echo $quiz_id_to_edit; ?>" method="post" onsubmit="return validateQuizForm()">
              <input type="hidden" name="quiz_id_to_edit" value="<?php echo $quiz_id_to_edit; ?>">
              <div class="card-header card-header-primary text-center">
                <h4 class="card-title"><?php echo $page_title; ?> #<?php echo htmlspecialchars($q_num); ?></h4>
              </div>
              <?php if(!empty($feedback_message)) echo $feedback_message; ?>
              <p class="description text-center">Update the details of the quiz.</p>
              <div class="card-body" style="padding-left: 20px;padding-right: 20px">
                <div class="row">
                  <div class="col">                   
                    <p class="h5 text-center" >Quiz Number</p>
                  </div>
                  <div class="col">
                    <input type="number" name="quiznumber_display" id="quiznumber_display" class="form-control text-center" value="<?php echo htmlspecialchars($q_num); ?>" readonly>
                  </div>
                  <div class="col">
                    <p class="h5 text-center">Duration (mins)</p>
                  </div>
                  <div class="col">
                    <input type="number" min="0" name="duration" class="form-control text-center" value="<?php echo htmlspecialchars($q_duration); ?>">
                  </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                  <div class="col-md-3">
                    <p class="h5 text-center">Quiz Name</p>
                  </div>
                  <div class="col-md-9">
                    <input type="text" name="quizname" class="form-control" value="<?php echo htmlspecialchars($quiz_data['quizname'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                  <div class="col-md-3">
                    <p class="h5 text-center">Attempts</p>
                  </div>
                  <div class="col-md-9">
                    <input type="number" min="1" name="attempts" class="form-control" value="<?php echo htmlspecialchars($quiz_data['attempts'] ?? 1); ?>" required>
                  </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                  <div class="col-md-3">
                    <p class="h5 text-center">Subject</p>
                  </div>
                  <div class="col-md-9">
                    <select name="subject_id" id="subject_id" class="form-control" onchange="loadChapters()">
                        <option value="">Select Subject</option>
                        <?php foreach($subjects as $subject): ?>
                            <option value="<?php echo $subject['subject_id']; ?>" <?php if($q_subject_id == $subject['subject_id']) echo 'selected'; ?> >
                                <?php echo htmlspecialchars($subject['subject_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                  <div class="col-md-3">
                    <p class="h5 text-center">Class</p>
                  </div>
                  <div class="col-md-9">
                    <select name="class_id" id="class_id" class="form-control">
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class_item): ?>
                            <option value="<?php echo htmlspecialchars($class_item['class_id']); ?>" <?php if($class_item['class_id'] == $q_class_id) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($class_item['class_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                  <div class="col-md-3">
                    <p class="h5 text-center">Chapters</p>
                  </div>
                  <div class="col-md-9">
                    <select name="chapter_id[]" id="chapter_id" class="form-control select2" multiple>
                        <option value="" disabled>Select Class and Subject first</option>
                        <option value="all" <?php if ($q_chapter_ids_str === 'all') echo 'selected'; ?>>All Chapters</option>
                        <?php if (!empty($preselected_chapters)): ?>
                            <?php foreach ($preselected_chapters as $chapter_id => $chapter_name): ?>
                                <option value="<?php echo $chapter_id; ?>" selected><?php echo htmlspecialchars($chapter_name); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <!-- Additional chapters will be loaded by AJAX -->
                    </select>
                    <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple chapters. Select 'All Chapters' to include all.</small>
                  </div>
                </div>
                 <div class="row" style="margin-top: 15px;">
                    <div class="col-md-3">
                        <p class="h5 text-center">Total Questions</p>
                    </div>
                    <div class="col-md-3">
                        <input type="number" min="1" name="total_questions" id="total_questions" class="form-control text-center" value="<?php echo htmlspecialchars($q_total_questions); ?>">
                    </div>
                    <div class="col-md-3">
                        <p class="h5 text-center">Randomize Quiz?</p>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="random_quiz" value="1" <?php if($q_is_random) echo 'checked'; ?>>
                                Yes
                                <span class="form-check-sign"><span class="check"></span></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="section" class="bmd-label-floating">Section (Optional)</label>
                    <input type="text" name="section" id="section" class="form-control" placeholder="Leave blank for all sections" value="<?php echo htmlspecialchars($q_section); ?>">
                  </div>
                </div>
              </div>
              <div class="card-body row form-group" style="padding-left: 20px;padding-right: 20px">
                <div class="col">
                  <p class="h5">Start Date and Time for the quiz:</p>
                </div>
                <div class="col">
                  <input type="text" class="form-control datetimepicker" id="starttime" name="starttime" 
                         value="<?php echo htmlspecialchars($q_starttime); ?>" placeholder="DD/MM/YYYY hh:mm AM" 
                         data-date-format="DD/MM/YYYY hh:mm A" autocomplete="off"/> 
                  <small class="form-text text-muted">Format: DD/MM/YYYY hh:mm AM/PM - use the calendar icon to select</small>
                </div>       
              </div>

              <div class="card-body row form-group" style="padding-left: 20px;padding-right: 20px">
                <div class="col">
                  <p class="h5">End Date and Time for the quiz:</p>
                </div>
                <div class="col">
                  <input type="text" class="form-control datetimepicker" id="endtime" name="endtime" 
                         value="<?php echo isset($quiz_data['endtime']) ? date('d/m/Y h:i A', strtotime($quiz_data['endtime'])) : date('d/m/Y h:i A', strtotime('+1 day')); ?>" 
                         placeholder="DD/MM/YYYY hh:mm AM" 
                         data-date-format="DD/MM/YYYY hh:mm A" autocomplete="off"/> 
                  <small class="form-text text-muted">Quiz will no longer be available after this time. Students who start the quiz before this time will still get the full duration.</small>
                </div>       
              </div>

              <div class="card-body" style="padding-left: 20px;padding-right: 20px">
                <div class="row">
                  <div class="col"><p class="h5 text-center">Type</p></div>
                  <div class="col"><p class="h5 text-center">Number</p></div>
                  <div class="col"><p class="h5 text-center">Marks for Each</p></div>
                  <div class="col"><p class="h5 text-center" style="font-style: italic;">Total</p></div>
                </div>
                <!-- Type A: MCQ -->
                <div class="row">
                  <div class="col"><p class="h6">MCQ :</p></div>
                  <div class="col"><input type="number" min="0" class="form-control text-center" name="typea" id="typea" value="<?php echo htmlspecialchars($q_typea); ?>" oninput="marks()"></div>
                  <div class="col"><input type="number" min="0" class="form-control text-center" name="typeamarks" id="typeamarks" value="<?php echo htmlspecialchars($q_typeamarks); ?>" oninput="marks()"></div>
                  <div class="col"><p class="text-center" id="totala" style="margin-top:15px;font-weight: bold;"></p></div>
                </div>
                <!-- Type B: Numerical -->
                <div class="row">
                  <div class="col"><p class="h6">Numerical :</p></div>
                  <div class="col"><input type="number" min="0" class="form-control text-center" name="typeb" id="typeb" value="<?php echo htmlspecialchars($q_typeb); ?>" oninput="marks()"></div>
                  <div class="col"><input type="number" min="0" class="form-control text-center" name="typebmarks" id="typebmarks" value="<?php echo htmlspecialchars($q_typebmarks); ?>" oninput="marks()"></div>
                  <div class="col"><p class="text-center" id="totalb" style="margin-top:15px;font-weight: bold;"></p></div>
                </div>
                <!-- Type C: Drop Down -->
                 <div class="row">
                  <div class="col"><p class="h6">Drop Down :</p></div>
                  <div class="col"><input type="number" min="0" class="form-control text-center" name="typec" id="typec" value="<?php echo htmlspecialchars($q_typec); ?>" oninput="marks()"></div>
                  <div class="col"><input type="number" min="0" class="form-control text-center" name="typecmarks" id="typecmarks" value="<?php echo htmlspecialchars($q_typecmarks); ?>" oninput="marks()"></div>
                  <div class="col"><p class="text-center" id="totalc" style="margin-top:15px;font-weight: bold;"></p></div>
                </div>
                <!-- Type D: Fill in the Blanks -->
                <div class="row">
                  <div class="col"><p class="h6">Fill in the Blanks :</p></div>
                  <div class="col"><input type="number" min="0" class="form-control text-center" name="typed" id="typed" value="<?php echo htmlspecialchars($q_typed); ?>" oninput="marks()"></div>
                  <div class="col"><input type="number" min="0" class="form-control text-center" name="typedmarks" id="typedmarks" value="<?php echo htmlspecialchars($q_typedmarks); ?>" oninput="marks()"></div>
                  <div class="col"><p class="text-center" id="totald" style="margin-top:15px;font-weight: bold;"></p></div>
                </div>
                <!-- Type E: Short Answer -->
                <div class="row">
                  <div class="col"><p class="h6">Short Answer :</p></div>
                  <div class="col"><input type="number" min="0" class="form-control text-center" name="typee" id="typee" value="<?php echo htmlspecialchars($q_typee); ?>" oninput="marks()"></div>
                  <div class="col"><input type="number" min="0" class="form-control text-center" name="typeemarks" id="typeemarks" value="<?php echo htmlspecialchars($q_typeemarks); ?>" oninput="marks()"></div>
                  <div class="col"><p class="text-center" id="totale" style="margin-top:15px;font-weight: bold;"></p></div>
                </div>
                <!-- Type F: Essay -->
                 <div class="row">
                  <div class="col"><p class="h6">Essay :</p></div>
                  <div class="col"><input type="number" min="0" class="form-control text-center" name="typef" id="typef" value="<?php echo htmlspecialchars($q_typef); ?>" oninput="marks()"></div>
                  <div class="col"><input type="number" min="0" class="form-control text-center" name="typefmarks" id="typefmarks" value="<?php echo htmlspecialchars($q_typefmarks); ?>" oninput="marks()"></div>
                  <div class="col"><p class="text-center" id="totalf" style="margin-top:15px;font-weight: bold;"></p></div>
                </div>
                <hr>
                <div class="row">
                  <div class="col"></div>
                  <div class="col"></div>
                  <div class="col"><p class="h5 text-center">Grand Total</p></div>
                  <div class="col"><p class="text-center" id="total" style="margin-top:15px;font-weight: bold;"></p></div>
                </div>
              </div>
              <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary btn-wd btn-lg">Update Quiz</button>
                <a href="manage_quizzes.php" class="btn btn-danger btn-wd btn-lg">Cancel</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <footer class="footer">
      <div class="container">
        <div class="copyright float-right">
          &copy; <script>document.write(new Date().getFullYear())</script> Biology Department NPS, Designed By Sir Hassan Tariq
        </div>
      </div>
    </footer>
  </div>
  <script src="./assets/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="./assets/js/core/popper.min.js" type="text/javascript"></script>
  <script src="./assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
  <script src="./assets/js/plugins/moment.min.js"></script>
  <script src="./assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
  <script src="./assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
  <script src="./assets/js/material-kit.js?v=2.0.4" type="text/javascript"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      marks(); // Calculate total marks on page load
      
      // Call marks() function after a small delay to ensure the values are properly calculated
      setTimeout(function() {
        marks();
      }, 500);
      
      // Call the loadChapters function manually to ensure the chapter selection works
      var classId = $('#class_id').val();
      var subjectId = $('#subject_id').val();
      if (classId && subjectId) {
        // Parse chapter IDs for initial loading
        var chapterIdsStr = "<?php echo $q_chapter_ids_str; ?>";
        var initialChapIds = chapterIdsStr !== 'all' ? 
            chapterIdsStr.split(',').map(s => s.trim()).filter(s => s !== '') : [];
        var allChapsSelected = chapterIdsStr === 'all';
        
        // Call with proper arguments
        loadChapters(classId, subjectId, initialChapIds, allChapsSelected);
      }
      
      // Initialize datetimepicker
      if($('.datetimepicker').length != 0){
        $('.datetimepicker').datetimepicker({
          icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
          },
          format: 'DD/MM/YYYY hh:mm A',
          timeZone: 'Asia/Karachi',
          useStrict: true,
          keepLocalTime: true,
          sideBySide: true,
          showTodayButton: true,
          showClear: true,
          showClose: true,
          toolbarPlacement: 'bottom',
          widgetPositioning: {
            horizontal: 'auto',
            vertical: 'bottom'
          }
        }).on('dp.change', function(e) {
          // Log the selected date when changed for debugging
          console.log("Date changed to: ", $(this).val());
          
          // Ensure the date is in the expected format for the server
          var formattedDate = moment(e.date).format('DD/MM/YYYY hh:mm A');
          $(this).val(formattedDate);
          console.log("Formatted date: ", formattedDate);
        });
      }

      // Initialize Select2 for chapters with enhanced settings
      $('#chapter_id').select2({
          placeholder: "Select chapters after class/subject",
          width: '100%',
          allowClear: true,
          closeOnSelect: false
      });

      var initialClassId = $('#class_id').val();
      var initialSubjectId = $('#subject_id').val();
      // Parse chapter IDs string properly to handle both 'all' and specific chapter IDs
      var chapterIdsStr = "<?php echo $q_chapter_ids_str; ?>";
      var initialChapterIds = chapterIdsStr !== 'all' ? 
          chapterIdsStr.split(',').map(s => s.trim()).filter(s => s !== '') : [];
      var allChaptersSelectedInitially = chapterIdsStr === 'all';

      function loadChapters(classId, subjectId, selectedChapterIds, allChapters) {
          console.log("Loading chapters for class: " + classId + ", subject: " + subjectId);
          console.log("Selected chapters: ", selectedChapterIds);
          console.log("All chapters selected: ", allChapters);
          
          if (classId) { // Subject ID is optional for fetching chapters as per quizconfig.php logic
              // Keep track of currently selected chapters before clearing
              var currentlySelected = $('#chapter_id').val() || [];
              
              $('#chapter_id').empty().append('<option value="" disabled>Loading chapters...</option>'); // Clear existing and show loading
              $.ajax({
                  url: 'get_chapters.php',
                  type: 'GET',
                  data: { class_id: classId, subject_id: subjectId }, // subject_id can be null
                  dataType: 'json',
                  success: function(response) {
                      $('#chapter_id').empty(); // Clear loading message
                      $('#chapter_id').append('<option value="all">All Chapters</option>');
                      
                      // Handle different response formats from get_chapters.php
                      var chapters = [];
                      if (response.chapters) {
                          chapters = response.chapters;
                      } else if (Array.isArray(response)) {
                          chapters = response;
                      }
                      
                      console.log("Chapters received: ", chapters);
                      
                      if (chapters.length > 0) {
                          // Add received chapters to dropdown
                          $.each(chapters, function(index, chapter) {
                              $('#chapter_id').append($('<option>', {
                                  value: chapter.chapter_id,
                                  text: chapter.chapter_name
                              }));
                          });
                          
                          // We need to initialize Select2 again after dynamically adding options
                          $('#chapter_id').select2({
                              placeholder: "Select chapters after class/subject",
                              width: '100%',
                              allowClear: true,
                              closeOnSelect: false
                          });
                          
                          // Set selected chapters after loading
                          setTimeout(function() {
                              if (allChapters) {
                                  console.log("Setting 'all' as selected");
                                  $('#chapter_id').val('all').trigger('change');
                              } else if (selectedChapterIds && selectedChapterIds.length > 0) {
                                  console.log("Setting specific chapters as selected: ", selectedChapterIds);
                                  // If this is coming from an AJAX reload but user had already selected chapters,
                                  // preserve their selections, otherwise use the initialChapterIds
                                  var chaptersToSelect = currentlySelected.length > 0 && 
                                      !currentlySelected.includes('') &&
                                      !currentlySelected.includes('all') ? 
                                      currentlySelected : selectedChapterIds;
                                  
                                  $('#chapter_id').val(chaptersToSelect).trigger('change');
                              }
                          }, 100);
                      } else {
                          $('#chapter_id').append('<option value="" disabled>No chapters found for this selection.</option>');
                      }
                  },
                  error: function(xhr, status, error) {
                      console.error("Error loading chapters: ", error);
                      $('#chapter_id').empty().append('<option value="" disabled>Error loading chapters: ' + error + '</option>');
                  }
              });
          } else {
              $('#chapter_id').empty()
                  .append('<option value="" disabled>Select Class first</option>')
                  .append('<option value="all">All Chapters</option>')
                  .trigger('change');
          }
      }

      // Special handling for when 'all' is selected
      $(document).on('change', '#chapter_id', function() {
          var selectedValues = $(this).val();
          if (selectedValues && Array.isArray(selectedValues) && selectedValues.includes('all')) {
              // If 'all' is one of multiple selections, make it the only selection
              $(this).val(['all']).trigger('change');
          }
      });

      // Load chapters on page load if class and subject are pre-selected
      if (initialClassId) {
          loadChapters(initialClassId, initialSubjectId, initialChapterIds, allChaptersSelectedInitially);
      }

      // Reload chapters when class or subject changes
      $('#class_id, #subject_id').on('change', function() {
          var classId = $('#class_id').val();
          var subjectId = $('#subject_id').val();
          
          if (!classId) {
              $('#chapter_id').empty()
                  .append('<option value="" disabled>Select Class first</option>')
                  .append('<option value="all">All Chapters</option>')
                  .trigger('change');
              return;
          }
          
          // When class/subject changes, preserve any currently selected chapters if possible
          var currentlySelected = $('#chapter_id').val() || [];
          // If all chapters is selected or nothing is selected, don't try to preserve
          if (currentlySelected.includes('all') || currentlySelected.length === 0) {
              currentlySelected = [];
          }
          
          loadChapters(classId, subjectId, currentlySelected, false);
      });

    });
    
    // Add scrolled class to navbar on scroll
    $(window).scroll(function() {
      if ($(document).scrollTop() > 50) {
        $('.navbar').addClass('scrolled');
      } else {
        $('.navbar').removeClass('scrolled');
      }
    });
  </script>
</body>
</html> 