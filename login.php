<?php
// login.php
session_start();
require_once 'config.php'; // Include the database configuration

$page_title = "Login - MemoSync";
$company_name = "MemoSync";

$email = $password = "";
$email_err = $password_err = "";
$login_error = ""; // General login error message

// Redirect if user is already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    header("Location: homepage.php");
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Attempt to log in if no input errors
    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;
            
            if ($stmt->execute()) {
                $stmt->store_result();
                
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $name, $email, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            session_regenerate_id(true); // Regenerate session ID for security
                            
                            $_SESSION['user_id'] = $id;
                            $_SESSION['user_email'] = $email;
                            $_SESSION['user_name'] = $name;
                            
                            header("Location: homepage.php");
                            exit();
                        } else {
                            $login_error = "Invalid email or password.";
                        }
                    }
                } else {
                    $login_error = "Invalid email or password.";
                }
            } else {
                $login_error = "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
}
$conn->close(); // Close connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            color: white; min-height: 100vh; overflow-x: hidden;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .page-bg { position: fixed; top: 0; left: 0; right: 0; bottom: 0; overflow: hidden; z-index: -1; }
        .glow-orb {
            position: absolute; width: 300px; height: 300px; border-radius: 50%;
            background: radial-gradient(circle, rgba(233, 30, 99, 0.2) 0%, transparent 70%);
            filter: blur(40px); animation: float 6s ease-in-out infinite;
        }
        .glow-orb:nth-child(1) { top: 10%; right: 20%; animation-delay: 0s; }
        .glow-orb:nth-child(2) { bottom: 20%; left: 15%; background: radial-gradient(circle, rgba(156, 39, 176, 0.2) 0%, transparent 70%); animation-delay: 3s; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .login-section { display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-container {
            background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px; padding: 50px; width: 100%; max-width: 450px; text-align: center;
            backdrop-filter: blur(10px);
        }
        .login-container h1 {
            font-size: 2.5rem; margin-bottom: 10px;
            background: linear-gradient(45deg, #e91e63, #9c27b0);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .login-container p { color: rgba(255, 255, 255, 0.7); margin-bottom: 40px; font-size: 1.1rem; }
        .form-group { margin-bottom: 25px; text-align: left; position: relative; } /* Added position relative */
        .form-group label { display: block; margin-bottom: 8px; color: rgba(255, 255, 255, 0.9); font-weight: 500; }
        .form-group input {
            width: 100%; padding: 15px; border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px; background: rgba(255, 255, 255, 0.05); color: white;
            font-size: 1rem; transition: all 0.3s ease;
        }
        .form-group input:focus {
            outline: none; border-color: #e91e63; background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.1);
        }
        .form-group input::placeholder { color: rgba(255, 255, 255, 0.5); }
        .login-btn {
            width: 100%; background: linear-gradient(45deg, #e91e63, #9c27b0);
            color: white; padding: 15px; border-radius: 10px; border: none;
            font-weight: 600; font-size: 1.1rem; cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease; margin-bottom: 30px;
        }
        .login-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(233, 30, 99, 0.3); }
        .signup-link { color: rgba(255, 255, 255, 0.7); font-size: 0.95rem; }
        .signup-link a { color: #e91e63; text-decoration: none; font-weight: 600; }
        .signup-link a:hover { text-decoration: underline; }
        .error-message {
            background: rgba(244, 67, 54, 0.1); border: 1px solid rgba(244, 67, 54, 0.3);
            color: #ff6b6b; padding: 10px 15px; border-radius: 8px; margin-top: 8px; font-size: 0.85rem;
            position: absolute; bottom: -25px; left: 0; width: 100%;
        }
        .general-error-message { /* For general login errors */
            background: rgba(244, 67, 54, 0.1); border: 1px solid rgba(244, 67, 54, 0.3);
            color: #ff6b6b; padding: 15px; border-radius: 10px; margin-bottom: 25px; font-size: 0.9rem;
            text-align: center;
        }
        .forgot-password { text-align: right; margin-bottom: 30px; }
        .forgot-password a { color: rgba(255, 255, 255, 0.6); text-decoration: none; font-size: 0.9rem; }
        .forgot-password a:hover { color: #e91e63; }
        @media (max-width: 768px) {
            .login-container { padding: 30px; margin: 20px; }
            .login-container h1 { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <div class="page-bg">
        <div class="glow-orb"></div>
        <div class="glow-orb"></div>
    </div>

    <main>
        <section class="login-section">
            <div class="container">
                <div class="login-container">
                    <h1>Welcome Back</h1>
                    <p>Sign in to your MemoSync account</p>
                    
                    <?php if (!empty($login_error)): ?>
                        <div class="general-error-message">
                            <?php echo htmlspecialchars($login_error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
                            <?php if (!empty($email_err)): ?><span class="error-message"><?php echo $email_err; ?></span><?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Enter your password" required>
                            <?php if (!empty($password_err)): ?><span class="error-message"><?php echo $password_err; ?></span><?php endif; ?>
                        </div>
                        
                        <div class="forgot-password">
                            <a href="#forgot">Forgot your password?</a>
                        </div>
                        
                        <button type="submit" class="login-btn">Sign In</button>
                    </form>
                    
                    <div class="signup-link">
                        Don't have an account? <a href="signup.php">Sign up here</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Remove JavaScript alerts for validation, PHP handles it now.
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                const formGroup = this.closest('.form-group');
                if (formGroup) {
                    formGroup.style.transform = 'translateY(-2px)';
                }
            });
            
            input.addEventListener('blur', function() {
                const formGroup = this.closest('.form-group');
                if (formGroup) {
                    formGroup.style.transform = 'translateY(0)';
                }
            });
        });
    </script>
</body>
</html>
