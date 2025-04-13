<?php
$professorRows = fetchAllprofessorRecordsFromDatabase();
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

            <button id="printButton" style="margin: 10px; padding: 8px 16px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Print Report
            </button>

            <div class="table-container" id="printed-area">
               

                <div class="report-info">
                    <p><strong>Date:</strong> <?= date('F d, Y') ?></p>
                    <p><strong>TUP ID:</strong> ______________________</p>
                </div>

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
                                <tr>
                                    <td><?= htmlspecialchars(ucfirst($prof['firstName']) . " " . $prof['lastName']) ?></td>
                                    <td><?= htmlspecialchars($prof['unitname']) ?></td>
                                    <td><?= $prof['attendance_timein'] ? htmlspecialchars($prof['attendance_timein']) : '' ?></td>
                                    <td><?= $prof['attendance_timeout'] ? htmlspecialchars($prof['attendance_timeout']) : '' ?></td>
                                </tr>
                            <?php endforeach;
                            endif;

                            // Fill remaining rows to make it 10
                            $fillCount = 10 - count($professorRows ?? []);
                            for ($i = 0; $i < $fillCount; $i++): ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div> <!-- End of printed-area -->

        </div> <!-- End of main--content -->
    </section>

    <?php js_asset(['min/js/filesaver', 'min/js/xlsx', 'active_link']) ?>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function () {
        $('#printButton').on('click', function () {
            var printContents = document.getElementById('printed-area').innerHTML;
            var printWindow = window.open('', '', 'height=600,width=800');

            printWindow.document.write('<html><head><title>Print Report</title>');
                        printWindow.document.write(`
                <div class="report-header">
                    <div class="header-logos">
                        <!-- Left Logo -->
                        <div class="logo-container">
                            <img src="resources/images/logo/logo1.png" class="logo" alt="TUP Logo">
                        </div>

                        <!-- Center Title -->
                        <div class="report-title">
                            <h2>TECHNOLOGICAL UNIVERSITY OF THE PHILIPPINES</h2>
                            <p>San Marcelino St, Ayala Blvd, Ermita, Manila, 1000</p>
                            <h3>DAILY TIME REPORT</h3>
                        </div>

                        <!-- Right Logo -->
                        <div class="logo-container">
                            <img src="resources/images/logo/logo2.png" class="logo" alt="COS Logo">
                        </div>
                    </div>
                </div>
            `);


            // Inline CSS styles
            printWindow.document.write(`
                <style>
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        padding: 8px;
                        border: 1px solid #000;
                    }
                    img {
                        position: static !important;
                        top: auto !important;
                        left: auto !important;
                        width: auto !important;
                        height: auto !important;
                        object-fit: contain !important;
                    }
                    .logo {
                        width: 100px !important;
                    }


                    .report-header {
                        text-align: center;
                        margin-bottom: 20px;
                    }

                    .header-logos {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                    }

                    .logo {
                        width: 80px;
                        height: auto;
                    }

                </style>
            `);

            printWindow.document.write('</head><body>');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            printWindow.onload = function () {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            };
        });
    });
</script>


</body>
</html>
