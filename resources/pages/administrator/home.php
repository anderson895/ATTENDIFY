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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #e50914;
            --secondary-color: #b81d24;
            --accent-color: #f5222d;
            --dark-red: #8c0f13;
            --light-red: #ff4d58;
            
            --background-color: #ffffff;
            --card-bg: #ffffff;
            --light-bg: #f9f9f9;
            
            --text-primary: #333333;
            --text-secondary: #666666;
            --text-muted: #999999;
            
            --border-color: #eeeeee;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --shadow-md: 0 5px 15px rgba(0,0,0,0.07);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
            
            --success-color: #52c41a;
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        .main--content {
            background-color: var(--light-bg);
            padding: 1.5rem;
            color: var(--text-primary);
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            position: relative;
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to right, 
                rgba(255, 255, 255, 0) 0%, 
                rgba(255, 255, 255, 0.2) 50%, 
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }
        
        .dashboard-title {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            margin: 0;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
        }
        
        .dashboard-date {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            position: relative;
            z-index: 1;
        }
        
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .summary-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
            transition: var(--transition);
            display: flex;
            align-items: center;
            border-left: 3px solid var(--primary-color);
        }
        
        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        
        .summary-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 30%;
            height: 3px;
            background: linear-gradient(to right, transparent, var(--primary-color));
            transition: var(--transition);
        }
        
        .summary-card:hover::after {
            width: 100%;
        }
        
        .summary-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: rgba(229, 9, 20, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.6rem;
            color: var(--primary-color);
            transition: var(--transition);
        }
        
        .summary-card:hover .summary-icon {
            background: var(--primary-color);
            color: white;
            transform: rotate(10deg);
        }
        
        .summary-info {
            flex: 1;
        }
        
        .summary-card .card-title {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .summary-card .card-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            transition: var(--transition);
        }
        
        .summary-card:hover .card-value {
            color: var(--primary-color);
        }
        
        .summary-card .card-change {
            font-size: 0.75rem;
            color: var(--success-color);
            display: flex;
            align-items: center;
        }
        
        .summary-card .card-change i {
            font-size: 1rem;
            margin-right: 0.25rem;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-5px); }
            60% { transform: translateY(-2px); }
        }
        
        .chart-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            grid-template-rows: auto auto;
            gap: 1.5rem;
        }
        
        .chart-box {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            padding: 1.5rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        
        .chart-box:hover {
            box-shadow: var(--shadow-lg);
        }
        
        .chart-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, var(--primary-color), var(--light-red));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.5s ease;
        }
        
        .chart-box:hover::before {
            transform: scaleX(1);
        }
        
        /* Different sizes for different charts */
        .chart-box.large {
            grid-column: 1;
            grid-row: 1;
            height: 350px;
        }
        
        .chart-box.medium {
            grid-column: 2;
            grid-row: 1 / span 2;
            height: 720px;
        }
        
        .chart-box.small {
            grid-column: 1;
            grid-row: 2;
            height: 350px;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.75rem;
        }
        
        .chart-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .chart-action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--light-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            color: var(--text-secondary);
        }
        
        .chart-action-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }
        
        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            position: relative;
        }
        
        .chart-desc {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }
        
        .chart-wrapper {
            position: relative;
            height: calc(100% - 90px);
            width: 100%;
        }
        
        .chart-wrapper canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .dashboard-header { animation: scaleIn 0.5s ease-out forwards; }
        
        .summary-cards .summary-card:nth-child(1) { animation: fadeInUp 0.5s ease-out 0.1s both; }
        .summary-cards .summary-card:nth-child(2) { animation: fadeInUp 0.5s ease-out 0.2s both; }
        .summary-cards .summary-card:nth-child(3) { animation: fadeInUp 0.5s ease-out 0.3s both; }
        .summary-cards .summary-card:nth-child(4) { animation: fadeInUp 0.5s ease-out 0.4s both; }
        
        .chart-box.large { animation: slideInRight 0.6s ease-out 0.5s both; }
        .chart-box.medium { animation: fadeInUp 0.6s ease-out 0.6s both; }
        .chart-box.small { animation: slideInRight 0.6s ease-out 0.7s both; }
        
        /* Pulse animation for card value */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .card-value {
            display: inline-block;
            animation: pulse 2s infinite;
        }
        
        /* Styled dropdown */
        select.dropdown {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            outline: none;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            z-index: 1;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            padding-right: 30px;
        }
        
        select.dropdown:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .chart-container {
                grid-template-columns: 1fr;
            }
            
            .chart-box.large,
            .chart-box.medium,
            .chart-box.small {
                grid-column: 1;
                height: 350px;
            }
            
            .chart-box.medium {
                grid-row: 2;
            }
            
            .chart-box.small {
                grid-row: 3;
            }
        }

        /* Update the HTML tag to support data-theme */
        html[data-theme='dark'] {
            --primary-color: #e50914;
            --secondary-color: #b81d24;
            --accent-color: #f5222d;
            --dark-red: #8c0f13;
            --light-red: #ff4d58;
            
            --background-color: #121212;
            --card-bg: #1e1e1e;
            --light-bg: #252525;
            
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --text-muted: #888888;
            
            --border-color: #333333;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.2);
            --shadow-md: 0 5px 15px rgba(0,0,0,0.3);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.4);
        }

        /* Header actions container */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Theme toggle button */
        .theme-toggle {
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 6px;
            width: auto;
            height: auto;
            padding: 8px 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            color: white;
            font-size: 1.2rem;
            position: relative;
            z-index: 1;
        }

        .theme-toggle:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: none;
        }

        /* Update chart colors for dark mode */
        html[data-theme='dark'] .chart-box {
            background: var(--card-bg);
        }

        html[data-theme='dark'] .chart-title,
        html[data-theme='dark'] .chart-desc {
            color: var(--text-primary);
        }

        html[data-theme='dark'] .chart-action-btn {
            color: var(--text-secondary);
            background: var(--background-color);
        }

        html[data-theme='dark'] .chart-action-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Update summary cards for dark mode */
        html[data-theme='dark'] .summary-card {
            background: var(--card-bg);
            border-color: var(--border-color);
        }

        html[data-theme='dark'] .card-title {
            color: var(--text-secondary);
        }

        html[data-theme='dark'] .card-value {
            color: var(--text-primary);
        }

        /* Update theme icon styling */
        #theme-icon {
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
    <?php include 'includes/topbar.php'; ?>
    <section class="main">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main--content">
            <?php
            $sql = "SELECT 
                        (SELECT COUNT(*) FROM tbldepthead) as total_dept_heads,
                        (SELECT COUNT(*) FROM tblunit) as total_units,
                        (SELECT COUNT(*) FROM tblcourse) as total_courses,
                        (SELECT COUNT(*) FROM tblprofessor) as total_professors";
            $stmt = $pdo->query($sql);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            
            <div class="dashboard-header">
                <div>
                    <h1 class="dashboard-title">ADMINISTRATOR DASHBOARD</h1>
                    <p class="dashboard-date"><?php echo date('l, F j, Y'); ?></p>
                </div>
                <div class="header-actions">
                    <select name="date" id="date" class="dropdown">
                        <option value="today">Today</option>
                        <option value="lastweek">Last Week</option>
                        <option value="lastmonth">Last Month</option>
                        <option value="lastyear">Last Year</option>
                        <option value="alltime">All Time</option>
                    </select>
                    <button id="theme-toggle" class="theme-toggle" title="Toggle dark/light mode">
                        <i class="ri-sun-line" id="theme-icon"></i>
                    </button>
                </div>
            </div>
            
            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="ri-user-line"></i>
                    </div>
                    <div class="summary-info">
                        <div class="card-title">Department Heads</div>
                        <div class="card-value"><?php echo $data['total_dept_heads']; ?></div>
                        <div class="card-change">
                            <i class="ri-arrow-up-line"></i> Active
                        </div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="ri-book-2-line"></i>
                    </div>
                    <div class="summary-info">
                        <div class="card-title">Subjects</div>
                        <div class="card-value"><?php echo $data['total_units']; ?></div>
                        <div class="card-change">
                            <i class="ri-arrow-up-line"></i> Active
                        </div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="ri-graduation-cap-line"></i>
                    </div>
                    <div class="summary-info">
                        <div class="card-title">Courses</div>
                        <div class="card-value"><?php echo $data['total_courses']; ?></div>
                        <div class="card-change">
                            <i class="ri-arrow-up-line"></i> Active
                        </div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="ri-user-2-line"></i>
                    </div>
                    <div class="summary-info">
                        <div class="card-title">Professors</div>
                        <div class="card-value"><?php echo $data['total_professors']; ?></div>
                        <div class="card-change">
                            <i class="ri-arrow-up-line"></i> Active
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Container -->
            <div class="chart-container">
                <!-- Bar Chart - Left Top -->
                <div class="chart-box large">
                    <div class="chart-header">
                        <h3 class="chart-title">Distribution by Category</h3>
                        <div class="chart-actions">
                            <div class="chart-action-btn"><i class="ri-download-line"></i></div>
                            <div class="chart-action-btn"><i class="ri-more-2-fill"></i></div>
                        </div>
                    </div>
                    <div class="chart-desc">Histogram showing the distribution of department heads, units, courses, and professors</div>
                    <div class="chart-wrapper">
                        <canvas id="histogramChart"></canvas>
                    </div>
                </div>
                
                <!-- Pie Chart - Right -->
                <div class="chart-box medium">
                    <div class="chart-header">
                        <h3 class="chart-title">Proportional Analysis</h3>
                        <div class="chart-actions">
                            <div class="chart-action-btn"><i class="ri-download-line"></i></div>
                            <div class="chart-action-btn"><i class="ri-more-2-fill"></i></div>
                        </div>
                    </div>
                    <div class="chart-desc">Pie chart showing the relative proportion of each category</div>
                    <div class="chart-wrapper">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
                
                <!-- Line Chart - Left Bottom -->
                <div class="chart-box small">
                    <div class="chart-header">
                        <h3 class="chart-title">Trend Analysis</h3>
                        <div class="chart-actions">
                            <div class="chart-action-btn"><i class="ri-download-line"></i></div>
                            <div class="chart-action-btn"><i class="ri-more-2-fill"></i></div>
                        </div>
                    </div>
                    <div class="chart-desc">Line chart showing the distribution across categories</div>
                    <div class="chart-wrapper">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>

            <script>
            // Initialize all charts with data and configurations
            var chartLabels = ['Department Heads', 'Units', 'Courses', 'Professors'];
            var chartData = [
                <?php echo $data['total_dept_heads']; ?>, 
                <?php echo $data['total_units']; ?>, 
                <?php echo $data['total_courses']; ?>, 
                <?php echo $data['total_professors']; ?>
            ];
            
            var backgroundColors = [
                'rgba(75, 192, 192, 0.7)',   // Teal
                'rgba(54, 162, 235, 0.7)',   // Blue
                'rgba(255, 159, 64, 0.7)',   // Orange
                'rgba(153, 102, 255, 0.7)'   // Purple
            ];
            
            var borderColors = [
                'rgba(75, 192, 192, 1)',     // Teal
                'rgba(54, 162, 235, 1)',     // Blue
                'rgba(255, 159, 64, 1)',     // Orange
                'rgba(153, 102, 255, 1)'     // Purple
            ];

            // Function to create a gradient for line chart
            function createGradient(ctx) {
                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(54, 162, 235, 0.8)');
                gradient.addColorStop(0.5, 'rgba(75, 192, 192, 0.5)');
                gradient.addColorStop(1, 'rgba(75, 192, 192, 0.1)');
                return gradient;
            }

            // Bar Chart with animation
            var ctxHistogram = document.getElementById('histogramChart').getContext('2d');
            new Chart(ctxHistogram, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Total Count',
                        data: chartData,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 2,
                        borderRadius: 6,
                        maxBarThickness: 50
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                color: '#666666'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#666666'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    animation: {
                        delay: function(context) {
                            return context.dataIndex * 300;
                        },
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });

            // Line Chart with animation
            var ctxLine = document.getElementById('lineChart').getContext('2d');
            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Total Count',
                        data: chartData,
                        backgroundColor: function(context) {
                            const chart = context.chart;
                            const {ctx, chartArea} = chart;
                            if (!chartArea) {
                              return null;
                            }
                            return createGradient(ctx);
                        },
                        borderColor: 'rgba(54, 162, 235, 1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: 'rgba(54, 162, 235, 1)',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                color: '#666666'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#666666'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#666666'
                            }
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            });

            // Pie Chart with animation and rainbow colors
            var ctxPie = document.getElementById('pieChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        data: chartData,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',    // Pink
                            'rgba(54, 162, 235, 0.8)',    // Blue
                            'rgba(255, 206, 86, 0.8)',    // Yellow
                            'rgba(75, 192, 192, 0.8)'     // Teal
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                color: '#666666'
                            }
                        }
                    },
                    cutout: '60%',
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                const themeToggle = document.getElementById('theme-toggle');
                const themeIcon = document.getElementById('theme-icon');
                const html = document.documentElement;
                
                // Check for saved theme preference or use default
                const savedTheme = localStorage.getItem('theme') || 'light';
                html.setAttribute('data-theme', savedTheme);
                updateThemeIcon(savedTheme);
                
                // Toggle theme when button is clicked
                themeToggle.addEventListener('click', () => {
                    const currentTheme = html.getAttribute('data-theme');
                    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                    
                    html.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateThemeIcon(newTheme);
                    
                    // Update chart colors based on theme
                    updateChartColors(newTheme);
                });
                
                function updateThemeIcon(theme) {
                    if (theme === 'dark') {
                        themeIcon.classList.remove('ri-sun-line');
                        themeIcon.classList.add('ri-moon-line');
                    } else {
                        themeIcon.classList.remove('ri-moon-line');
                        themeIcon.classList.add('ri-sun-line');
                    }
                }
                
                function updateChartColors(theme) {
                    const isDark = theme === 'dark';
                    const textColor = isDark ? '#b0b0b0' : '#666666';
                    const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
                    
                    // Update colors for all charts
                    [histogramChart, lineChart, pieChart].forEach(chart => {
                        if (chart) {
                            // Update scale colors
                            if (chart.options.scales) {
                                Object.values(chart.options.scales).forEach(scale => {
                                    if (scale.ticks) scale.ticks.color = textColor;
                                    if (scale.grid) scale.grid.color = gridColor;
                                });
                            }
                            
                            // Update legend colors
                            if (chart.options.plugins && chart.options.plugins.legend) {
                                chart.options.plugins.legend.labels.color = textColor;
                            }
                            
                            chart.update();
                        }
                    });
                }
            });
            </script>
        </div>
    </section>

    <?php js_asset(["active_link", "delete_request"]) ?>
</body>

</html>