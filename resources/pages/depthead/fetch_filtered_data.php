<?php 
header('Content-Type: application/json'); // Set JSON response type

global $pdo;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $name = $_POST["name"] ?? ''; // Optional filter for professor's name
    $subject = $_POST["subject"] ?? ''; // Optional filter for subject

    try {
        // Begin constructing the SQL query
        $sql = "SELECT * FROM tblattendance
        LEFT JOIN tblprofessor ON tblprofessor.registrationNumber = tblattendance.professorRegistrationNumber
        LEFT JOIN tblunit ON tblunit.unitCode = tblattendance.unit
        WHERE dateMarked BETWEEN :startDate AND :endDate";

        // Add additional filters if provided
        if (!empty($name)) {
            $sql .= " AND CONCAT(tblprofessor.firstName, ' ', tblprofessor.lastName) LIKE :name";
        }
        if (!empty($subject)) {
            $sql .= " AND tblunit.unitCode LIKE :subject";
        }

        // Prepare the statement
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);

        // Bind name and subject filters if they are provided
        if (!empty($name)) {
            $stmt->bindParam(':name', $name);
        }
        if (!empty($subject)) {
            $stmt->bindParam(':subject', $subject);
        }

        // Execute the query
        $stmt->execute();

        // Fetch results as associative array
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Echo results as JSON
        echo json_encode($results);
    } catch (PDOException $e) {
        // Handle any errors and send response with error message
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    // Handle invalid HTTP methods
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method."]);
}
?>
