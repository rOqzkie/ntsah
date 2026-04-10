<?php
ob_start();
ini_set('display_errors', 0);
require '../config.php'; // Ensure database connection
header('Content-Type: application/json');

try {
    // Get the logged-in user's department
    $department_id = $_settings->userdata('department_id');
    $user_type = $_settings->userdata('type');

    if ($user_type == 2) { // If user is a Department Chair
        // Count pending archives for the assigned department
        $query1 = $conn->prepare("SELECT COUNT(*) as count FROM archive_list WHERE status = 0 AND department_id = ?");
        $query1->bind_param("i", $department_id);
        $query1->execute();
        $result1 = $query1->get_result();
        $pendingArchive = $result1->fetch_assoc()['count'];

        // Count pending advisers for the assigned department
        $query3 = $conn->prepare("SELECT COUNT(*) as count FROM adviser_list WHERE status = 0 AND department_id = ?");
        $query3->bind_param("i", $department_id);
        $query3->execute();
        $result3 = $query3->get_result();
        $pendingAdvisers = $result3->fetch_assoc()['count'];

        $response = [
            "pendingArchive" => $pendingArchive,
            "pendingStudents" => 0, // Department chairs shouldn't see student verifications
            "pendingAdvisers" => $pendingAdvisers,
        ];
    } else { // If user is Admin (User type 1)
        $query1 = $conn->query("SELECT COUNT(*) as count FROM archive_list WHERE status = 0");
        $query2 = $conn->query("SELECT COUNT(*) as count FROM student_list WHERE status = 0");
        $query3 = $conn->query("SELECT COUNT(*) as count FROM adviser_list WHERE status = 0");

        $response = [
            "pendingArchive" => $query1 ? $query1->fetch_assoc()['count'] : 0,
            "pendingStudents" => $query2 ? $query2->fetch_assoc()['count'] : 0,
            "pendingAdvisers" => $query3 ? $query3->fetch_assoc()['count'] : 0,
        ];
    }
} catch (Exception $e) {
    $response = [
        "pendingArchive" => 0,
        "pendingStudents" => 0,
        "pendingAdvisers" => 0,
        "error" => $e->getMessage(),
    ];
}

ob_clean();
echo json_encode($response);
ob_end_flush();
?>