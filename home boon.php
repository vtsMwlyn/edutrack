<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduTrack Platform</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* Global Styling */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'nunito', sans-serif;
      background: #504E76;
      color: #504E76;
      overflow-x: hidden;
    }

    h1, h2, h3 {
      font-weight: 700;
    }

    p {
      font-weight: 400;
      color: #504E76;
    }

    a {
      text-decoration: none;
      color: #F8F8F8;
    }

    button {
      cursor: pointer;
      border: none;
    }

    /* Header */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 10%;
      background-color: #504E76;
    }

    .navbar .logo {
      font-size: 1.8rem;
      font-weight: 600;
      color: #FDF8E2;
    }

    .nav-links {
      display: flex;
      gap: 30px;
    }

    .nav-links a {
      color: #FDF8E2;
      font-size: 1rem;
      font-weight: 500;
      transition: 0.3s;
    }

    .nav-links a:hover {
      color: #A3B565;
    }

    .btn.primary {
      background: #F1642E;
      color: #f8faff;
      padding: 10px 20px;
      border-radius: 5px;
      transition: 0.3s;
    }

    .btn.primary:hover {
      background: #ff4400;
    }

    /* Hero Section */
    .hero {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 80px 10%;
      background: linear-gradient(145deg, #C4C3E3, #FCDD9D);
      border-bottom-left-radius: 100px;
    }

    .hero .text {
      flex: 1;
    }

    .hero h1 {
      font-size: 3.2rem;
      margin-bottom: 20px;
      line-height: 1.2;
    }

    .hero p {
      font-size: 1rem;
      margin-bottom: 30px;
      max-width: 500px;
    }

    .hero .buttons {
      display: flex;
      gap: 20px;
    }

    .hero .image {
      flex: 1;
      display: flex;
      justify-content: center;
    }

    .hero .image img {
      width: 85%;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    /* Features Section */
    .features {
      padding: 60px 10%;
      text-align: center;
    }

    .features h2 {
      margin-bottom: 40px;
      font-size: 2.2rem;
      color: #FDF8E2;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 30px;
    }

    .feature-card {
      background: #C4C3E3;
      border-radius: 15px;
      text-align: center;
      padding: 20px;
      transition: 0.3s;
    }

    .feature-card:hover {
      background: #FCDD9D;
      color: #F1642E;
      transform: scale(1.05);
    }

    .feature-card i {
      font-size: 2.5rem;
      margin-bottom: 15px;
    }

    .feature-card h3 {
      font-size: 1.2rem;
      margin-bottom: 10px;
    }

    .feature-card p {
      font-size: 0.9rem;
    }

    /* About Section */
    .about {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 60px 10%;
      background: linear-gradient(145deg, #C4C3E3, #FCDD9D);
      border-top-right-radius: 100px;
    }

    .about .about-content {
      flex: 1;
    }

    .about h2 {
      font-size: 2rem;
      margin-bottom: 20px;
    }

    .about ul {
      margin-bottom: 20px;
    }

    .about ul li {
      margin-bottom: 10px;
      font-size: 1rem;
      list-style: none;
    }

    .about ul li i {
      color: #A3B565;
      margin-right: 10px;
    }

    .about .btn {
      background: #F1642E;
      color: #f8faff;
      padding: 10px 20px;
      border-radius: 5px;
    }

    .about .about-image {
      flex: 1;
      display: flex;
      justify-content: center;
    }

    .about .about-image img {
      width: 85%;
      border-radius: 20px;
    }

    /* Categories Section */
    .categories {
      padding: 60px 10%;
      background: #504E76;
    }

    .categories h2 {
      text-align: center;
      margin-bottom: 40px;
      font-size: 2rem;
      color: #F8F8F8;
    }

    .category-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 30px;
    }

    .category-card {
      text-align: center;
      background: #C4C3E3;
      border-radius: 15px;
      padding: 20px;
      transition: 0.3s;
    }

    .category-card:hover {
      background: #FCDD9D;
      color: #504E76;
    }

    .category-card img {
      width: 100%;
      border-radius: 10px;
      margin-bottom: 15px;
    }

    .category-card h3 {
      margin-bottom: 5px;
    }

    .category-card p {
      font-size: 0.9rem;
    }

    .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #f8faff;
        }

  </style>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduTrack - Your Learning Tracker </title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <nav class="navbar">
      <div class="logo">EduTrack</div>
      <ul class="nav-links">
        <li><a href="#">Home</a></li>
        <li><a href="#">Features</a></li>
        <li><a href="#">Rate Us</a></li>
        <li><a href="#">Contact Us</a></li>
      </ul>
      <button class="btn primary" onclick="location.href='login.php'">Log In</button>

    </nav>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="text">
      <h1>Empower Your Learning Journey with EduTrack</h1>
      <p>EduTrack is your trusted platform for organizing, tracking, and enhancing your educational experience.</p>
      <div class="buttons">
      <button class="btn primary" onclick="location.href='login.php'">Join Us</button>

      </div>
    </div>
    <div class="image">
      <img src="https://via.placeholder.com/500x350" alt="Empowering Education">
    </div>
  </section>

  <!-- Features Section -->
  <section class="features">
    <h2>Why Choose EduTrack?</h2>
    <div class="features-grid">
      <div class="feature-card">
      <i class="fas fa-user-cog"></i>
        <h3>Customizable Learning</h3>
        <p>Personalize subjects to match your interests and goals.</p>
      </div>
      <div class="feature-card">
      <i class="fas fa-calendar-check"></i>
        <h3>Daily Agenda</h3>
        <p>Stay organized with reminders for your tasks and classes.</p>
      </div>
      <div class="feature-card">
        <i class="fas fa-bell"></i>
        <h3>Reminders & Tracking</h3>
        <p>Monitor your assingments and stay motivated.</p>
      </div>
      <div class="feature-card">
        <i class="fas fa-users"></i>
        <h3>Collaborative Learning</h3>
        <p>Engage with teachers and clasmates.</p>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section class="about">
    <div class="about-content">
      <h2>About EduTrack</h2>
      <ul>
        <li><i class="fas fa-check-circle"></i> Comprehensive Scheduling Tools</li>
        <li><i class="fas fa-check-circle"></i> Real-Time Progress Insights</li>
        <li><i class="fas fa-check-circle"></i> Integrated Task Management</li>
      </ul>
      <button class="btn primary">Explore More</button>
    </div>
    <div class="about-image">
      <img src="https://via.placeholder.com/500x350" alt="About EduTrack">
    </div>
  </section>

  <!-- Categories Section -->
  <section class="categories">
    <h2>Explore Topics with EduTrack</h2>
    <div class="category-grid">
      <div class="category-card">
        <img src="https://via.placeholder.com/150" alt="Science">
        <h3>Science</h3>
      </div>
      <div class="category-card">
        <img src="https://via.placeholder.com/150" alt="Informatics">
        <h3>Informatics</h3>
      </div>
      <div class="category-card">
        <img src="https://via.placeholder.com/150" alt="Mathematics">
        <h3>Mathematics</h3>
      </div>
      <div class="category-card">
        <img src="https://via.placeholder.com/150" alt="Indonesian">
        <h3>Indonesian</h3>
      </div>
    </div>
  </section>
    <!-- Footer -->
    <footer>
        <div class="container">
            &copy; 2024 EduTrack. All rights reserved.
        </div>
    </footer>
</body>
</html>