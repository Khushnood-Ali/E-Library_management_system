<?php
session_start();
include 'db.php';

// Restrict access to logged-in users only
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];
    $borrow_date = date("Y-m-d");
    $due_date = date("Y-m-d", strtotime("+14 days")); // 2 weeks due date

    // Check if the book is available
    $check_sql = "SELECT availability FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['availability'] == 1) {
        // Borrow the book
        $borrow_sql = "INSERT INTO borrowed_books (user_id, book_id, borrow_date, due_date) 
                       VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($borrow_sql);
        $stmt->bind_param("iiss", $user_id, $book_id, $borrow_date, $due_date);

        if ($stmt->execute()) {
            // Update book availability to 0 (not available)
            $update_sql = "UPDATE books SET availability = 0 WHERE book_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("i", $book_id);
            $stmt->execute();

            echo "Book borrowed successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "This book is not available for borrowing.";
    }
} else {
    echo "Invalid request.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrow a Book</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Borrow a Book</h1>
    <form method="post">
        <label for="book_id">Book ID:</label>
        <input type="number" id="book_id" name="book_id" required>
        <button type="submit">Borrow</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>