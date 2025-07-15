Welcome to MemoSync, a smart AI-powered productivity tool that turns talk into tasks, instantly. This homepage is the logged-in landing screen for authenticated users, built with PHP, HTML, and CSS. It includes dynamic content rendering and a fully responsive, modern UI.

🚀 Features
🔐 Authentication: Checks for active session (user_id, user_email) before rendering content.

🎯 Dynamic Content: User-specific greeting and real-time display of app features.

🎨 Responsive UI: Clean, modern interface styled with CSS Flexbox and Grid.

🧠 Feature Highlights:

Smarter meetings — AI summaries.

Instant task extraction — Converts conversations into action items.

Seamless integration — Supports Zoom, Google Meet, and more.

🌌 Background Effects: Includes animated glow orbs, light beams, and smooth scrolling.

📱 Mobile Friendly: Adapts to tablets and mobile devices with responsive design.

🧱 Technologies Used
PHP: Server-side logic and session management.

HTML5: Semantic structure and layout.

CSS3: Custom responsive styles, animations, and layout control.

JavaScript: Enhancements for smooth scrolling, parallax, and hover interactions.

📁 File Overview
File	Description
homepage.php	Main landing page for authenticated users.
login.php	(Expected) Login handler for user authentication.
logout.php	(Expected) Session destroyer for logging out.

🔧 How It Works
Session Validation:

php
Copy
Edit
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}
Redirects users who aren’t logged in.

Dynamic User Greeting:

php
Copy
Edit
$user_name = $_SESSION['user_name'] ?? 'Guest';
Modular Navigation: Updates links and user controls depending on authentication state.

Responsive UI Layout: Grid and Flexbox-based layout that adapts across devices.

Feature Cards: Loaded from a PHP array for easy extension.

JavaScript Enhancements:

Smooth anchor scrolling.

Animated background effects.

Feature card hover transitions.

✅ Requirements
PHP 7.0+

A web server (e.g., Apache, Nginx with PHP support)

Browser with modern CSS and JavaScript support

🚦 Setup Instructions
Place the files (homepage.php, login.php, logout.php) in your web server’s root directory.

Start your PHP server:

bash
Copy
Edit
php -S localhost:8000
Log in via login.php to start a session, then navigate to homepage.php.

Enjoy the interface!

🛠 Future Improvements (Suggestions)
Add backend integration for dynamic feature loading.

Convert inline styles into external CSS for better maintainability.

Implement a full authentication system (database-driven).

Optimize animations for performance on lower-end devices.

📸 Screenshot
(You can add a screenshot of the UI here for visual reference)

© License
This project is for demonstration purposes. Use and modify freely for personal or educational projects.

