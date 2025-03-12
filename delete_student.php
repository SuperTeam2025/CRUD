<?php
include 'db_connect.php';

if (isset($_GET['id_number'])) {
    $id_number = $_GET['id_number'];

    // Get user ID from ID Number
    $stmt = $conn->prepare("SELECT id FROM users WHERE id_number = ?");
    $stmt->bind_param("s", $id_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];

        // Delete from enrollments table first (to avoid foreign key issues)
        $stmt = $conn->prepare("DELETE FROM enrollments WHERE student_id = ? OR teacher_id = ?");
        $stmt->bind_param("ii", $user_id, $user_id);
        $stmt->execute();

        // Now delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            echo "User deleted successfully.";
        } else {
            echo "Error deleting user.";
        }
    } else {
        echo "User not found!";
    }

    $stmt->close();
    $conn->close();
    header("Location: dashboard.php");
    exit();
}
?>
