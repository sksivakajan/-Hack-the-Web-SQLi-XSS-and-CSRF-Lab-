<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Search Result</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            font-family: 'Arial', sans-serif;
        }

        .result {
            text-align: center;
            border: 2px solid #fff;
            padding: 20px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .button {
            padding: 10px 20px;
            background-color: #ff5722;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>

<div class="result">
    <?php
   if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];

    // Vulnerable reflection of user input
    echo "<div class='result'>You searched for: " . $searchQuery . "</div>";

    // Check if the user triggered an XSS attack with script tag
    if (strpos($searchQuery, '<script>alert(') !== false) {
        echo "<script>window.location.href = 'con.html';</script>";
    }
} else {
    echo "<div class='result'> No search query provided.</div>";
}

    ?>
</div>

</body>
</html>
