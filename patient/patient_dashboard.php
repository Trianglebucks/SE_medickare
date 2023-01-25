<?php
    //Check if patient is logged in properly
	session_start();
	if(!isset($_SESSION['patientemail']) && !isset($_SESSION['patientpassword']))
	{
		header('location: ../index.php');
	}

    $i = 0;

    $db = mysqli_connect("localhost","root","","medickare");

    $email = $_SESSION['patientemail'];

    //SQL query to get patient information from database
    $id = $db->prepare("SELECT * FROM patient WHERE patientEmail = ? limit 1");
    $id->bind_param('s', $email);
    $id->execute();
    $resultid = $id->get_result();
    $value = $resultid->fetch_object();

    //Declaraiton of variables for patient information
    $patientid = $value->patientID;
    $firstname = $value->patientFirstName;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
    //SQL query for appointment information
    $query = "SELECT appointmentID, doctor.doctorLastName AS doctorLastName, doctor.doctorFirstName AS doctorFirstName, schedule.doctorSchedDate AS appointmentDate, schedule.doctorSchedStartTime AS appointmentTime, appointmentReason, appointmentStatus
    FROM appointment
    INNER JOIN doctor
    ON appointment.doctorID = doctor.doctorID 
    LEFT JOIN schedule
    ON appointment.doctorSchedID = schedule.doctorSchedID WHERE appointment.patientID = '$patientid'
    ORDER BY appointmentID;";

    $data = mysqli_query($db, $query);

    //Function for setting appointment
    if(isset($_POST['submit'])) {
        $doctorID = $_POST['doctorID'];
        $doctorSchedID = $_POST['doctorSchedID'];
        $appointmentReason = $_POST['appointmentReason'];

        //SQL query to add appointment
        $sql = "INSERT INTO appointment (doctorID, patientID, doctorSchedID, appointmentReason, appointmentStatus) VALUES ('$doctorID', '$patientid', '$doctorSchedID', '$appointmentReason', 'Active')";

        //SQL query to change schedule availability to no
        $availbility = "UPDATE schedule SET doctorSchedAvailability = 'No' WHERE doctorSchedID = '$doctorSchedID'";

        $availbility_run = mysqli_query($db, $availbility);
        
        //Prompt that setting appointment is successful
        if ($db->query($sql) === TRUE) {
            echo '<script>alert("Appointment is set");</script>';
            echo '<script>self.location = "patient_dashboard.php";</script>';
            } else {
            echo "Error updating record: " . $db->error;
        }
    }
    //SQL query to get the list of specializations
    $readSpecialization = "SELECT DISTINCT doctorSpecialization FROM doctor";

    $specializationData = mysqli_query($db, $readSpecialization);
