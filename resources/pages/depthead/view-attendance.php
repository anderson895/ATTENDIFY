<?php
$professorRows = fetchAllprofessorRecordsFromDatabase();
// echo "<pre>";
// print_r($professorRows);
// echo "</pre>";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="resources/images/tablogo.png" type="image/svg+xml">
    <title>ATTENDIFY</title>

    <link rel="stylesheet" href="resources/assets/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
</head>

<body>
<?php include 'includes/topbar.php'; ?>
<section class="main">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main--content">

        <button id="exportBtn" style="margin: 10px; padding: 8px 16px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Export
        </button>

        <h3>Daily Time Report</h3>

        <div class="filter-container" style="margin: 10px 0;">
            <label for="filterName">Filter by Name:</label>
            <select id="filterName" style="margin-right: 10px;">
                <option value="">All</option>
                <?php 
                // Initialize an array to keep track of unique names
                $uniqueNames = [];

                // Loop through the professor rows and collect unique names
                foreach ($professorRows as $prof) {
                    // Combine first name and last name
                    $fullName = ucfirst($prof['firstName']) . " " . $prof['lastName'];

                    // Check if the name is already in the uniqueNames array
                    if (!in_array($fullName, $uniqueNames)) {
                        // Add the name to the array if it's not already present
                        $uniqueNames[] = $fullName;
                        ?>
                        <option value="<?= htmlspecialchars($fullName) ?>">
                            <?= htmlspecialchars($fullName) ?>
                        </option>
                        <?php
                    }
                }
                ?>
            </select>


            <label for="filterSubject">Filter by Subject:</label>
            <select id="filterSubject">
                <option value="">All</option>
                <?php 
                // Initialize an array to keep track of unique units
                $uniqueUnits = [];

                // Loop through the professor rows and collect unique units
                foreach ($professorRows as $prof) {
                    if (!in_array($prof['unitname'], $uniqueUnits)) {
                        // Add the unit to the array if it's not already present
                        $uniqueUnits[] = $prof['unitname'];
                        ?>
                        <option value="<?= htmlspecialchars($prof['unitname']) ?>">
                            <?= htmlspecialchars($prof['unitname']) ?>
                        </option>
                        <?php
                    }
                }
                ?>
            </select>

        </div>

        <div class="table-container" id="printed-area">
            <div id="attendanceTable">
                <table>
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>SUBJECT</th>
                            <th>TIME IN</th>
                            <th>TIME OUT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($professorRows)):
                            foreach ($professorRows as $prof): ?>
                            <tr class="attendance-row">
                                <td class="name"><?= htmlspecialchars(ucfirst($prof['firstName']) . " " . $prof['lastName']) ?></td>
                                <td class="subject"><?= htmlspecialchars($prof['unitname']) ?></td>
                                <td><?= $prof['attendance_timein'] ? htmlspecialchars($prof['attendance_timein']) : '' ?></td>
                                <td><?= $prof['attendance_timeout'] ? htmlspecialchars($prof['attendance_timeout']) : '' ?></td>
                            </tr>
                        <?php endforeach;
                        endif;

                        $fillCount = 10 - count($professorRows ?? []);
                        for ($i = 0; $i < $fillCount; $i++): ?>
                            <tr class="attendance-row">
                                <td>&nbsp;</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php js_asset(['min/js/filesaver', 'min/js/xlsx', 'active_link']) ?>

<div id="exportModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div id="modalContent" style="background: white; padding: 20px; border-radius: 8px; width: 300px; text-align: center; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <h3>Select Date Range</h3>
        <label for="startDate">Start Date:</label><br>
        <input type="date" id="startDate"><br><br>

        <label for="endDate">End Date:</label><br>
        <input type="date" id="endDate"><br><br>

        <button id="exportConfirm" style="padding: 8px 12px; background-color: green; color: white; border: none; border-radius: 5px;">Export</button>
        <button id="exportCancel" style="padding: 8px 12px; background-color: red; color: white; border: none; border-radius: 5px; margin-left: 10px;">Cancel</button>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#exportBtn').on('click', function () {
        $('#exportModal').fadeIn();
    });

    $('#exportCancel').on('click', function () {
        $('#exportModal').fadeOut();
    });

    $('#exportConfirm').on('click', function () {
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();
    const nameFilter = $('#filterName').val();
    const subjectFilter = $('#filterSubject').val();

    if (!startDate || !endDate) {
        alert('Please select both start and end dates.');
        return;
    }

    $.ajax({
        url: 'fetch_filtered_data', // Update with the correct endpoint
        method: 'POST',
        data: {
            startDate: startDate,
            endDate: endDate,
            name: nameFilter,
            subject: subjectFilter
        },
        dataType: 'json',
        success: function (response) {
            console.log(response);
            $('#exportModal').fadeOut();

            // Display the filtered data in your exported table
            let html = `
                <div style="margin-bottom: 10px; text-align: center;">
                    <strong>Report From:</strong> ${new Date(startDate).toLocaleDateString()} <strong>To:</strong> ${new Date(endDate).toLocaleDateString()}
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            if (response.length > 0) {
                response.forEach(function (row) {
                    html += `<tr>
                                <td>${row.firstName} ${row.lastName}</td>
                                <td>${row.unitName}</td>
                                <td>${row.attendance_timein}</td>
                                <td>${row.attendance_timeout}</td>
                            </tr>`;
                });
            } else {
                html += '<tr><td colspan="4">No records found.</td></tr>';
            }

            html += '</tbody></table>';

            $('#exportedTable').html(html).show();
            printArea();  // Calls the print function with filtered data
        },
        error: function (xhr, status, error) {
            alert('Failed to fetch filtered data.\n' + error);
        }
    });
});


    $('#filterName, #filterSubject').on('change', function () {
        const nameFilter = $('#filterName').val().toLowerCase();
        const subjectFilter = $('#filterSubject').val().toLowerCase();

        $('.attendance-row').each(function () {
            const name = $(this).find('.name').text().toLowerCase();
            const subject = $(this).find('.subject').text().toLowerCase();

            if ((name.includes(nameFilter) || !nameFilter) && (subject.includes(subjectFilter) || !subjectFilter)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});

function printArea() {
    const printContents = document.getElementById('printed-area').innerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');

    printWindow.document.write('<html><head><title>Print Report</title>');
    printWindow.document.write(`
        <style>
            table { width: 100%; border-collapse: collapse; }
            th, td { padding: 8px; border: 1px solid #000; }
            .report-header { text-align: center; margin-bottom: 20px; }
            .header-logos { display: flex; align-items: center; justify-content: space-between; }
            .logo { width: 80px; height: auto; }
        </style>
    `);
    printWindow.document.write('</head><body>');
    printWindow.document.write(`
        <div class="report-header">
            <div class="header-logos">
                <img src="resources/images/logo/logo1.png" class="logo" alt="TUP Logo">
                <div>
                    <h2>TECHNOLOGICAL UNIVERSITY OF THE PHILIPPINES</h2>
                    <p>San Marcelino St, Ayala Blvd, Ermita, Manila, 1000</p>
                    <h3>DAILY TIME REPORT</h3>
                </div>
                <img src="resources/images/logo/logo2.png" class="logo" alt="COS Logo">
            </div>
        </div>
    `);
    printWindow.document.write(printContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    printWindow.onload = function () {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };
}
</script>

</body>
</html>
