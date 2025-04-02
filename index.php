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
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About O-GYM - Your Ultimate Workout Guide</title>
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

    .logo {
        height: 60px;
        transition: transform 0.3s ease;
    }

    .logo:hover {
        transform: scale(1.05) rotate(-5deg);
    }

    header h1 {
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

    /* Main Content Styles */
    .about-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 2rem;
        animation: fadeIn 1s ease-out;
    }

    .about-hero {
        text-align: center;
        margin-bottom: 3rem;
    }

    .about-hero h2 {
        font-size: 2.8rem;
        margin-bottom: 1rem;
        color: var(--primary);
        font-weight: 700;
    }

    .about-hero p {
        font-size: 1.2rem;
        color: var(--dark);
        max-width: 800px;
        margin: 0 auto;
    }

    .mission-section, .workout-section, .team-section {
        margin-bottom: 3rem;
        padding: 2rem;
        background: rgba(255,255,255,0.8);
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .mission-section:hover, .workout-section:hover, .team-section:hover {
        transform: translateY(-5px);
    }

    .section-title {
        font-size: 2rem;
        color: var(--secondary);
        margin-bottom: 1.5rem;
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: '';
        position: absolute;
        width: 50%;
        height: 4px;
        bottom: -8px;
        left: 0;
        background: linear-gradient(to right, var(--accent), var(--primary));
        border-radius: 2px;
    }

    .workout-benefits {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .benefit-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .benefit-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .benefit-card h4 {
        color: var(--primary);
        margin-bottom: 1rem;
        font-size: 1.3rem;
    }

    .benefit-card i {
        font-size: 2rem;
        color: var(--accent);
        margin-bottom: 1rem;
        display: block;
    }

    .team-members {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 2rem;
        margin-top: 2rem;
    }

    .team-card {
        background: white;
        width: 250px;
        padding: 1.5rem;
        text-align: center;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .team-card img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 1rem;
        border: 3px solid var(--accent);
    }

    .team-card h4 {
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .team-card p {
        color: var(--secondary);
        font-style: italic;
    }

    .cta-section {
        text-align: center;
        margin: 3rem 0;
    }

    .cta-button {
        display: inline-block;
        background: linear-gradient(45deg, var(--accent), var(--primary));
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-size: 1.2rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(43, 88, 118, 0.3);
    }

    .cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(43, 88, 118, 0.4);
    }

    footer.main-footer {
        background: linear-gradient(to right, var(--primary), var(--secondary));
        color: white;
        text-align: center;
        padding: 1.5rem;
        margin-top: 2rem;
        font-size: 0.9rem;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .about-hero h2 {
            font-size: 2rem;
        }
        
        .mission-section, .workout-section, .team-section {
            padding: 1.5rem;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .team-members {
            flex-direction: column;
            align-items: center;
        }
        
        nav ul {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
    </style>
</head>
<body>
    <header class="main-header">
        <img src="img/logo.png" alt="Logo" class="logo">
        <h1>O-GYM</h1>
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

    <div class="about-container">
        <section class="about-hero">
            <h2>Welcome to O-GYM</h2>
            <p>Your ultimate guide to proper workout techniques and fitness transformation. We're dedicated to helping you achieve your fitness goals through scientifically-proven methods and personalized training approaches.</p>
        </section>

        <section class="mission-section">
            <h3 class="section-title">Our Mission</h3>
            <p>At O-GYM, we believe that proper form and technique are the foundation of any successful fitness journey. Our mission is to educate and empower individuals to perform exercises correctly, maximizing results while minimizing the risk of injury.</p>
            <p>We've created this platform to provide clear, step-by-step guidance for every major muscle group, ensuring you get the most out of every workout session.</p>
        </section>

        <section class="workout-section">
            <h3 class="section-title">How We Teach Workouts</h3>
            <p>Our comprehensive workout guides cover all aspects of gym training:</p>
            
            <div class="workout-benefits">
                <div class="benefit-card">
                    <i class="fas fa-dumbbell"></i>
                    <h4>Proper Form Demonstration</h4>
                    <p>Detailed instructions and visual guides showing the correct execution of each exercise to target the right muscles effectively.</p>
                </div>
                
                <div class="benefit-card">
                    <i class="fas fa-running"></i>
                    <h4>Movement Breakdown</h4>
                    <p>Step-by-step breakdowns of each phase of the movement to ensure perfect technique from start to finish.</p>
                </div>
                
                <div class="benefit-card">
                    <i class="fas fa-heartbeat"></i>
                    <h4>Breathing Techniques</h4>
                    <p>Guidance on when to inhale and exhale during exercises for optimal performance and safety.</p>
                </div>
                
                <div class="benefit-card">
                    <i class="fas fa-bullseye"></i>
                    <h4>Muscle Engagement</h4>
                    <p>Tips on how to properly engage the target muscles and avoid compensating with other muscle groups.</p>
                </div>
                
                <div class="benefit-card">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h4>Common Mistakes</h4>
                    <p>Identification of frequent errors people make and how to correct them to prevent injuries.</p>
                </div>
                
                <div class="benefit-card">
                    <i class="fas fa-chart-line"></i>
                    <h4>Progression Plans</h4>
                    <p>Guidance on how to progressively increase intensity while maintaining proper form as you get stronger.</p>
                </div>
            </div>
        </section>

        <div class="cta-section">
            <a href="<?php echo $isLoggedIn ? $homePage : 'login.php'; ?>" class="cta-button">
                <?php echo $isLoggedIn ? 'Start Your Workout' : 'Join Us Now'; ?>
            </a>
        </div>
    </div>

    <footer class="main-footer">
        <p>&copy; 2024 O-GYM. All rights reserved.</p>
    </footer>
</body>
</html>