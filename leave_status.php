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

/* ================================
   FETCH STUDENT LEAVE STATUS
   ================================ */
$student_id = $_SESSION['student_id'];

$query = "SELECT * FROM leave_applications 
          WHERE student_id='$student_id' 
          ORDER BY applied_date DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leave Status</title>
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
            background: #6366f1;
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
            width: 95%;
            max-width: 1200px;
            margin: 0 auto 80px;
        }

        .table-card {
            background: linear-gradient(
                180deg,
                rgba(255,255,255,0.14),
                rgba(255,255,255,0.04)
            );
            backdrop-filter: blur(18px);
            border-radius: 28px;
            padding: 25px;
            box-shadow:
                0 40px 80px rgba(0,0,0,0.85),
                inset 0 1px 0 rgba(255,255,255,0.2);
            position: relative;
            overflow-x: auto;
        }

        .table-card::before {
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

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th, td {
            padding: 14px 12px;
            text-align: center;
            font-size: 14px;
        }

        th {
            font-weight: 600;
            color: #e5e7eb;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        td {
            color: #f1f5f9;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        tr:hover td {
            background: rgba(255,255,255,0.03);
        }

        .status-approved {
            color: #22c55e;
            font-weight: 600;
        }

        .status-rejected {
            color: #f87171;
            font-weight: 600;
        }

        .status-pending {
            color: #fbbf24;
            font-weight: 600;
        }

        .back {
            text-align: center;
            margin-top: 22px;
        }

        .back a {
            color: #93c5fd;
            text-decoration: none;
            font-weight: 500;
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

        @media (max-width: 768px) {
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
    <h1>Leave Status</h1>
    <p>Track Your Leave Applications</p>
</header>

<div class="container">

    <div class="table-card">
        <table>
            <tr>
                <th>Leave Type</th>
                <th>From</th>
                <th>To</th>
                <th>Days</th>
                <th>Status</th>
                <th>Faculty Remark</th>
                <th>HOD Remark</th>
            </tr>

            <?php if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['leave_type']; ?></td>
                <td><?php echo $row['from_date']; ?></td>
                <td><?php echo $row['to_date']; ?></td>
                <td><?php echo $row['total_days']; ?></td>
                <td>
                    <?php
                        if ($row['status'] == 'Approved') {
                            echo "<span class='status-approved'>Approved</span>";
                        } elseif ($row['status'] == 'Rejected') {
                            echo "<span class='status-rejected'>Rejected</span>";
                        } else {
                            echo "<span class='status-pending'>Pending</span>";
                        }
                    ?>
                </td>
                <td><?php echo $row['faculty_remark']; ?></td>
                <td><?php echo $row['hod_remark']; ?></td>
            </tr>
            <?php } } else { ?>
            <tr>
                <td colspan="7">No leave applications found</td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <div class="back">
        <a href="student_dashboard.php">⬅ Back to Dashboard</a>
    </div>

</div>

<footer>
    © 2026 | Student Leave Management System
</footer>

</body>
</html>
