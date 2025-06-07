<?php
session_start();
// Ensure instructor is logged in
if (!isset($_SESSION["instructorloggedin"]) || $_SESSION["instructorloggedin"] !== true) {
    header("location: instructorlogin.php");
    exit;
}

include "database.php"; // Database connection

$add_message = "";
$list_instructors_html = "";

// Handle Add Instructor form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_instructor'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Plain text for now, HASHING IS ESSENTIAL for production

    if (!empty($name) && !empty($email) && !empty($password)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Check if email already exists
            $check_email_sql = sprintf("SELECT email FROM instructorinfo WHERE email = '%s'", $conn->real_escape_string($email));
            $check_email_result = $conn->query($check_email_sql);
            if ($check_email_result && $check_email_result->num_rows == 0) {
                // HASH PASSWORD - IMPORTANT: Replace with password_hash() in a real scenario
                // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $hashed_password = $password; // Placeholder

                $insert_sql = sprintf("INSERT INTO instructorinfo (name, email, password) VALUES ('%s', '%s', '%s')",
                    $conn->real_escape_string($name),
                    $conn->real_escape_string($email),
                    $conn->real_escape_string($hashed_password) // Use hashed password here
                );

                if ($conn->query($insert_sql)) {
                    $add_message = "<p class='text-success'>Instructor added successfully!</p>";
                } else {
                    $add_message = "<p class='text-danger'>Error adding instructor: " . $conn->error . "</p>";
                }
            } else {
                $add_message = "<p class='text-danger'>Error: Email address already exists.</p>";
            }
        } else {
            $add_message = "<p class='text-danger'>Error: Invalid email format.</p>";
        }
    } else {
        $add_message = "<p class='text-danger'>Error: All fields are required.</p>";
    }
}

// Fetch and display current instructors
$fetch_instructors_sql = "SELECT name, email FROM instructorinfo ORDER BY name ASC";
$instructors_result = $conn->query($fetch_instructors_sql);

if ($instructors_result && $instructors_result->num_rows > 0) {
    $list_instructors_html .= "<h4 class='mt-5'>Current Instructors:</h4><ul class='list-group'>";
    while ($instructor_row = $instructors_result->fetch_assoc()) {
        $list_instructors_html .= "<li class='list-group-item'>" . htmlspecialchars($instructor_row['name']) . " ( " . htmlspecialchars($instructor_row['email']) . " )</li>";
    }
    $list_instructors_html .= "</ul>";
} else {
    $list_instructors_html = "<p class='mt-4'>No instructors found.</p>";
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
    <title>Manage Instructors</title>
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
        .form-group { margin-bottom: 20px; }
        .list-group-item { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            padding: 12px 20px;
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
                    <h2 class="title">Manage Instructors</h2>
                </div>
                <div class="section">
                    <div class="row">
                        <div class="col-md-8 ml-auto mr-auto">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Add New Instructor</h4>
                                </div>
                                <div class="card-body">
                                    <?php echo $add_message; ?>
                                    <form method="POST" action="manage_instructors.php">
                                        <div class="form-group">
                                            <label for="name" class="bmd-label-floating">Full Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="bmd-label-floating">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password" class="bmd-label-floating">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                        </div>
                                        <button type="submit" name="add_instructor" class="btn btn-primary">Add Instructor</button>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Current Instructors</h4>
                                </div>
                                <div class="card-body">
                                    <?php echo $list_instructors_html; ?>
                                </div>
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