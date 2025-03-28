<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT books.book_id, books.title, borrowed_books.borrow_date, borrowed_books.due_date 
        FROM borrowed_books 
        JOIN books ON borrowed_books.book_id = books.book_id 
        WHERE borrowed_books.user_id = ? AND borrowed_books.return_date IS NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['borrow_book_id'])) {
        $book_id = $_POST['borrow_book_id'];
        $borrow_date = date("Y-m-d");
        $due_date = date("Y-m-d", strtotime("+14 days"));

        $check_sql = "SELECT availability FROM books WHERE book_id = ?";
        $stmt = $conn->prepare($check_sql);
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }        
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $check_result = $stmt->get_result();
        $row = $check_result->fetch_assoc();

        if ($row['availability'] == 1) {
            $borrow_sql = "INSERT INTO borrowed_books (user_id, book_id, borrow_date, due_date) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($borrow_sql);
            $stmt->bind_param("iiss", $user_id, $book_id, $borrow_date, $due_date);
            if ($stmt->execute()) {
                $update_sql = "UPDATE books SET availability = 0 WHERE book_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("i", $book_id);
                $stmt->execute();
                echo "Book borrowed successfully!";
            } else {
                echo "Error borrowing book.";
            }
        } else {
            echo "This book is not available for borrowing.";
        }
    } elseif (isset($_POST['return_book_id'])) {
        $book_id = $_POST['return_book_id'];
        $return_date = date("Y-m-d");

        $return_sql = "UPDATE borrowed_books SET return_date = ? WHERE user_id = ? AND book_id = ? AND return_date IS NULL";
        $stmt = $conn->prepare($return_sql);
        $stmt->bind_param("sii", $return_date, $user_id, $book_id);
        if ($stmt->execute()) {
            $update_sql = "UPDATE books SET availability = 1 WHERE book_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            echo "Book returned successfully!";
        } else {
            echo "Error returning book.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light">
    <div class="container mt-5">
        <h1 class="text-center">Welcome, <?php echo $_SESSION['username']; ?></h1>
        <h2 class="text-center mt-4">Your Borrowed Books</h2>
        <div class="row mt-4">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card bg-secondary text-light p-3">
                        <h3><?php echo $row['title']; ?></h3>
                        <p><strong>Book ID:</strong> <?php echo $row['book_id']; ?></p>
                        <p><strong>Borrowed on:</strong> <?php echo $row['borrow_date']; ?></p>
                        <p><strong>Due on:</strong> <?php echo $row['due_date']; ?></p>
                        <form method="post" class="mt-2">
                            <input type="hidden" name="return_book_id" value="<?php echo $row['book_id']; ?>">
                            <button type="submit" class="btn btn-success">Return</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <h2 class="text-center mt-5">Borrow a Book</h2>
        <form method="post" class="mt-3 text-center">
            <label for="borrow_book_id" class="form-label">Enter Book ID:</label>
            <input type="number" id="borrow_book_id" name="borrow_book_id" required class="form-control w-25 mx-auto">
            <button type="submit" class="btn btn-danger mt-3">Borrow</button>
        </form>
        <div class="text-center mt-4">
            <a href="search.php" class="btn btn-outline-light">Search Books</a>
            <a href="logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
