<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    echo "Access denied!";
    exit();
}

$student_id = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT records.id, users.name AS teacher_name, records.subject, records.description, records.created_at 
                        FROM records 
                        JOIN users ON records.teacher_id = users.id 
                        WHERE records.student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Records</title>
</head>
<body>
    <h2>My Records</h2>
    <table border="1">
        <tr>
            <th>Teacher</th>
            <th>Subject</th>
            <th>Description</th>
            <th>Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
        <?php } ?>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
