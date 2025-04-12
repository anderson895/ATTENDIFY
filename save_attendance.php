<?php
require_once __DIR__ . "/database/database_connection.php";
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['registrationNumber']) || !isset($data['timeStamp'])) {
        throw new Exception('Missing required data');
    }

    $registrationNumber = $data['registrationNumber'];
    $timeStamp = $data['timeStamp'];
    
    // Parse the timestamp
    $dateTime = DateTime::createFromFormat('m/d/Y, H:i:s', $timeStamp);
    $date = $dateTime->format('Y-m-d');
    $time = $dateTime->format('H:i:s');

    // Insert or update attendance record
    $stmt = $pdo->prepare("
        INSERT INTO attendance (registration_number, date, time_in)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE time_in = ?
    ");

    $stmt->execute([
        $registrationNumber,
        $date,
        $time,
        $time
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Attendance recorded successfully'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 