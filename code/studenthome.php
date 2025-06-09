<?php
  session_start();
  if(!isset($_SESSION["studentloggedin"]) || $_SESSION["studentloggedin"] !== true){
      header("location: studentlogin.php");
      exit;
  }
  
  include("database.php");
  
  // If no class_id in session, try a workaround mapping from department to class_id
  if (!isset($_SESSION['class_id']) && isset($_SESSION['department'])) {
    // Create a mapping array for common department to class_id mappings
    $department_to_class = array(
      '1st Year' => 4,  // Assuming 1st Year maps to class_id 4
      '2nd Year' => 6,  // Assuming 2nd Year maps to class_id 6
      '9th' => 1,       // Assuming 9th maps to class_id 1
      '10th' => 2       // Assuming 10th maps to class_id 2
    );
    
    if (array_key_exists($_SESSION['department'], $department_to_class)) {
      $_SESSION['class_id'] = $department_to_class[$_SESSION['department']];
    }
  }
  
  // Get notifications for this student's class and section
  if (isset($_SESSION['class_id'])) {
    $class_id = $_SESSION['class_id'];
    $section_id = isset($_SESSION['section_id']) ? $_SESSION['section_id'] : null;
    
    // Actual query for student's notifications
    if ($section_id) {
      $sql = "SELECT * FROM notifications 
              WHERE is_active = 1 
              AND class_id = ?
              AND (section_id IS NULL OR section_id = ?)
              ORDER BY created_at DESC";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ii", $class_id, $section_id);
    } else {
      $sql = "SELECT * FROM notifications 
              WHERE is_active = 1 
              AND class_id = ?
              AND section_id IS NULL
              ORDER BY created_at DESC";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $class_id);
    }
    
    $stmt->execute();
    $notifications = $stmt->get_result();
    $stmt->close();
  } else {
    $notifications = null;
  }

  // Fetch upcoming quiz for this student
  $upcoming_quiz = null;
  if (isset($_SESSION['class_id'])) {
    $class_id = $_SESSION['class_id'];
    $section = isset($_SESSION['section']) ? $_SESSION['section'] : null;
    $rollnumber = $_SESSION['rollnumber'];

    $quiz_sql = "SELECT quizname, starttime, maxmarks
                 FROM quizconfig
                 WHERE endtime >= NOW()
                   AND class_id = ?
                   AND (section IS NULL OR LOWER(section) = LOWER(?))
                   AND (
                       SELECT COUNT(*) FROM quizrecord qr
                       WHERE qr.quizid = quizconfig.quizid
                         AND qr.rollnumber = ?
                   ) < attempts
                 ORDER BY starttime ASC
                 LIMIT 1";
    $stmt_quiz = $conn->prepare($quiz_sql);
    $section_param = $section ? $section : '';
    $stmt_quiz->bind_param('isi', $class_id, $section_param, $rollnumber);
    if ($stmt_quiz->execute()) {
      $result_quiz = $stmt_quiz->get_result();
      if ($result_quiz && $result_quiz->num_rows > 0) {
        $upcoming_quiz = $result_quiz->fetch_assoc();
      }
    }
    $stmt_quiz->close();
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Narowal Public School And College - Student Portal</title>
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
    /* Notification Styles */
    .notification-popup {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      max-width: 450px;
      width: 100%;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 5px 25px rgba(0,0,0,0.2);
      z-index: 1000;
      overflow: hidden;
    }
    
    .notification-header {
      background-color: #9c27b0;
      color: white;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .notification-header h5 {
      margin: 0;
      font-weight: 600;
    }
    
    .notification-close {
      cursor: pointer;
      background: none;
      border: none;
      color: white;
      font-size: 20px;
    }
    
    .notification-content {
      padding: 20px;
      max-height: 300px;
      overflow-y: auto;
    }
    
    .notification-footer {
      padding: 15px 20px;
      text-align: right;
      border-top: 1px solid #eee;
    }
    
    .notification-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
      z-index: 999;
    }
    
    /* Notification Badge */
    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background-color: #f44336;
      color: white;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      font-size: 12px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    
    .notifications-icon {
      position: relative;
      display: inline-block;
    }
  </style>
</head>

<body class="landing-page sidebar-collapse">
  <header class="header">
      <div class="header-content">
          <div class="school-name">
              Narowal Public School And College
              <span>Educating The Souls</span>
          </div>
      </div>
  </header>
  <nav class="navbar navbar-transparent navbar-color-on-scroll fixed-top navbar-expand-lg" color-on-scroll="100" id="sectionsNav">
    <div class="container">
      <div class="navbar-translate">
        <a class="navbar-brand" href="">
          Genesis </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation" >
          <span class="sr-only">Toggle navigation</span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse" >
        <ul class="navbar-nav ml-auto" >
          <li class="nav-item" >
            <a href="quizhome.php" class="nav-link">
              <i class="material-icons">queue_play_next</i> Attempt Quiz
            </a>
          </li>
          <li class="nav-item">
            <a href="my_results.php" class="nav-link">
              <i class="material-icons">assessment</i> My Results
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link" id="show-notifications">
              <div class="notifications-icon">
                <i class="material-icons">notifications</i>
                <?php if($notifications && $notifications->num_rows > 0): ?>
                <div class="notification-badge"><?php echo $notifications->num_rows; ?></div>
                <?php endif; ?>
              </div>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" rel="tooltip" title="" data-placement="bottom" href="studentlogout.php" data-original-title="Get back to Login Page">
              <i class="material-icons">power_settings_new</i></i> Log Out
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
            <h4 class="title">Student Portal</h4>
            <?php if(isset($upcoming_quiz) && $upcoming_quiz): ?>
            <div class="alert alert-info" style="margin-top:20px;">
                <strong>Upcoming Quiz:</strong>
                <?php echo htmlspecialchars($upcoming_quiz['quizname']); ?> |
                Starts at: <?php echo date('M d, Y h:i A', strtotime($upcoming_quiz['starttime'])); ?> |
                Marks: <?php echo htmlspecialchars($upcoming_quiz['maxmarks']); ?>
            </div>
            <?php endif; ?>
        </div>
        <br>
        <br>
      </div>
    </div>
  </div>  
    <footer class="footer footer-default">
        <div class="container">
            <div class="copyright" style="text-align: center; width: 100%;">
                Biology Department NPS<br>
                Designed By Sir Hassan Tariq<br>
                &copy;
                <script>
                    document.write(new Date().getFullYear())
                </script>
            </div>
        </div>
    </footer>
    
  <!-- Notification Overlay -->
  <div class="notification-overlay" id="notification-overlay"></div>
  
  <!-- Notifications Popup -->
  <div class="notification-popup" id="notification-popup">
    <div class="notification-header">
      <h5><i class="material-icons">notifications</i> Notifications</h5>
      <button class="notification-close" id="close-notification">&times;</button>
    </div>
    <div class="notification-content">
      <?php if($notifications && $notifications->num_rows > 0): ?>
        <?php while($notification = $notifications->fetch_assoc()): ?>
          <div class="notification-item" style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
            <h6 style="margin-bottom: 5px; font-weight: 600;"><?php echo htmlspecialchars($notification['title']); ?></h6>
            <p style="margin-bottom: 5px;"><?php echo nl2br(htmlspecialchars($notification['message'])); ?></p>
            <small style="color: #777;"><?php echo date('M d, Y h:i A', strtotime($notification['created_at'])); ?></small>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">No new notifications</p>
      <?php endif; ?>
    </div>
    <div class="notification-footer">
      <button class="btn btn-primary btn-sm" id="close-popup">Close</button>
    </div>
  </div>
  
  <script src="./assets/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="./assets/js/core/popper.min.js" type="text/javascript"></script>
  <script src="./assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
  <script src="./assets/js/plugins/moment.min.js"></script>
  <script src="./assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
  <script src="./assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
  <script src="./assets/js/plugins/jquery.sharrre.js" type="text/javascript"></script>
  <script src="./assets/js/material-kit.js?v=2.0.4" type="text/javascript"></script>
  
  <script>
    $(document).ready(function() {
      // Show first notification automatically if there are notifications
      <?php if($notifications && $notifications->num_rows > 0): ?>
      setTimeout(function() {
        showNotification();
      }, 1000);
      <?php endif; ?>
      
      // Show notification popup when notification icon is clicked
      $("#show-notifications").click(function(e) {
        e.preventDefault();
        showNotification();
      });
      
      // Close notification popup
      $("#close-notification, #close-popup").click(function() {
        hideNotification();
      });
      
      // Close notification when overlay is clicked
      $("#notification-overlay").click(function() {
        hideNotification();
      });
      
      function showNotification() {
        $("#notification-overlay").fadeIn(300);
        $("#notification-popup").fadeIn(300);
      }
      
      function hideNotification() {
        $("#notification-overlay").fadeOut(300);
        $("#notification-popup").fadeOut(300);
      }
    });
  </script>
</body>
</html>