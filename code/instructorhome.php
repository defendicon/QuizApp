<?php
  session_start();
  if(!isset($_SESSION["instructorloggedin"]) || $_SESSION["instructorloggedin"] !== true){
      header("location: instructorlogin.php");
      exit;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Narowal Public School And College - Instructor Portal</title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css"> --> <!-- Replaced by new Font Awesome -->
  <!-- New Links -->
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- CSS Files -->
  <link href="./assets/css/material-kit.css?v=2.0.4" rel="stylesheet" />
  <link href="./assets/demo/demo.css" rel="stylesheet" />
  <style>
    /* Responsive Styles */
    @media (max-width: 991px) {
      .navbar .navbar-nav {
        margin-top: 10px;
      }
      .navbar .nav-item {
        margin: 5px 0;
      }
      .navbar-collapse {
        background: rgba(255, 255, 255, 0.97);
        border-radius: 3px;
        padding: 15px;
      }
      .navbar.navbar-transparent .navbar-collapse {
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      }
      .navbar-collapse .nav-link {
        color: #333 !important;
      }
      .page-header .brand h1 {
        font-size: 2rem;
      }
      .page-header .brand h3.title {
        font-size: 1.5rem;
      }
      .page-header .brand h4.title {
        font-size: 1.2rem;
      }
    }

    /* Fixed Navbar Styles */
    .navbar {
      transition: all 0.3s ease;
      padding-top: 20px !important;
    }
    .navbar.navbar-transparent {
      background-color: transparent !important;
    }
    .navbar.fixed-top.scrolled {
      background-color: #fff !important;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .navbar.fixed-top.scrolled .nav-link {
      color: #333 !important;
    }
    .navbar.fixed-top.scrolled .navbar-brand {
      color: #333 !important;
    }
    
    /* Additional UI Improvements */
    .nav-link {
      display: flex;
      align-items: center;
      gap: 5px;
      font-weight: 500;
    }
    .nav-link i {
      font-size: 18px;
    }
    .navbar-brand {
      font-weight: 600;
      font-size: 1.3rem;
    }
    .navbar-toggler {
      border: none;
      padding: 0;
    }
    .navbar-toggler-icon {
      background-color: #fff;
      height: 2px;
      margin: 4px 0;
      display: block;
      transition: all 0.3s ease;
    }
    .navbar.scrolled .navbar-toggler-icon {
      background-color: #333;
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
  </style>
</head>

<body class="landing-page sidebar-collapse">
  <nav class="navbar navbar-transparent navbar-color-on-scroll fixed-top navbar-expand-lg" color-on-scroll="100" id="sectionsNav">
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
            <a href="manage_notifications.php" class="nav-link">
              <i class="material-icons">notifications</i> Manage Notifications
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
  <div class="page-header header-filter" data-parallax="true" style="background-image: url('./assets/img/profile_city.jpg')">
    <div class="container">
      <div class="row ">
        <div class="brand text-center" style="width: 100%;">
            <h1>Narowal Public School And College</h1>
            <h3 class="title">Educating The Souls</h3>
            <h4 class="title">Instructor Portal</h4>
        </div>
        <br>
        <p class="h5 text-center" style="width:100%;" ><i>Manage all the content of the Quiz here with ease. Feed Questions into multiple servers and Set Quiz..........</i></p> 
        <br>
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
  <script src="./assets/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="./assets/js/core/popper.min.js" type="text/javascript"></script>
  <script src="./assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
  <script src="./assets/js/plugins/moment.min.js"></script>
  <script src="./assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
  <script src="./assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
  <script src="./assets/js/plugins/jquery.sharrre.js" type="text/javascript"></script>
  <script src="./assets/js/material-kit.js?v=2.0.4" type="text/javascript"></script>
  <script>
    // Add scrolled class to navbar on scroll
    $(window).scroll(function() {
      if($(this).scrollTop() > 50) {
        $('.navbar').addClass('scrolled');
      } else {
        $('.navbar').removeClass('scrolled');
      }
    });
  </script>
</body>
</html>