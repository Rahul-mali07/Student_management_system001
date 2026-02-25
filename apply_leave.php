<<<<<<< HEAD
<?php
session_start();

/* ================================
   SESSION CHECK (STUDENT ONLY)
   ================================ */
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

/* ================================
   DATABASE CONNECTION
   ================================ */
$conn = mysqli_connect("localhost", "root", "", "student_leave_db");
if (!$conn) {
    die("Database connection failed");
}

$msg = "";

/* ================================
   FETCH STUDENT DETAILS
   ================================ */
$student_id = $_SESSION['student_id'];
$student_q = mysqli_query($conn, "SELECT * FROM students WHERE id='$student_id'");
$student = mysqli_fetch_assoc($student_q);

/* ================================
   APPLY LEAVE LOGIC
   ================================ */
if (isset($_POST['apply_leave'])) {

    $leave_type = mysqli_real_escape_string($conn, $_POST['leave_type']);
    $from_date  = $_POST['from_date'];
    $to_date    = $_POST['to_date'];
    $reason     = mysqli_real_escape_string($conn, $_POST['reason']);

    $date1 = new DateTime($from_date);
    $date2 = new DateTime($to_date);
    $total_days = $date1->diff($date2)->days + 1;

    $proof_name = "";
    if (!empty($_FILES['proof']['name'])) {
        $proof_name = time() . "_" . $_FILES['proof']['name'];
        move_uploaded_file($_FILES['proof']['tmp_name'], "uploads/" . $proof_name);
    }

    $insert = "INSERT INTO leave_applications
              (student_id, leave_type, from_date, to_date, total_days, reason, proof)
              VALUES
              ('$student_id', '$leave_type', '$from_date', '$to_date', '$total_days', '$reason', '$proof_name')";

    if (mysqli_query($conn, $insert)) {
        $msg = "Leave application submitted successfully!";
    } else {
        $msg = "Error submitting leave.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Apply Leave</title>
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

        /* Background glow */
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

        header p {
            margin-top: 12px;
            font-size: 17px;
            color: #cbd5e1;
        }

        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 520px;
            margin: 0 auto 80px;
            padding: 0 20px;
        }

        .card {
            background: linear-gradient(
                180deg,
                rgba(255,255,255,0.14),
                rgba(255,255,255,0.04)
            );
            backdrop-filter: blur(18px);
            border-radius: 28px;
            padding: 40px 35px;
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
            pointer-events: none;
        }

        .card h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 26px;
        }

        label {
            font-weight: 500;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            padding: 14px 16px;
            margin: 8px 0 18px;
            border-radius: 14px;
            border: none;
            outline: none;
            font-size: 14px;
            background: rgba(255,255,255,0.95);
            color: #0b0f1a;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            transform: translateY(-1px);
            box-shadow: 0 0 0 3px rgba(96,165,250,0.5);
        }

        textarea {
            resize: none;
        }

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
            transform: scale(1.04);
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        }

        .msg {
            text-align: center;
            margin-top: 18px;
            color: #22c55e;
            font-weight: 600;
        }

        .back {
            text-align: center;
            margin-top: 18px;
        }

        .back a {
            color: #93c5fd;
            text-decoration: none;
        }

        .back a:hover {
            text-decoration: underline;
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
    <h1>Apply Leave</h1>
    <p>Student Leave Application Form</p>
</header>

<div class="container">
    <div class="card">
        <h2>Leave Application</h2>

        <form method="post" enctype="multipart/form-data">

            <label>Student Name</label>
            <input type="text" value="<?php echo $student['name']; ?>" readonly>

            <label>EN Number</label>
            <input type="text" value="<?php echo $student['en_no']; ?>" readonly>

            <label>Leave Type</label>
            <select name="leave_type" required>
                <option value="">Select Leave Type</option>
                <option>Sick Leave</option>
                <option>Casual Leave</option>
                <option>Emergency Leave</option>
            </select>

            <label>From Date</label>
            <input type="date" name="from_date" required>

            <label>To Date</label>
            <input type="date" name="to_date" required>

            <label>Reason</label>
            <textarea name="reason" rows="3" required></textarea>

            <label>Upload Proof (optional)</label>
            <input type="file" name="proof">

            <button type="submit" name="apply_leave" class="btn">Submit Leave</button>
        </form>

        <?php if ($msg != "") { ?>
            <div class="msg"><?php echo $msg; ?></div>
        <?php } ?>

        <div class="back">
            <a href="student_dashboard.php">⬅ Back to Dashboard</a>
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
   SESSION CHECK (STUDENT ONLY)
   ================================ */
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

/* ================================
   DATABASE CONNECTION
   ================================ */
$conn = mysqli_connect("localhost", "root", "", "student_leave_db");
if (!$conn) {
    die("Database connection failed");
}

$msg = "";

/* ================================
   FETCH STUDENT DETAILS
   ================================ */
$student_id = $_SESSION['student_id'];
$student_q = mysqli_query($conn, "SELECT * FROM students WHERE id='$student_id'");
$student = mysqli_fetch_assoc($student_q);

/* ================================
   APPLY LEAVE LOGIC
   ================================ */
if (isset($_POST['apply_leave'])) {

    $leave_type = mysqli_real_escape_string($conn, $_POST['leave_type']);
    $from_date  = $_POST['from_date'];
    $to_date    = $_POST['to_date'];
    $reason     = mysqli_real_escape_string($conn, $_POST['reason']);

    $date1 = new DateTime($from_date);
    $date2 = new DateTime($to_date);
    $total_days = $date1->diff($date2)->days + 1;

    $proof_name = "";
    if (!empty($_FILES['proof']['name'])) {
        $proof_name = time() . "_" . $_FILES['proof']['name'];
        move_uploaded_file($_FILES['proof']['tmp_name'], "uploads/" . $proof_name);
    }

    $insert = "INSERT INTO leave_applications
              (student_id, leave_type, from_date, to_date, total_days, reason, proof)
              VALUES
              ('$student_id', '$leave_type', '$from_date', '$to_date', '$total_days', '$reason', '$proof_name')";

    if (mysqli_query($conn, $insert)) {
        $msg = "Leave application submitted successfully!";
    } else {
        $msg = "Error submitting leave.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Apply Leave</title>
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

        /* Background glow */
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

        header p {
            margin-top: 12px;
            font-size: 17px;
            color: #cbd5e1;
        }

        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 520px;
            margin: 0 auto 80px;
            padding: 0 20px;
        }

        .card {
            background: linear-gradient(
                180deg,
                rgba(255,255,255,0.14),
                rgba(255,255,255,0.04)
            );
            backdrop-filter: blur(18px);
            border-radius: 28px;
            padding: 40px 35px;
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
            pointer-events: none;
        }

        .card h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 26px;
        }

        label {
            font-weight: 500;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            padding: 14px 16px;
            margin: 8px 0 18px;
            border-radius: 14px;
            border: none;
            outline: none;
            font-size: 14px;
            background: rgba(255,255,255,0.95);
            color: #0b0f1a;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            transform: translateY(-1px);
            box-shadow: 0 0 0 3px rgba(96,165,250,0.5);
        }

        textarea {
            resize: none;
        }

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
            transform: scale(1.04);
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        }

        .msg {
            text-align: center;
            margin-top: 18px;
            color: #22c55e;
            font-weight: 600;
        }

        .back {
            text-align: center;
            margin-top: 18px;
        }

        .back a {
            color: #93c5fd;
            text-decoration: none;
        }

        .back a:hover {
            text-decoration: underline;
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
    <h1>Apply Leave</h1>
    <p>Student Leave Application Form</p>
</header>

<div class="container">
    <div class="card">
        <h2>Leave Application</h2>

        <form method="post" enctype="multipart/form-data">

            <label>Student Name</label>
            <input type="text" value="<?php echo $student['name']; ?>" readonly>

            <label>EN Number</label>
            <input type="text" value="<?php echo $student['en_no']; ?>" readonly>

            <label>Leave Type</label>
            <select name="leave_type" required>
                <option value="">Select Leave Type</option>
                <option>Sick Leave</option>
                <option>Casual Leave</option>
                <option>Emergency Leave</option>
            </select>

            <label>From Date</label>
            <input type="date" name="from_date" required>

            <label>To Date</label>
            <input type="date" name="to_date" required>

            <label>Reason</label>
            <textarea name="reason" rows="3" required></textarea>

            <label>Upload Proof (optional)</label>
            <input type="file" name="proof">

            <button type="submit" name="apply_leave" class="btn">Submit Leave</button>
        </form>

        <?php if ($msg != "") { ?>
            <div class="msg"><?php echo $msg; ?></div>
        <?php } ?>

        <div class="back">
            <a href="student_dashboard.php">⬅ Back to Dashboard</a>
        </div>
    </div>
</div>

<footer>
    © 2026 | Student Leave Management System
</footer>

</body>
</html>
>>>>>>> 37ab6df4249719db4fc9fefb4872290fa0452799
