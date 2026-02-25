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

$student_id = $_SESSION['student_id'];

/* ================================
   FETCH COUNTS
   ================================ */
$approved = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM leave_applications 
     WHERE student_id='$student_id' AND status='Approved'"
))['total'];

$rejected = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM leave_applications 
     WHERE student_id='$student_id' AND status='Rejected'"
))['total'];

$pending = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM leave_applications 
     WHERE student_id='$student_id' AND status='Pending'"
))['total'];

/* ================================
   LEAVE TYPE DISTRIBUTION
   ================================ */
$sick = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM leave_applications 
     WHERE student_id='$student_id' AND leave_type='Sick Leave'"
))['total'];

$casual = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM leave_applications 
     WHERE student_id='$student_id' AND leave_type='Casual Leave'"
))['total'];

$emergency = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM leave_applications 
     WHERE student_id='$student_id' AND leave_type='Emergency Leave'"
))['total'];

/* ================================
   ATTENDANCE
   ================================ */
$student = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT attendance_percent FROM students WHERE id='$student_id'"
));
$attendance = $student['attendance_percent'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Analytics & Charts</title>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f5f9;
        }

        header {
            background-color: #0a3d62;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            width: 90%;
            margin: 30px auto;
        }

        .chart-box {
            background: white;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            color: #0a3d62;
        }

        .back {
            text-align: center;
            margin-top: 20px;
        }

        footer {
            margin-top: 40px;
            text-align: center;
            color: #555;
        }
    </style>
</head>

<body>

<header>
    <h1>Analytics & Charts</h1>
    <p>Leave and Attendance Insights</p>
</header>

<div class="container">

    <div class="chart-box">
        <h2>Leave Status Overview</h2>
        <canvas id="statusChart"></canvas>
    </div>

    <div class="chart-box">
        <h2>Leave Type Distribution</h2>
        <canvas id="typeChart"></canvas>
    </div>

    <div class="chart-box">
        <h2>Attendance Overview</h2>
        <canvas id="attendanceChart"></canvas>
    </div>

    <div class="back">
        <a href="student_dashboard.php">⬅ Back to Dashboard</a>
    </div>

</div>

<footer>
    <p>© 2026 | Student Leave Management System</p>
</footer>

<script>
/* Leave Status Chart */
new Chart(document.getElementById('statusChart'), {
    type: 'bar',
    data: {
        labels: ['Approved', 'Rejected', 'Pending'],
        datasets: [{
            data: [<?= $approved ?>, <?= $rejected ?>, <?= $pending ?>]
        }]
    }
});

/* Leave Type Chart */
new Chart(document.getElementById('typeChart'), {
    type: 'pie',
    data: {
        labels: ['Sick', 'Casual', 'Emergency'],
        datasets: [{
            data: [<?= $sick ?>, <?= $casual ?>, <?= $emergency ?>]
        }]
    }
});

/* Attendance Chart */
new Chart(document.getElementById('attendanceChart'), {
    type: 'bar',
    data: {
        labels: ['Attendance %'],
        datasets: [{
            data: [<?= $attendance ?>]
        }]
    },
    options: {
        scales: {
            y: { min: 0, max: 100 }
        }
    }
});
</script>

</body>
</html>
