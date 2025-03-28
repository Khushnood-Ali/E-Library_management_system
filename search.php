<?php
session_start();
include 'db.php';

$search_term = "";
$result = null;

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search_term = trim($_GET['search']);
    $search_sql = "SELECT DISTINCT books.book_id, books.title, books.author, books.genre, books.total_copies, 
    (books.total_copies - COALESCE(borrowed.borrowed_count, 0)) AS available_copies 
    FROM books 
    LEFT JOIN (SELECT book_id, COUNT(*) AS borrowed_count 
        FROM borrowed_books 
        WHERE return_date IS NULL 
        GROUP BY book_id) AS borrowed 
    ON books.book_id = borrowed.book_id 
    WHERE books.title LIKE ? OR books.author LIKE ? OR books.genre LIKE ?";


    
    $stmt = $conn->prepare($search_sql);
    $like_search = "%$search_term%";
    $stmt->bind_param("sss", $like_search, $like_search, $like_search);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books - E-Library</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: 'Poppins', sans-serif;
        }
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            background: #222;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(255, 0, 0, 0.5);
        }
        .form-control {
            background: #333;
            color: white;
            border: 1px solid #e63946;
        }
        .btn-search {
            background: #e63946;
            color: white;
            border: none;
            transition: 0.3s;
        }
        .btn-search:hover {
            background: #ff0000;
        }
        .book-list {
            margin-top: 20px;
        }
        .book-item {
            background: #222;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 3px 10px rgba(255, 0, 0, 0.3);
            margin-bottom: 10px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="search-container text-center">
        <h2>Search Books</h2>
        <form method="get">
            <div class="mb-3">
                <input type="text" id="search" name="search" class="form-control" placeholder="Enter book title, author, or genre" value="<?php echo htmlspecialchars($search_term); ?>">
            </div>
            <button type="submit" class="btn btn-search w-100">Search</button>
        </form>
        <div class="book-list">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='book-item'>
                            <h3>{$row['title']}</h3>
                            <p><strong>Author:</strong> {$row['author']}</p>
                            <p><strong>Genre:</strong> {$row['genre']}</p>
                            <p><strong>Available Copies:</strong> {$row['available_copies']} / {$row['total_copies']}</p>
                          </div>";
                }
            } else {
                echo "<p class='mt-3'>No books found.</p>";
            }
            ?>
        </div>
        <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
</body>
</html>
