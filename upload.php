<?php
if ($_FILES['image']['error'] == 0) {
    $target_dir = "uploads/";
    $file_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES["image"]["name"]));
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo json_encode(['status' => 'success', 'file' => $file_name]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'File error']);
}
?>