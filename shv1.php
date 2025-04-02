<?php
session_start();

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "uday";

// Check if user is logged in
$isLoggedIn = isset($_SESSION['email']);
$homePage = 'main.php'; // Default home page

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // If logged in, get user's gender from database
    if ($isLoggedIn) {
        $stmt = $conn->prepare("SELECT gen FROM ogym WHERE email = ?");
        $stmt->execute([$_SESSION['email']]);
        $user = $stmt->fetch();
        $homePage = ($user['gen'] === 'Female') ? 'main_girl.php' : 'main.php';
    }
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
}

// Handle login link click
if (isset($_GET['action']) && $_GET['action'] === 'login') {
    if ($isLoggedIn) {
        header("Location: $homePage");
        exit();
    } else {
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbell Overhead Press - O-GYM</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Oswald:wght@500&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    :root {
        --primary: #2b5876;
        --secondary: #4e4376;
        --accent: #f857a6;
        --light: #f8f9fa;
        --dark: #212529;
        --success: #28a745;
        --warning: #ffc107;
        --danger: #dc3545;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Montserrat', sans-serif;
    }

    body {
        background: linear-gradient(135deg, #e0f7fa, #b2ebf2);
        color: var(--dark);
        min-height: 100vh;
        overflow-x: hidden;
    }

    header.main-header {
        background: linear-gradient(to right, var(--primary), var(--secondary));
        color: white;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        position: relative;
        z-index: 100;
    }

    .logo-container {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .logo-img {
        height: 60px;
        transition: transform 0.3s ease;
    }

    .logo-img:hover {
        transform: scale(1.05) rotate(-5deg);
    }

    .logo-text {
        font-family: 'Oswald', sans-serif;
        font-size: 2.5rem;
        background: linear-gradient(to right, #f857a6, #ff5858);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    nav ul {
        display: flex;
        list-style: none;
        gap: 1.5rem;
    }

    nav a {
        color: white;
        text-decoration: none;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        transition: all 0.3s ease;
        position: relative;
    }

    nav a:hover {
        background: rgba(255,255,255,0.2);
        transform: translateY(-2px);
    }

    nav a::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 0;
        background-color: var(--accent);
        transition: width 0.3s ease;
    }

    nav a:hover::after {
        width: 100%;
    }

    /* Exercise Content Styles */
    .exercise-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 2rem;
        text-align: center;
    }

    .exercise-title {
        font-size: 2.5rem;
        color: var(--primary);
        margin: 1.5rem 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .exercise-video {
        width: 100%;
        max-width: 800px;
        height: auto;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        margin: 2rem auto;
    }

    .navigation-buttons {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin: 2rem 0;
    }

    .button {
        position: relative;
        padding: 1rem 2rem;
        background: linear-gradient(45deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(43, 88, 118, 0.3);
    }

    .button span {
        position: relative;
        z-index: 1;
    }

    .button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(43, 88, 118, 0.4);
    }

    .button:active {
        transform: translateY(1px);
    }

    hr.divider {
        border: 0;
        height: 2px;
        background: linear-gradient(to right, transparent, var(--accent), transparent);
        margin: 2rem 0;
    }

    footer.main-footer {
        background: linear-gradient(to right, var(--primary), var(--secondary));
        color: white;
        text-align: center;
        padding: 1.5rem;
        margin-top: 2rem;
        font-size: 0.9rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .exercise-title {
            font-size: 2rem;
        }
        
        .navigation-buttons {
            flex-direction: column;
            gap: 1rem;
        }
        
        .button {
            width: 100%;
        }
        
        nav ul {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        header.main-header {
            flex-direction: column;
            padding: 1rem;
        }
        
        .logo-container {
            margin-bottom: 1rem;
        }
    }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="logo-container">
            <img src="img/logo.png" alt="O-GYM Logo" class="logo-img">
            <h1 class="logo-text">O-GYM</h1>
        </div>
        <nav>
            <ul>
                <li><a href="?action=login"><?php echo $isLoggedIn ? 'Home' : 'Login'; ?></a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="logout.php">Logout</a></li>
                    <li><a href="profile.php">Profile</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="exercise-container">
        <hr class="divider">
        
        <div class="navigation-buttons">
            <form action="shoulder.php">
                <button class="button"><span>Back</span></button>
            </form>
            <form action="<?php echo $isLoggedIn ? $homePage : 'login.php'; ?>">
                <button class="button"><span><?php echo $isLoggedIn ? 'Home' : 'Login'; ?></span></button>
            </form>
        </div>
        
        <hr class="divider">
        
        <h1 class="exercise-title">Barbell Overhead Press</h1>
        
        <hr class="divider">
        
        <video class="exercise-video" width="800" height="450" controls>
            <source src="img/shv1.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <footer class="main-footer">
        <p>&copy; 2024 O-GYM. All rights reserved.</p>
    </footer>
</body>
</html>