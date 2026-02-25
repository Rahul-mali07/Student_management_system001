<<<<<<< HEAD
<?php
session_start();

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

$msg = "";

/* ================================
   REGISTRATION LOGIC
   ================================ */
if (isset($_POST['register'])) {
    $name   = mysqli_real_escape_string($conn, $_POST['name']);
    $class  = mysqli_real_escape_string($conn, $_POST['class']);
    $roll   = mysqli_real_escape_string($conn, $_POST['roll_no']);
    $en_no  = mysqli_real_escape_string($conn, $_POST['en_no']);
    $passw  = mysqli_real_escape_string($conn, $_POST['password']);

    $check = mysqli_query($conn, "SELECT * FROM students WHERE en_no='$en_no'");
    if (mysqli_num_rows($check) > 0) {
        $msg = "EN Number already registered!";
    } else {
        $insert = "INSERT INTO students 
                   (name, class, roll_no, en_no, password, attendance_percent)
                   VALUES 
                   ('$name', '$class', '$roll', '$en_no', '$passw', 75.00)";

        if (mysqli_query($conn, $insert)) {
            $msg = "Registration successful! You can login now.";
        } else {
            $msg = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: #0b0f1a;
            color: #fff;
            overflow-x: hidden;
        }

        /* Background glow */
        .bg-1, .bg-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.7;
            z-index: 0;
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

        /* Header */
        header {
            position: relative;
            z-index: 1;
            padding: 80px 20px 40px;
            text-align: center;
        }

        header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 44px;
            margin: 0;
            letter-spacing: -1px;
        }

        /* Form wrapper */
        .wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            padding: 40px 20px 80px;
        }

        .card {
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            border-radius: 28px;
            background: linear-gradient(
                180deg,
                rgba(255,255,255,0.14),
                rgba(255,255,255,0.04)
            );
            backdrop-filter: blur(18px);
            box-shadow:
                0 40px 80px rgba(0,0,0,0.85),
                inset 0 1px 0 rgba(255,255,255,0.2);
            position: relative;
        }

        .card::before {
            content: " ";
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
            pointer-events: none; /* ✅ FINAL FIX */
        }

        .card h2 {
            text-align: center;
            font-size: 26px;
            margin-bottom: 30px;
            font-weight: 600;
        }

        /* Inputs */
        input {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 16px;
            border-radius: 14px;
            border: none;
            outline: none;
            font-size: 14px;
            background: rgba(255,255,255,0.9);
            color: #0b0f1a;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        input:focus {
            transform: translateY(-1px);
            box-shadow: 0 0 0 3px rgba(96,165,250,0.5);
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 15px;
            border-radius: 999px;
            border: none;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(135deg, #e0f2fe, #d1fae5);
            color: #0b0f1a;
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
            transform: scale(1.03);
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        }

        /* Message */
        .msg {
            margin-top: 15px;
            text-align: center;
            color: #22c55e;
            font-weight: 500;
        }

        .login-link {
            text-align: center;
            margin-top: 18px;
        }

        .login-link a {
            color: #93c5fd;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        footer {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 20px;
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
    <h1>Student Leave Management System</h1>
</header>

<div class="wrapper">
    <div class="card">
        <h2>Student Registration</h2>

        <form method="post">
            <input type="text" name="name" placeholder="Student Name" required>
            <input type="text" name="class" placeholder="Class (e.g. TY CSE)" required>
            <input type="text" name="roll_no" placeholder="Roll Number" required>
            <input type="text" name="en_no" placeholder="EN Number" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" name="register" class="btn">Register</button>
        </form>

        <?php if ($msg != "") { ?>
            <div class="msg"><?php echo $msg; ?></div>
        <?php } ?>

        <div class="login-link">
            <a href="student_login.php">Already registered? Login</a>
        </div>
    </div>
</div>

<footer>
    © 2026 | Student Leave Management System
</footer>

</body>
</html>
=======
<?php
session_start();

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

$msg = "";

/* ================================
   REGISTRATION LOGIC
   ================================ */
if (isset($_POST['register'])) {
    $name   = mysqli_real_escape_string($conn, $_POST['name']);
    $class  = mysqli_real_escape_string($conn, $_POST['class']);
    $roll   = mysqli_real_escape_string($conn, $_POST['roll_no']);
    $en_no  = mysqli_real_escape_string($conn, $_POST['en_no']);
    $passw  = mysqli_real_escape_string($conn, $_POST['password']);

    $check = mysqli_query($conn, "SELECT * FROM students WHERE en_no='$en_no'");
    if (mysqli_num_rows($check) > 0) {
        $msg = "EN Number already registered!";
    } else {
        $insert = "INSERT INTO students 
                   (name, class, roll_no, en_no, password, attendance_percent)
                   VALUES 
                   ('$name', '$class', '$roll', '$en_no', '$passw', 75.00)";

        if (mysqli_query($conn, $insert)) {
            $msg = "Registration successful! You can login now.";
        } else {
            $msg = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: #0b0f1a;
            color: #fff;
            overflow-x: hidden;
        }

        /* Background glow */
        .bg-1, .bg-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.7;
            z-index: 0;
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

        /* Header */
        header {
            position: relative;
            z-index: 1;
            padding: 80px 20px 40px;
            text-align: center;
        }

        header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 44px;
            margin: 0;
            letter-spacing: -1px;
        }

        /* Form wrapper */
        .wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            padding: 40px 20px 80px;
        }

        .card {
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            border-radius: 28px;
            background: linear-gradient(
                180deg,
                rgba(255,255,255,0.14),
                rgba(255,255,255,0.04)
            );
            backdrop-filter: blur(18px);
            box-shadow:
                0 40px 80px rgba(0,0,0,0.85),
                inset 0 1px 0 rgba(255,255,255,0.2);
            position: relative;
        }

        .card::before {
            content: " ";
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
            pointer-events: none; /* ✅ FINAL FIX */
        }

        .card h2 {
            text-align: center;
            font-size: 26px;
            margin-bottom: 30px;
            font-weight: 600;
        }

        /* Inputs */
        input {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 16px;
            border-radius: 14px;
            border: none;
            outline: none;
            font-size: 14px;
            background: rgba(255,255,255,0.9);
            color: #0b0f1a;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        input:focus {
            transform: translateY(-1px);
            box-shadow: 0 0 0 3px rgba(96,165,250,0.5);
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 15px;
            border-radius: 999px;
            border: none;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(135deg, #e0f2fe, #d1fae5);
            color: #0b0f1a;
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
            transform: scale(1.03);
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        }

        /* Message */
        .msg {
            margin-top: 15px;
            text-align: center;
            color: #22c55e;
            font-weight: 500;
        }

        .login-link {
            text-align: center;
            margin-top: 18px;
        }

        .login-link a {
            color: #93c5fd;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        footer {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 20px;
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
    <h1>Student Leave Management System</h1>
</header>

<div class="wrapper">
    <div class="card">
        <h2>Student Registration</h2>

        <form method="post">
            <input type="text" name="name" placeholder="Student Name" required>
            <input type="text" name="class" placeholder="Class (e.g. TY CSE)" required>
            <input type="text" name="roll_no" placeholder="Roll Number" required>
            <input type="text" name="en_no" placeholder="EN Number" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" name="register" class="btn">Register</button>
        </form>

        <?php if ($msg != "") { ?>
            <div class="msg"><?php echo $msg; ?></div>
        <?php } ?>

        <div class="login-link">
            <a href="student_login.php">Already registered? Login</a>
        </div>
    </div>
</div>

<footer>
    © 2026 | Student Leave Management System
</footer>

</body>
</html>
>>>>>>> 37ab6df4249719db4fc9fefb4872290fa0452799
