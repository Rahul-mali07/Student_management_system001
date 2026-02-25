<?php
// Database connection (UNCHANGED)
$conn = mysqli_connect("localhost", "root", "", "student_leave_db");
if (!$conn) {
    die("Database connection failed");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Leave Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #0b0f1a;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* ===== BACKGROUND DECOR ===== */
        .bg-shape {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, #3b82f6, transparent 60%);
            top: -200px;
            left: -200px;
            z-index: 0;
        }

        .bg-shape-2 {
            position: absolute;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, #22c55e, transparent 60%);
            bottom: -250px;
            right: -250px;
            z-index: 0;
        }

        /* ===== HERO ===== */
        header {
            position: relative;
            z-index: 1;
            padding: 100px 20px 60px;
            text-align: center;
        }

        header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 56px;
            line-height: 1.1;
            margin: 0;
            letter-spacing: -1px;
        }

        header p {
            margin-top: 20px;
            font-size: 18px;
            color: #cbd5e1;
        }

        /* ===== MAIN ===== */
        .main {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            padding: 60px 20px 120px;
        }

        .grid {
            max-width: 1100px;
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 50px;
        }

        /* ===== PANEL ===== */
        .panel {
            position: relative;
            padding: 50px 40px;
            border-radius: 28px;
            background: linear-gradient(
                180deg,
                rgba(255,255,255,0.12),
                rgba(255,255,255,0.02)
            );
            box-shadow:
                0 40px 80px rgba(0,0,0,0.8),
                inset 0 1px 0 rgba(255,255,255,0.2);
            backdrop-filter: blur(18px);
            transition: transform 0.6s ease, box-shadow 0.6s ease;
        }

        .panel::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 28px;
            padding: 1px;
            background: linear-gradient(135deg, #60a5fa, #34d399, #a78bfa);
            -webkit-mask:
                linear-gradient(#000 0 0) content-box,
                linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }

        .panel:hover {
            transform: translateY(-18px) rotateX(4deg);
            box-shadow: 0 60px 120px rgba(0,0,0,0.9);
        }

        .panel h3 {
            font-size: 26px;
            margin: 0 0 30px;
            font-weight: 600;
        }

        /* ===== BUTTON ===== */
        .btn {
            display: block;
            margin: 18px 0;
            padding: 16px;
            text-align: center;
            text-decoration: none;
            border-radius: 999px;
            font-weight: 600;
            font-size: 15px;
            color: #0b0f1a;
            background: linear-gradient(135deg, #e0f2fe, #d1fae5);
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
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
            transform: scale(1.04);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }

        /* ===== FOOTER ===== */
        footer {
            text-align: center;
            padding: 25px;
            font-size: 14px;
            color: #94a3b8;
            position: relative;
            z-index: 1;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            header h1 {
                font-size: 38px;
            }
            header p {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

<div class="bg-shape"></div>
<div class="bg-shape-2"></div>

<header>
    <h1>Student Leave Management System</h1>
    <p>Online Leave Application & Approval Portal</p>
</header>

<div class="main">
    <div class="grid">

        <!-- STUDENT PANEL (CONTENT UNCHANGED) -->
        <div class="panel">
            <h3>Student Panel</h3>
            <a href="student_register.php" class="btn">Student Registration</a>
            <a href="student_login.php" class="btn">Student Login</a>
        </div>

        <!-- STAFF PANEL (CONTENT UNCHANGED) -->
        <div class="panel">
            <h3>Staff Panel</h3>
            <a href="faculty_register.php" class="btn">Faculty / HOD Registration</a>
            <a href="faculty_login.php" class="btn">Faculty / HOD Login</a>
        </div>

    </div>
</div>

<footer>
    © 2026 | Student Leave Management System
</footer>

</body>
</html>
