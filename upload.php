<?php
if ($_FILES['image']['error'] == 0) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo json_encode(['status' => 'success', 'file' => basename($_FILES["image"]["name"])]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'File error']);
}
?>
