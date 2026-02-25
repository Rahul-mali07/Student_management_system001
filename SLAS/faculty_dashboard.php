<?php
session_start();

/* FACULTY SESSION CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty') {
    header("Location: faculty_login.php");
    exit();
}

/* DATABASE CONNECTION */
$conn = mysqli_connect("localhost", "root", "", "student_leave_db");
if (!$conn) {
    die("Database connection failed");
}

/* APPROVE / REJECT LOGIC */
if (isset($_POST['action'])) {
    $leave_id = $_POST['leave_id'];
    $remark   = mysqli_real_escape_string($conn, $_POST['remark']);
    $status   = $_POST['action'];

    mysqli_query($conn,
        "UPDATE leave_applications 
         SET status='$status', faculty_remark='$remark' 
         WHERE id='$leave_id'"
    );
}

/* FETCH PENDING LEAVES */
$result = mysqli_query($conn,
    "SELECT leave_applications.*, students.name, students.en_no 
     FROM leave_applications 
     JOIN students ON leave_applications.student_id = students.id
     WHERE leave_applications.status='Pending'"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Faculty Dashboard</title>
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

.bg-1 { background:#6366f1; top:-200px; left:-200px; }
.bg-2 { background:#22c55e; bottom:-200px; right:-200px; }

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
}

header p {
    margin-top: 10px;
    color: #cbd5e1;
}

.container {
    position: relative;
    z-index: 1;
    width: 95%;
    max-width: 1300px;
    margin: 0 auto 80px;
}

/* Top buttons */
.top-btns {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-bottom: 18px;
}

.top-btns a {
    padding: 10px 18px;
    border-radius: 999px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    color: #0b0f1a;
    background: linear-gradient(135deg,#e0f2fe,#d1fae5);
    transition: transform .3s ease, box-shadow .3s ease;
}

.top-btns a:hover {
    transform: scale(1.05);
    box-shadow: 0 15px 30px rgba(0,0,0,.6);
}

/* Table card */
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
    content:"";
    position:absolute;
    inset:0;
    border-radius:28px;
    padding:1px;
    background:linear-gradient(135deg,#60a5fa,#34d399,#a78bfa);
    -webkit-mask:
        linear-gradient(#000 0 0) content-box,
        linear-gradient(#000 0 0);
    -webkit-mask-composite:xor;
    mask-composite:exclude;
    pointer-events:none;
}

table {
    width:100%;
    border-collapse:collapse;
    min-width:1000px;
}

th, td {
    padding: 12px;
    text-align: center;
    font-size: 14px;
}

th {
    border-bottom: 1px solid rgba(255,255,255,0.25);
    font-weight: 600;
}

td {
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

tr:hover td {
    background: rgba(255,255,255,0.04);
}

textarea {
    width: 100%;
    border-radius: 10px;
    padding: 8px;
    border: none;
    outline: none;
    resize: none;
    font-family: 'Inter', sans-serif;
}

/* Action buttons */
.btn-a {
    background: #22c55e;
    color: #0b0f1a;
    border: none;
    padding: 6px 12px;
    border-radius: 999px;
    font-weight: 600;
    cursor: pointer;
    margin-right: 4px;
}

.btn-r {
    background: #f87171;
    color: #0b0f1a;
    border: none;
    padding: 6px 12px;
    border-radius: 999px;
    font-weight: 600;
    cursor: pointer;
}

footer {
    position: relative;
    z-index: 1;
    text-align: center;
    padding: 25px;
    font-size: 14px;
    color: #94a3b8;
}
</style>
</head>

<body>

<div class="bg-1"></div>
<div class="bg-2"></div>

<header>
<h1>Faculty Dashboard</h1>
<p>Leave Approval & Attendance Management</p>
</header>

<div class="container">

<div class="top-btns">
    <a href="logout.php">Logout</a>
</div>

<div class="table-card">
<table>
<tr>
<th>Student Name</th>
<th>EN No</th>
<th>Leave Type</th>
<th>From</th>
<th>To</th>
<th>Days</th>
<th>Reason</th>
<th>Remark</th>
<th>Action</th>
</tr>

<?php if(mysqli_num_rows($result)>0){
while($row=mysqli_fetch_assoc($result)){ ?>
<tr>
<td><?= $row['name'] ?></td>
<td><?= $row['en_no'] ?></td>
<td><?= $row['leave_type'] ?></td>
<td><?= $row['from_date'] ?></td>
<td><?= $row['to_date'] ?></td>
<td><?= $row['total_days'] ?></td>
<td><?= $row['reason'] ?></td>
<td>
<form method="post">
<textarea name="remark" required></textarea>
</td>
<td>
<input type="hidden" name="leave_id" value="<?= $row['id'] ?>">
<button name="action" value="Approved" class="btn-a">Approve</button>
<button name="action" value="Rejected" class="btn-r">Reject</button>
</form>
</td>
</tr>
<?php }} else { ?>
<tr><td colspan="9">No pending leave applications</td></tr>
<?php } ?>
</table>
</div>

</div>

<footer>© 2026 | Student Leave Management System</footer>
</body>
</html>
