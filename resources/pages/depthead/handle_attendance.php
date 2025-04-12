<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceData = json_decode(file_get_contents("php://input"), true);
    $response = [];

    if ($attendanceData) {
        try {
            foreach ($attendanceData as $data) {
                $professorID = $data['professorID'];
                $attendance_time = $data['attendance_time'] ?? null;
                $course = $data['course'];
                $unit = $data['unit'];
                $date = date("Y-m-d");

                // Check if professor has an entry today with no timeout (get the latest one)
                $checkSql = "SELECT attendanceID FROM tblattendance 
                             WHERE professorRegistrationNumber = :professorID 
                             AND dateMarked = :date
                             AND attendance_timeout IS NULL
                             ORDER BY attendanceID DESC
                             LIMIT 1";
                $checkStmt = $pdo->prepare($checkSql);
                $checkStmt->execute([
                    ':professorID' => $professorID,
                    ':date' => $date
                ]);

                $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

                if ($existing) {
                    // Update only the latest record without timeout
                    $updateSql = "UPDATE tblattendance 
                                  SET attendance_timeout = :attendance_timeout 
                                  WHERE attendanceID = :attendanceID";
                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute([
                        ':attendance_timeout' => $attendance_time,
                        ':attendanceID' => $existing['attendanceID']
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
            }

            $response['status'] = 'success';
            $response['message'] = "Attendance processed successfully.";
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Database error: " . $e->getMessage();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = "No attendance data received.";
    }

    echo json_encode($response);
}
