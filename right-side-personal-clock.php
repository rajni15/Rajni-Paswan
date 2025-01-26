<!DOCTYPE html>
<html lang="en">
<head>
    <title>Full Moon Calendar <?php echo $year; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="http://192.168.1.6/calendarhub/logo/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,300;1,900&display=swap" rel="stylesheet">
    <link href="http://192.168.1.6/calendarhub/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php
//  include "../menu/main-menu.php"; 
 ?>
<div class="container-fluid mt-5 pb-0">
    <div class="row pb-1">
        <div class="col-sm-12" id="eight">
            <div class="px-2 px-md-2 text-lg-start border mt-2 bg-white">
                <div class="row gx-lg-5">
                    <div class="col-lg-12 mb-5 mb-lg-0">
                        <div class="row p-3">
                            <!-- Left menu -->
                            <div class="class col-md-3 border-end">
                                <?php
                                //  include "dashboard-left-side-menu.php";
                                 ?>
                            </div>
                            <!-- Right menu -->
                            <div class="class col-md-9">
                                <!-- Personal Clock -->
                                <div id="Personal Clock">
                                    <div class="pb-2">
                                        <div class="card">
                                            <div class="card-header">Personal Clock</div>
                                            <div class="card-body">
                                                <div class="mb-3 m-3">
                                                    <form class="row g-3" name="postData" method="post" action="">
                                                        <div class="col-lg-12">
                                                            <label for="country" class="form-label mb-0">Select Country</label>
                                                            <div class="input-group mb-3">
                                                                <select class="form-select" name="country" id="clockzone" onchange="zchange()">
                                                                    <!-- Timezones will be populated here -->
                                                                </select>
                                                            </div>
                                                        </div>
														<div class="row text-center" id="clock-container">
                                                            <!-- Clocks will be displayed here -->
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add JavaScript files -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>
<script type="text/javascript" src="https://www.distancelatlong.com/js/jquery.thooClock.js"></script>

<script type="text/javascript">


var timeZones = ["Africa/Johannesburg", "Africa/Lagos", "Africa/Cairo", "Asia/Shanghai", "Asia/Kolkata", "Asia/Tokyo", "Europe/London", "Europe/Berlin", "Europe/Paris", "America/Chicago", "America/Los_Angeles", "America/New_York", "America/Toronto", "America/Mexico_City", "America/Sao_Paulo", "America/Argentina/Buenos_Aires", "America/Santiago", "Australia/Sydney", "Pacific/Auckland", "Pacific/Fiji", "Antarctica/Palmer"];
 
$(document).ready(function () {
   
    timeZones.forEach(function (timeZone) {
        $("#clockzone").append($('<option>', { value: timeZone, text: timeZone }));
    });

    fatchIimezone();
});


function zchange() {
    let czone = $("#clockzone").val();
    let timezonesArray = [czone];
    let parts = czone.split("/");
    let country = parts[0];
    let city = parts[1].replace("_", " "); 

    let clockId = "clock-" + czone.replace("/", "-") + "-" + new Date().getTime();

  
    $("#clock-container").append(`
        <div class="col-lg-3 text-center mb-3" id="${clockId}">
            <div class="clock-wrapper">
                <div></div>
            </div>
            <h5 class="mt-2">
                <span class="ms-2">${country}, ${city}</span>
                <i class="fa fa-trash text-danger" style="cursor:pointer;" onclick="updateClock('${clockId}')"></i>
            </h5>
        </div>
    `);

    $("#" + clockId + " .clock-wrapper div").thooClock({
        size: 200,
        showNumerals: true,
        brandText: "calendarhub",
        dialColor: '#000',
        secondHandColor: '#E6A800',
        hourHandColor: '#000',
        minuteHandColor: '#000',
        dialBackgroundColor: 'transparent',
        timeZone: czone
    });


    $.ajax({
        url: 'clock-timezone-insert.php',
        type: 'POST',
        data: { timeZone: JSON.stringify(timezonesArray) },
        success: function(response) {
            // alert('Time Zone Stored: ' + response);
        },
        error: function() {
            alert('Error storing time zone.');
        }
    });
}


function fatchIimezone() {
    $.ajax({
        url: 'fetch-timezone.php',  
        type: 'GET',
        success: function(response) {
            let timezones = JSON.parse(response);

            if (timezones.length > 0) {
                $("#clock-container").empty();

                timezones.forEach(function(czone) {
                    let parts = czone.split("/");  
                    let country = parts[0];
                    let city = parts[1].replace("/", " ");  

                    let clockId = "clock-" + czone.replace("/", "-") + "-" + new Date().getTime();

                    $("#clock-container").append(`
                        <div class="col-lg-3 text-center mb-3" id="${clockId}">
                            <div class="clock-wrapper" data-timezone="${czone}">
                                <div></div>
                            </div>
                            <h5 class="mt-2">
                                <span class="ms-2">${country}, ${city}</span>
                                <i class="fa fa-trash text-danger" style="cursor:pointer;" id="updateClock" onclick="updateClock('${clockId}')"></i>
                            </h5>
                        </div>
                    `);

                    $("#" + clockId + " .clock-wrapper div").thooClock({
                        size: 200,
                        showNumerals: true,
                        brandText: "calendarhub",
                        dialColor: '#000',
                        secondHandColor: '#E6A800',
                        hourHandColor: '#000',
                        minuteHandColor: '#000',
                        dialBackgroundColor: 'transparent',
                        timeZone: czone
                    });
                });
            } else {
                alert("No timezones found.");
            }
        },
        error: function() {
            alert("Error fetching timezones.");
        }
    });
}



//     function updateClock(clockId) {

//     $("#" + clockId).remove();
//     // deleteTimezone=[];

//     // alert(Timezone);
//     // $.ajax({
//     //     url: 'timezone-delete.php',
//     //     type: 'POST',
//     //     data: { timeZone: Timezone }, 
//     //     success: function(response) {
//     //         // location.reload(true);
//     //         alert('Time Zone Deleted: ' + response);
//     //     },
//     //     error: function() {
//     //         alert('Error deleting time zone.');
//     //     }
//     // });
// }
function updateClock(clockId) {
    let parts = clockId.split("-");
    let countryCity = parts.slice(1, parts.length - 1).join("/").replace("-", "/"); 

    $("#" + clockId).remove();
 
    let currentTimezones = []; 
    $(".clock-wrapper").each(function() {
        let timezone = $(this).data('timezone');
        currentTimezones.push(timezone);
    });
    // alert(currentTimezones);
   
    let updatedTimezones = currentTimezones.filter(function(timezone) {
        return timezone !== countryCity;
    });
// alert(updatedTimezones);
    $.ajax({
        url: 'timezone-delete.php', 
        type: 'POST',
        data: { timezones: updatedTimezones }, 
        success: function(response) {
            // alert('Timezone Deleted: ' + response);
        },
        error: function() {
            alert('Error deleting timezone from the database.');
        }
    });
}


</script>

</body>
</html>
