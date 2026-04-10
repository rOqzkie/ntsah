<?php
require_once './config.php'; // Database connection

if(isset($_POST['folder_name']) && !empty($_POST['folder_name'])){
    $folder_name = $_POST['folder_name'];
    $user_id = $_SESSION['user_id']; 

    $stmt = $conn->prepare("INSERT INTO folders (user_id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $folder_name);
    if($stmt->execute()){
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}
?>