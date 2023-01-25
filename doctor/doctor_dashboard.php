<?php
    //Check if doctor is logged in properly
	session_start();
	if(!isset($_SESSION['doctoremail']) && !isset($_SESSION['doctorpassword']))
	{
		header('location: ../index.php');
	}

    $db = mysqli_connect("localhost","root","","medickare");

    $email = $_SESSION['doctoremail'];

    //SQL query to get doctor information from database
    $id = $db->prepare("SELECT * FROM doctor WHERE doctorEmail = ? limit 1");
    $id->bind_param('s', $email);
    $id->execute();
    $resultid = $id->get_result();
    $value = $resultid->fetch_object();

    //Declaraiton of variables for doctor information
    $doctorid = $value->doctorID;
    $lastname = $value->doctorLastName;

    //SQL query for appointment information
    $queryappointment = "SELECT appointmentID, patient.patientLastName AS patientLastName, patient.patientFirstName AS patientFirstName, schedule.doctorSchedDate AS appointmentDate, schedule.doctorSchedStartTime AS appointmentTime, appointmentReason, appointmentStatus
    FROM appointment
    LEFT JOIN patient 
    ON appointment.patientID = patient.patientID
    LEFT JOIN schedule
    ON appointment.doctorSchedID = schedule.doctorSchedID WHERE appointment.doctorID = '$doctorid'
    ORDER BY appointmentID;";

    $dataappointment = mysqli_query($db, $queryappointment);

    //SQL query for schedule information
    $queryschedule = "SELECT * FROM schedule WHERE doctorID = '$doctorid' ORDER BY doctorSchedID;";

    $dataschedule = mysqli_query($db, $queryschedule);
