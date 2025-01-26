<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Monthly Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .calendar {
            margin: 20px auto;
            border-collapse: collapse;
            width: 100%;
            max-width: 700px;
        }
        .calendar th {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }
        .calendar td {
            text-align: center;
            padding: 10px;
            height: 80px;
        }
        .calendar td.holiday {
            background-color: red;
            color: white;
            cursor: pointer;
        }
        .calendar th, .calendar td {
            border: 1px solid #ddd;
        }
        .month-header {
            background-color: #f8f9fa;
            color: #212529;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center my-4">
            <button id="prevMonth" class="btn btn-primary">&lt; Prev</button>
            <span id="monthYear" class="month-header"></span>
            <button id="nextMonth" class="btn btn-primary">Next &gt;</button>
        </div>
        <table class="calendar">
            <thead>
                <tr id="daysHeader"></tr>
            </thead>
            <tbody id="calendarBody"></tbody>
        </table>
    </div>

    <!-- Holiday Info Modal -->
    <div class="modal fade" id="holidayModal" tabindex="-1" aria-labelledby="holidayModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="holidayModalLabel">Holiday Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="holidayDetails"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const holidays = {
            "12-25": "Christmas",
            "1-1": "New Year",
            "7-4": "Independence Day"
        };

        let currentDate = new Date();
        const weekStart = "sunday"; // Change to "monday" or any other day if needed.

        const weekDays = weekStart === "sunday" 
            ? ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
            : ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

        function generateCalendar(date) {
            const month = date.getMonth();
            const year = date.getFullYear();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();

            $("#monthYear").text(`${firstDay.toLocaleString('default', { month: 'long' })} ${year}`);

            const daysHeader = weekDays.map(day => `<th>${day}</th>`).join("");
            $("#daysHeader").html(daysHeader);

            const calendarBody = [];
            let row = [];
            const emptyDaysStart = (firstDay.getDay() - weekDays.indexOf(weekDays[0]) + 7) % 7;

            for (let i = 0; i < emptyDaysStart; i++) {
                row.push(`<td></td>`);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const dateKey = `${month + 1}-${day}`;
                const isHoliday = holidays[dateKey];
                const cellClass = isHoliday ? "holiday" : "";

                row.push(`<td class="${cellClass}" data-day="${day}" data-month="${month + 1}" data-year="${year}" data-holiday="${isHoliday || ''}">${day}</td>`);

                if (row.length === 7) {
                    calendarBody.push(`<tr>${row.join("")}</tr>`);
                    row = [];
                }
            }

            while (row.length < 7) {
                row.push(`<td></td>`);
            }
            if (row.length > 0) {
                calendarBody.push(`<tr>${row.join("")}</tr>`);
            }

            $("#calendarBody").html(calendarBody.join(""));
        }

        function showHolidayDetails(day, month, year, holiday) {
            const holidayDate = new Date(year, month - 1, day);
            const today = new Date();
            const diffDays = Math.ceil((holidayDate - today) / (1000 * 60 * 60 * 24));
            const dayText = diffDays > 0 ? `${diffDays} day(s) to go.` : `${Math.abs(diffDays)} day(s) ago.`;

            const content = `<p><strong>Date:</strong> ${holidayDate.toDateString()}</p>
                             <p><strong>Holiday:</strong> ${holiday}</p>
                             <p>${dayText}</p>`;
            $("#holidayDetails").html(content);
            $("#holidayModal").modal("show");
        }

        $(document).ready(function () {
            generateCalendar(currentDate);

            $("#prevMonth").click(function () {
                currentDate.setMonth(currentDate.getMonth() - 1);
                generateCalendar(currentDate);
            });

            $("#nextMonth").click(function () {
                currentDate.setMonth(currentDate.getMonth() + 1);
                generateCalendar(currentDate);
            });

            $(document).on("click", ".calendar td.holiday", function () {
                const day = $(this).data("day");
                const month = $(this).data("month");
                const year = $(this).data("year");
                const holiday = $(this).data("holiday");

                showHolidayDetails(day, month, year, holiday);
            });
        });
    </script>
</body>
</html>