?>
<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="jquery-3.6.0.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <link rel="stylesheet" href="patient_dashboard.css">
    </head>
    <body>
        <!-- Navbar !-->
        <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="../pictures/logo.png" alt="" width="100px" height="100px" class="d-inline-block align-text-top">
                    <label>MedicKare Health Care</label>
                </a>
                <!-- Display patient last name !-->
                <label style="font-size: 25px; margin: auto; margin-right: 10px;">
                    <?php
                        if (isset($firstname)) {
                            echo "Welcome, ".$firstname;
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
                    <a class="links-hidden" href="patient_dashboard.php">Dashboard</a>
                        <a class="links-hidden" href="patient_viewprofile.php">View Profile</a>
                        <a class="links-hidden" href="patient_editprofile.php">Edit Profile</a>
                        <a class="links-hidden" href="patient_logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </nav>

        <br><br><br><br>
        <div class="mytabs">
            <!-- Tab for setting appointment !-->
            <input type="radio" id="tabset" name="mytabs" checked="checked">
            <label for="tabset">Set Appointment</label>
            <div class="tab">
                <br>
                <!-- Form for setting appointment !-->
                <form method="POST" class="needs-validation row g-4" novalidate>
                    <div class="col-md-1"></div>

                    <div class="col-md-5">
                        <label for="validationCustom" class="form-label">Doctor Specialization</label>
                        <select name="doctorSpecialization" class="form-control" id="selectedSpecialization" required>
                            <option value="" selected hidden></option>
                            <?php 
                                while($row = $specializationData->fetch_assoc()) {
                                    echo '<option>'.$row['doctorSpecialization']. '</option>';
                                }
                            ?>
                        </select>
                        <div class="invalid-feedback">
                            Please enter doctor specialization.
                        </div>
                    </div>

                    <div class="col-md-5">
                        <label for="validationCustom" class="form-label">Doctor Name</label>
                        <select name="doctorID" class="form-control" id="doctorName" required>
                            <option selected hidden value=""></option>
                        </select>
                        <div class="invalid-feedback">
                            Please enter doctor name.
                        </div>
                    </div>

                    <div class="col-md-1"></div>

                    <div class="col-md-1"></div>

                    <div class="col-md-5">
                        <label for="validationCustom" class="form-label">Appointment Date</label>
                        <select class="form-control" id="doctorSchedDate" required>
                            <option selected hidden value=""></option>
                            
                        </select>
                        <div class="invalid-feedback">
                            Please enter appointment date.
                        </div>
                    </div>

                    <div class="col-md-5">
                        <label for="validationCustom" class="form-label">Appointment Time</label>
                        <select name="doctorSchedID" class="form-control" id="doctorSchedTime" required>
                            <option selected hidden value=""></option>
                        </select>
                        <div class="invalid-feedback">
                            Please enter appointment time.
                        </div>
                    </div>

                    <div class="col-md-1"></div>

                    <div class="col-md-1"></div>

                    <div class="col-md-10">
                        <label for="validationCustom" class="form-label">Reason for Appointment</label>
                        <input type="text" class="form-control" id="validationCustom" name="appointmentReason" value=""required>
                        <div class="invalid-feedback">
                            Please enter the reason for your appointment.
                        </div>
                    </div>

                    <div class="col-md-1"></div>
                    <br><br><br><br><br>
                    <div class="container d-flex justify-content-center">
                        <input type="submit" value="SET APPOINTMENT" name="submit" class="btn mt-4" style="margin-right: 30px;">
                        <input type="reset" value="RESET" class="btn mt-4">
                    </div>
                </form>
                <br>
            </div>
            
            <!-- Table to display appointments !-->
            <input type="radio" id="tabview" name="mytabs">
            <label for="tabview">View Appointment</label>
            <div class="tab" id="forfilter">
                <div class="filter">
                    <input type="radio" value="All" checked name="filter"><a> All</a>
                    <input type="radio" value="Active" name="filter"><a> Active</a>
                    <input type="radio" value="Cancelled" name="filter"><a> Cancelled</a>
                    <input type="radio" value="Complete" name="filter"><a> Complete</a>
                </div>

                <div class="row">
                    <table class="table table-striped table-hover">
                        <thead>
                            <th>Appointment ID</th>
                            <th>Doctor</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Appointment Reason</th>
                            <th>Appointment Status</th>
                            <th>Action</th>
                        </thead>
                    
                        <tbody>
                            <?php 
                            while($row = $data->fetch_assoc()) {
                            echo '<tr  value="'.$row['appointmentStatus'].'">
                                    <td id="ID">'.$row['appointmentID'].'</td>
                                    <td> Dr. '.$row['doctorFirstName'].' '.$row['doctorLastName'].'</td>
                                    <td>'.$row['appointmentDate'].'</td>
                                    <td>'.$row['appointmentTime'].'</td>
                                    <td>'.$row['appointmentReason'].'</td>
                                    <td id="Status">'.$row['appointmentStatus'].'</td>
                                    <td><input onclick="selectedButton(this)" id="cancel" type="button" value="Cancel" name = "Cancel" class="cancelbtn" style="background-color: red; border-color: transparent; color: white; border-radius: 5px; padding:7px; font-weight: bold;" data-bs-toggle="modal" data-bs-target="#exampleModal"></td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
                
                
            </div>
        </div>
        
        <!-- Confimation modal for cancelling appointment !-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

            var appointID;

            function selectedButton(e) {
                const selectedElement = e.parentElement.parentElement.querySelector('#ID');

                appointID = {'appointmentID': selectedElement.textContent};

                console.log(appointID);
            }

            //Script for cancelling appointment
            function cancel() {
                $.ajax({
                    type:'POST',
                    url:'cancel.php',
                    data: appointID,
                    success: function(response){
                        alert(JSON.parse(response));
                        self.location = "patient_dashboard.php";
                    }
                });
            }

            //Script to display doctors name
            $('#selectedSpecialization').on('change', function(){
                console.log($('#selectedSpecialization').val());

                var doctorSpecialization = {'doctorSpecialization': $('#selectedSpecialization').val()};
                
				$.ajax({
					type: "POST",
                    dataType: "html",
					data: doctorSpecialization,
					url : "doctorName.php",
					success: function(data)
					{
                        console.log(data);
						document.getElementById('doctorName').innerHTML = data;
                        document.getElementById('doctorSchedDate').innerHTML = '<option selected hidden value=""></option>';
                        document.getElementById('doctorSchedTime').innerHTML = '<option selected hidden value=""></option>';
					}
				})
            });

            //Script to display doctors schedule date
            $('#doctorName').on('change', function(){
                console.log($('#doctorName').val());

                var selDoctorID = {'selDoctorID': $('#doctorName').val()};

                $.ajax({
					type: "POST",
                    dataType: "html",
					data: selDoctorID,
					url : "doctorSchedDate.php",
					success: function(data)
					{
                        console.log(data);
						document.getElementById('doctorSchedDate').innerHTML = data;
                        document.getElementById('doctorSchedTime').innerHTML = '<option selected hidden value=""></option>';
					}
				})
            });

            //Script to display doctors schedule time
            $('#doctorSchedDate').on('change', function(){
                var selSchedDate = {'selSchedDate': $('#doctorSchedDate').val(), 'selDoctorID': $('#doctorName').val()};

                $.ajax({
					type: "POST",
                    dataType: "html",
					data: selSchedDate,
					url : "doctorSchedTime.php",
					success: function(data)
					{
                        console.log(data);
						document.getElementById('doctorSchedTime').innerHTML = data;
					}
				})
            });

            
            $('input[name="filter"]').on('change', function (a, b) {
                var value = this.value;

                $('tbody > tr').hide();

                if (value == 'All') {
                    $('tbody > tr').show();
                }

                if (value == 'Active') {
                    $('tbody > tr').filter(function (a, b) {
                        var v = b.getAttribute("value");
                        return 'Active' == v;
                    }).show();
                }

                if (value == 'Cancelled') {
                    $('tbody > tr').filter(function (a, b) {
                        var v = b.getAttribute("value");
                        console.log(v);
                        return 'Cancelled' == v;
                    }).show();
                }

                if (value == 'Complete') {
                    $('tbody > tr').filter(function (a, b) {
                        var v = b.getAttribute("value");
                        return 'Complete' == v;
                    }).show();
                }
            });
        </script>
    </body>
</html>