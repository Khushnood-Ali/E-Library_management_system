<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: #e63946;
        }
        header {
            background: linear-gradient(90deg, #ff0000, #8b0000);
            padding: 20px 0;
            text-align: center;
            color: white;
        }
        header h1 {
            font-weight: 700;
        }
        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }
        nav a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            padding: 40px;
        }
        .book-item {
            background: #222;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 6px 12px rgba(255, 0, 0, 0.3);
            transition: all 0.3s;
            text-align: center;
            position: relative;
            color: white;
        }
        .book-item:hover {
            transform: translateY(-8px);
            box-shadow: 0px 10px 18px rgba(255, 0, 0, 0.5);
        }
        .book-item h3 {
            font-weight: 600;
            color: #e63946;
        }
        .book-item p {
            font-size: 14px;
            color: #ddd;
        }
        .book-item::before {
            content: '\f02d';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            font-size: 40px;
            color: rgba(230, 57, 70, 0.5);
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .container {
            max-width: 1200px;
        }
    </style>
</head>
<body>
    <header>
        <h1>E-Library</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="search.php">Search Books</a>
        </nav>
    </header>
    <main class="container mt-5">
        <h2 class="text-center mb-4" style="color: #e63946; font-weight: 700;">Featured Books</h2>
        <div class="book-list">
            <?php
            $sql = "SELECT * FROM books WHERE availability = 1 ORDER BY RAND() LIMIT 6";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<div class='book-item'>
                        <h3>{$row['title']}</h3>
                        <p><strong>Author:</strong> {$row['author']}</p>
                        <p><strong>Genre:</strong> {$row['genre']}</p>
                      </div>";
            }
            ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
