<?php
session_start();
// Ensure instructor is logged in
if (!isset($_SESSION["instructorloggedin"]) || $_SESSION["instructorloggedin"] !== true) {
    header("location: instructorlogin.php");
    exit;
}

include "database.php"; // Database connection

// Store current page URL in session to redirect back from calculate_marks.php
$_SESSION['instructor_results_page_url'] = $_SERVER['REQUEST_URI'];

$selected_quiz_number = isset($_GET['quiz_number']) ? intval($_GET['quiz_number']) : 0;
$quiz_results_html = "";

$instructor_email = $_SESSION["email"];

// COMPLETELY NEW APPROACH: Get all quizzes to show instructors - more permissive approach
$all_quizzes_sql = "SELECT DISTINCT qc.quiznumber, qc.quizname 
                    FROM quizconfig qc 
                    ORDER BY qc.quiznumber DESC";
$stmt = $conn->prepare($all_quizzes_sql);
$stmt->execute();
$quizzes_result = $stmt->get_result();

$quizzes_options_html = '<option value="0">Select Quiz</option>';
while ($quiz = $quizzes_result->fetch_assoc()) {
    $selected = ($quiz['quiznumber'] == $selected_quiz_number) ? 'selected' : '';
    $quizzes_options_html .= sprintf(
        '<option value="%d" %s>Quiz #%d - %s</option>',
        $quiz['quiznumber'],
        $selected,
        $quiz['quiznumber'],
        htmlspecialchars($quiz['quizname'])
    );
}

