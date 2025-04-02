<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "uday";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$error = null;
$success = null;
$showForgotForm = false;
$emailToReset = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password";
    } else {
        try {
            $stmt = $conn->prepare("SELECT email, password, gen FROM ogym WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($row = $stmt->fetch()) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['gender'] = $row['gen'];
                    
                    $redirect = ($row['gen'] === 'Female') ? 'main_girl.php' : 'main.php';
                    header("Location: $redirect");
                    exit();
                } else {
                    $error = "Invalid email or password";
                }
            } else {
                $error = "Invalid email or password";
            }
        } catch(PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $error = "System error. Please try again later.";
        }
    }
}

// Handle forgot password request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['forgot_password'])) {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error = "Please enter your email";
    } else {
        try {
            $stmt = $conn->prepare("SELECT email FROM ogym WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $showForgotForm = true;
                $emailToReset = $email;
                $success = "Please verify your date of birth to reset password";
            } else {
                $error = "Email not found in our system";
            }
        } catch(PDOException $e) {
            error_log("Forgot password error: " . $e->getMessage());
            $error = "System error. Please try again later.";
        }
    }
}

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $email = trim($_POST['email']);
    $dob = trim($_POST['dob']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    if (empty($dob) || empty($new_password) || empty($confirm_password)) {
        $error = "Please fill all fields";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        try {
            // Verify DOB
            $stmt = $conn->prepare("SELECT dob FROM ogym WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($row = $stmt->fetch()) {
                if ($dob == $row['dob']) {
                    // Update password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_stmt = $conn->prepare("UPDATE ogym SET password = ? WHERE email = ?");
                    
                    if ($update_stmt->execute([$hashed_password, $email])) {
                        $success = "Password updated successfully! You can now login with your new password.";
                        $showForgotForm = false;
                    } else {
                        $error = "Failed to update password. Please try again.";
                    }
                } else {
                    $error = "Incorrect date of birth";
                }
            } else {
                $error = "User not found";
            }
        } catch(PDOException $e) {
            error_log("Password reset error: " . $e->getMessage());
            $error = "System error. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OGYM Login</title>
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
            justify-content: center;
            align-items: center;
        }
        
        .login-container {
            background: rgba(41, 47, 54, 0.9);
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            width: 100%;
            max-width: 450px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .logo {
            margin-bottom: 2rem;
        }
        
        .logo h1 {
            font-size: 3rem;
            background: linear-gradient(to right, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .logo h3 {
            color: var(--secondary);
            margin-top: 0.5rem;
            font-weight: 400;
        }
        
        .error-message {
            color: #ff6b6b;
            margin: 1rem 0;
            font-weight: 600;
            padding: 0.8rem;
            background: rgba(255, 107, 107, 0.1);
            border-radius: 5px;
        }
        
        .success-message {
            color: #4ecdc4;
            margin: 1rem 0;
            font-weight: 600;
            padding: 0.8rem;
            background: rgba(78, 205, 196, 0.1);
            border-radius: 5px;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .form-group {
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--secondary);
        }
        
        input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            color: var(--light);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.3);
        }
        
        .btn {
            padding: 0.8rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(to right, var(--primary), var(--accent));
            color: var(--dark);
            margin-top: 1rem;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }
        
        .btn-secondary {
            background: transparent;
            color: var(--secondary);
            border: 2px solid var(--secondary);
        }
        
        .btn-secondary:hover {
            background: rgba(78, 205, 196, 0.1);
            box-shadow: 0 5px 15px rgba(78, 205, 196, 0.2);
        }
        
        .links {
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .links a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
        
        .forgot-form {
            display: none;
        }
        
        .show {
            display: block;
        }
        
        .hide {
            display: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>OGYM</h1>
            <h3><?php echo $showForgotForm ? 'Reset Your Password' : 'Enter your login credentials'; ?></h3>
        </div>
        
        <?php if (isset($_GET['registered'])): ?>
            <div class="success-message">
                Registration successful! Please log in.
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <!-- Login Form -->
        <form action="login.php" method="post" class="<?php echo $showForgotForm ? 'hide' : ''; ?>">
            <div class="form-group">
                <label for="email">EMAIL:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label for="password">PASSWORD:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" name="login" class="btn">LOGIN</button>
            
            <div class="links">
                <a href="#" id="forgotPasswordLink">Forgot Password?</a>
                <a href="register.php">Not registered? Create an account</a>
            </div>
        </form>
        
        <!-- Forgot Password Initial Form -->
        <form action="login.php" method="post" id="forgotPasswordForm" class="forgot-form <?php echo $showForgotForm ? 'hide' : ''; ?>">
            <div class="form-group">
                <label for="forgot_email">ENTER YOUR EMAIL:</label>
                <input type="email" id="forgot_email" name="email" placeholder="Enter your registered email" required>
            </div>
            
            <button type="submit" name="forgot_password" class="btn">VERIFY EMAIL</button>
            <button type="button" id="backToLogin" class="btn btn-secondary">BACK TO LOGIN</button>
        </form>
        
        <!-- Password Reset Form (shown after email verification) -->
        <form action="login.php" method="post" id="resetPasswordForm" class="forgot-form <?php echo $showForgotForm ? 'show' : ''; ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($emailToReset); ?>">
            
            <div class="form-group">
                <label for="dob">DATE OF BIRTH (YYYY-MM-DD):</label>
                <input type="text" id="dob" name="dob" placeholder="Enter your date of birth (YYYY-MM-DD)" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">NEW PASSWORD:</label>
                <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">CONFIRM NEW PASSWORD:</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
            </div>
            
            <button type="submit" name="reset_password" class="btn">UPDATE PASSWORD</button>
            <button type="button" id="backToForgot" class="btn btn-secondary">BACK</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form[action="login.php"]:not(.forgot-form)');
            const forgotPasswordForm = document.getElementById('forgotPasswordForm');
            const resetPasswordForm = document.getElementById('resetPasswordForm');
            const forgotPasswordLink = document.getElementById('forgotPasswordLink');
            const backToLogin = document.getElementById('backToLogin');
            const backToForgot = document.getElementById('backToForgot');
            
            forgotPasswordLink.addEventListener('click', function(e) {
                e.preventDefault();
                loginForm.classList.add('hide');
                forgotPasswordForm.classList.remove('forgot-form');
                forgotPasswordForm.classList.add('show');
            });
            
            backToLogin.addEventListener('click', function() {
                loginForm.classList.remove('hide');
                forgotPasswordForm.classList.add('forgot-form');
                forgotPasswordForm.classList.remove('show');
            });
            
            backToForgot.addEventListener('click', function() {
                window.location.href = 'login.php';
            });
        });
    </script>
</body>
</html>