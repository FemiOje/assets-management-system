<?php
    include "../database/insert_sample_data.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Management System</title>
    <link rel="stylesheet" href="../styles/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <div class="logo">AMS</div>
        <a href="login.php" class="nav-login">Login</a>
    </nav>
    
    <main>
        <div class="hero">
            
            <div class="hero-content">
                <div class="col-1">
                <h1>Manage assets<br>with precision</h1>
                <p>
                    A modern solution for tracking and managing your business assets. 
                    Simple. Efficient. Reliable.
                </p>
                <a href="signup.php" class="cta-button">Start Now</a>
                </div>
                <div class="col-2">
                    <img src="../images/landing.svg" class="landing-img"/>
                </div>
                
            </div>
        </div>
        
        <div class="features">
            <div class="feature">
                <span class="number">01</span>
                <h2>Track</h2>
                <p>Real-time asset tracking and monitoring</p>
            </div>
            <div class="feature">
                <span class="number">02</span>
                <h2>Analyze</h2>
                <p>Detailed analytics and reporting tools</p>
            </div>
            <div class="feature">
                <span class="number">03</span>
                <h2>Optimize</h2>
                <p>Resource optimization and allocation</p>
            </div>
        </div>
    </main>
</body>
</html>