if ($selected_quiz_number > 0) {
    // Get quiz details - now show any quiz without instructor restriction
    $quiz_sql = "SELECT qc.*, c.class_name, s.subject_name 
                 FROM quizconfig qc
                 LEFT JOIN classes c ON qc.class_id = c.class_id
                 LEFT JOIN subjects s ON qc.subject_id = s.subject_id
                 WHERE qc.quiznumber = ?";
    $stmt = $conn->prepare($quiz_sql);
    $stmt->bind_param("i", $selected_quiz_number);
    $stmt->execute();
    $quiz_info = $stmt->get_result()->fetch_assoc();

    if ($quiz_info) {
        // Get student results
        $results_sql = "SELECT 
                          s.name as student_name,
                          c.class_name,
                          IFNULL(cs.section_name, s.section) as section_name,
                          s.rollnumber,
                          r.attempt,
                          r.mcqmarks,
                          r.numericalmarks,
                          r.dropdownmarks,
                          r.fillmarks,
                          r.shortmarks,
                          r.essaymarks,
                          r.mcqmarks + r.numericalmarks + r.dropdownmarks + r.fillmarks + r.shortmarks + r.essaymarks as total_marks,
                          qr.starttime,
                          qr.endtime,
                          TIMESTAMPDIFF(MINUTE, qr.starttime, qr.endtime) as time_taken
                       FROM result r
                       JOIN studentinfo s ON r.rollnumber = s.rollnumber
                       LEFT JOIN class_sections cs ON s.section_id = cs.id
                       LEFT JOIN classes c ON cs.class_id = c.class_id
                       JOIN quizrecord qr ON r.quizid = qr.quizid AND r.rollnumber = qr.rollnumber AND r.attempt = qr.attempt
                       WHERE r.quizid = ?
                       ORDER BY r.attempt ASC, total_marks DESC";
        
        $stmt = $conn->prepare($results_sql);
        $stmt->bind_param("i", $quiz_info['quizid']);
        $stmt->execute();
        $results = $stmt->get_result();

        if ($results->num_rows > 0) {
            $quiz_results_html = '<div class="card mt-4">
                <div class="card-header card-header-primary">
                    <h4 class="card-title mb-0">Quiz Results</h4>
                    <p class="card-category">
                        ' . htmlspecialchars($quiz_info['quizname']) . ' - 
                        Class: ' . htmlspecialchars($quiz_info['class_name'] ?? 'N/A') . ', 
                        Subject: ' . htmlspecialchars($quiz_info['subject_name'] ?? 'N/A') . '
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Student Name</th>
                                    <th>Roll Number</th>
                                    <th>Attempt</th>
                                    <th>MCQ</th>
                                    <th>Numerical</th>
                                    <th>Dropdown</th>
                                    <th>Fill Blanks</th>
                                    <th>Short Answer</th>
                                    <th>Essay</th>
                                    <th>Total Score</th>
                                    <th>Time Taken</th>
                                    <th>Start Time / Actions</th>
                                </tr>
                            </thead>
                            <tbody>';

                            while ($row = $results->fetch_assoc()) {
                $percentage = ($row['total_marks'] / $quiz_info['maxmarks']) * 100;
                $row_class = '';
                if ($percentage >= 80) $row_class = 'table-success';
                else if ($percentage >= 60) $row_class = 'table-info';
                else if ($percentage >= 40) $row_class = 'table-warning';
                else $row_class = 'table-danger';

                // Add export links with student-specific parameter
                $student_pdf_link = 'direct_export.php?quiz_id=' . $selected_quiz_number . '&student=' . $row['rollnumber'] . '&attempt=' . $row['attempt'] . '&student_specific=1';

                $quiz_results_html .= '<tr class="' . $row_class . '">
                    <td>' . htmlspecialchars($row['class_name'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($row['section_name'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($row['student_name']) . '</td>
                    <td>' . htmlspecialchars($row['rollnumber']) . '</td>
                    <td>' . htmlspecialchars($row['attempt']) . '</td>
                    <td>' . $row['mcqmarks'] . '</td>
                    <td>' . $row['numericalmarks'] . '</td>
                    <td>' . $row['dropdownmarks'] . '</td>
                    <td>' . $row['fillmarks'] . '</td>
                    <td>' . $row['shortmarks'] . '</td>
                    <td>' . $row['essaymarks'] . '</td>
                    <td><strong>' . $row['total_marks'] . '/' . $quiz_info['maxmarks'] . ' 
                        (' . round($percentage, 1) . '%)</strong></td>
                    <td>' . $row['time_taken'] . ' mins</td>
                    <td>' . date('d M Y, h:i A', strtotime($row['starttime'])) . '
                        <a href="' . $student_pdf_link . '" class="btn btn-sm btn-info ml-2" title="Export student quiz PDF">
                            <i class="material-icons">picture_as_pdf</i>
                        </a>
                        <a href="view_student_attempt.php?quiz_id=' . $selected_quiz_number . '&student=' . $row['rollnumber'] . '&attempt=' . $row['attempt'] . '" class="btn btn-sm btn-primary ml-2" title="View attempted questions">
                            <i class="material-icons">visibility</i>
                        </a>
                    </td>
                </tr>';
            }

            $quiz_results_html .= '</tbody></table></div></div></div>';
        } else {
            $quiz_results_html = '<div class="alert alert-info mt-4">
                No results found for this quiz.
            </div>';
        }
    } else {
        $quiz_results_html = '<div class="alert alert-warning mt-4">
            Quiz not found.
        </div>';
    }
} else if (isset($_GET['quiz_number'])) {
    $quiz_results_html = '<p class="text-muted mt-3">Please select a valid quiz to view results.</p>';
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="./assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>View Quiz Results</title>
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
            margin-top: 80px !important;
        }
        .section {
            padding: 40px 0;
        }
        .title {
            margin-bottom: 30px;
        }
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
                    <h2 class="title">View Quiz Results</h2>
                </div>
                <div class="section">
                    <form method="GET" action="view_quiz_results.php" class="form-inline justify-content-center mb-4">
                        <div class="form-group">
                            <label for="quiz_number_select" class="mr-2">Select Quiz:</label>
                            <select name="quiz_number" id="quiz_number_select" class="form-control mr-2" onchange="this.form.submit()">
                                <?php echo $quizzes_options_html; ?>
                            </select>
                        </div>
                        <noscript><button type="submit" class="btn btn-primary btn-sm">View Results</button></noscript>
                    </form>
                    
                    <div id="results_display_area">
                        <?php echo $quiz_results_html; ?>
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
    <script src="./assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
    <script src="./assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
    <script src="./assets/js/material-kit.js?v=2.0.4" type="text/javascript"></script>
</body>
</html> 