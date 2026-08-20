<?php
declare(strict_types=1);

$databaseHost = '127.0.0.1';
$databaseName = 'pragatishil_classes';
$databaseUser = 'root';
$databasePassword = '';

function cleanInput(string $key): string
{
    return trim((string)($_POST[$key] ?? ''));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admission.html');
    exit;
}

$studentName = cleanInput('student_name');
$parentName = cleanInput('parent_name');
$dateOfBirth = cleanInput('date_of_birth');
$course = cleanInput('course');
$className = cleanInput('class_name');
$phone = cleanInput('phone');
$email = cleanInput('email');
$previousSchool = cleanInput('previous_school');
$address = cleanInput('address');
$message = cleanInput('message');

$allowedClasses = ['Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10'];
$allowedCourses = ['BSEB', 'CBSE'];
$errors = [];

if ($studentName === '' || $parentName === '' || $phone === '' || $address === '') {
    $errors[] = 'Please complete all required fields.';
}
if (!in_array($course, $allowedCourses, true)) {
    $errors[] = 'Please choose a valid course.';
}
if (!in_array($className, $allowedClasses, true) || ($course === 'BSEB' && in_array($className, ['Class 5', 'Class 6', 'Class 7'], true))) {
    $errors[] = 'Please choose a valid class.';
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if ($dateOfBirth !== '') {
    $date = DateTime::createFromFormat('Y-m-d', $dateOfBirth);
    if (!$date || $date->format('Y-m-d') !== $dateOfBirth) {
        $errors[] = 'Please enter a valid date of birth.';
    }
}

if ($errors) {
    http_response_code(422);
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Admission form error</title></head><body>';
    echo '<h1>We could not submit your application</h1><p>' . htmlspecialchars(implode(' ', $errors), ENT_QUOTES, 'UTF-8') . '</p><p><a href="admission.html">Return to the admission form</a></p></body></html>';
    exit;
}

try {
    $pdo = new PDO(
        "mysql:host={$databaseHost};dbname={$databaseName};charset=utf8mb4",
        $databaseUser,
        $databasePassword,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );

    $statement = $pdo->prepare(
        'INSERT INTO admissions (student_name, parent_name, date_of_birth, course, class_name, phone, email, previous_school, address, message)
         VALUES (:student_name, :parent_name, :date_of_birth, :course, :class_name, :phone, :email, :previous_school, :address, :message)'
    );
    $statement->execute([
        ':student_name' => $studentName,
        ':parent_name' => $parentName,
        ':date_of_birth' => $dateOfBirth !== '' ? $dateOfBirth : null,
        ':course' => $course,
        ':class_name' => $className,
        ':phone' => $phone,
        ':email' => $email !== '' ? $email : null,
        ':previous_school' => $previousSchool !== '' ? $previousSchool : null,
        ':address' => $address,
        ':message' => $message !== '' ? $message : null,
    ]);

    header('Location: admission.html?submitted=1');
    exit;
} catch (PDOException $exception) {
    http_response_code(500);
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Admission unavailable</title></head><body>';
    echo '<h1>Admission service unavailable</h1><p>Please call us at 9470042602.</p></body></html>';
}
