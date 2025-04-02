<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Database connection using mysqli
$host = "localhost";
$username = "root";
$password = "";
$database = "uday";
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$str = ["FIRST NAME", "LAST NAME", "GENDER", "DATE OF BIRTH", "HEIGHT (cm)", "WEIGHT (kg)", "PHONE NUMBER", "EMAIL", "PASSWORD"];
$str2 = array_fill(0, 9, ''); // Initialize with empty values

// Fetch current user data
$stmt = $conn->prepare("SELECT first_name, last_name, gen, dob, height, weight, phone, email FROM ogym WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $str2 = [
        $row['first_name'] ?? '',
        $row['last_name'] ?? '',
        $row['gen'] ?? '',
        $row['dob'] ?? '',
        $row['height'] ?? '',
        $row['weight'] ?? '',
        $row['phone'] ?? '',
        $row['email'] ?? '',
        '' // Empty for password field
    ];
}

$stmt->close();

// Handle form submission
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gen = $_POST['gen'];
    $dob = $_POST['dob'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $phone = $_POST['phone'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];
    
    // Validate and update
    try {
        // Check if password needs to be updated
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE ogym SET first_name=?, last_name=?, gen=?, dob=?, height=?, weight=?, phone=?, email=?, password=? WHERE email=?");
            $stmt->bind_param("ssssddssss", $first_name, $last_name, $gen, $dob, $height, $weight, $phone, $new_email, $hashed_password, $email);
        } else {
            $stmt = $conn->prepare("UPDATE ogym SET first_name=?, last_name=?, gen=?, dob=?, height=?, weight=?, phone=?, email=? WHERE email=?");
            $stmt->bind_param("ssssddsss", $first_name, $last_name, $gen, $dob, $height, $weight, $phone, $new_email, $email);
        }
        
        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
            // Update session email if changed
            if ($new_email != $email) {
                $_SESSION['email'] = $new_email;
                $_SESSION['gender'] = $gen;
            }
            // Refresh data
            $str2 = [
                $first_name,
                $last_name,
                $gen,
                $dob,
                $height,
                $weight,
                $phone,
                $new_email,
                '' // Clear password field
            ];
            header("Location: profile.php");
        } else {
            $error = "Error updating profile: " . $conn->error;
        }

        $stmt->close();
    } catch(Exception $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

$conn->close();

// Determine home page based on gender
$homePage = (isset($str2[2]) && $str2[2] === "Female") ? 'main_girl.php' : 'main.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | O-GYM</title>
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

    /* Edit Profile Content Styles */
    .edit-container {
        max-width: 1000px;
        margin: 3rem auto;
        padding: 0 2rem;
        animation: fadeIn 0.8s ease-out;
    }

    .edit-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .edit-header h2 {
        font-size: 2.5rem;
        color: var(--primary);
        margin-bottom: 0.5rem;
        position: relative;
        display: inline-block;
    }

    .edit-header h2::after {
        content: '';
        position: absolute;
        width: 50%;
        height: 4px;
        bottom: -10px;
        left: 25%;
        background: linear-gradient(to right, var(--accent), var(--primary));
        border-radius: 2px;
    }

    .edit-header p {
        color: var(--secondary);
        font-size: 1.1rem;
    }

    .edit-card {
        background: rgba(255,255,255,0.9);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--secondary);
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(248, 87, 166, 0.2);
    }

    .form-row {
        display: flex;
        gap: 1.5rem;
    }

    .form-row .form-group {
        flex: 1;
    }

    .error-message {
        color: var(--danger);
        margin: 1rem 0;
        padding: 0.5rem;
        background-color: rgba(220, 53, 69, 0.1);
        border-radius: 4px;
    }

    .success-message {
        color: var(--success);
        margin: 1rem 0;
        padding: 0.5rem;
        background-color: rgba(40, 167, 69, 0.1);
        border-radius: 4px;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .profile-button {
        position: relative;
        background: linear-gradient(45deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 4px 15px rgba(43, 88, 118, 0.3);
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .profile-button.secondary {
        background: linear-gradient(45deg, #6c757d, #495057);
    }

    .profile-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(43, 88, 118, 0.4);
    }

    footer.main-footer {
        background: linear-gradient(to right, var(--primary), var(--secondary));
        color: white;
        text-align: center;
        padding: 1.5rem;
        margin-top: 3rem;
        font-size: 0.9rem;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 0;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 1rem;
        }
        
        .profile-button {
            width: 100%;
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
                <li><a href="index.php">About Us</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
    </header>

    <div class="edit-container">
        <div class="edit-header">
            <h2>Edit Profile</h2>
            <p>Update your personal information</p>
        </div>

        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" class="edit-card">
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($str2[0]); ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($str2[1]); ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="gen">Gender</label>
                    <select id="gen" name="gen" required>
                        <option value="Male" <?php echo ($str2[2] === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($str2[2] === 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($str2[2] === 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($str2[3]); ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="height">Height (cm)</label>
                    <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($str2[4]); ?>" required>
                </div>
                <div class="form-group">
                    <label for="weight">Weight (kg)</label>
                    <input type="number" id="weight" name="weight" value="<?php echo htmlspecialchars($str2[5]); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($str2[6]); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($str2[7]); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">New Password (leave blank to keep current)</label>
                <input type="password" id="password" name="password" placeholder="Enter new password">
            </div>

            <div class="action-buttons">
                <button type="submit" class="profile-button"><span>SAVE CHANGES</span></button>
                <a href="<?php echo $homePage; ?>" class="profile-button secondary"><span>CANCEL</span></a>
            </div>
        </form>
    </div>

    <footer class="main-footer">
        <p>&copy; 2024 O-GYM. All rights reserved.</p>
    </footer>
</body>
</html>