<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    echo "Access denied!";
    exit();
}

$teacher_id = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT records.id, users.name AS student_name, records.subject, records.description, records.created_at 
                        FROM records 
                        JOIN users ON records.student_id = users.id 
                        WHERE records.teacher_id = ?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Records</title>
</head>
<body>
    <h2>Your Records</h2>
    <table border="1">
        <tr>
            <th>Student</th>
            <th>Subject</th>
            <th>Description</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a href="edit_record.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                    <a href="delete_record.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
