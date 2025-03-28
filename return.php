<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];
    $return_date = date("Y-m-d");

    // Check if the book is borrowed by the user
    $check_sql = "SELECT * FROM borrowed_books 
                  WHERE user_id = $user_id AND book_id = $book_id AND return_date IS NULL";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Return the book
        $return_sql = "UPDATE borrowed_books SET return_date = '$return_date' 
                       WHERE user_id = $user_id AND book_id = $book_id";
        if ($conn->query($return_sql) {
            // Update book availability
            $update_sql = "UPDATE books SET availability = 1 WHERE book_id = $book_id";
            $conn->query($update_sql);
            echo "Book returned successfully!";
        } else {
            echo "Error: " . $return_sql . "<br>" . $conn->error;
        }
    } else {
        echo "You have not borrowed this book.";
    }
} else {
    echo "Invalid request.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Return a Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Return a Book</h1>
    <form method="post">
        <label for="book_id">Book ID:</label>
        <input type="number" id="book_id" name="book_id" required>
        <button type="submit">Return</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>