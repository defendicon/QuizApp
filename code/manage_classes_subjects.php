<?php
session_start();
if (!isset($_SESSION["instructorloggedin"]) || $_SESSION["instructorloggedin"] !== true || !isset($_SESSION["email"])) {
    header("location: instructorlogin.php");
    exit;
}

include "database.php";
$instructor_email = $_SESSION["email"];
$feedback_message = "";

// Pagination and Filtering Configuration
$items_per_page = 10; // Number of items per page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$class_filter = isset($_GET['class_filter']) ? $_GET['class_filter'] : '';
$subject_filter = isset($_GET['subject_filter']) ? $_GET['subject_filter'] : '';
$section_filter = isset($_GET['section_filter']) ? $_GET['section_filter'] : '';
$chapter_filter = isset($_GET['chapter_filter']) ? $_GET['chapter_filter'] : '';

// Handle Class Operations
if (isset($_POST['action'])) {
    if ($_POST['action'] === 'add_class' && !empty($_POST['class_name'])) {
        $class_name = $conn->real_escape_string(trim($_POST['class_name']));
        
        // Check if class already exists
        $check_sql = "SELECT class_id FROM classes WHERE class_name = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $class_name);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $feedback_message = '<div class="alert alert-danger">A class with this name already exists.</div>';
        } else {
            $sql = "INSERT INTO classes (class_name, instructor_email) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $class_name, $instructor_email);
            if ($stmt->execute()) {
                $feedback_message = '<div class="alert alert-success">Class added successfully!</div>';
            } else {
                $feedback_message = '<div class="alert alert-danger">Error adding class: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
    elseif ($_POST['action'] === 'delete_class' && !empty($_POST['class_id'])) {
        $class_id = intval($_POST['class_id']);
        // First check if class has any associated chapters
        $check_sql = "SELECT COUNT(*) FROM chapters WHERE class_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $class_id);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            $feedback_message = '<div class="alert alert-warning">Cannot delete class. Please delete associated chapters first.</div>';
        } else {
            $sql = "DELETE FROM classes WHERE class_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $class_id);
            if ($stmt->execute()) {
                $feedback_message = '<div class="alert alert-success">Class deleted successfully!</div>';
            } else {
                $feedback_message = '<div class="alert alert-danger">Error deleting class: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
    }
    
    // Handle Section Operations
    elseif ($_POST['action'] === 'add_section' && !empty($_POST['section_name']) && !empty($_POST['class_id'])) {
        $section_name = $conn->real_escape_string(trim($_POST['section_name']));
        $class_id = intval($_POST['class_id']);
        
        // Check if section already exists for this class
        $check_sql = "SELECT COUNT(*) FROM class_sections WHERE class_id = ? AND section_name = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("is", $class_id, $section_name);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();
        
        if ($count > 0) {
            $feedback_message = '<div class="alert alert-warning">A section with this name already exists for this class.</div>';
        } else {
            // Add section to class_sections table
            $sql = "INSERT INTO class_sections (class_id, section_name) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $class_id, $section_name);
            
            if ($stmt->execute()) {
                $feedback_message = '<div class="alert alert-success">Section added successfully!</div>';
            } else {
                $feedback_message = '<div class="alert alert-danger">Error adding section: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
    }
    elseif ($_POST['action'] === 'delete_section' && !empty($_POST['section_id'])) {
        $section_id = intval($_POST['section_id']);
        
        // Get section details first
        $section_sql = "SELECT cs.section_name, cs.class_id, c.class_name 
                        FROM class_sections cs 
                        JOIN classes c ON cs.class_id = c.class_id 
                        WHERE cs.id = ?";
        $section_stmt = $conn->prepare($section_sql);
        $section_stmt->bind_param("i", $section_id);
        $section_stmt->execute();
        $section_result = $section_stmt->get_result();
        
        if ($section_row = $section_result->fetch_assoc()) {
            $section_name = $section_row['section_name'];
            $class_id = $section_row['class_id'];
            $class_name = $section_row['class_name'];
            
            // Check if section is being used in quizzes
            $check_sql = "SELECT COUNT(*) FROM quizconfig WHERE section = ? AND class_id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("si", $section_name, $class_id);
            $check_stmt->execute();
            $check_stmt->bind_result($quiz_count);
            $check_stmt->fetch();
            $check_stmt->close();
            
            // Check if section has actual students
            $check_sql = "SELECT COUNT(*) FROM studentinfo WHERE section = ? AND department = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ss", $section_name, $class_name);
            $check_stmt->execute();
            $check_stmt->bind_result($student_count);
            $check_stmt->fetch();
            $check_stmt->close();
            
            if ($quiz_count > 0) {
                $feedback_message = '<div class="alert alert-warning">Cannot delete section. It is being used in quizzes.</div>';
            } elseif ($student_count > 0) {
                $feedback_message = '<div class="alert alert-warning">Cannot delete section. Students are assigned to this section.</div>';
            } else {
                // Delete the section from class_sections table
                $sql = "DELETE FROM class_sections WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $section_id);
                if ($stmt->execute()) {
                    $feedback_message = '<div class="alert alert-success">Section deleted successfully!</div>';
                } else {
                    $feedback_message = '<div class="alert alert-danger">Error deleting section: ' . $stmt->error . '</div>';
                }
                $stmt->close();
            }
        } else {
            $feedback_message = '<div class="alert alert-danger">Section not found.</div>';
        }
        $section_stmt->close();
    }
    
    // Handle Subject Operations
    elseif ($_POST['action'] === 'add_subject' && !empty($_POST['subject_name'])) {
        $subject_name = $conn->real_escape_string(trim($_POST['subject_name']));
        $sql = "INSERT INTO subjects (subject_name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $subject_name);
        if ($stmt->execute()) {
            $feedback_message = '<div class="alert alert-success">Subject added successfully!</div>';
        } else {
            $feedback_message = '<div class="alert alert-danger">Error adding subject: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
    elseif ($_POST['action'] === 'delete_subject' && !empty($_POST['subject_id'])) {
        $subject_id = intval($_POST['subject_id']);
        // First check if subject has any associated chapters
        $check_sql = "SELECT COUNT(*) FROM chapters WHERE subject_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $subject_id);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            $feedback_message = '<div class="alert alert-warning">Cannot delete subject. Please delete associated chapters first.</div>';
        } else {
            $sql = "DELETE FROM subjects WHERE subject_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $subject_id);
            if ($stmt->execute()) {
                $feedback_message = '<div class="alert alert-success">Subject deleted successfully!</div>';
            } else {
                $feedback_message = '<div class="alert alert-danger">Error deleting subject: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
    }

    // Handle Chapter Operations
    elseif ($_POST['action'] === 'add_chapter' && !empty($_POST['chapter_name']) && !empty($_POST['class_id']) && !empty($_POST['subject_id'])) {
        $chapter_name = $conn->real_escape_string(trim($_POST['chapter_name']));
        $class_id = intval($_POST['class_id']);
        $subject_id = intval($_POST['subject_id']);
        
        // Get next chapter number for this class and subject
        $sql_next_number = "SELECT COALESCE(MAX(chapter_number), 0) + 1 as next_number 
                           FROM chapters 
                           WHERE class_id = ? AND subject_id = ?";
        $stmt_next = $conn->prepare($sql_next_number);
        $stmt_next->bind_param("ii", $class_id, $subject_id);
        $stmt_next->execute();
        $result_next = $stmt_next->get_result();
        $next_number = $result_next->fetch_assoc()['next_number'];
        $stmt_next->close();
        
        $sql = "INSERT INTO chapters (chapter_name, class_id, subject_id, chapter_number) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siii", $chapter_name, $class_id, $subject_id, $next_number);
        if ($stmt->execute()) {
            $feedback_message = '<div class="alert alert-success">Chapter added successfully!</div>';
        } else {
            $feedback_message = '<div class="alert alert-danger">Error adding chapter: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
    elseif ($_POST['action'] === 'delete_chapter' && !empty($_POST['chapter_id'])) {
        $chapter_id = intval($_POST['chapter_id']);
        
        // First check if chapter has any associated questions
        $check_sql = "SELECT COUNT(*) FROM mcqdb WHERE chapter_id = ? 
                      UNION ALL 
                      SELECT COUNT(*) FROM numericaldb WHERE chapter_id = ?
                      UNION ALL
                      SELECT COUNT(*) FROM fillintheblanks WHERE chapter_id = ?
                      UNION ALL
                      SELECT COUNT(*) FROM shortanswer WHERE chapter_id = ?
                      UNION ALL
                      SELECT COUNT(*) FROM essay WHERE chapter_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("iiiii", $chapter_id, $chapter_id, $chapter_id, $chapter_id, $chapter_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $total_questions = 0;
        while ($row = $check_result->fetch_assoc()) {
            $total_questions += $row['COUNT(*)'];
        }
        $check_stmt->close();

        if ($total_questions > 0) {
            $feedback_message = '<div class="alert alert-warning">Cannot delete chapter. Please delete associated questions first.</div>';
        } else {
            $sql = "DELETE FROM chapters WHERE chapter_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $chapter_id);
            if ($stmt->execute()) {
                $feedback_message = '<div class="alert alert-success">Chapter deleted successfully!</div>';
            } else {
                $feedback_message = '<div class="alert alert-danger">Error deleting chapter: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
    }
    elseif ($_POST['action'] === 'add_topic' && !empty($_POST['topic_name']) && !empty($_POST['chapter_id'])) {
        $topic_name = $conn->real_escape_string(trim($_POST['topic_name']));
        $chapter_id = intval($_POST['chapter_id']);

        // Check if topic already exists for this chapter
        $check_sql = "SELECT COUNT(*) FROM topics WHERE chapter_id = ? AND topic_name = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("is", $chapter_id, $topic_name);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            $feedback_message = '<div class="alert alert-warning">Topic already exists for this chapter.</div>';
        } else {
            $sql = "INSERT INTO topics (chapter_id, topic_name) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $chapter_id, $topic_name);
            if ($stmt->execute()) {
                $feedback_message = '<div class="alert alert-success">Topic added successfully!</div>';
            } else {
                $feedback_message = '<div class="alert alert-danger">Error adding topic: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
    }
    elseif ($_POST['action'] === 'delete_topic' && !empty($_POST['topic_id'])) {
        $topic_id = intval($_POST['topic_id']);
        $sql = "DELETE FROM topics WHERE topic_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $topic_id);
        if ($stmt->execute()) {
            $feedback_message = '<div class="alert alert-success">Topic deleted successfully!</div>';
        } else {
            $feedback_message = '<div class="alert alert-danger">Error deleting topic: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// Fetch existing classes with filtering
$classes = [];
$classes_count = 0;

// Count total classes with filter
$count_sql = "SELECT COUNT(*) as total FROM classes WHERE class_name LIKE ?";
$stmt = $conn->prepare($count_sql);
$class_search = "%" . $class_filter . "%";
$stmt->bind_param("s", $class_search);
$stmt->execute();
$result = $stmt->get_result();
$classes_count = $result->fetch_assoc()['total'];
$stmt->close();

// Calculate pagination
$total_pages_classes = ceil($classes_count / $items_per_page);
$offset_classes = ($current_page - 1) * $items_per_page;

// Get classes with filter and pagination
$sql_classes = "SELECT * FROM classes WHERE class_name LIKE ? ORDER BY class_name LIMIT ?, ?";
$stmt = $conn->prepare($sql_classes);
$stmt->bind_param("sii", $class_search, $offset_classes, $items_per_page);
$stmt->execute();
$result_classes = $stmt->get_result();
if ($result_classes) {
    while ($row = $result_classes->fetch_assoc()) {
        $classes[] = $row;
    }
}
$stmt->close();

// Fetch existing subjects with filtering
$subjects = [];
$subjects_count = 0;

// Count total subjects with filter
$count_sql = "SELECT COUNT(*) as total FROM subjects WHERE subject_name LIKE ?";
$stmt = $conn->prepare($count_sql);
$subject_search = "%" . $subject_filter . "%";
$stmt->bind_param("s", $subject_search);
$stmt->execute();
$result = $stmt->get_result();
$subjects_count = $result->fetch_assoc()['total'];
$stmt->close();

// Calculate pagination
$total_pages_subjects = ceil($subjects_count / $items_per_page);
$offset_subjects = ($current_page - 1) * $items_per_page;

// Get subjects with filter and pagination
$sql_subjects = "SELECT * FROM subjects WHERE subject_name LIKE ? ORDER BY subject_name LIMIT ?, ?";
$stmt = $conn->prepare($sql_subjects);
$stmt->bind_param("sii", $subject_search, $offset_subjects, $items_per_page);
$stmt->execute();
$result_subjects = $stmt->get_result();
if ($result_subjects) {
    while ($row = $result_subjects->fetch_assoc()) {
        $subjects[] = $row;
    }
}
$stmt->close();

// Fetch existing chapters with filtering
$chapters = [];
$chapters_count = 0;

// Build the query conditions for filtering
$where_conditions = [];
$params = [];
$types = "";

// Base query for counting
$count_sql = "SELECT COUNT(*) as total 
              FROM chapters c 
              JOIN classes cl ON c.class_id = cl.class_id 
              JOIN subjects s ON c.subject_id = s.subject_id 
              WHERE 1=1";

// Base query for data
$sql_base = "SELECT c.chapter_id as id, c.chapter_name, c.chapter_number, cl.class_name, s.subject_name 
             FROM chapters c 
             JOIN classes cl ON c.class_id = cl.class_id 
             JOIN subjects s ON c.subject_id = s.subject_id 
             WHERE 1=1";

// Add filters if provided
if (!empty($chapter_filter)) {
    $where_conditions[] = "c.chapter_name LIKE ?";
    $params[] = "%" . $chapter_filter . "%";
    $types .= "s";
}
if (!empty($class_filter)) {
    $where_conditions[] = "cl.class_name LIKE ?";
    $params[] = "%" . $class_filter . "%";
    $types .= "s";
}
if (!empty($subject_filter)) {
    $where_conditions[] = "s.subject_name LIKE ?";
    $params[] = "%" . $subject_filter . "%";
    $types .= "s";
}

// Add conditions to queries
if (!empty($where_conditions)) {
    $condition_string = implode(" AND ", $where_conditions);
    $count_sql .= " AND " . $condition_string;
    $sql_base .= " AND " . $condition_string;
}

// Count total chapters with filters
$stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$chapters_count = $result->fetch_assoc()['total'];
$stmt->close();

// Calculate pagination
$total_pages_chapters = ceil($chapters_count / $items_per_page);
$offset_chapters = ($current_page - 1) * $items_per_page;

// Get chapters with filter and pagination
$sql_chapters = $sql_base . " ORDER BY cl.class_name, s.subject_name, c.chapter_number LIMIT ?, ?";
$stmt = $conn->prepare($sql_chapters);

// Add pagination parameters
$params[] = $offset_chapters;
$types .= "i";
$params[] = $items_per_page;
$types .= "i";

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result_chapters = $stmt->get_result();
if ($result_chapters) {
    while ($row = $result_chapters->fetch_assoc()) {
        $chapters[] = $row;
    }
}
$stmt->close();

// Fetch all chapters for topic dropdown
$all_chapters = [];
$chap_result = $conn->query("SELECT chapter_id, chapter_name FROM chapters ORDER BY chapter_number");
if ($chap_result) {
    while ($row = $chap_result->fetch_assoc()) {
        $all_chapters[] = $row;
    }
}

// Fetch existing topics
$topics = [];
$topic_sql = "SELECT t.topic_id, t.topic_name, c.chapter_name FROM topics t JOIN chapters c ON t.chapter_id = c.chapter_id ORDER BY c.chapter_number, t.topic_name";
$topic_result = $conn->query($topic_sql);
if ($topic_result) {
    while ($row = $topic_result->fetch_assoc()) {
        $topics[] = $row;
    }
}

// Fetch existing sections with class information and filtering
$class_sections = [];
$sections_count = 0;

// Build the query conditions for filtering
$where_conditions = [];
$params = [];
$types = "";

// Base query for counting
$count_sql = "SELECT COUNT(*) as total 
              FROM class_sections cs
              JOIN classes c ON cs.class_id = c.class_id
              WHERE 1=1";

// Base query for data
$sql_base = "SELECT cs.id, cs.section_name, cs.class_id, c.class_name 
             FROM class_sections cs
             JOIN classes c ON cs.class_id = c.class_id
             WHERE 1=1";

// Add filters if provided
if (!empty($section_filter)) {
    $where_conditions[] = "cs.section_name LIKE ?";
    $params[] = "%" . $section_filter . "%";
    $types .= "s";
}
if (!empty($class_filter)) {
    $where_conditions[] = "c.class_name LIKE ?";
    $params[] = "%" . $class_filter . "%";
    $types .= "s";
}

// Add conditions to queries
if (!empty($where_conditions)) {
    $condition_string = implode(" AND ", $where_conditions);
    $count_sql .= " AND " . $condition_string;
    $sql_base .= " AND " . $condition_string;
}

// Count total sections with filters
$stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$sections_count = $result->fetch_assoc()['total'];
$stmt->close();

// Calculate pagination
$total_pages_sections = ceil($sections_count / $items_per_page);
$offset_sections = ($current_page - 1) * $items_per_page;

// Get sections with filter and pagination
$sql_sections = $sql_base . " ORDER BY c.class_name, cs.section_name LIMIT ?, ?";
$stmt = $conn->prepare($sql_sections);

// Add pagination parameters
$params[] = $offset_sections;
$types .= "i";
$params[] = $items_per_page;
$types .= "i";

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result_sections = $stmt->get_result();
if ($result_sections) {
    while ($row = $result_sections->fetch_assoc()) {
        $class_sections[] = $row;
    }
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="./assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Manage Classes & Subjects</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="./assets/css/material-kit.css?v=2.0.4" rel="stylesheet" />
    <style>
        /* Fixed Navbar Styles */
        .navbar {
            transition: all 0.3s ease;
            padding-top: 0 !important;
            background-color: #fff !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            height: 60px;
        }
        
        .navbar-brand {
            color: #333 !important;
            font-weight: 600;
            font-size: 1.3rem;
            padding: 0 15px;
        }
        
        .nav-link {
            color: #333 !important;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
            padding: 8px 15px !important;
        }
        
        .nav-link i {
            font-size: 18px;
            color: #333;
        }
        
        .navbar-toggler {
            border: none;
            padding: 0;
        }
        
        .navbar-toggler-icon {
            background-color: #333;
            height: 2px;
            margin: 4px 0;
            display: block;
            transition: all 0.3s ease;
        }
        
        @media (max-width: 991px) {
            .navbar .navbar-nav {
                margin-top: 10px;
                background: #fff;
                border-radius: 4px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                padding: 10px;
            }
            
            .navbar .nav-item {
                margin: 5px 0;
            }
            
            .nav-link {
                color: #333 !important;
                padding: 8px 15px !important;
            }
        }

        /* Footer Styles */
        .footer {
            padding: 30px 0;
            margin-top: 50px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            position: relative;
            width: 100%;
            bottom: 0;
        }
        
        .footer .copyright {
            color: #555;
            font-size: 14px;
            line-height: 1.8;
        }
        
        .footer .copyright strong {
            font-weight: 600;
            color: #333;
        }
        
        .footer .copyright .department {
            color: #1a73e8;
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .footer .copyright .designer {
            font-style: italic;
            margin: 5px 0;
        }
        
        .footer .copyright .year {
            background: #1a73e8;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .footer {
                padding: 20px 0;
                margin-top: 30px;
            }
            
            .footer .copyright {
                font-size: 12px;
            }
        }

        /* Additional Styles */
        .main-raised { 
            margin-top: 80px;
            min-height: calc(100vh - 200px);
        }
        .card { margin-bottom: 30px; }
        .add-form { margin-bottom: 20px; }
        .list-group-item { display: flex; justify-content: space-between; align-items: center; }
        .delete-btn { color: #dc3545; cursor: pointer; }
        .delete-btn:hover { color: #c82333; }
    </style>
</head>
<body class="landing-page sidebar-collapse">
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
                        <a class="nav-link" rel="tooltip" title="" data-placement="bottom" href="instructorlogout.php" data-original-title="Get back to Login Page">
                            <i class="material-icons">power_settings_new</i> Log Out
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="wrapper">
        <div class="main main-raised">
            <div class="container">
                <div class="section text-center">
                    <h2 class="title">Manage Classes & Subjects</h2>
                    <?php if (!empty($feedback_message)) echo $feedback_message; ?>
                </div>
                <div class="section">
                    <div class="row">
                        <!-- Classes Management -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Classes</h4>
                                </div>
                                <div class="card-body">
                                    <form class="add-form" method="post">
                                        <input type="hidden" name="action" value="add_class">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="class_name" placeholder="Enter class name" required>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">Add Class</button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <!-- Filter for Classes -->
                                    <form method="get" class="mb-3 mt-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="class_filter" placeholder="Filter classes..." value="<?php echo htmlspecialchars($class_filter); ?>">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-info">Filter</button>
                                                <?php if (!empty($class_filter)): ?>
                                                <a href="?<?php echo http_build_query(array_merge($_GET, ['class_filter' => ''])); ?>" class="btn btn-secondary">Clear</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <div class="list-group">
                                        <?php foreach ($classes as $class): ?>
                                        <div class="list-group-item">
                                            <?php echo htmlspecialchars($class['class_name']); ?>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="action" value="delete_class">
                                                <input type="hidden" name="class_id" value="<?php echo $class['class_id']; ?>">
                                                <button type="submit" class="btn btn-link delete-btn" onclick="return confirm('Are you sure you want to delete this class?');">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Pagination for Classes -->
                                    <?php if ($total_pages_classes > 1): ?>
                                    <nav aria-label="Classes pagination" class="mt-3">
                                        <ul class="pagination justify-content-center">
                                            <?php if ($current_page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => ($current_page - 1)])); ?>">
                                                    <span aria-hidden="true">&laquo;</span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                            
                                            <?php for ($i = 1; $i <= $total_pages_classes; $i++): ?>
                                            <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            </li>
                                            <?php endfor; ?>
                                            
                                            <?php if ($current_page < $total_pages_classes): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => ($current_page + 1)])); ?>">
                                                    <span aria-hidden="true">&raquo;</span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Subjects Management -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Subjects</h4>
                                </div>
                                <div class="card-body">
                                    <form class="add-form" method="post">
                                        <input type="hidden" name="action" value="add_subject">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="subject_name" placeholder="Enter subject name" required>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">Add Subject</button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <!-- Filter for Subjects -->
                                    <form method="get" class="mb-3 mt-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="subject_filter" placeholder="Filter subjects..." value="<?php echo htmlspecialchars($subject_filter); ?>">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-info">Filter</button>
                                                <?php if (!empty($subject_filter)): ?>
                                                <a href="?<?php echo http_build_query(array_merge($_GET, ['subject_filter' => ''])); ?>" class="btn btn-secondary">Clear</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <div class="list-group">
                                        <?php foreach ($subjects as $subject): ?>
                                        <div class="list-group-item">
                                            <?php echo htmlspecialchars($subject['subject_name']); ?>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="action" value="delete_subject">
                                                <input type="hidden" name="subject_id" value="<?php echo $subject['subject_id']; ?>">
                                                <button type="submit" class="btn btn-link delete-btn" onclick="return confirm('Are you sure you want to delete this subject?');">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Pagination for Subjects -->
                                    <?php if ($total_pages_subjects > 1): ?>
                                    <nav aria-label="Subjects pagination" class="mt-3">
                                        <ul class="pagination justify-content-center">
                                            <?php if ($current_page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => ($current_page - 1)])); ?>">
                                                    <span aria-hidden="true">&laquo;</span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                            
                                            <?php for ($i = 1; $i <= $total_pages_subjects; $i++): ?>
                                            <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            </li>
                                            <?php endfor; ?>
                                            
                                            <?php if ($current_page < $total_pages_subjects): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => ($current_page + 1)])); ?>">
                                                    <span aria-hidden="true">&raquo;</span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sections Management -->
                <div class="section">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Class Sections</h4>
                                </div>
                                <div class="card-body">
                                    <form class="add-form" method="post">
                                        <input type="hidden" name="action" value="add_section">
                                        <div class="row" style="max-width: 700px; margin: 0 auto;">
                                            <div class="col-md-5">
                                                <select class="form-control" name="class_id" required>
                                                    <option value="">Select Class</option>
                                                    <?php foreach ($classes as $class): ?>
                                                    <option value="<?php echo $class['class_id']; ?>"><?php echo htmlspecialchars($class['class_name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="section_name" placeholder="Enter section name (e.g., A, B, Gold)" required>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary">Add Section</button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <!-- Filter for Sections -->
                                    <div class="row mt-4 mb-3">
                                        <div class="col-md-8 offset-md-2">
                                            <form method="get" class="mb-3">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="section_filter" placeholder="Filter sections..." value="<?php echo htmlspecialchars($section_filter); ?>">
                                                    <input type="hidden" name="class_filter" value="<?php echo htmlspecialchars($class_filter); ?>">
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-info">Filter</button>
                                                        <?php if (!empty($section_filter)): ?>
                                                        <a href="?<?php echo http_build_query(array_merge($_GET, ['section_filter' => ''])); ?>" class="btn btn-secondary">Clear</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-md-8 offset-md-2">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Class</th>
                                                            <th class="text-center">Section Name</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (empty($class_sections)): ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center">No sections found</td>
                                                        </tr>
                                                        <?php else: ?>
                                                            <?php foreach ($class_sections as $section): ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo htmlspecialchars($section['class_name']); ?></td>
                                                                <td class="text-center"><?php echo htmlspecialchars($section['section_name']); ?></td>
                                                                <td class="text-center">
                                                                    <form method="post" style="display: inline;">
                                                                        <input type="hidden" name="action" value="delete_section">
                                                                        <input type="hidden" name="section_id" value="<?php echo $section['id']; ?>">
                                                                        <button type="submit" class="btn btn-link text-danger" onclick="return confirm('Are you sure you want to delete this section?');">
                                                                            <i class="material-icons">delete</i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                                
                                                <!-- Pagination for Sections -->
                                                <?php if ($total_pages_sections > 1): ?>
                                                <nav aria-label="Sections pagination" class="mt-3">
                                                    <ul class="pagination justify-content-center">
                                                        <?php if ($current_page > 1): ?>
                                                        <li class="page-item">
                                                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => ($current_page - 1)])); ?>">
                                                                <span aria-hidden="true">&laquo;</span>
                                                                <span class="sr-only">Previous</span>
                                                            </a>
                                                        </li>
                                                        <?php endif; ?>
                                                        
                                                        <?php for ($i = 1; $i <= $total_pages_sections; $i++): ?>
                                                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>">
                                                                <?php echo $i; ?>
                                                            </a>
                                                        </li>
                                                        <?php endfor; ?>
                                                        
                                                        <?php if ($current_page < $total_pages_sections): ?>
                                                        <li class="page-item">
                                                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => ($current_page + 1)])); ?>">
                                                                <span aria-hidden="true">&raquo;</span>
                                                                <span class="sr-only">Next</span>
                                                            </a>
                                                        </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </nav>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Add Chapter -->
                <div class="section">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Add New Chapter</h4>
                                </div>
                                <div class="card-body">
                                    <form class="add-form" method="post">
                                        <input type="hidden" name="action" value="add_chapter">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <select class="form-control" name="class_id" required>
                                                    <option value="">Select Class</option>
                                                    <?php foreach ($classes as $class): ?>
                                                    <option value="<?php echo $class['class_id']; ?>"><?php echo htmlspecialchars($class['class_name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="subject_id" required>
                                                    <option value="">Select Subject</option>
                                                    <?php foreach ($subjects as $subject): ?>
                                                    <option value="<?php echo $subject['subject_id']; ?>"><?php echo htmlspecialchars($subject['subject_name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="chapter_name" placeholder="Enter chapter name" required>
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-primary">Add Chapter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Topic -->
                <div class="section">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Add New Topic</h4>
                                </div>
                                <div class="card-body">
                                    <form class="add-form" method="post">
                                        <input type="hidden" name="action" value="add_topic">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select class="form-control" name="chapter_id" required>
                                                    <option value="">Select Chapter</option>
                                                    <?php foreach ($all_chapters as $chap): ?>
                                                    <option value="<?php echo $chap['chapter_id']; ?>"><?php echo htmlspecialchars($chap['chapter_name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="topic_name" placeholder="Enter topic name" required>
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-primary">Add Topic</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chapters Management -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Manage Chapters</h4>
                        </div>
                        <div class="card-body">
                            <!-- Filter for Chapters -->
                            <div class="row mt-4 mb-3">
                                <div class="col-md-12">
                                    <form method="get" class="mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="class_filter" placeholder="Filter by class..." value="<?php echo htmlspecialchars($class_filter); ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="subject_filter" placeholder="Filter by subject..." value="<?php echo htmlspecialchars($subject_filter); ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="chapter_filter" placeholder="Filter by chapter..." value="<?php echo htmlspecialchars($chapter_filter); ?>">
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-info">Filter</button>
                                                        <?php if (!empty($class_filter) || !empty($subject_filter) || !empty($chapter_filter)): ?>
                                                        <a href="?page=1" class="btn btn-secondary">Clear All</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Class</th>
                                            <th>Subject</th>
                                            <th>Chapter</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($chapters as $chapter): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($chapter['class_name']); ?></td>
                                            <td><?php echo htmlspecialchars($chapter['subject_name']); ?></td>
                                            <td><?php echo htmlspecialchars($chapter['chapter_name']); ?></td>
                                            <td>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete_chapter">
                                                    <input type="hidden" name="chapter_id" value="<?php echo $chapter['id']; ?>">
                                                    <button type="submit" class="btn btn-link text-danger" onclick="return confirm('Are you sure you want to delete this chapter?');">
                                                        <i class="material-icons">delete</i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                
                                <!-- Pagination for Chapters -->
                                <?php if ($total_pages_chapters > 1): ?>
                                <nav aria-label="Chapters pagination" class="mt-3">
                                    <ul class="pagination justify-content-center">
                                        <?php if ($current_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => ($current_page - 1)])); ?>">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                        
                                        <?php for ($i = 1; $i <= $total_pages_chapters; $i++): ?>
                                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                        <?php endfor; ?>
                                        
                                        <?php if ($current_page < $total_pages_chapters): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => ($current_page + 1)])); ?>">
                                                <span aria-hidden="true">&raquo;</span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Topics Management -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Manage Topics</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Chapter</th>
                                            <th>Topic</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($topics as $topic): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($topic['chapter_name']); ?></td>
                                            <td><?php echo htmlspecialchars($topic['topic_name']); ?></td>
                                            <td>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete_topic">
                                                    <input type="hidden" name="topic_id" value="<?php echo $topic['topic_id']; ?>">
                                                    <button type="submit" class="btn btn-link text-danger" onclick="return confirm('Are you sure you want to delete this topic?');">
                                                        <i class="material-icons">delete</i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer footer-default">
            <div class="container">
                <div class="copyright text-center">
                    <div class="department">Biology Department NPS</div>
                    <div class="designer">Designed By Sir Hassan Tariq</div>
                    <div class="year">
                        &copy; <script>document.write(new Date().getFullYear())</script>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!--   Core JS Files   -->
    <script src="./assets/js/core/jquery.min.js" type="text/javascript"></script>
    <script src="./assets/js/core/popper.min.js" type="text/javascript"></script>
    <script src="./assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
    <script src="./assets/js/plugins/moment.min.js"></script>
    <script src="./assets/js/material-kit.js?v=2.0.4" type="text/javascript"></script>
</body>
</html> 