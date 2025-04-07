<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Use PDO consistently
    $host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
    $username = "2chHuJJjXN4T7Z9.root";
    $password = "65J2Srkj49NBfmuX";
    $database = "test";
    $port = 4000
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password, $port);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $fi_na = $_POST['fi_na'];
        $la_na = $_POST['la_na'];
        $gen = $_POST['gen'];
        $bir = $_POST['bir'];
        $hei = (int)$_POST['hei'];
        $wei = (int)$_POST['wei'];
        $phno = (int)$_POST['phno'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        
        $stmt = $conn->prepare("INSERT INTO ogym (first_name, last_name, gen, dob, height, weight, phone, email, password) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fi_na, $la_na, $gen, $bir, $hei, $wei, $phno, $email, $password]);
        
        header("Location: login.php?registered=1");
        exit();
    } catch(PDOException $e) {
        echo "<div class='error-message'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O-GYM Registration</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #ff6b6b;
            --secondary: #4ecdc4;
            --dark: #292f36;
            --light: #f7fff7;
            --accent: #ffd166;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: var(--light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            padding: 2rem;
        }
        
        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .logo img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-right: 1rem;
            filter: drop-shadow(0 0 10px rgba(255, 107, 107, 0.7));
        }
        
        .logo h1 {
            font-size: 3.5rem;
            background: linear-gradient(to right, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .form-container {
            background: rgba(41, 47, 54, 0.9);
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        
        h1.title {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: var(--accent);
            text-align: center;
            position: relative;
        }
        
        h1.title::after {
            content: '';
            display: block;
            width: 100px;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            margin: 0.5rem auto 0;
            border-radius: 2px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group.full-width {
            grid-column: span 2;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--secondary);
        }
        
        input, select {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            color: var(--light);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.3);
        }
        
        .radio-group {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .radio-option input {
            width: auto;
        }
        
        .button-group {
            grid-column: span 2;
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1rem;
        }
        
        .btn {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(to right, var(--primary), var(--accent));
            color: var(--dark);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: var(--light);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .error-message {
            background-color: rgba(255, 0, 0, 0.2);
            color: #ff6b6b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #ff6b6b;
        }
        
        .input-hint {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 0.3rem;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-group.full-width {
                grid-column: span 1;
            }
            
            .button-group {
                grid-column: span 1;
                flex-direction: column;
            }
            
            .logo h1 {
                font-size: 2.5rem;
            }
            .register-link {
                margin-top: 1.5rem;
                color: var(--light);
            }
        
            .register-link a {
                color: var(--accent);
                text-decoration: none;
                font-weight: 600;
            }
        
            .register-link a:hover {
                text-decoration: underline;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="img/logo.png" alt="O-GYM Logo">
                <h1>O-GYM</h1>
            </div>
        </div>
        
        <div class="form-container">
            <h1 class="title">REGISTRATION FORM</h1>
            
            <form name="register" onsubmit="return valid()" action="register.php" method="post" class="form-grid">
                <div class="form-group">
                    <label for="fi_na">FIRST NAME</label>
                    <input type="text" id="fi_na" name="fi_na" maxlength="30" required>
                </div>
                
                <div class="form-group">
                    <label for="la_na">LAST NAME</label>
                    <input type="text" id="la_na" name="la_na" maxlength="30" required>
                </div>
                
                <div class="form-group full-width">
                    <label>GENDER</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="male" name="gen" value="Male" required>
                            <label for="male">Male</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="female" name="gen" value="Female">
                            <label for="female">Female</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="bir">DATE OF BIRTH</label>
                    <input type="date" id="bir" name="bir" required>
                </div>
                
                <div class="form-group">
                    <label for="hei">HEIGHT</label>
                    <input type="number" id="hei" name="hei" maxlength="3" required>
                    <div class="input-hint">in centimeters (cm)</div>
                </div>
                
                <div class="form-group">
                    <label for="wei">WEIGHT</label>
                    <input type="number" id="wei" name="wei" maxlength="3" required>
                    <div class="input-hint">in kilograms (kg)</div>
                </div>
                
                <div class="form-group full-width">
                    <label for="phno">MOBILE NUMBER</label>
                    <input type="tel" id="phno" name="phno" maxlength="10" required>
                    <div class="input-hint">10 digit number</div>
                </div>
                
                <div class="form-group full-width">
                    <label for="email">EMAIL ID</label>
                    <input type="email" id="email" name="email" maxlength="100" required>
                    <div class="input-hint">We'll never share your email with anyone else</div>
                </div>
                
                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" maxlength="100" required>
                </div>
                
                <div class="form-group">
                    <label for="password2">CONFIRM PASSWORD</label>
                    <input type="password" id="password2" name="password2" maxlength="100" required>
                </div>
                <div class="register-link">
                    Already registered? <a href="login.php">If Having account</a>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> REGISTER
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> RESET
                    </button>
                </div>
            </form>
            
        </div>
    </div>
    
    <script>
        function valid() {
            // Password validation
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password2').value;
            
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return false;
            }
            
            // Phone number validation
            const phone = document.getElementById('phno').value;
            if (phone.length !== 10 || isNaN(phone)) {
                alert('Please enter a valid 10-digit phone number');
                return false;
            }
            
            // Height validation
            const height = document.getElementById('hei').value;
            if (height < 100 || height > 250) {
                alert('Please enter a valid height between 100cm and 250cm');
                return false;
            }
            
            return true;
        }
        
        // Add real-time feedback for password match
        document.getElementById('password2').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.style.borderColor = '#ff6b6b';
            } else {
                this.style.borderColor = '#4ecdc4';
            }
        });
    </script>
</body>
</html>
