<<<<<<< HEAD
<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "student_leave_db");
if (!$conn) {
    die("Database connection failed");
}

/* ===============================
   FORCE REGISTRATION BEFORE LOGIN
   =============================== */
$check = mysqli_query($conn, "SELECT id FROM students");
if (mysqli_num_rows($check) == 0) {
    header("Location: student_register.php");
    exit();
}

$error = "";

/* ===============================
   LOGIN LOGIC
   =============================== */
if (isset($_POST['login'])) {
    $en_no = mysqli_real_escape_string($conn, $_POST['en_no']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM students 
              WHERE en_no='$en_no' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['student_id'] = $row['id'];
        $_SESSION['student_name'] = $row['name'];
        header("Location: student_dashboard.php");
        exit();
    } else {
        $error = "Invalid EN Number or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Login</title>
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
            color: #ffffff;
            overflow-x: hidden;
        }

        /* 🔥 Background glow (FIXED) */
        .bg-1, .bg-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.7;
            z-index: 0;
            pointer-events: none; /* ✅ IMPORTANT FIX */
        }

        .bg-1 {
            background: #6366f1;
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

        header p {
            margin-top: 12px;
            font-size: 17px;
            color: #cbd5e1;
        }

        /* Login Card */
        .wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            padding: 40px 20px 80px;
        }

        .card {
            width: 100%;
            max-width: 400px;
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
            background: rgba(255,255,255,0.95);
            color: #0b0f1a;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        input:focus {
            transform: translateY(-1px);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.5);
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

        /* Error */
        .error {
            margin-top: 15px;
            text-align: center;
            color: #f87171;
            font-weight: 500;
        }

        .link {
            text-align: center;
            margin-top: 18px;
        }

        .link a {
            color: #93c5fd;
            text-decoration: none;
        }

        .link a:hover {
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
    <p>Online Leave Application Portal</p>
</header>

<div class="wrapper">
    <div class="card">
        <h2>Student Login</h2>

        <form method="post">
            <input type="text" name="en_no" placeholder="EN Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn" name="login">Login</button>
        </form>

        <?php if ($error != "") { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <div class="link">
            New student? <a href="student_register.php">Register here</a>
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

// Database connection
$conn = mysqli_connect("localhost", "root", "", "student_leave_db");
if (!$conn) {
    die("Database connection failed");
}

/* ===============================
   FORCE REGISTRATION BEFORE LOGIN
   =============================== */
$check = mysqli_query($conn, "SELECT id FROM students");
if (mysqli_num_rows($check) == 0) {
    header("Location: student_register.php");
    exit();
}

$error = "";

/* ===============================
   LOGIN LOGIC
   =============================== */
if (isset($_POST['login'])) {
    $en_no = mysqli_real_escape_string($conn, $_POST['en_no']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM students 
              WHERE en_no='$en_no' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['student_id'] = $row['id'];
        $_SESSION['student_name'] = $row['name'];
        header("Location: student_dashboard.php");
        exit();
    } else {
        $error = "Invalid EN Number or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Login</title>
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
            color: #ffffff;
            overflow-x: hidden;
        }

        /* 🔥 Background glow (FIXED) */
        .bg-1, .bg-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.7;
            z-index: 0;
            pointer-events: none; /* ✅ IMPORTANT FIX */
        }

        .bg-1 {
            background: #6366f1;
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

        header p {
            margin-top: 12px;
            font-size: 17px;
            color: #cbd5e1;
        }

        /* Login Card */
        .wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            padding: 40px 20px 80px;
        }

        .card {
            width: 100%;
            max-width: 400px;
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
            background: rgba(255,255,255,0.95);
            color: #0b0f1a;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        input:focus {
            transform: translateY(-1px);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.5);
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

        /* Error */
        .error {
            margin-top: 15px;
            text-align: center;
            color: #f87171;
            font-weight: 500;
        }

        .link {
            text-align: center;
            margin-top: 18px;
        }

        .link a {
            color: #93c5fd;
            text-decoration: none;
        }

        .link a:hover {
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
    <p>Online Leave Application Portal</p>
</header>

<div class="wrapper">
    <div class="card">
        <h2>Student Login</h2>

        <form method="post">
            <input type="text" name="en_no" placeholder="EN Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn" name="login">Login</button>
        </form>

        <?php if ($error != "") { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <div class="link">
            New student? <a href="student_register.php">Register here</a>
        </div>
    </div>
</div>

<footer>
    © 2026 | Student Leave Management System
</footer>

</body>
</html>
>>>>>>> 37ab6df4249719db4fc9fefb4872290fa0452799
