<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduTrack Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet" />
    <style>
        /* General Styles */
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #C4C3E3;
            height: 100vh;
            overflow: hidden;
        }

        .dashboard-card {
            background-color: #F8F8F8;
            border-radius: 30px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: row;
            gap: 20px;
            width: 92vw;
            height: 42vw;
            max-width: 3000px;
            max-height: 100vh;
            padding: 20px;
            margin-top: 30px;
            overflow: hidden;
        }

        .sidebar {
            width: 150px;
            background-color: #504E76;
            padding: 50px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 15px;
        }

        .menu ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .menu ul li {
            margin: 15px 0;
            width: 100%;
            display: flex;
        }

        .menu ul li a {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 80px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 15px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .menu ul li a.active {
            background-color: #F8F8F8;
            color: #62c1b6;
        }

        .menu ul li a:hover {
            background-color: #FCDD9D;
            transform: scale(1.1);
        }

        .logout-button {
            margin-top: auto;
            width: 50px;
            height: 50px;
            background-color: #504E76;
            border: none;
            color: white;
            border-radius: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .logout-button:hover {
            background-color: #F1642E;
            transform: scale(1.1);
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            gap: 30px;
            background-color: #F8F8F8;
            border-radius: 15px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.05);
            overflow-y: auto;
            margin-left: -40px;
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-text h1 {
            margin: 0;
            font-size: 40px;
            font-weight: bold;
        }

        .calendar-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex-grow: 1;
        }

        /* FullCalendar custom styles */
        .fc-daygrid-day {
            background-color: white;
        }

        .fc-daygrid-day.fc-day-today {
            background-color: #FDF8E2;
        }

        .fc-daygrid-day.fc-day-future {
            background-color: #FFF;
        }

        .fc-popover {
            width: 250px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

    </style>
</head>

<body>
    <div class="dashboard-card">
        <div class="sidebar">
            <div class="menu">
                <ul>
                    <li><a href="#" class="active"><i class="fas fa-home"></i></a></li>
                    <li><a href="#"><i class="fas fa-calendar"></i></a></li>
                    <li><a href="#"><i class="fas fa-chart-line"></i></a></li>
                    <li><a href="#"><i class="fas fa-user"></i></a></li>
                </ul>
            </div>
            <button class="logout-button"><i class="fas fa-sign-out-alt"></i></button>
        </div>

        <div class="main-content">
            <div class="main-header">
                <div class="header-text">
                    <h1>Welcome to EduTrack</h1>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search...">
                </div>
            </div>

            <div class="calendar-container">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    {
                        title: 'Event 1',
                        start: '2024-11-05'
                    },
                    {
                        title: 'Event 2',
                        start: '2024-11-07'
                    },
                    {
                        title: 'Event 3',
                        start: '2024-11-10'
                    }
                ]
            });
            calendar.render();
        });
    </script>
</body>

</html>
