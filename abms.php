<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Get user's gender for proper home page redirection
$gender = $_SESSION['gender'] ?? '';
$homePage = ($gender === 'Female') ? 'main_girl.php' : 'main.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoulder Workouts | O-GYM</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Oswald:wght@500&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    :root {
        --primary: #2b5876;
        --secondary: #4e4376;
        --accent: #f857a6;
        --light: #f8f9fa;
        --dark: #212529;
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
    }

    header.main-header {
        background: linear-gradient(to right, var(--primary), var(--secondary));
        color: white;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .logo-container {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
    }

    .logo-img {
        height: 100px;
        transition: transform 0.3s ease;
    }

    .logo-img:hover {
        transform: scale(1.05) rotate(-5deg);
    }

    .logo-text {
        font-family: 'Oswald', sans-serif;
        font-size: 3.5rem;
        background: linear-gradient(to right, #f857a6, #ff5858);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    hr {
        border: 0;
        height: 1px;
        background: linear-gradient(to right, transparent, rgba(0,0,0,0.1), transparent);
        margin: 1rem 0;
    }

    /* Exercise Cards */
    .exercise-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
    }

    .exercise-card {
        background: rgba(255,255,255,0.9);
        border-radius: 15px;
        padding: 2rem;
        margin: 2rem 0;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .exercise-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .exercise-title {
        font-size: 2rem;
        color: var(--primary);
        text-align: center;
        margin-bottom: 1.5rem;
        font-weight: 700;
    }

    .exercise-image {
        width: 100%;
        max-width: 450px;
        height: auto;
        border-radius: 10px;
        display: block;
        margin: 0 auto 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .exercise-image:hover {
        transform: scale(1.02);
    }

    .exercise-steps {
        padding: 0 1.5rem;
        margin-bottom: 1.5rem;
    }

    .exercise-steps li {
        margin-bottom: 0.8rem;
        line-height: 1.6;
    }

    .video-prompt {
        text-align: center;
        font-style: italic;
        color: var(--secondary);
        margin-top: 1.5rem;
    }

    .video-prompt u {
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
    }

    /* Buttons */
    .button {
        display: inline-block;
        background: linear-gradient(45deg, var(--primary), var(--secondary));
        color: white;
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(43, 88, 118, 0.3);
        text-decoration: none;
        margin: 1rem 0;
    }

    .button:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(43, 88, 118, 0.4);
    }

    /* Footer */
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
        .logo-text {
            font-size: 2.5rem;
        }
        
        .exercise-title {
            font-size: 1.5rem;
        }
        
        .exercise-card {
            padding: 1.5rem;
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
            <a href="<?php echo $homePage; ?>" class="button">Back to Home</a>
        </nav>
    </header>

    <div class="exercise-container">
        <!-- Barbell Overhead Press -->
        <div class="exercise-card">
            <h2 class="exercise-title">Barbell Overhead Press</h2>
            <form action="shv1.php">
                <button type="submit" class="image-button">
                    <img src="https://liftmanual.com/wp-content/uploads/2023/04/ez-bar-standing-overhead-press.jpg" 
                         alt="Barbell Overhead Press" class="exercise-image">
                </button>
            </form>
            <ol class="exercise-steps">
                <li>Take a roughly shoulder width grip. There should be a straight line from your elbow to fist (vertical forearms).</li>
                <li>Pull your chin back and press the weight toward the ceiling by extending at the elbow joint and flexing at the shoulder joint.</li>
                <li>Press until your elbows are extended and push your head forward slightly.</li>
                <li>Return to the start position with control. Pulling your chin back to allow the bar to pass your face safely.</li>
            </ol>
            <p class="video-prompt">For demo video click on <u>IMAGE</u></p>
        </div>

        <!-- Dumbbell Seated Overhead Press -->
        <div class="exercise-card">
            <h2 class="exercise-title">Dumbbell Seated Overhead Press</h2>
            <form action="shv2.php">
                <button type="submit" class="image-button">
                    <img src="https://www.endomondo.com/wp-content/uploads/2024/03/Standing-Barbell-Overhead-Press-.jpg" 
                         alt="Dumbbell Seated Overhead Press" class="exercise-image">
                </button>
            </form>
            <ol class="exercise-steps">
                <li>Sit on a bench with back support. Raise the dumbbells to shoulder height with your palms forward.</li>
                <li>Raise the dumbbells upwards and pause at the contracted position.</li>
                <li>Lower the weights back to starting position</li>
            </ol>
            <p class="video-prompt">For demo video click on <u>IMAGE</u></p>
        </div>

        <!-- Dumbbell Front Raise -->
        <div class="exercise-card">
            <h2 class="exercise-title">Dumbbell Front Raise</h2>
            <form action="shv3.php">
                <button type="submit" class="image-button">
                    <img src="https://liftmanual.com/wp-content/uploads/2023/04/dumbbell-seated-front-raise.jpg" 
                         alt="Dumbbell Front Raise" class="exercise-image">
                </button>
            </form>
            <ol class="exercise-steps">
                <li>Grab two dumbbells while standing upright with the dumbbells at your side.</li>
                <li>Raise the two dumbbells with your elbows being fully extended until the dumbbells are eye level.</li>
                <li>Lower the weights in a controlled manner to the starting position and repeat.</li>
            </ol>
            <p class="video-prompt">For demo video click on <u>IMAGE</u></p>
        </div>

        <!-- Cable Rope Face Pulls -->
        <div class="exercise-card">
            <h2 class="exercise-title">Cable Rope Face Pulls</h2>
            <form action="shv4.php">
                <button type="submit" class="image-button">
                    <img src="https://trainingstation.co.uk/cdn/shop/articles/face-pulls-muscles-used_fe27890e-ac33-489a-bb21-a9bbcc84bfae_1400x.png?v=1738219155" 
                         alt="Cable Rope Face Pulls" class="exercise-image">
                </button>
            </form>
            <ol class="exercise-steps">
                <li>Facing the pulley, pull the weight towards you while keeping your arms parallel to the ground.</li>
                <li>Pull your hands back to both sides of your head and hold the position.</li>
                <li>Slowly return weight to starting position. Repeat.</li>
            </ol>
            <p class="video-prompt">For demo video click on <u>IMAGE</u></p>
        </div>
    </div>

    <footer class="main-footer">
        <p>&copy; 2024 O-GYM. All rights reserved.</p>
    </footer>
</body>
</html>
