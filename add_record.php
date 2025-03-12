<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    echo "Access denied!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = $_SESSION['user']['id'];
    $student_id = $_POST['student_id'];
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);

    // Input validation
    if (empty($student_id) || empty($subject) || empty($description)) {
        echo "All fields are required!";
        exit();
    }

    // Check if student exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ? AND role = 'student'");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 0) {
        echo "Student not found!";
        exit();
    }
    $stmt->close();

    // Insert record into database
    $stmt = $conn->prepare("INSERT INTO records (teacher_id, student_id, subject, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $teacher_id, $student_id, $subject, $description);

    if ($stmt->execute()) {
        echo "Record added successfully!";
    } else {
        echo "Error adding record!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Record</title>
</head>
<body>
    <h2>Add Record</h2>
    <form action="add_record.php" method="POST">
        <label for="student_id">Student:</label>
        <select name="student_id" required>
            <option value="">-- Select Student --</option>
            <?php
            $students = $conn->query("SELECT id, name FROM users WHERE role = 'student'");
            while ($student = $students->fetch_assoc()) {
                echo "<option value='{$student['id']}'>{$student['name']}</option>";
            }
            ?>
        </select>

        <label for="subject">Subject:</label>
        <input type="text" name="subject" required>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea>

        <button type="submit">Add Record</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
