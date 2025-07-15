<?php
// homepage.php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Get user details from session
$user_name = $_SESSION['user_name'] ?? 'Guest'; // Fallback name if not set

// MemoSync Homepage content variables
$page_title = "MemoSync - Turn talk into tasks. Instantly.";
$company_name = "MemoSync";
$tagline = "Turn talk into tasks. Instantly.";
$description = "MemoSync listens, understands, and turns your meetings into clear action items — so nothing gets lost in conversation.";

// Navigation items
// These will be displayed if the user is NOT logged in.
// When logged in, the navigation actions will change to show user name and logout.
$nav_items = [
    'Product' => '#product',
    'Pricing' => '#pricing',
    'Resources' => '#resources',
    'Features' => '#features',
    'About' => '#about'
];

// Feature cards
$features = [
    [
        'icon' => '🧠',
        'title' => 'Smarter meetings',
        'description' => 'AI that understands and summarizes in real time.'
    ],
    [
        'icon' => '⚡',
        'title' => 'Action, not just notes',
        'description' => 'Automatically extract tasks and next steps.'
    ],
    [
        'icon' => '🔗',
        'title' => 'Seamless integration',
        'description' => 'Connects with Zoom and Google Meet.'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <style>
        /* Global Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            color: white;
            min-height: 100vh;
            overflow-x: hidden;
            display: flex; /* Use flexbox for body to ensure content pushes footer down if needed */
            flex-direction: column;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            width: 100%; /* Ensure container takes full width on small screens */
        }

        /* Header */
        header {
            padding: 20px 0;
            position: relative;
            z-index: 100;
            background: rgba(0, 0, 0, 0.2); /* Slightly darker background for header */
            backdrop-filter: blur(5px); /* Blur effect for header */
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 20px;
            font-weight: 600;
            color: white;
            text-decoration: none;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(45deg, #e91e63, #9c27b0);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 40px;
        }

        .nav-links li {
            position: relative;
        }

        .nav-links li:not(:last-child)::after {
            content: "|";
            position: absolute;
            right: -20px;
            color: rgba(255, 255, 255, 0.3);
            font-weight: 300;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: white;
        }

        .nav-actions {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .user-greeting {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            font-size: 1rem;
            white-space: nowrap; /* Prevent wrapping for user name */
        }

        .logout-btn {
            background: linear-gradient(45deg, #e91e63, #9c27b0);
            color: white;
            padding: 10px 20px; /* Slightly smaller padding for a button in the header */
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            cursor: pointer;
            white-space: nowrap; /* Prevent wrapping */
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.3);
        }

        /* Hero Section */
        .hero {
            padding: 80px 0 120px;
            position: relative;
            text-align: left;
            flex-grow: 1; /* Allow hero section to take up available space */
            display: flex;
            align-items: center; /* Center content vertically */
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 4rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 30px;
        }

        .hero-text .highlight {
            background: linear-gradient(45deg, #e91e63, #9c27b0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-text p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .hero-actions {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .start-free-btn {
            background: linear-gradient(45deg, #e91e63, #9c27b0);
            color: white;
            padding: 15px 30px; /* Larger padding for main CTA */
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .start-free-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(233, 30, 99, 0.3);
        }

        .watch-demo-btn {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 15px 30px; /* Larger padding for main CTA */
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .watch-demo-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Smart summaries badge */
        .smart-badge {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 40px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* Background Effects */
        .page-bg { /* Renamed from hero-bg to page-bg for consistency */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            z-index: -1;
        }

        .glow-orb {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(233, 30, 99, 0.3) 0%, transparent 70%);
            filter: blur(40px);
            animation: float 6s ease-in-out infinite;
        }

        .glow-orb:nth-child(1) {
            top: 20%;
            right: 10%;
            animation-delay: 0s;
        }

        .glow-orb:nth-child(2) {
            bottom: 20%;
            left: 10%;
            background: radial-gradient(circle, rgba(156, 39, 176, 0.3) 0%, transparent 70%);
            animation-delay: 3s;
        }

        .light-beam {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #e91e63 50%, #9c27b0 100%);
            box-shadow: 0 0 20px #e91e63;
        }

        .light-point {
            position: absolute;
            bottom: 0;
            right: 30%;
            width: 20px;
            height: 20px;
            background: radial-gradient(circle, #ffffff 0%, #e91e63 50%, transparent 100%);
            border-radius: 50%;
            box-shadow: 0 0 40px #ffffff, 0 0 80px #e91e63;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        /* Features Section */
        .features {
            padding: 80px 0;
            background: rgba(255, 255, 255, 0.02);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }

        .feature-card {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .feature-icon {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: white;
        }

        .feature-card p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none; /* Hide navigation links on small screens */
            }

            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-text h1 {
                font-size: 2.5rem;
            }

            .hero-actions {
                justify-content: center;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .header .nav-actions {
                /* Ensure logout button and greeting stack nicely if needed */
                flex-direction: column;
                align-items: flex-end;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .hero-text h1 {
                font-size: 2rem;
            }

            .hero-actions {
                flex-direction: column;
                width: 100%;
            }

            .start-free-btn,
            .watch-demo-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="page-bg">
        <div class="glow-orb"></div>
        <div class="glow-orb"></div>
        <div class="light-beam"></div>
        <div class="light-point"></div>
    </div>

    <header>
        <nav class="container">
            <a href="#" class="logo">
                <div class="logo-icon">M</div>
                <?php echo htmlspecialchars($company_name); ?>
            </a>
            
            <ul class="nav-links">
                <?php foreach ($nav_items as $label => $link): ?>
                    <li><a href="<?php echo htmlspecialchars($link); ?>"><?php echo htmlspecialchars($label); ?></a></li>
                <?php endforeach; ?>
            </ul>
            
            <div class="nav-actions">
                <span class="user-greeting">Welcome, <?php echo htmlspecialchars($user_name); ?>!</span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <div class="smart-badge">
                            📝 Smart summaries from every meeting
                        </div>
                        
                        <h1>
                            Turn talk into tasks. <span class="highlight">Instantly.</span>
                        </h1>
                    </div>
                    
                    <div class="hero-visual">
                        <p><?php echo htmlspecialchars($description); ?></p>
                        
                        <div class="hero-actions">
                            <a href="#start" class="start-free-btn">
                                ⚡ Start for free
                            </a>
                            <a href="#demo" class="watch-demo-btn">
                                📺 Watch demo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <div class="features-grid">
                    <?php foreach ($features as $feature): ?>
                        <div class="feature-card">
                            <div class="feature-icon"><?php echo $feature['icon']; ?></div>
                            <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                            <p><?php echo htmlspecialchars($feature['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Add smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add parallax effect to background elements
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.glow-orb');
            
            parallaxElements.forEach((element, index) => {
                // Adjust speed for a more subtle effect if needed
                const speed = 0.1 + (index * 0.05); // Reduced speed for less aggressive parallax
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });

        // Add hover effects for cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>
