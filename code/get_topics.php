<?php
header('Content-Type: application/json');
include "database.php";
$chapter_ids = [];
if (isset($_GET['chapter_ids'])) {
    $chapter_ids = array_map('intval', explode(',', $_GET['chapter_ids']));
} elseif (isset($_GET['chapter_id'])) {
    $chapter_ids = [intval($_GET['chapter_id'])];
}
$topics = [];
if (!empty($chapter_ids)) {
    $placeholders = implode(',', array_fill(0, count($chapter_ids), '?'));
    $types = str_repeat('i', count($chapter_ids));
    $sql = "SELECT topic_id, topic_name FROM topics WHERE chapter_id IN ($placeholders) ORDER BY topic_name ASC";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param($types, ...$chapter_ids);
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
