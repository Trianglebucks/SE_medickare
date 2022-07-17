<?php
	session_start();
	if(!isset($_SESSION['email']) && !isset($_SESSION['password']))
	{
		header('location: ../index.php');
	}

    $db = mysqli_connect("localhost","root","","medickare");

    $email = $_SESSION['email'];

    $id = $db->prepare("SELECT * FROM doctor WHERE doctorEmail = ? limit 1");
    $id->bind_param('s', $email,);
    $id->execute();
    $resultid = $id->get_result();
    $value = $resultid->fetch_object();

    $doctorid = $value->doctorID;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
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
                        if (isset($_SESSION['lastname'])) {
                            echo "Doctor " .$_SESSION["lastname"];
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
                        <th>Appointment ID</th>
                        <th>Patient</th>
                        <th>Appointment Date</th>
                        <th>Appointment Time</th>
                        <th>Appointment Reason</th>
                        <th>Appointment Status</th>
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
                            <td><input onclick="selectedButton(this)" type="submit" value="Cancel" name = "Cancel" class="cancelbtn" style="background-color: red; border-color: transparent; color: white; border-radius: 5px; padding:5px;"></td>
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
                            <td style="padding-top: 10px; padding-right: 10px"><button class="cancelbtn" style="background-color: yellow; border-color: transparent; color: black; border-radius: 5px; padding:5px;">Edit</button></td>
                            <td style="padding-top: 10px;"><button class="cancelbtn" style="background-color: red; border-color: transparent; color: white; border-radius: 5px; padding:5px;">Remove</button></td>
                        </tr>';
                    }
                    ?>
                    </form>
                </table>
            </div>
        </div>

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
        </script>
    </body>
</html>