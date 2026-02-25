<?php
session_start();

/* ================================
   SESSION CHECK
   ================================ */
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

/* ================================
   DATABASE CONNECTION
   ================================ */
$host = "localhost";
$user = "root";
$pass = "";
$db   = "student_leave_db";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

/* ================================
   FETCH STUDENT DATA
   ================================ */
$student_id = $_SESSION['student_id'];
$query = "SELECT * FROM students WHERE id='$student_id'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #0b0f1a;
            color: #ffffff;
            overflow-x: hidden;
        }

        /* Ambient background */
        .bg-1, .bg-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.7;
            z-index: 0;
            pointer-events: none;
        }

        .bg-1 {
            background: #3b82f6;
            top: -200px;
            left: -200px;
        }

        .bg-2 {
            background: #22c55e;
            bottom: -200px;
            right: -200px;
        }

        header {
            position: relative;
            z-index: 1;
            padding: 70px 20px 40px;
            text-align: center;
        }

        header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            margin: 0;
            letter-spacing: -1px;
        }

        .container {
            position: relative;
            z-index: 1;
            width: 90%;
            max-width: 1100px;
            margin: 0 auto 80px;
        }

        /* Welcome card */
        .welcome {
            background: linear-gradient(
                180deg,
                rgba(255,255,255,0.14),
                rgba(255,255,255,0.04)
            );
            backdrop-filter: blur(18px);
            border-radius: 26px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow:
                0 40px 80px rgba(0,0,0,0.85),
                inset 0 1px 0 rgba(255,255,255,0.2);
            position: relative;
        }

        .welcome::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 26px;
            padding: 1px;
            background: linear-gradient(135deg, #60a5fa, #34d399, #a78bfa);
            -webkit-mask:
                linear-gradient(#000 0 0) content-box,
                linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }

        .welcome h2 {
            margin-top: 0;
            font-size: 28px;
        }

        /* Cards grid */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 30px;
        }

        .card {
            background: linear-gradient(
                180deg,
                rgba(255,255,255,0.14),
                rgba(255,255,255,0.04)
            );
            backdrop-filter: blur(18px);
            border-radius: 26px;
            padding: 35px 25px;
            text-align: center;
            box-shadow:
                0 30px 70px rgba(0,0,0,0.8),
                inset 0 1px 0 rgba(255,255,255,0.2);
            transition: transform 0.6s ease, box-shadow 0.6s ease;
            position: relative;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 26px;
            padding: 1px;
            background: linear-gradient(135deg, #60a5fa, #34d399, #a78bfa);
            -webkit-mask:
                linear-gradient(#000 0 0) content-box,
                linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }

        .card:hover {
            transform: translateY(-12px);
            box-shadow: 0 50px 100px rgba(0,0,0,0.9);
        }

        .card h3 {
            margin-top: 0;
            font-size: 22px;
        }

        .btn {
            display: inline-block;
            margin-top: 18px;
            padding: 14px 26px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            color: #0b0f1a;
            background: linear-gradient(135deg, #e0f2fe, #d1fae5);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .btn::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                120deg,
                transparent 30%,
                rgba(255,255,255,0.7),
                transparent 70%
            );
            transform: translateX(-100%);
            transition: transform 0.8s ease;
        }

        .btn:hover::after {
            transform: translateX(100%);
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        }

        footer {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 25px;
            font-size: 14px;
            color: #94a3b8;
        }

        @media (max-width: 600px) {
            header h1 {
                font-size: 32px;
            }
        }
    </style>
</head>

<body>

<div class="bg-1"></div>
<div class="bg-2"></div>

<header>
    <h1>Student Dashboard</h1>
</header>

<div class="container">

    <div class="welcome">
        <h2>Welcome, <?php echo $student['name']; ?></h2>
        <p><strong>Class:</strong> <?php echo $student['class']; ?></p>
        <p><strong>EN Number:</strong> <?php echo $student['en_no']; ?></p>
    </div>

    <div class="cards">
        <div class="card">
            <h3>Apply Leave</h3>
            <p>Submit a new leave application</p>
            <a href="apply_leave.php" class="btn">Apply</a>
        </div>

        <div class="card">
            <h3>Leave Status</h3>
            <p>Check your leave application status</p>
            <a href="leave_status.php" class="btn">View</a>
        </div>

        <div class="card">
            <h3>Download PDF</h3>
            <p>Generate leave report</p>
            <a href="generate_pdf.php" class="btn">Download</a>
        </div>

        <div class="card">
            <h3>Analytics</h3>
            <a href="analytics.php" class="btn">Download</a>
        </div>

        <div class="card">
            <h3>Logout</h3>
            <p>End your session safely</p>
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>

</div>

<footer>
    © 2026 | Student Leave Management System
</footer>

</body>
</html>
