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

// Handle account deletion
if (isset($_POST['delete_account'])) {
    // Verify password before deletion
    $password = $_POST['confirm_password'];
    $stmt = $conn->prepare("SELECT password FROM ogym WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Delete account
            $delete_stmt = $conn->prepare("DELETE FROM ogym WHERE email = ?");
            $delete_stmt->bind_param("s", $email);
            
            if ($delete_stmt->execute()) {
                // Account deleted successfully
                session_unset();
                session_destroy();
                header("Location: login.php?account_deleted=1");
                exit();
            } else {
                $error = "Error deleting account: " . $conn->error;
            }
            $delete_stmt->close();
        } else {
            $error = "Incorrect password. Account not deleted.";
        }
    }
    $stmt->close();
}

// Fetch user data
$stmt = $conn->prepare("SELECT first_name, last_name, gen, dob, height, weight, phone, email, password FROM ogym WHERE email = ?");
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
        '••••••••' // Password placeholder
    ];
}

$stmt->close();
$conn->close();

// Determine home page based on gender
$homePage = (isset($str2[2]) && $str2[2] === "Female" ? 'main_girl.php' : 'main.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | O-GYM</title>
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
    }

    body {
        font-family: 'Montserrat', sans-serif;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: var(--dark);
        min-height: 100vh;
    }

    .main-header {
        background-color: white;
        padding: 1rem 2rem;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .logo {
        height: 50px;
        margin-right: 1rem;
    }

    .main-header h1 {
        font-family: 'Oswald', sans-serif;
        color: var(--primary);
        margin-right: auto;
    }

    .main-header nav ul {
        display: flex;
        list-style: none;
    }

    .main-header nav ul li {
        margin-left: 1.5rem;
    }

    .main-header nav ul li a {
        text-decoration: none;
        color: var(--dark);
        font-weight: 600;
        transition: color 0.3s;
    }

    .main-header nav ul li a:hover {
        color: var(--accent);
    }

    .profile-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .profile-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .profile-header h2 {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .profile-header p {
        color: #666;
    }

    .profile-card {
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .profile-table {
        width: 100%;
        border-collapse: collapse;
    }

    .profile-table th, .profile-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .profile-table th {
        width: 30%;
        color: var(--primary);
        font-weight: 600;
    }

    .profile-table tr:last-child th, 
    .profile-table tr:last-child td {
        border-bottom: none;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .profile-button {
        display: inline-block;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        color: white;
        background: linear-gradient(45deg, var(--primary), var(--secondary));
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
        cursor: pointer;
        text-align: center;
    }

    .profile-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .profile-button.secondary {
        background: linear-gradient(45deg, var(--warning), #fd7e14);
    }

    .profile-button span {
        position: relative;
        z-index: 1;
    }

    .main-footer {
        background-color: white;
        text-align: center;
        padding: 1.5rem;
        margin-top: 2rem;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
    }

    .main-footer p {
        color: #666;
    }

    /* Delete Account Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 2rem;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        animation: modalFadeIn 0.3s ease-out;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(-50px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .modal-title {
        font-size: 1.5rem;
        color: var(--danger);
        font-weight: 700;
    }

    .close-modal {
        color: #aaa;
        font-size: 1.5rem;
        font-weight: bold;
        cursor: pointer;
    }

    .close-modal:hover {
        color: var(--dark);
    }

    .modal-body {
        margin-bottom: 1.5rem;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .delete-btn {
        background: linear-gradient(45deg, var(--danger), #c82333);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
    }

    .cancel-btn {
        background: linear-gradient(45deg, #6c757d, #495057);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
    }

    .error-message {
        color: var(--danger);
        margin: 0.5rem 0;
        font-size: 0.9rem;
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

    <div class="profile-container">
        <div class="profile-header">
            <h2>My Profile</h2>
            <p>View and manage your personal information</p>
        </div>

        <?php if (isset($error)): ?>
            <div style="color: var(--danger); margin: 1rem 0; padding: 0.5rem; background-color: rgba(220, 53, 69, 0.1); border-radius: 4px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="profile-card">
            <table class="profile-table">
                <?php for($i=0; $i<9; $i++): ?>
                    <tr>
                        <th><?php echo htmlspecialchars($str[$i]); ?></th>
                        <td><?php echo htmlspecialchars($str2[$i]); ?></td>
                    </tr>
                <?php endfor; ?>
            </table>
        </div>

        <div class="action-buttons">
            <?php if(isset($str2[2]) && $str2[2] === "Female"): ?>
                <a href="main_girl.php" class="profile-button"><span>BACK TO HOME</span></a>
            <?php else: ?>
                <a href="main.php" class="profile-button"><span>BACK TO HOME</span></a>
            <?php endif; ?>
            <a href="edit_profile.php" class="profile-button secondary"><span>EDIT PROFILE</span></a>
            <button id="deleteAccountBtn" class="profile-button" style="background: linear-gradient(45deg, var(--danger), #c82333);">
                <span>DELETE ACCOUNT</span>
            </button>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Delete Account</h3>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                <p>All your data will be permanently removed from our system.</p>
                
                <form id="deleteForm" method="POST">
                    <div style="margin: 1.5rem 0;">
                        <label for="confirm_password" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            Enter your password to confirm:
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
                        <?php if (isset($error)): ?>
                            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="cancel-btn" id="cancelDelete">Cancel</button>
                        <button type="submit" name="delete_account" class="delete-btn">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <p>&copy; 2024 O-GYM. All rights reserved.</p>
    </footer>

    <script>
        // Modal functionality
        const modal = document.getElementById("deleteModal");
        const btn = document.getElementById("deleteAccountBtn");
        const span = document.getElementsByClassName("close-modal")[0];
        const cancelBtn = document.getElementById("cancelDelete");

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        cancelBtn.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Confirm before submitting delete form
        document.getElementById("deleteForm").addEventListener("submit", function(e) {
            if (!confirm("Are you absolutely sure you want to delete your account? This cannot be undone.")) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>