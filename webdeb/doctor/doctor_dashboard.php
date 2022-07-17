<?php
	session_start();
	if(!isset($_SESSION['doctoremail']) && !isset($_SESSION['doctorpassword']))
	{
		header('location: ../index.php');
	}

    $db = mysqli_connect("localhost","root","","medickare");

    $email = $_SESSION['doctoremail'];

    $id = $db->prepare("SELECT * FROM doctor WHERE doctorEmail = ? limit 1");
    $id->bind_param('s', $email,);
    $id->execute();
    $resultid = $id->get_result();
    $value = $resultid->fetch_object();

    $doctorid = $value->doctorID;
    $lastname = $value->doctorLastName;

    $queryappointment = "SELECT appointmentID, patient.patientLastName AS patientLastName, patient.patientFirstName AS patientFirstName, appointmentDate, appointmentTime, appointmentReason, appointmentStatus
    FROM appointment
    INNER JOIN patient
    ON appointment.patientID = patient.patientID AND appointment.doctorID = '$doctorid'
    ORDER BY appointmentID;";

    $dataappointment = mysqli_query($db, $queryappointment);

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
        <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="../pictures/logo.png" alt="" width="100px" height="100px" class="d-inline-block align-text-top">
                    <label>MedicKare Health Care</label>
                </a>
                <label style="font-size: 25px; margin: auto; margin-right: 10px;">
                    <?php
                        if (isset($lastname)) {
                            echo "Doctor " .$lastname;
                        }
                    ?>
                </label>
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
            <input type="radio" id="tabset" name="mytabs" checked="checked">
            <label for="tabset">Appointment</label>
            <div class="tab">
                <table>
                    <tr>
                        <th style="padding-right: 40px;">Appointment ID</th>
                        <th style="padding-right: 120px;">Patient</th>
                        <th style="padding-right: 80px;">Date</th>
                        <th style="padding-right: 50px;">Time</th>
                        <th style="padding-right: 80px;">Reason</th>
                        <th style="padding-right: 60px;">Status</th>
                        <th>Action</th>
                    </tr>
                    
                    <form method="POST">
                    <?php 
                    while($row = $dataappointment->fetch_assoc()) {
                    echo '<tr>
                            <td id="ID">'.$row['appointmentID'].'</td>
                            <td>'.$row['patientFirstName'].' '.$row['patientLastName'].'</td>
                            <td>'.$row['appointmentDate'].'</td>
                            <td>'.$row['appointmentTime'].'</td>
                            <td>'.$row['appointmentReason'].'</td>
                            <td>'.$row['appointmentStatus'].'</td>
                            <td><input onclick="selectedButtonCancel(this)" id="cancel" type="button" value="Cancel" name = "Cancel" class="cancelbtn" style="background-color: red; border-color: transparent; color: white; border-radius: 5px; padding:5px;" data-bs-toggle="modal" data-bs-target="#cancelmodal"></td>
                            <td><input onclick="selectedButton(this)" id="cancel" type="button" value="Complete" name = "Cancel" class="cancelbtn" style="background-color: green; border-color: transparent; color: white; border-radius: 5px; padding:5px;" data-bs-toggle="modal" data-bs-target="#exampleModal"></td>
                        </tr>';
                    }
                    ?>
                    </form>
                </table>
            </div>

            <input type="radio" id="tabview" name="mytabs">
            <label for="tabview">Schedule</label>
            <div class="tab">
                <table>
                    <tr>
                        <th style="padding-right: 80px;">Schedule ID</th>
                        <th style="padding-right: 120px;">Schedule Date</th>
                        <th style="padding-right: 100px;">Start Time</th>
                        <th style="padding-right: 100px;">Time</th>
                        <th>Action</th>
                    </tr>
                    
                    <form method="POST">
                    <?php 
                    while($row = $dataschedule->fetch_assoc()) {
                    echo '<tr>
                            <td id="ID">'.$row['doctorSchedID'].'</td>
                            <td>'.$row['doctorSchedDate'].'</td>
                            <td>'.$row['doctorSchedStartTime'].'</td>
                            <td>'.$row['doctorSchedEndTime'].'</td>
                            <td padding-right: 10px"><button class="cancelbtn" style="background-color: yellow; border-color: transparent; color: black; border-radius: 5px; padding:5px;">Edit</button></td>
                            <td><input onclick="selectedButtonRemove(this)" id="cancel" type="button" value="Remove" name = "Cancel" class="cancelbtn" style="background-color: red; border-color: transparent; color: white; border-radius: 5px; padding:5px;" data-bs-toggle="modal" data-bs-target="#removemodal"></td>
                        </tr>';
                    }
                    ?>
                    </form>
                </table>
            </div>
        </div>

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

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
        <script>
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
                        console.log(response);
                    }
                });

                $('#Mcancelodal').modal('hide');
                self.location = "http://localhost/webdeb/doctor/doctor_dashboard.php";
            }

            var schedID;

            function selectedButtonRemove(e) {
                const selectedElement = e.parentElement.parentElement.querySelector('#ID');

                schedID = {'doctorSchedID': selectedElement.textContent};

                console.log(schedID);
            }

            function remove() {
                $.ajax({
                    type:'POST',
                    url:'remove.php',
                    data: schedID,
                    success: function(response){
                        console.log(response);
                    }
                });

                $('#removemodal').modal('hide');
                self.location = "http://localhost/webdeb/doctor/doctor_dashboard.php";
            }
        </script>
    </body>
</html>