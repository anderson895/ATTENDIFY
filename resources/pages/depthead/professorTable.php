

<div class="table">
    <table>
        <thead>
            <tr>
                <th>Registration No</th>
                <th>Name</th>
                <th>Course</th>
                <th>Subject</th>
                <th>Room</th>
                <th>Time-In</th>
                <th>Time-Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="professorTableContainer">
<?php
if (isset($_POST['courseID']) && isset($_POST['unitID']) && isset($_POST['roomID'])) {
    $courseID = $_POST['courseID'];
    $unitID = $_POST['unitID'];
    $roomID = $_POST['roomID'];

    $today = date("Y-m-d");

    $sql = "SELECT p.registrationNumber, p.firstName, p.lastName,
            a.attendance_timein, a.attendance_timeout,u.name as unitName 
        FROM tblprofessor p
        LEFT JOIN tblattendance a 
            ON p.registrationNumber = a.professorRegistrationNumber 
            AND DATE(a.dateMarked) = :today
        LEFT JOIN tblunit u 
            ON u.unitCode = a.unit 
        WHERE p.courseCode = :courseID";


    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':today' => $today,
        ':courseID' => $courseID
    ]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        foreach ($result as $row) {
            $timeIn = $row["attendance_timein"] ?? null;
            $timeOut = $row["attendance_timeout"] ?? null;
            $status = (!empty($timeIn) && !empty($timeOut)) 
    ? "Complete" 
    : (!empty($timeIn) || !empty($timeOut) ? "" : "");


            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["registrationNumber"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["firstName"] . " " . $row["lastName"]) . "</td>";
            echo "<td>" . htmlspecialchars($courseID) . "</td>";
            echo "<td>" . htmlspecialchars($row["unitName"]) . "</td>";
            echo "<td>" . htmlspecialchars($roomID) . "</td>";
            echo "<td data-timein='$timeIn'>" . htmlspecialchars($timeIn ?? 'No Time In') . "</td>";
            echo "<td data-timeOut='$timeOut'>" . htmlspecialchars($timeOut ?? 'No Time Out') . "</td>";
            echo "<td>" . $status . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No records found</td></tr>";
    }
}
?>
</tbody>

    </table>
</div>
