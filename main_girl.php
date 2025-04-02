<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html> 
<html> 
<head> 
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>O-GYM Workout Hub</title>
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

hr {
    border: 0;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(0,0,0,0.1), transparent);
    margin: 1rem 0;
}

/* Button Styles */
.button {
    position: relative;
    background: linear-gradient(45deg, var(--primary), var(--secondary));
    color: white;
    border: none;
    padding: 1rem 2rem;
    margin: 0.5rem;
    border-radius: 50px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.4s ease;
    box-shadow: 0 4px 15px rgba(43, 88, 118, 0.3);
    width: 180px;
    text-align: center;
}

.button span {
    position: relative;
    z-index: 1;
}

.button::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, var(--secondary), var(--primary));
    opacity: 0;
    transition: opacity 0.4s ease;
}

.button:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(43, 88, 118, 0.4);
}

.button:hover::before {
    opacity: 1;
}

.button1 {
    background: linear-gradient(45deg, #f857a6, #ff5858);
    color: white;
    border: none;
    padding: 1rem 1.5rem;
    margin: 0.5rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(248, 87, 166, 0.3);
    min-width: 120px;
}

.button1:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(248, 87, 166, 0.4);
}

/* Animation Classes */
.slide-left {
    animation: slideInLeft 0.8s ease-out forwards;
}

.slide-right {
    animation: slideInRight 0.8s ease-out forwards;
}

@keyframes slideInLeft {
    from {
        transform: translateX(-50px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideInRight {
    from {
        transform: translateX(50px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Main Content */
table {
    margin: 2rem auto;
    border-collapse: separate;
    border-spacing: 1rem;
}

td {
    vertical-align: top;
}

img {
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    transition: transform 0.5s ease, box-shadow 0.5s ease;
    object-fit: cover;
}

img:hover {
    transform: scale(1.02);
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
}

/* Week Plan Section */
.center {
    text-align: center;
    margin: 2rem 0;
}

.center p {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary);
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

/* Footer */
.main-footer {
    background: linear-gradient(to right, var(--primary), var(--secondary));
    color: white;
    text-align: center;
    padding: 1.5rem;
    margin-top: 2rem;
    font-size: 0.9rem;
}

/* Responsive Design */
@media (max-width: 1200px) {
    table {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    td {
        display: block;
        margin-bottom: 2rem;
    }
    
    img {
        width: 100%;
        height: auto;
        max-width: 400px;
    }
}

@media (max-width: 768px) {
    header.main-header {
        flex-direction: column;
        padding: 1rem;
    }
    
    nav ul {
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 1rem;
    }
    
    .button, .button1 {
        padding: 0.8rem 1.2rem;
        font-size: 1rem;
    }
}
</style>
</head> 
<body> 
<header class="main-header"> 
<img src="img/logo.png" alt="Logo" class="logo slide-left"> 
<h1 class="slide-right">O-GYM</h1> 
<nav> 
<ul> 
<li><a href="index.php">About Us</a></li> 
<li><a href="logout.php">Logout</a></li> 
<li><a href="profile.php">Profile</a></li> 
</ul> 
</nav> 
</header> 

<hr> 

<center> 
<table> 
<tr> 
<td> 
<table class="slide-left"> 
<tr> 
<td><form action="shoulder.php"> 
<button class="button"><span>Shoulder</span></button> 
</form> 
</td> 
</tr> 
<tr> 
<td> 
<form action="chest.php"> 
<button class="button">Chest</button> 
</form> 
</td> 
</tr> 
<tr> 
<td><form action="biceps.php"> 
<button class="button"><span>Biceps</span></button> 
</form> 
</td> 
</tr><tr>
<td><form action="forearms.php"> 
<button class="button"><span>Forearms</span></button> 
</form> 
</td> 
</tr><tr> 
<td><form action="abms.php"> 
<button class="button"><span>Abs</span></button> 
</form> 
</td> 
</tr><tr> 
<td><form action="thighs.php"> 
<button class="button"><span>Thighs</span></button> 
</form> 
</td> 
</tr><tr> 
<td><form action="calfs.php"> 
<button class="button"><span>Calves</span></button> 
</form> 
</td> 
</table> 
</td> 
<td class="slide-left"><img src="img/pic1.png" width="400" height="700" alt="gym"></td> 
<td class="slide-right"><img src="img/pic2.png" width="400" height="700" alt="gym"></td> 
<td> 
<table class="slide-right"> 
<tr> 
<td><form action="shrug.php"> 
<button class="button"><span>Shrug</span></button> 
</form>
</td> 
</tr> 
<tr> 
<td><form action="unilateral.php"> 
<button class="button"><span>Unilateral</span></button> 
</form> 
</td> 
</tr> 
<tr> 
<td><form action="triceps.php"> 
<button class="button"><span>Triceps</span></button>
</form> 
</td> 
</tr><tr> 
<td><form action="lats.php"> 
<button class="button"><span>Lats</span></button> 
</form> 
</td> 
</tr><tr> 
<td><form action="hips.php"> 
<button class="button"><span>Hips</span></button> 
</form> 
</td> 
</tr><tr> 
<td><form action="back_thighs.php"> 
<button class="button"><span>Back Thighs</span></button> 
</form> 
</td> 
</tr><tr> 
<td><form action="calfs.php"> 
<button class="button"><span>Calves</span></button>
</form> 
</td> 
</table> 
</td> 
</tr> 
</table> 

<hr> 

<div class="center">
    <p>Week Plan</p>
</div>

<table class="slide-right"> 
<tr> 
<td><form action="chest.php"> 
<button class="button1"><span>Monday</span></button> 
</form> 
</td> 
<td><form action="shoulder.php"> 
<button class="button1"><span>Tuesday</span></button> 
</form> 
</td> 
<td><form action="shoulder.php"> 
<button class="button1"><span>Wednesday</span></button> 
</form> 
</td> 
<td><form action="biceps.php"> 
<button class="button1"><span>Thursday</span></button> 
</form> 
</td>
<td><form action="triceps.php"> 
<button class="button1"><span>Friday</span></button> 
</form> 
</td> 
<td><form action="thighs.php"> 
<button class="button1"><span>Saturday</span></button> 
</form> 
</td> 
<td><form action=""> 
<button class="button1"><span>Sunday</span></button> 
</form> 
</td> 
</tr> 
</table> 

<footer class="main-footer"> 
<p>&copy; 2024 O-GYM. All rights reserved.</p> 
</footer> 
</body> 
</html>