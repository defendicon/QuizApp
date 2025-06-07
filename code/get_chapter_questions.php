<?php
include 'database.php';

header('Content-Type: application/json');

// Get parameters
$chapter_ids = isset($_GET['chapter_ids']) ? explode(',', $_GET['chapter_ids']) : [];
$question_type = isset($_GET['type']) ? $_GET['type'] : '';

// Sanitize input
$chapter_ids = array_map('intval', $chapter_ids);
$chapter_ids_str = implode(',', $chapter_ids);

if (empty($chapter_ids) || empty($chapter_ids_str)) {
    echo json_encode(['error' => 'No chapter IDs provided']);
    exit;
}

if (empty($question_type)) {
    echo json_encode(['error' => 'No question type specified']);
    exit;
}

$questions = [];

try {
    switch ($question_type) {
        case 'mcq':
            $sql = "SELECT id, question, optiona, optionb, optionc, optiond, answer, chapter_id 
                    FROM mcqdb 
                    WHERE chapter_id IN ($chapter_ids_str)
                    ORDER BY id";
            break;
            
        case 'numerical':
            $sql = "SELECT id, question, answer, chapter_id 
                    FROM numericaldb 
                    WHERE chapter_id IN ($chapter_ids_str)
                    ORDER BY id";
            break;
            
        case 'dropdown':
            $sql = "SELECT id, question, options, answer, chapter_id 
                    FROM dropdown 
                    WHERE chapter_id IN ($chapter_ids_str)
                    ORDER BY id";
            break;
            
        case 'fillblanks':
            $sql = "SELECT id, sentence, answer, chapter_id 
                    FROM fillintheblanks 
                    WHERE chapter_id IN ($chapter_ids_str)
                    ORDER BY id";
            break;
            
        case 'short':
            $sql = "SELECT id, question, answer, chapter_id 
                    FROM shortanswer 
                    WHERE chapter_id IN ($chapter_ids_str)
                    ORDER BY id";
            break;
            
        case 'essay':
            $sql = "SELECT id, question, answer, chapter_id 
                    FROM essay 
                    WHERE chapter_id IN ($chapter_ids_str)
                    ORDER BY id";
            break;
            
        default:
            echo json_encode(['error' => 'Invalid question type']);
            exit;
    }

    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Query error: " . $conn->error);
    }
    
    while ($row = $result->fetch_assoc()) {
        // Add prefix to id for identification when saving selected questions
        $row['unique_id'] = $question_type . '_' . $row['id'];
        $questions[] = $row;
    }
    
    echo json_encode($questions);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?> 