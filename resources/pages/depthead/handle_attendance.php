<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceData = json_decode(file_get_contents("php://input"), true);
    $response = [];
    // echo "<pre>";
    // print_r($attendanceData);
    // echo "</pre>";
    if ($attendanceData && isset($attendanceData[0])) {
        $data = $attendanceData[0]; // Get only the first item

        // echo $data['attendance_time'];

        // if ($data['attendance_time'] != "No Time In") {
            try {
                date_default_timezone_set('Asia/Manila');
                $professorID = $data['professorID'];
                $attendance_time = date("h:i:s A");

                $course = $data['course'];
                $unit = $data['unit'];
                $date = date("Y-m-d");

                // Check if there is an existing record without timeout
                $checkSql = "SELECT attendanceID,unit FROM tblattendance 
                             WHERE professorRegistrationNumber = :professorID 
                             AND dateMarked = :date
                             AND unit = :unit
                             AND attendance_timeout IS NULL
                             ORDER BY attendanceID DESC
                             LIMIT 1";
                $checkStmt = $pdo->prepare($checkSql);
                $checkStmt->execute([
                    ':professorID' => $professorID,
                    ':date' => $date,
                    ':unit' => $unit
                ]);

                $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

                if ($existing) {

                  

                    $updateSql = "UPDATE tblattendance 
                    SET attendance_timeout = :attendance_timeout 
                    WHERE attendanceID = :attendanceID AND unit = :unit";  // Fixed the issue here
                        $updateStmt = $pdo->prepare($updateSql);
                        $updateStmt->execute([
                            ':attendance_timeout' => $attendance_time,
                            ':attendanceID' => $existing['attendanceID'],
                            ':unit' => $existing['unit'],
                        ]);

                } else {
                    // Insert new record
                    $insertSql = "INSERT INTO tblattendance 
                                 (professorRegistrationNumber, course, unit, attendance_timein, dateMarked)  
                                 VALUES (:professorID, :course, :unit, :attendance_timein, :date)";
                    $insertStmt = $pdo->prepare($insertSql);
                    $insertStmt->execute([
                        ':professorID' => $professorID,
                        ':course' => $course,
                        ':unit' => $unit,
                        ':attendance_timein' => $attendance_time,
                        ':date' => $date
                    ]);
                }

                $response['status'] = 'success';
                $response['message'] = "Attendance processed successfully.";
            } catch (PDOException $e) {
                $response['status'] = 'error';
                $response['message'] = "Database error: " . $e->getMessage();
            }
        // } else {
        //     $response['status'] = 'error';
        //     $response['message'] = "Invalid attendance time.";
        // }
    } else {
        $response['status'] = 'error';
        $response['message'] = "No valid attendance data received.";
    }

    echo json_encode($response);
}