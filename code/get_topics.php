<?php
header('Content-Type: application/json');
include "database.php";
$chapter_id = isset($_GET['chapter_id']) ? intval($_GET['chapter_id']) : 0;
$topics = [];
if ($chapter_id > 0) {
    $stmt = $conn->prepare("SELECT topic_id, topic_name FROM topics WHERE chapter_id = ? ORDER BY topic_name ASC");
    if ($stmt) {
        $stmt->bind_param('i', $chapter_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $topics[] = $row;
        }
        $stmt->close();
    }
}
echo json_encode($topics);
?>
