<?php
session_start();
include "database.php";

// Check if student is logged in
if(!isset($_SESSION["studentloggedin"]) || $_SESSION["studentloggedin"] !== true) {
    header("location: studentlogin.php");
    exit;
}

$rollnumber = $_SESSION["rollnumber"];

// Get student's quiz results
$sql = "SELECT 
            qc.quizid,
            qc.quizname,
            qc.quiznumber,
            c.class_name,
            s.subject_name,
            r.attempt,
            r.mcqmarks + r.numericalmarks + r.dropdownmarks + r.fillmarks + r.shortmarks + r.essaymarks as total_marks,
            qc.maxmarks,
            qr.starttime,
            qr.endtime,
            TIMESTAMPDIFF(MINUTE, qr.starttime, qr.endtime) as time_taken
        FROM result r
        JOIN quizconfig qc ON r.quizid = qc.quizid
        JOIN quizrecord qr ON r.quizid = qr.quizid AND r.rollnumber = qr.rollnumber AND r.attempt = qr.attempt
        LEFT JOIN classes c ON qc.class_id = c.class_id
        LEFT JOIN subjects s ON qc.subject_id = s.subject_id
        WHERE r.rollnumber = ?
        ORDER BY qr.starttime DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $rollnumber);
$stmt->execute();
$results = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Quiz Results</title>
    <!-- Material Dashboard CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@material/material-dashboard@1.0.0/dist/css/material-dashboard.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Roboto', sans-serif;
        }
        .card {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            margin-top: 20px;
        }
        .card-header-primary {
            background: linear-gradient(60deg, #ab47bc, #8e24aa);
            box-shadow: 0 4px 20px 0px rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(156, 39, 176, 0.4);
            border-radius: 8px 8px 0 0;
            padding: 15px;
            color: white;
        }
        .result-row {
            transition: all 0.3s ease;
        }
        .result-row:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .score-badge {
            font-size: 1.2em;
            padding: 8px 15px;
        }
        .back-btn {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <a href="quizhome.php" class="btn btn-primary back-btn">
                    <i class="material-icons">arrow_back</i> Back to Quiz Home
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">My Quiz Results</h4>
                <p class="card-category">View all your quiz attempts and scores</p>
            </div>
            <div class="card-body">
                <?php if ($results->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Quiz Name</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>Attempt</th>
                                    <th>Score</th>
                                    <th>Time Taken</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $results->fetch_assoc()): 
                                    $percentage = ($row['total_marks'] / $row['maxmarks']) * 100;
                                    $badge_class = 'badge-';
                                    if ($percentage >= 80) $badge_class .= 'success';
                                    else if ($percentage >= 60) $badge_class .= 'info';
                                    else if ($percentage >= 40) $badge_class .= 'warning';
                                    else $badge_class .= 'danger';
                                ?>
                                    <tr class="result-row">
                                        <td>
                                            <strong><?php echo htmlspecialchars($row['quizname']); ?></strong>
                                            <br>
                                            <small class="text-muted">Quiz #<?php echo htmlspecialchars($row['quiznumber']); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['attempt']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $badge_class; ?> score-badge">
                                                <?php echo $row['total_marks']; ?>/<?php echo $row['maxmarks']; ?>
                                                (<?php echo round($percentage, 1); ?>%)
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['time_taken']); ?> minutes</td>
                                        <td>
                                            <?php 
                                                $start_date = new DateTime($row['starttime']);
                                                echo $start_date->format('d M Y, h:i A'); 
                                            ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="material-icons">info</i>
                        You haven't attempted any quizzes yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 