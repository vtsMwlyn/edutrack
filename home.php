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

    
    /* Enhanced Header */
    .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 10%;
            background-color: rgba(80, 78, 118, 0.95);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            backdrop-filter: blur(10px);
            transition: padding 0.3s ease;
        }

        .navbar.scrolled {
            padding: 15px 10%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 600;
            color: #FDF8E2;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            color: #FDF8E2;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            transition: 0.3s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #FCDD9D;
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

     /* Button Enhancement */
     .btn.primary {
            background: #F1642E;
            color: #f8faff;
            padding: 12px 25px;
            border-radius: 25px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .btn.primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }

        .btn.primary:hover::before {
            width: 300px;
            height: 300px;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
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
      background:rgb(215, 214, 233);
      border-radius: 15px;
      text-align: center;
      padding: 20px;
      transition: 0.3s;
    }

    .feature-card:hover {
      background:rgb(246, 225, 182);
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
      width: 35%;
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
    width: 100%;  /* Changed from 22% to 100% */
    max-width: 1200px;
    margin: 20px auto;
    display: flex;
    justify-content: center;  /* Changed from space-between to center */
    align-items: center;
    color: #f8faff;
    text-align: center;  /* Added for extra centering support */
}

/* Logo Styling */
.logo {
  display: flex;
  align-items: center;
  gap: 20px; /* Jarak antara logo dan teks */
}

.logo-img {
  width: 30px; /* Sesuaikan ukuran logo */
  height: auto; /* Menjaga aspek rasio gambar */
  transition: transform 0.3s ease;
}

.logo-img:hover {
  transform: scale(1.1); /* Efek hover pada logo */
}

.logo span {
  font-size: 1.8rem;
  font-weight: 600;
  color: #FDF8E2;
  transition: transform 0.3s ease;
}

.logo:hover span {
  transform: scale(1.05); /* Efek hover pada teks */
}

  </style>
</head>
<body>
  <!-- Header -->
  <header>
  <nav class="navbar">
    <div class="logo">
      <img src="https://cdn.discordapp.com/attachments/1282538476079677533/1335218996852555808/Untitled_design_20250201_190204_0000.png?ex=67a0b098&is=679f5f18&hm=df23008f6322f361d3fac53002f2442809d42acfc0fc096b3641e26bb78f978e&" alt="EduTrack Logo" class="logo-img">
      <span>EduTrack</span>
    </div>
    <ul class="nav-links">
      <li><a href="tes_home.php"><b>Home</b></a></li>
      <li><a href="aboutus.php">About Us</a></li>
      <li><a href="rateus.php">Rate Us</a></li>
    </ul>
    <button class="btn primary" onclick="window.location.href='login.php';">Log In</button>
  </nav>
</header>
  <!-- Hero Section -->
  <section class="hero">
    <div class="text">
      <h1>Empower Your Learning Journey with EduTrack</h1>
      <p>EduTrack is your trusted platform for organizing, tracking, and enhancing your educational experience.</p>
    </div>
    <div class="image">
      <img src="https://cdn.discordapp.com/attachments/1282538476079677533/1325740503475556445/Students-cuate.png?ex=677ce38f&is=677b920f&hm=02ced2e85fdb1c82bfdff090b69582b6a4a5e94751388dea254c824967482bce&" alt="Students">
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
    </div>
    <div class="about-image">
      <img src="">
    </div>
  </section>

  <!-- Categories Section -->
  <section class="categories">
    <h2>Explore Topics with EduTrack</h2>
    <div class="category-grid">
      <div class="category-card">
        <img src="https://cdn.discordapp.com/attachments/1282538476079677533/1325746913378697237/Typing.gif?ex=677ce987&is=677b9807&hm=0a9c6acd22d8c776f6aa66277791ee6df2953357cd4ef8583bc8895283c1f524&" alt="Science">
        <h3>Science</h3>
        <p>Explore topics from physics, chemistry, biology, and more.</p>
      </div>
      <div class="category-card">
        <img src="https://cdn.discordapp.com/attachments/1282538476079677533/1325753342936354909/Code_review.gif?ex=677cef84&is=677b9e04&hm=30a6e473d7583e176e410ca92aa170974b85f23fce502b7980bbd5ca20a04601&" alt="Informatics">
        <h3>Informatics</h3>
        <p>Master HTML, CSS, PHP, JavaScript, and more.</p>
      </div>
      <div class="category-card">
        <img src="https://cdn.discordapp.com/attachments/1282538476079677533/1325750341391876137/Studying.gif?ex=677cecb8&is=677b9b38&hm=e84a7fad74ccd8508b494e9b15aa678ce8ff58d23a46ef14a09d1dab09e59d73&" alt="Mathematics">
        <h3>Mathematics</h3>
        <p>Master algebra, calculus, geometry, and more!</p>
      </div>
      <div class="category-card">
        <img src="https://cdn.discordapp.com/attachments/1282538476079677533/1325754399133274143/Office_management_1.gif?ex=677cf080&is=677b9f00&hm=69574546ad51f7e4de48728190378226efb666ad465f15324433af26a761dc5b&" alt="Indonesian">
        <h3>Indonesian</h3>
        <p>Explore the world of books, essays, and poetry.</p>
      </div>
      
    </div>
  </section>
    <!-- Footer -->
    <footer>
        <div class="container">
           &copy; 2024 EduTrack. All rights reserved.</div>
    </footer>
</body>
</html>