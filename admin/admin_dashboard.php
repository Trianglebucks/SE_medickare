<?php
    //Check if admin is logged in properly
	session_start();
	if(!isset($_SESSION['adminemail']) && !isset($_SESSION['aminpassword']))
	{
		header('location: ../index.php');
	}

    $db = mysqli_connect("localhost","root","","medickare");

    $email = $_SESSION['adminemail'];

    //SQL query to get admin information from database
    $stmt = $db->prepare("SELECT * FROM admin WHERE adminEmail = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_object();

    //Declaraiton of variables for admin information
    $firstname = $value->adminFirstName;
    $lastname = $value->adminLastName;
    $email = $value->adminEmail;
    $password = $value->adminPassword;

    //SQL query for patient information
    $querypatient = "SELECT * FROM patient";

    $datapatient = mysqli_query($db, $querypatient);

    //SQL query for doctor information
    $querydoctor = "SELECT * FROM doctor";

    $datadoctor = mysqli_query($db, $querydoctor);

    //SQL query for schedules of doctor
    $queryschedule = "SELECT doctorSchedID, doctor.doctorLastName AS doctorLastName, doctor.doctorFirstName AS doctorFirstName, doctorSchedDate, doctorSchedStartTime, doctorSchedEndTime
    FROM schedule
    INNER JOIN doctor
    ON schedule.doctorID = doctor.doctorID
    ORDER BY doctorSchedID;";

    $dataschedule = mysqli_query($db, $queryschedule);
?>
<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="jquery-3.6.0.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <link rel="stylesheet" href="admin_dashboard.css">
    </head>
    <body>
        <!-- Navbar !-->
        <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="../pictures/logo.png" alt="" width="100px" height="100px" class="d-inline-block align-text-top">
                    <label>MedicKare Health Care</label>
                </a>
                <!-- Display admin last name !-->
                <label style="font-size: 25px; margin: auto; margin-right: 10px;">
                    <?php
                        if (isset($lastname)) {
                            echo "Admin " .$lastname;
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
                    <a class="links-hidden" href="admin_dashboard.php">Dashboard</a>
                        <a class="links-hidden" href="admin_viewprofile.php">View Profile</a>
                        <a class="links-hidden" href="admin_editprofile.php">Edit Profile</a>
                        <a class="links-hidden" href="admin_logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
        
        
        <br><br><br><br>
        <div class="mytabs">
            <!-- Tab for manage doctor !-->
            <input type="radio" id="tabset" name="mytabs" checked="checked">
            <label for="tabset">Manage Doctor</label>
            <div class="tab">
                <!-- Button for adding doctor !-->
                <button style="border-color: transparent; background-color: transparent; margin-bottom: 10px; margin-left: 1355px" data-bs-toggle="modal" data-bs-target="#addmodal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16" style="color: green;">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                </button>
                <!-- Table to display information of doctors !-->
                <div class="row" id="header">
                    <div class="col-1">Doctor's ID</div>
                    <div class="col-2">Doctor's Email</div>
                    <div class="col-2">Doctor's Password</div>
                    <div class="col-2">Doctor's Name</div>
                    <div class="col-3">Doctor's Specialization</div>
                    <div class="col-2">Action</div>
                </div>
                <hr>
                    
                <form method="POST">
                    <?php 
                    while($row = $datadoctor->fetch_assoc()) {
                    echo '<div class="row" id="info">
                            <div class="col-1" id="ID">'.$row['doctorID'].'</div>
                            <div class="col-2" id="email">'.$row['doctorEmail'].'</div>
                            <div class="col-2" id="password">'.$row['doctorPassword'].'</div>
                            <div class="col-2"> Dr. '.$row['doctorFirstName'].' '.$row['doctorLastName'].'</div>
                            <div class="col-3" id="specialization">'.$row['doctorSpecialization'].'</div>
                            <div class="col-2">
                                <input onclick="selectedButtonEdit(this)" id="cancel" type="button" value="Edit" name = "Cancel" class="cancelbtn" style="background-color: yellow; border-color: transparent; color: black; border-radius: 5px; padding:5px; margin-right: 10px;" data-bs-toggle="modal" data-bs-target="#editmodal">
                                <input onclick="selectedButtonRemove(this)" id="cancel" type="button" value="Remove" name = "Cancel" class="cancelbtn" style="background-color: red; border-color: transparent; color: white; border-radius: 5px; padding:5px;" data-bs-toggle="modal" data-bs-target="#removemodal">
                            </div>
                        </div><hr>';
                    }
                    ?>
                </form>
            </div>
            
            <!-- Tab for manage patient !-->
            <input type="radio" id="tabpatient" name="mytabs">
            <label for="tabpatient">Manage Patient</label>
            <div class="tab">
                <!-- Table to display information of patients !-->
                <div class="row" id="header">
                    <div class="col-1">Patient's ID</div>
                    <div class="col-2">Patient's Email</div>
                    <div class="col-2">Patient's Password</div>
                    <div class="col-2">Patient's Name</div>
                    <div class="col-2">Patient's Address</div>
                    <div class="col-1">Patient's Phone No.</div>
                    <div class="col-2">Action</div>
                </div>
                <hr>
                    
                <form method="POST">
                    <?php 
                    while($row = $datapatient->fetch_assoc()) {
                    echo '<div class="row" id="info">
                            <div class="col-1" id="ID">'.$row['patientID'].'</div>
                            <div class="col-2" id="email">'.$row['patientEmail'].'</div>
                            <div class="col-2" id="password">'.$row['patientPassword'].'</div>
                            <div class="col-2">'.$row['patientFirstName'].' '.$row['patientLastName'].'</div>
                            <div class="col-2" id="address">'.$row['patientAddress'].'</div>
                            <div class="col-1" id="phonenumber">'.$row['patientPhoneNumber'].'</div>
                            <div class="col-2">
                                <input onclick="selectedButtonEditPatient(this)" id="cancel" type="button" value="Edit" name = "Cancel" class="cancelbtn" style="background-color: yellow; border-color: transparent; color: black; border-radius: 5px; padding:5px; margin-right: 10px; font-weight: bold;" data-bs-toggle="modal" data-bs-target="#editpatientmodal">
                                <input onclick="selectedButtonRemovePatient(this)" id="cancel" type="button" value="Remove" name = "Cancel" class="cancelbtn" style="background-color: red; border-color: transparent; color: white; border-radius: 5px; padding:5px; font-weight: bold;" data-bs-toggle="modal" data-bs-target="#removepatientmodal">
                            </div>
                        </div><hr>';
                    }
                    ?>
                </form>
            </div>
            
            <!-- Tab for manage doctor availability !-->
            <input type="radio" id="tabview" name="mytabs">
            <label for="tabview">Manage Doctor Availability</label>
            <div class="tab">
                <!-- Table to display schedules of doctors !-->
                <div class="row" id="header">
                    <div class="col">Schedule ID</div>
                    <div class="col">Doctor</div>
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
                            <div class="col" id="ID">'.$row['doctorSchedID'].'</div>
                            <div class="col"> Dr. '.$row['doctorFirstName'].' '.$row['doctorLastName'].'</div>
                            <div class="col">'.$row['doctorSchedDate'].'</div>
                            <div class="col">'.$row['doctorSchedStartTime'].'</div>
                            <div class="col">'.$row['doctorSchedEndTime'].'</div>
                            <div class="col"><input onclick="selectedButtonRemoveSched(this)" id="cancel" type="button" value="Remove" name = "Cancel" class="cancelbtn" style="background-color: red; border-color: transparent; color: white; border-radius: 5px; padding:5px; font-weight: bold;" data-bs-toggle="modal" data-bs-target="#removeschedmodal"></div>
                        </div><hr>';
                    }
                    ?>
                </form>
            </div>
        </div>

        <!-- Confimation modal for removing doctor !-->
        <div class="modal fade" id="removemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure to remove this doctor?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: gray; border-color: transparent; color: white; border-radius: 5px; padding:10px;">Close</button>
                    <button onclick="remove()" type="button" class="btn btn-primary" style="background-color: rgb(255, 70, 70); border-color: transparent; color: white; border-radius: 5px; padding:10px;">Yes</button>
                </div>
                </div>
            </div>
        </div>
        
        <!-- Confirmation model for removing schedule !-->
        <div class="modal fade" id="removeschedmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <button onclick="removesched()" type="button" class="btn btn-primary" style="background-color: rgb(255, 70, 70); border-color: transparent; color: white; border-radius: 5px; padding:10px;">Yes</button>
                </div>
                </div>
            </div>
        </div>
        
        <!-- Confirmation modal for removing patient !-->
        <div class="modal fade" id="removepatientmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure to remove this patient?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: gray; border-color: transparent; color: white; border-radius: 5px; padding:10px;">Close</button>
                    <button onclick="removepatient()" type="button" class="btn btn-primary" style="background-color: rgb(255, 70, 70); border-color: transparent; color: white; border-radius: 5px; padding:10px;">Yes</button>
                </div>
                </div>
            </div>
        </div>
        
        <!-- Modal for adding doctor !-->
        <div class="modal fade" id="addmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        Last Name<br>
                        <input type="text" id="inputlastname"><br><br>
                        First Name<br>
                        <input type="text" id="inputfirstname"><br><br>
                        Email<br>
                        <input type="email" id="inputemail"><br><br>
                        Password<br>
                        <input type="password" id="inputpassword"><br><br>
                        Specialization<br>
                        <select name="" id="inputspecialization">
                            <option selected hidden value=""></option>
                            <option value="Pediatrician">Pediatrician</option>
                            <option value="Neurologist">Neurologist</option>
                            <option value="Dermatologist">Dermatologist</option>
                            <option value="Dentist">Dentist</option>
                            <option value="Psychiatrist">Psychiatrist</option>
                        </select><br><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: gray; border-color: transparent; color: white; border-radius: 5px; padding:10px;">Close</button>
                        <button id="add" name="add" type="button" class="btn btn-primary" style="background-color: rgb(255, 70, 70); border-color: transparent; color: white; border-radius: 5px; padding:10px;">Yes</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <!-- Modal for editting information of doctor !-->
        <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        Email<br>
                        <input type="text" id="displayemail"><br><br>
                        Password<br>
                        <input type="password" id="displaypassword"><br><br>
                        Specialization<br>
                        <input type="text" id="displayspecialization"><br><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: gray; border-color: transparent; color: white; border-radius: 5px; padding:10px;">Close</button>
                        <button id="edit" name="edit" type="button" class="btn btn-primary" style="background-color: rgb(255, 70, 70); border-color: transparent; color: white; border-radius: 5px; padding:10px;">Yes</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
        
        <!-- Modal for editting information of patient !-->
        <div class="modal fade" id="editpatientmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        Email<br>
                        <input type="text" id="displaypatientemail"><br><br>
                        Password<br>
                        <input type="password" id="displaypatientpassword"><br><br>
                        Address<br>
                        <input type="text" id="displaypatientaddress"><br><br>
                        Phone Number<br>
                        <input type="text" id="displaypatientphonenumber"><br><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: gray; border-color: transparent; color: white; border-radius: 5px; padding:10px;">Close</button>
                        <button id="editpatient" name="edit" type="button" class="btn btn-primary" style="background-color: rgb(255, 70, 70); border-color: transparent; color: white; border-radius: 5px; padding:10px;">Yes</button>
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

            //Script for removing doctor in database
            var doctorID;

            function selectedButtonRemove(e) {
                const selectedElement = e.parentElement.parentElement.querySelector('#ID');

                doctorID = {'doctorID': selectedElement.textContent};

                console.log(doctorID);
            }

            function remove() {
                
                $.ajax({
                    type:'POST',
                    url:'remove.php',
                    data: doctorID,
                    success: function(response){
                        console.log(response);
                    }
                });

                console.log("asas");

                $('#removemodal').modal('hide');
                alert("A doctor is removed");
                self.location = "http://localhost/webdeb/admin/admin_dashboard.php";
            }

            //Script for removing schedule in database
            var schedID;

            function selectedButtonRemoveSched(e) {
                const selectedElement = e.parentElement.parentElement.querySelector('#ID');

                schedID = {'doctorSchedID': selectedElement.textContent};

                console.log(schedID);
            }

            function removesched() {
                
                $.ajax({
                    type:'POST',
                    url:'removesched.php',
                    data: schedID,
                    success: function(response){
                        console.log(response);
                    }
                });

                console.log("asas");

                $('#removemodal').modal('hide');
                self.location = "http://localhost/webdeb/admin/admin_dashboard.php";
                alert("A schedule is removed");
            }

            $(document).ready(function() {
                $("#add").click(function() {
                    var lastname = $("#inputlastname").val();
                    var firstname = $("#inputfirstname").val();
                    var email = $("#inputemail").val();
                    var password = $("#inputpassword").val();
                    var specialization = $("#inputspecialization").val();

                    $.ajax({
                        type:'POST',
                        url:'add.php',
                        data: {
                            lastname: lastname,
                            firstname: firstname,
                            email: email,
                            password: password,
                            specialization: specialization
                        },
                        cache: false,
                        success: function(response){
                            alert("A new doctor is added");
                            self.location = "admin_dashboard.php";
                        }
                    });
                });
            });

            //Script for editting information of doctor in database
            var doctorID;

            function selectedButtonEdit(e) {
                const selectedElementID = e.parentElement.parentElement.querySelector('#ID');
                const selectedElementEmail = e.parentElement.parentElement.querySelector('#email');
                const selectedElementPassword = e.parentElement.parentElement.querySelector('#password');
                const selectedElementSpecialization = e.parentElement.parentElement.querySelector('#specialization');

                doctorID = selectedElementID.textContent;

                var doctorEmail = {'doctorEmail': selectedElementEmail.textContent};
                var doctorPassword = {'doctorPassword': selectedElementPassword.textContent};
                var doctorSpecialization = {'doctorSpecialization': selectedElementSpecialization.textContent};

                document.getElementById("displayemail").value = selectedElementEmail.textContent;
                document.getElementById("displaypassword").value = selectedElementPassword.textContent;
                document.getElementById("displayspecialization").value = selectedElementSpecialization.textContent;
            }

            $(document).ready(function() {
                $("#edit").click(function() {
                    var email = $("#displayemail").val();
                    var password = $("#displaypassword").val();
                    var specialization = $("#displayspecialization").val();

                    $.ajax({
                        type:'POST',
                        url:'edit.php',
                        data: {
                            doctorID,
                            email: email,
                            password: password,
                            specialization: specialization
                        },
                        cache: false,
                        success: function(response){
                            console.log(response);
                        }
                    });

                    $('#aeditmodal').modal('hide');
                    alert("Doctor information is editted");
                    self.location = "http://localhost/webdeb/admin/admin_dashboard.php";
                });
            });

            //Script for editting information of patient in database
            var patientID;

            function selectedButtonEditPatient(e) {
                const selectedElementID = e.parentElement.parentElement.querySelector('#ID');
                const selectedElementEmail = e.parentElement.parentElement.querySelector('#email');
                const selectedElementPassword = e.parentElement.parentElement.querySelector('#password');
                const selectedElementAddress = e.parentElement.parentElement.querySelector('#address');
                const selectedElementPhoneNumber = e.parentElement.parentElement.querySelector('#phonenumber');

                patientID = selectedElementID.textContent;

                var patientEmail = {'patientEmail': selectedElementEmail.textContent};
                var patientPassword = {'patientPassword': selectedElementPassword.textContent};
                var patientAddress = {'patientAddress': selectedElementAddress.textContent};
                var patientPhoneNumber = {'patientPhoneNumber': selectedElementPhoneNumber.textContent};

                document.getElementById("displaypatientemail").value = selectedElementEmail.textContent;
                document.getElementById("displaypatientpassword").value = selectedElementPassword.textContent;
                document.getElementById("displaypatientaddress").value = selectedElementAddress.textContent;
                document.getElementById("displaypatientphonenumber").value = selectedElementPhoneNumber.textContent;
            }

            $(document).ready(function() {
                $("#editpatient").click(function() {
                    var email = $("#displaypatientemail").val();
                    var password = $("#displaypatientpassword").val();
                    var address = $("#displaypatientaddress").val();
                    var phonenumber = $("#displaypatientphonenumber").val();

                    $.ajax({
                        type:'POST',
                        url:'edit_patient.php',
                        data: {
                            patientID,
                            email: email,
                            password: password,
                            address: address,
                            phonenumber: phonenumber
                        },
                        cache: false,
                        success: function(response){
                            console.log(response);
                        }
                    });

                    $('#aeditmodal').modal('hide');
                    alert("Patient information is editted");
                    self.location = "http://localhost/webdeb/admin/admin_dashboard.php";
                });
            });

            //Script for removing patient in database
            var patientID;

            function selectedButtonRemovePatient(e) {
                const selectedElement = e.parentElement.parentElement.querySelector('#ID');

                patientID = {'patientID': selectedElement.textContent};

                console.log(patientID);
            }

            function removepatient() {
                $.ajax({
                    type:'POST',
                    url:'removepatient.php',
                    data: patientID,
                    success: function(response){
                        console.log(response);
                    }
                });

                $('#removepatientmodal').modal('hide');
                self.location = "http://localhost/webdeb/admin/admin_dashboard.php";
                alert("A patient is removed");
            }
        </script>
    </body>
</html>