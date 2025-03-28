<?php
include 'db.php';

// Fetch overdue books
$sql = "SELECT users.email, books.title, borrowed_books.due_date 
        FROM borrowed_books 
        JOIN users ON borrowed_books.user_id = users.user_id 
        JOIN books ON borrowed_books.book_id = books.book_id 
        WHERE borrowed_books.return_date IS NULL AND borrowed_books.due_date < CURDATE()";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $to = $row['email'];
        $subject = "Overdue Book Alert";
        $message = "Your book '{$row['title']}' is overdue. Please return it immediately.";
        $headers = "From: library@example.com";

        // Send email
        if (mail($to, $subject, $message, $headers)) {
            echo "Alert sent to {$row['email']} for '{$row['title']}'.<br>";
        } else {
            echo "Failed to send alert to {$row['email']}.<br>";
        }
    }
} else {
    echo "No overdue books found.";
}
?>