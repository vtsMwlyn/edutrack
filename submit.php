<?php
// Proses form submit

include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["id_siswa"];
    $task_title = $_POST["nama_tugas"];
    $task_description = $_POST["deskripsi"];

    // Proses upload file
    $target_dir = "uploads/";
    $file_name = basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        // Insert data ke dalam database
        $stmt = $conn->prepare("INSERT INTO assignments (id_siswa, nama_tugas, deskripsi, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $student_id, $task_title, $task_description, $target_file);

        if ($stmt->execute()) {
            echo "Assignment submitted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Assignment</title>
</head>
<body>
    <h2>Submit Your Assignment</h2>
    <form action="submit_task.php" method="post" enctype="multipart/form-data">
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" required><br><br>

        <label for="task_title">Task Title:</label>
        <input type="text" name="task_title" required><br><br>

        <label for="task_description">Task Description:</label>
        <textarea name="task_description" rows="4" cols="50"></textarea><br><br>

        <label for="file">Upload File:</label>
        <input type="file" name="file" required><br><br>

        <input type="submit" value="Submit Assignment">
    </form>
</body>
</html>
