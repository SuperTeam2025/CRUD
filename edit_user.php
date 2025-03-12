<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $idNumber = $_POST['id_number'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, id_number = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $idNumber, $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating record!";
    }
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<form method="POST" action="edit_user.php">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
    <label>ID Number:</label>
    <input type="text" name="id_number" value="<?php echo $user['id_number']; ?>" required>
    <button type="submit">Update</button>
</form>