?>
<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="jquery-3.6.0.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <link rel="stylesheet" href="doctor_dashboard.css">
    </head>
    <body>
        <!-- Navbar !-->
        <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="../pictures/logo.png" alt="" width="100px" height="100px" class="d-inline-block align-text-top">
                    <label>MedicKare Health Care</label>
                </a>
                <!-- Display doctor last name !-->
                <label style="font-size: 25px; margin: auto; margin-right: 10px;">
                    <?php
                        if (isset($lastname)) {
                            echo "Doctor " .$lastname;
                        }
                    ?>
                </label>
                <!-- Drop down menu for navigation !-->
                <div class="dropdown-menu">
                    <button class="menu-btn"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>                        
                    </svg></button>
                    <div class="menu-content">
                    <a class="links-hidden" href="doctor_dashboard.php">Dashboard</a>
                        <a class="links-hidden" href="doctor_viewprofile.php">View Profile</a>
                        <a class="links-hidden" href="doctor_editprofile.php">Edit Profile</a>
                        <a class="links-hidden" href="doctor_logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
        
        <br><br><br><br>
        <div class="mytabs">
            <!-- Tab for appointment !-->
            <input type="radio" id="tabset" name="mytabs" checked="checked">
            <label for="tabset">Appointment</label>
            <div class="tab">
                <!-- Table to display information of doctors appointment !-->
                <div class="row" id="header">
                    <div class="col-1">Appointment ID</div>
                    <div class="col-3">Patient</div>
                    <div class="col-1">Date</div>
                    <div class="col-1">Time</div>
                    <div class="col-3">Reason</div>
                    <div class="col-1">Status</div>
                    <div class="col-2">Action</div>
                </div>
                <hr>
                    
                <form method="POST">
                    <?php 
                    while($row = $dataappointment->fetch_assoc()) {
                    echo '<div class="row" id="info">
                            <div class="col" id="ID">'.$row['appointmentID'].'</div>
                            <div class="col-3">'.$row['patientFirstName'].' '.$row['patientLastName'].'</div>
                            <div class="col-1">'.$row['appointmentDate'].'</div>
                            <div class="col-1">'.$row['appointmentTime'].'</div>
                            <div class="col-3">'.$row['appointmentReason'].'</div>
                            <div class="col-1">'.$row['appointmentStatus'].'</div>
                            <div class="col-2">
                                <input onclick="selectedButtonCancel(this)" id="cancel" type="button" value="Cancel" name = "Cancel" class="cancelbtn" style="background-color: red; border-color: transparent; color: white; border-radius: 5px; padding:5px; font-weight: bold;" data-bs-toggle="modal" data-bs-target="#cancelmodal">
                                <input onclick="selectedButtonComplete(this)" id="cancel" type="button" value="Complete" name = "Cancel" class="cancelbtn" style="background-color: green; border-color: transparent; color: white; border-radius: 5px; padding:5px; font-weight: bold;" data-bs-toggle="modal" data-bs-target="#completemodal">
                            </div>
                            </div><hr>';
                    }
                    ?>
                </form>
            </div>
            
            <!-- Tab for schedule !-->
            <input type="radio" id="tabview" name="mytabs">
            <label for="tabview">Schedule</label>
            <div class="tab">
                <!-- Button for adding schedule !-->
                <button style="border-color: transparent; background-color: transparent; margin-bottom: 10px; margin-left: 1355px" data-bs-toggle="modal" data-bs-target="#addmodal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16" style="color: green;">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                </button>
                <!-- Table to display schedule !-->
                <div class="row" id="header">
                    <div class="col">Schedule ID</div>
                    <div class="col">Schedule Date</div>
                    <div class="col">Start Time</div>
                    <div class="col">End Time</div>
                    <div class="col">Action</div>
                </div>
                <hr>
                    
                <form method="POST">
                    <?php 
                    while($row = $dataschedule->fetch_assoc()) {
                    echo '<div class="row" id="info">
                            <div class="col id="ID">'.$row['doctorSchedID'].'</div>
                            <div class="col">'.$row['doctorSchedDate'].'</div>
                            <div class="col">'.$row['doctorSchedStartTime'].'</div>
                            <div class="col">'.$row['doctorSchedEndTime'].'</div>
                            <div class="col"><input onclick="selectedButtonRemove(this)" id="cancel" type="button" value="Remove" name = "Cancel" class="cancelbtn" style="background-color: red; border-color: transparent; color: white; border-radius: 5px; padding:5px; font-weight: bold;" data-bs-toggle="modal" data-bs-target="#removemodal"></div>
                        </div><hr>';
                    }
                    ?>
                </form>
            </div>
        </div>
        
        <!-- Confimation modal for cancelling appointment !-->
        <div class="modal fade" id="cancelmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure to cancel this appointment?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: gray; border-color: transparent; color: white; border-radius: 5px; padding:10px;">Close</button>
                    <button onclick="cancel()" type="button" class="btn btn-primary" style="background-color: rgb(255, 70, 70); border-color: transparent; color: white; border-radius: 5px; padding:10px;">Yes</button>
                </div>
                </div>
            </div>
        </div>
        
        <!-- Confimation modal for removing schedule !-->
        <div class="modal fade" id="removemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure to remove this schedule?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: gray; border-color: transparent; color: white; border-radius: 5px; padding:10px;">Close</button>
                    <button onclick="remove()" type="button" class="btn btn-primary" style="background-color: rgb(255, 70, 70); border-color: transparent; color: white; border-radius: 5px; padding:10px;">Yes</button>
                </div>
                </div>
            </div>
        </div>
        
        <!-- Confimation modal for completing appointment !-->
        <div class="modal fade" id="completemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure to complete this appointment?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: gray; border-color: transparent; color: white; border-radius: 5px; padding:10px;">Close</button>
                    <button onclick="complete()" type="button" class="btn btn-primary" style="background-color: rgb(255, 70, 70); border-color: transparent; color: white; border-radius: 5px; padding:10px;">Yes</button>
                </div>
                </div>
            </div>
        </div>
        
        <!-- Confimation modal for adding schedule !-->
        <div class="modal fade" id="addmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Doctor Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        Schedule Date<br>
                        <input type="date" id="scheddate"><br><br>
                        Start Time<br>
                        <input type="time" id="schedstarttime"><br><br>
                        End Time<br>
                        <input type="time" id="schedendtime"><br><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: gray; border-color: transparent; color: white; border-radius: 5px; padding:10px;">Close</button>
                        <button id="add" name="add" type="button" class="btn btn-primary" style="background-color: rgb(255, 70, 70); border-color: transparent; color: white; border-radius: 5px; padding:10px;">Yes</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
        <script>
            //Script for form validation
            (() => {
                'use strict'
                const forms = document.querySelectorAll('.needs-validation')

                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                    }, false)
                })
            })()

            //Script for cancelling appointment
            var appointID;

            function selectedButtonCancel(e) {
                const selectedElement = e.parentElement.parentElement.querySelector('#ID');

                appointID = {'appointmentID': selectedElement.textContent};

                console.log(appointID);
            }

            function cancel() {
                $.ajax({
                    type:'POST',
                    url:'cancel.php',
                    data: appointID,
                    success: function(response){
                        alert(JSON.parse(response));
                        self.location = "doctor_dashboard.php";
                    }
                });
            }

            //Function for adding and removing schedule
            var schedID;

            function selectedButtonRemove(e) {
                const selectedElement = e.parentElement.parentElement.querySelector('#ID');

                schedID = {'doctorSchedID': selectedElement.textContent};

                console.log(schedID);
            }

            //Function for removing schedule
            function remove() {
                $.ajax({
                    type:'POST',
                    url:'remove.php',
                    data: schedID,
                    success: function(response){
                        alert("A schedule is removed");
                        self.location = "doctor_dashboard.php";
                    }
                });
            }

            //Function for adding schedule
            $(document).ready(function() {
                $("#add").click(function() {
                    var scheddate = $("#scheddate").val();
                    var schedstarttime = $("#schedstarttime").val();
                    var schedendtime = $("#schedendtime").val();
                    var doctorid = <?php Print($doctorid); ?>;
                    $.ajax({
                        type:'POST',
                        url:'add.php',
                        data: {
                            doctorid: doctorid,
                            scheddate: scheddate,
                            schedstarttime: schedstarttime,
                            schedendtime: schedendtime
                        },
                        cache: false,
                        success: function(response){
                            alert("A new schedule is added");
                            self.location = "doctor_dashboard.php";
                        }
                    });
                });
            });

            //Function for completing appointment
            var appointID;

            function selectedButtonComplete(e) {
                const selectedElement = e.parentElement.parentElement.querySelector('#ID');

                appointID = {'appointmentID': selectedElement.textContent};

                console.log(appointID);
            }

            function complete() {
                $.ajax({
                    type:'POST',
                    url:'complete.php',
                    data: appointID,
                    success: function(response){
                        alert(JSON.parse(response));
                        self.location = "doctor_dashboard.php";
                    }
                });
            }
        </script>
    </body>
</html>