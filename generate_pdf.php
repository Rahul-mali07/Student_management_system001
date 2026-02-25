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

$student_id = $_SESSION['student_id'];

/* ================================
   FETCH STUDENT DETAILS
   ================================ */
$student_q = mysqli_query($conn, "SELECT * FROM students WHERE id='$student_id'");
$student = mysqli_fetch_assoc($student_q);

/* ================================
   FETCH LATEST APPROVED LEAVE
   ================================ */
$leave_q = mysqli_query(
    $conn,
    "SELECT * FROM leave_applications 
     WHERE student_id='$student_id' AND status='Approved'
     ORDER BY applied_date DESC LIMIT 1"
);

$leave = mysqli_fetch_assoc($leave_q);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leave Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts (same family feel as register page) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #eef2f7;
            color: #111827;
        }

        /* Top action */
        .print-btn {
            text-align: center;
            margin: 30px 0;
        }

        .print-btn button {
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 600;
            background: #0a3d62;
            color: #fff;
            border: none;
            border-radius: 999px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .print-btn button:hover {
            background: #062f4f;
        }

        /* Card-style report (like register page) */
        .report {
            width: 100%;
            max-width: 850px;
            margin: 0 auto 50px;
            background: #ffffff;
            padding: 45px 50px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border: 1px solid #d1d5db;
        }

        h2, h3, h4 {
            text-align: center;
            margin: 6px 0;
            font-weight: 600;
        }

        h2 {
            font-size: 20px;
            text-transform: uppercase;
        }

        h3 {
            font-size: 16px;
        }

        h4 {
            font-size: 15px;
            margin-top: 10px;
            text-decoration: underline;
        }

        .section {
            margin-top: 28px;
        }

        .section strong {
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 14px;
        }

        td, th {
            border: 1px solid #000;
            padding: 10px 12px;
            vertical-align: top;
        }

        td:first-child {
            width: 30%;
            font-weight: 500;
            background: #f8fafc;
        }

        footer {
            text-align: center;
            margin: 40px 0;
            font-size: 14px;
            color: #555;
        }

        /* PRINT MODE – keep it formal */
        @media print {
            body {
                background: white;
            }

            .print-btn,
            footer {
                display: none;
            }

            .report {
                box-shadow: none;
                border-radius: 0;
                border: 2px solid #000;
                margin: 0;
                padding: 30px;
            }

            td:first-child {
                background: #fff;
            }
        }
    </style>
</head>

<body>

<div class="print-btn">
    <button onclick="window.print()">Print / Save as PDF</button>
</div>

<div class="report">

    <h2>N. B. NAVALE SINHGAD COLLEGE OF ENGINEERING, KEGAON, SOLAPUR</h2>
    <h3>DEPARTMENT OF COMPUTER SCIENCE ENGINEERING</h3>
    <h4>Student Leave Application Report</h4>

    <div class="section">
        <strong>Student Details</strong>
        <table>
            <tr>
                <td>Name</td>
                <td><?php echo $student['name']; ?></td>
            </tr>
            <tr>
                <td>Class</td>
                <td><?php echo $student['class']; ?></td>
            </tr>
            <tr>
                <td>EN Number</td>
                <td><?php echo $student['en_no']; ?></td>
            </tr>
        </table>
    </div>

    <?php if ($leave) { ?>
    <div class="section">
        <strong>Leave Details</strong>
        <table>
            <tr>
                <td>Leave Type</td>
                <td><?php echo $leave['leave_type']; ?></td>
            </tr>
            <tr>
                <td>From Date</td>
                <td><?php echo $leave['from_date']; ?></td>
            </tr>
            <tr>
                <td>To Date</td>
                <td><?php echo $leave['to_date']; ?></td>
            </tr>
            <tr>
                <td>Total Days</td>
                <td><?php echo $leave['total_days']; ?></td>
            </tr>
            <tr>
                <td>Reason</td>
                <td><?php echo $leave['reason']; ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td><?php echo $leave['status']; ?></td>
            </tr>
            <tr>
                <td>Faculty Remark</td>
                <td><?php echo $leave['faculty_remark']; ?></td>
            </tr>
            <tr>
                <td>HOD Remark</td>
                <td><?php echo $leave['hod_remark']; ?></td>
            </tr>
        </table>
    </div>
    <?php } else { ?>
        <p style="text-align:center;margin-top:25px;">
            No approved leave found.
        </p>
    <?php } ?>

    <div class="section">
        <table>
            <tr>
                <td>Student Signature</td>
                <td>________________________</td>
            </tr>
            <tr>
                <td>HOD Signature</td>
                <td>dr.hari</td>
            </tr>
        </table>
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

$student_id = $_SESSION['student_id'];

/* ================================
   FETCH STUDENT DETAILS
   ================================ */
$student_q = mysqli_query($conn, "SELECT * FROM students WHERE id='$student_id'");
$student = mysqli_fetch_assoc($student_q);

/* ================================
   FETCH LATEST APPROVED LEAVE
   ================================ */
$leave_q = mysqli_query(
    $conn,
    "SELECT * FROM leave_applications 
     WHERE student_id='$student_id' AND status='Approved'
     ORDER BY applied_date DESC LIMIT 1"
);

$leave = mysqli_fetch_assoc($leave_q);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leave Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts (same family feel as register page) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #eef2f7;
            color: #111827;
        }

        /* Top action */
        .print-btn {
            text-align: center;
            margin: 30px 0;
        }

        .print-btn button {
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 600;
            background: #0a3d62;
            color: #fff;
            border: none;
            border-radius: 999px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .print-btn button:hover {
            background: #062f4f;
        }

        /* Card-style report (like register page) */
        .report {
            width: 100%;
            max-width: 850px;
            margin: 0 auto 50px;
            background: #ffffff;
            padding: 45px 50px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border: 1px solid #d1d5db;
        }

        h2, h3, h4 {
            text-align: center;
            margin: 6px 0;
            font-weight: 600;
        }

        h2 {
            font-size: 20px;
            text-transform: uppercase;
        }

        h3 {
            font-size: 16px;
        }

        h4 {
            font-size: 15px;
            margin-top: 10px;
            text-decoration: underline;
        }

        .section {
            margin-top: 28px;
        }

        .section strong {
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 14px;
        }

        td, th {
            border: 1px solid #000;
            padding: 10px 12px;
            vertical-align: top;
        }

        td:first-child {
            width: 30%;
            font-weight: 500;
            background: #f8fafc;
        }

        footer {
            text-align: center;
            margin: 40px 0;
            font-size: 14px;
            color: #555;
        }

        /* PRINT MODE – keep it formal */
        @media print {
            body {
                background: white;
            }

            .print-btn,
            footer {
                display: none;
            }

            .report {
                box-shadow: none;
                border-radius: 0;
                border: 2px solid #000;
                margin: 0;
                padding: 30px;
            }

            td:first-child {
                background: #fff;
            }
        }
    </style>
</head>

<body>

<div class="print-btn">
    <button onclick="window.print()">Print / Save as PDF</button>
</div>

<div class="report">

    <h2>N. B. NAVALE SINHGAD COLLEGE OF ENGINEERING, KEGAON, SOLAPUR</h2>
    <h3>DEPARTMENT OF COMPUTER SCIENCE ENGINEERING</h3>
    <h4>Student Leave Application Report</h4>

    <div class="section">
        <strong>Student Details</strong>
        <table>
            <tr>
                <td>Name</td>
                <td><?php echo $student['name']; ?></td>
            </tr>
            <tr>
                <td>Class</td>
                <td><?php echo $student['class']; ?></td>
            </tr>
            <tr>
                <td>EN Number</td>
                <td><?php echo $student['en_no']; ?></td>
            </tr>
        </table>
    </div>

    <?php if ($leave) { ?>
    <div class="section">
        <strong>Leave Details</strong>
        <table>
            <tr>
                <td>Leave Type</td>
                <td><?php echo $leave['leave_type']; ?></td>
            </tr>
            <tr>
                <td>From Date</td>
                <td><?php echo $leave['from_date']; ?></td>
            </tr>
            <tr>
                <td>To Date</td>
                <td><?php echo $leave['to_date']; ?></td>
            </tr>
            <tr>
                <td>Total Days</td>
                <td><?php echo $leave['total_days']; ?></td>
            </tr>
            <tr>
                <td>Reason</td>
                <td><?php echo $leave['reason']; ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td><?php echo $leave['status']; ?></td>
            </tr>
            <tr>
                <td>Faculty Remark</td>
                <td><?php echo $leave['faculty_remark']; ?></td>
            </tr>
            <tr>
                <td>HOD Remark</td>
                <td><?php echo $leave['hod_remark']; ?></td>
            </tr>
        </table>
    </div>
    <?php } else { ?>
        <p style="text-align:center;margin-top:25px;">
            No approved leave found.
        </p>
    <?php } ?>

    <div class="section">
        <table>
            <tr>
                <td>Student Signature</td>
                <td>________________________</td>
            </tr>
            <tr>
                <td>HOD Signature</td>
                <td>dr.hari</td>
            </tr>
        </table>
    </div>

</div>

<footer>
    © 2026 | Student Leave Management System
</footer>

</body>
</html>
>>>>>>> 37ab6df4249719db4fc9fefb4872290fa0452799
