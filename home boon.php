<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Professional and modern Learning Management System website template.">
    <title>LMS Website</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8faff;
            color: #333;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Header */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: #504E76;
            color: #fff;
            padding: 1rem 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 600;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 1.5rem;
        }

        nav ul li a {
            color: #fff;
            font-size: 1rem;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #ffdd00;
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(135deg, #C4C3E3, #FDF8E2);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 1rem;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .hero button {
            background: #504E76;
            color: #0044cc;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .hero button:hover {
            transform: scale(1.1);
        }

        /* Features Section */
        section {
            padding: 4rem 0;
            text-align: center;
        }

        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }

        .feature {
            background: #fff;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            flex: 1 1 calc(33.333% - 2rem);
            max-width: 300px;
            transition: transform 0.3s;
        }

        .feature:hover {
            transform: translateY(-10px);
        }

        .feature img {
            width: 50px;
            margin-bottom: 1rem;
        }

        .feature h3 {
            margin: 1rem 0;
            color: #0044cc;
        }

        /* Footer */
        footer {
            background: #504E76;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
        }

        footer p {
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .features {
                flex-direction: column;
                align-items: center;
            }

            .feature {
                max-width: 100%;
            }

            .hero h1 {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="logo">EduTrack</div>
            <nav>
                <ul>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#courses">Courses</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Transform Your Learning Experience</h1>
        <p>Explore modern education with our advanced LMS platform.</p>
        <button onclick="window.location.href='login.php'">Get Started</button>

    </section>

    <!-- Features Section -->
    <section id="features">
        <div class="container">
            <h2>Our Key Features</h2>
            <div class="features">
                <div class="feature">
                    <img src="https://via.placeholder.com/50" alt="Feature 1">
                    <h3>Interactive Learning</h3>
                    <p>Engage with dynamic and interactive lessons.</p>
                </div>
                <div class="feature">
                    <img src="https://via.placeholder.com/50" alt="Feature 2">
                    <h3>Progress Tracking</h3>
                    <p>Monitor your achievements and milestones.</p>
                </div>
                <div class="feature">
                    <img src="https://via.placeholder.com/50" alt="Feature 3">
                    <h3>Customizable Courses</h3>
                    <p>Choose topics that suit your interests and goals.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section id="courses">
        <div class="container">
            <h2>Popular Courses</h2>
            <p>Discover our wide range of courses designed for every learner's needs.</p>
            <div class="features">
                <div class="feature">
                    <img src="https://via.placeholder.com/50" alt="Course 1">
                    <h3>Web Development</h3>
                    <p>Master the art of creating stunning websites and applications.</p>
                </div>
                <div class="feature">
                    <img src="https://via.placeholder.com/50" alt="Course 2">
                    <h3>Data Science</h3>
                    <p>Learn how to analyze and visualize data effectively.</p>
                </div>
                <div class="feature">
                    <img src="https://via.placeholder.com/50" alt="Course 3">
                    <h3>Digital Marketing</h3>
                    <p>Explore the strategies to excel in the digital world.</p>
                </div>
            </div>
            <button style="margin-top: 2rem;">View All Courses</button>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" style="background: #f8faff; padding: 4rem 0;">
        <div class="container">
            <h2>What Our Students Say</h2>
            <div class="features">
                <div class="feature">
                    <p>"EduTrack has revolutionized the way I learn. The courses are engaging and the tools are intuitive."</p>
                    <h3>- Maria, Web Development Student</h3>
                </div>
                <div class="feature">
                    <p>"The progress tracking feature kept me motivated to achieve my goals."</p>
                    <h3>- Alex, Data Science Enthusiast</h3>
                </div>
                <div class="feature">
                    <p>"The customizable course options allowed me to focus on my passion for digital marketing."</p>
                    <h3>- Taylor, Marketing Specialist</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" style="padding: 4rem 0;">
        <div class="container">
            <h2>Why Choose EduTrack?</h2>
            <p>At EduTrack, we believe that education is the foundation for personal and professional growth. Here’s why thousands of learners trust us:</p>
            <ul style="text-align: left; max-width: 800px; margin: 2rem auto; list-style-type: disc; padding-left: 1.5rem;">
                <li>Interactive and flexible learning environment.</li>
                <li>Seamless integration with your schedule.</li>
            </ul>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 EduTrack. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
