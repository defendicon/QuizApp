<?php
include 'database.php';

header('Content-Type: application/json');

if (!isset($_GET['chapter_ids'])) {
    echo json_encode(['error' => 'No chapter IDs provided']);
    exit;
}

$chapter_ids = explode(',', $_GET['chapter_ids']);
$chapter_ids = array_map('intval', $chapter_ids); // Sanitize input
$chapter_ids_str = implode(',', $chapter_ids);

$counts = array(
    'mcq' => 0,
    'numerical' => 0,
    'dropdown' => 0,
    'fillblanks' => 0,
    'short' => 0,
    'essay' => 0
);

if (!empty($chapter_ids)) {
    // Count MCQs
    $sql = "SELECT COUNT(*) as count FROM mcqdb WHERE chapter_id IN ($chapter_ids_str)";
    $result = $conn->query($sql);
    $counts['mcq'] = (int)$result->fetch_assoc()['count'];
    
    // Count Numerical
    $sql = "SELECT COUNT(*) as count FROM numericaldb WHERE chapter_id IN ($chapter_ids_str)";
    $result = $conn->query($sql);
    $counts['numerical'] = (int)$result->fetch_assoc()['count'];
    
    // Count Dropdown
    $sql = "SELECT COUNT(*) as count FROM dropdown WHERE chapter_id IN ($chapter_ids_str)";
    $result = $conn->query($sql);
    $counts['dropdown'] = (int)$result->fetch_assoc()['count'];
    
    // Count Fill in Blanks
    $sql = "SELECT COUNT(*) as count FROM fillintheblanks WHERE chapter_id IN ($chapter_ids_str)";
    $result = $conn->query($sql);
    $counts['fillblanks'] = (int)$result->fetch_assoc()['count'];
    
    // Count Short Answer
    $sql = "SELECT COUNT(*) as count FROM shortanswer WHERE chapter_id IN ($chapter_ids_str)";
    $result = $conn->query($sql);
    $counts['short'] = (int)$result->fetch_assoc()['count'];
    
    // Count Essay
    $sql = "SELECT COUNT(*) as count FROM essay WHERE chapter_id IN ($chapter_ids_str)";
    $result = $conn->query($sql);
    $counts['essay'] = (int)$result->fetch_assoc()['count'];
}

echo json_encode($counts);
?> 