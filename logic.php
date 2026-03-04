<?php
include 'connection.php';

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];

    $stmt = mysqli_prepare($conn, "DELETE FROM enrollments WHERE enrollment_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $deleteId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

 
    header('Location: OnlineCourseEnrollmentSystem.php');
    exit;
}


$editId            = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$formFirstName     = '';
$formLastName      = '';
$formEmailAddress  = '';
$formCourseName    = '';
$formEntryDate     = date('Y-m-d');

if ($editId > 0) {
 
    $stmt = mysqli_prepare(
        $conn,
        "SELECT e.enrollment_id,
                s.student_id,
                s.first_name,
                s.last_name,
                s.email_address,
                e.course_name,
                e.entry_date
         FROM enrollments e
         INNER JOIN students s ON e.student_id = s.student_id
         WHERE e.enrollment_id = ?"
    );
    mysqli_stmt_bind_param($stmt, 'i', $editId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($res)) {
        $formFirstName    = htmlspecialchars($row['first_name']);
        $formLastName     = htmlspecialchars($row['last_name']);
        $formEmailAddress = htmlspecialchars($row['email_address']);
        $formCourseName   = htmlspecialchars($row['course_name']);
        $formEntryDate    = $row['entry_date'];
    }

    mysqli_stmt_close($stmt);
}

// Handle create / update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName    = trim($_POST['first_name'] ?? '');
    $lastName     = trim($_POST['last_name'] ?? '');
    $emailAddress = trim($_POST['email_address'] ?? '');
    $courseName   = trim($_POST['course_name'] ?? '');
    $entryDate    = trim($_POST['entry_date'] ?? '');

    if (
        $firstName !== '' &&
        $lastName !== '' &&
        $emailAddress !== '' &&
        $courseName !== '' &&
        $entryDate !== ''
    ) {
        if ($editId > 0) {
        
            $lookupStmt = mysqli_prepare(
                $conn,
                "SELECT student_id FROM enrollments WHERE enrollment_id = ?"
            );
            mysqli_stmt_bind_param($lookupStmt, 'i', $editId);
            mysqli_stmt_execute($lookupStmt);
            $lookupRes = mysqli_stmt_get_result($lookupStmt);
            $studentId = null;
            if ($row = mysqli_fetch_assoc($lookupRes)) {
                $studentId = (int) $row['student_id'];
            }
            mysqli_stmt_close($lookupStmt);

            if ($studentId !== null) {
               
                $studentStmt = mysqli_prepare(
                    $conn,
                    "UPDATE students
                     SET first_name = ?, last_name = ?, email_address = ?
                     WHERE student_id = ?"
                );
                mysqli_stmt_bind_param(
                    $studentStmt,
                    'sssi',
                    $firstName,
                    $lastName,
                    $emailAddress,
                    $studentId
                );
                mysqli_stmt_execute($studentStmt);
                mysqli_stmt_close($studentStmt);

            
                $enrollStmt = mysqli_prepare(
                    $conn,
                    "UPDATE enrollments
                     SET course_name = ?, entry_date = ?
                     WHERE enrollment_id = ?"
                );
                mysqli_stmt_bind_param(
                    $enrollStmt,
                    'ssi',
                    $courseName,
                    $entryDate,
                    $editId
                );
                mysqli_stmt_execute($enrollStmt);
                mysqli_stmt_close($enrollStmt);
            }
        } else {
       
            $studentStmt = mysqli_prepare(
                $conn,
                "INSERT INTO students (first_name, last_name, email_address)
                 VALUES (?, ?, ?)"
            );
            mysqli_stmt_bind_param(
                $studentStmt,
                'sss',
                $firstName,
                $lastName,
                $emailAddress
            );
            mysqli_stmt_execute($studentStmt);

            $studentId = mysqli_insert_id($conn);
            mysqli_stmt_close($studentStmt);

            if ($studentId > 0) {
                $enrollStmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO enrollments (student_id, course_name, entry_date)
                     VALUES (?, ?, ?)"
                );
                mysqli_stmt_bind_param(
                    $enrollStmt,
                    'iss',
                    $studentId,
                    $courseName,
                    $entryDate
                );
                mysqli_stmt_execute($enrollStmt);
                mysqli_stmt_close($enrollStmt);
            }
        }

        header('Location: OnlineCourseEnrollmentSystem.php');
        exit;
    }
}

$result = mysqli_query(
    $conn,
    "SELECT
        e.enrollment_id AS id,
        s.first_name,
        s.last_name,
        s.email_address,
        e.course_name,
        e.entry_date
     FROM enrollments e
     INNER JOIN students s ON e.student_id = s.student_id
     ORDER BY e.entry_date DESC, e.enrollment_id DESC"
);
?>