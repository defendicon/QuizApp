<?php
session_start();
// Ensure instructor is logged in
if (!isset($_SESSION["instructorloggedin"]) || $_SESSION["instructorloggedin"] !== true) {
    header("location: instructorlogin.php");
    exit;
}

include "database.php";

$message = "";
$instructor_email = $_SESSION["email"];

// Fetch instructor details
$fetch_sql = sprintf(
    "SELECT name, email FROM instructorinfo WHERE email='%s'",
    $conn->real_escape_string($instructor_email)
);
$result = $conn->query($fetch_sql);

if ($result && $result->num_rows > 0) {
    $instructor = $result->fetch_assoc();
} else {
    $message = "<div class='alert alert-danger'>Error: Could not fetch instructor information.</div>";
    $instructor = array('name' => 'Not Found', 'email' => 'Not Found');
}

// For debugging
error_log("Session email: " . print_r($_SESSION, true));
error_log("Fetch SQL: " . $fetch_sql);
error_log("Instructor data: " . print_r($instructor, true));

// Handle password update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Verify current password
    $verify_sql = sprintf(
        "SELECT password FROM instructorinfo WHERE email='%s'",
        $conn->real_escape_string($instructor_email)
    );
    $verify_result = $conn->query($verify_sql);
    
    if ($verify_result && $verify_result->num_rows > 0) {
        $stored_password = $verify_result->fetch_assoc()['password'];
        
        if ($current_password === $stored_password) {
            if ($new_password === $confirm_password) {
                if (strlen($new_password) >= 6) {
                    $update_sql = sprintf(
                        "UPDATE instructorinfo SET password='%s' WHERE email='%s'",
                        $conn->real_escape_string($new_password),
                        $conn->real_escape_string($instructor_email)
                    );
                    
                    if ($conn->query($update_sql)) {
                        $message = "<div class='alert alert-success'>Password updated successfully!</div>";
                    } else {
                        $message = "<div class='alert alert-danger'>Error updating password: " . $conn->error . "</div>";
                    }
                } else {
                    $message = "<div class='alert alert-danger'>New password must be at least 6 characters long.</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>New password and confirm password do not match.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Current password is incorrect.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Error: Could not verify current password.</div>";
    }
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
    <title>My Profile</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="./assets/css/material-kit.css?v=2.0.4" rel="stylesheet" />
    <style>
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
        
        .main-raised {
            margin-top: 80px !important;
        }
        
        .profile-card {
            max-width: 500px;
            margin: 0 auto;
        }
        
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
                        <a class="nav-link" href="instructorlogout.php">
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
                    <h2 class="title">My Profile</h2>
                </div>
                <div class="section">
                    <div class="row">
                        <div class="col-md-8 ml-auto mr-auto">
                            <div class="profile-card card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Profile Information</h4>
                                </div>
                                <div class="card-body">
                                    <?php echo $message; ?>
                                    
                                    <!-- Profile Info -->
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <h6 class="text-primary">Name</h6>
                                            <p><?php echo htmlspecialchars($instructor['name']); ?></p>
                                            
                                            <h6 class="text-primary">Email</h6>
                                            <p><?php echo htmlspecialchars($instructor['email']); ?></p>
                                        </div>
                                    </div>
                                    
                                    <!-- Change Password Form -->
                                    <form method="POST" action="my_profile.php">
                                        <h4 class="text-primary mb-4">Change Password</h4>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Current Password</label>
                                                    <input type="password" class="form-control" name="current_password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">New Password</label>
                                                    <input type="password" class="form-control" name="new_password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Confirm New Password</label>
                                                    <input type="password" class="form-control" name="confirm_password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" name="update_password" class="btn btn-primary">Update Password</button>
                                    </form>
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
                    <div class="designer">Designed by Sir Hassan Tariq</div>
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