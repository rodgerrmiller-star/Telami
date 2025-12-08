<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST['fullName'] ?? '');
    $dob = $_POST['dob'] ?? '';
    $course = $_POST['courseCategory'] ?? '';
    $email = $_POST['email'] ?? '';
    $idNumber = $_POST['idNumber'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // basic server-side validation
    if (!$fullName || !$dob || !$course || !$email || !$idNumber || !$phone) {
        echo "<h3>Missing required field. Please go back and complete the form.</h3>";
        exit;
    }

    // Append to CSV
    $csvFile = __DIR__ . '/applications.csv';
    $exists = file_exists($csvFile);
    $fp = fopen($csvFile, 'a');
    if ($fp) {
        if (!$exists) fputcsv($fp, ['Timestamp','Full Name','DOB','Course','Email','ID Number','Phone']);
        fputcsv($fp, [date('Y-m-d H:i:s'), $fullName, $dob, $course, $email, $idNumber, $phone]);
        fclose($fp);
    }

    // Email notification (best-effort; hosting must allow mail)
    $to = "rodgerr.miller@gmail.com";
    $subject = "New Application Submission - " . $course;
    $message = "New application submitted:\n\nFull Name: $fullName\nDOB: $dob\nCourse: $course\nEmail: $email\nID: $idNumber\nPhone: $phone\n";
    $headers = "From: no-reply@yourcollege.ac.ke\r\nReply-To: $email";

    @mail($to, $subject, $message, $headers);

    // Redirect to success page
    header('Location: success.html');
    exit;
} else {
    echo "<h3>Invalid request method.</h3>";
}
?>