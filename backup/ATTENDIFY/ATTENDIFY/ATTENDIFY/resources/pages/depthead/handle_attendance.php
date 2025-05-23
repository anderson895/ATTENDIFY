<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceData = json_decode(file_get_contents("php://input"), true);
    $response = [];

    if ($attendanceData) {
        try {
            $sql = "INSERT INTO tblattendance (professorRegistrationNumber, course, unit, attendanceStatus, dateMarked)  
                VALUES (:professorID, :course, :unit, :attendanceStatus, :date)";

            $stmt = $pdo->prepare($sql);

            foreach ($attendanceData as $data) {
                $professorID = $data['professorID'];
                $attendanceStatus = $data['attendanceStatus'];
                $course = $data['course'];
                $unit = $data['unit'];
                $date = date("Y-m-d");

                $stmt->execute([
                    ':professorID' => $professorID,
                    ':course' => $course,
                    ':unit' => $unit,
                    ':attendanceStatus' => $attendanceStatus,
                    ':date' => $date
                ]);
            }

            $response['status'] = 'success';
            $response['message'] = "Attendance recorded successfully for all entries.";
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Error inserting attendance data: " . $e->getMessage();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = "No attendance data received.";
    }

    echo json_encode($response);
}
