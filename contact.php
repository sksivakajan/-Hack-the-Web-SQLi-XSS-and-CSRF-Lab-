<?php
session_start();

// Database connection
$servername = "localhost"; // Update with your database server
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "ws"; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check CSRF token
    
        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $message = trim($_POST['message']);
        
        // Input validation
        if (empty($fullname) || empty($email) || empty($message)) {
            echo "All fields are required!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format!";
        } else {
            // Sanitize inputs
            $fullname = htmlspecialchars($fullname);
            $email = htmlspecialchars($email);
            $message = htmlspecialchars($message);

            // Save message to the database
            $stmt = $conn->prepare("INSERT INTO contact_messages (fullname, email, message) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $fullname, $email, $message);
            if ($stmt->execute()) {
                echo "Message sent! Thank you for contacting us.";

                // Optionally send an email
                $to = 'your_email@example.com'; // Update with your email address
                $subject = 'New Contact Form Message';
                $body = "Name: $fullname\nEmail: $email\nMessage:\n$message";
                $headers = "From: $email\r\n";

                if (mail($to, $subject, $body, $headers)) {
                    echo "An email has been sent.";
                } else {
                    echo "Failed to send email.";
                }
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        echo "Invalid CSRF token.";
    }

$conn->close();
?>

<!-- Contact form HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <h1>WEB SECURITY</h1>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="media.html">Media</a></li>
                <li><a href="careers.html">Careers</a></li>
                <li><a href="">Contact</a></li>
                <li><a href="login.html">LOGIN</a></li>
            </ul>
        </nav>
    </header>

    <main>
<section class="contact-section">
<h2>Contact Us</h2>
<form method="POST" action="">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <label for="fullname">Full Name:</label>
    <input type="text" name="fullname" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" name="email" required>
    <br>
    <label for="message">Message:</label>
    <textarea name="message" required></textarea>
    <br>
    <button type="submit">Send</button>
</form>
</section>
</main>

<footer>
        <p>&copy; 2024 WEB SECURITY. All rights reserved.</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>