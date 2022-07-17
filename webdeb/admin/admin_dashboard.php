<?php
	session_start();
	if(!isset($_SESSION['adminemail']) && !isset($_SESSION['aminpassword']))
	{
		header('location: ../index.php');
	}

    $db = mysqli_connect("localhost","root","","medickare");

    $email = $_SESSION['adminemail'];

    $stmt = $db->prepare("SELECT * FROM admin WHERE adminEmail = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_object();

    $firstname = $value->adminFirstName;
    $lastname = $value->adminLastName;
    $email = $value->adminEmail;
    $password = $value->adminPassword;

    $querydoctor = "SELECT * FROM doctor";

    $datadoctor = mysqli_query($db, $querydoctor);

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
        <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="../pictures/logo.png" alt="" width="100px" height="100px" class="d-inline-block align-text-top">
                    <label>MedicKare Health Care</label>
                </a>
                <label style="font-size: 25px; margin: auto; margin-right: 10px;">
                    <?php
                        if (isset($lastname)) {
                            echo "Admin " .$lastname;
                        }
                    ?>
                </label>
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
            <input type="radio" id="tabset" name="mytabs" checked="checked">
            <label for="tabset">Manage Doctor</label>
            <div class="tab">
                <button style="border-color: transparent; background-color: transparent; margin-bottom: 10px; margin-left: 855px" data-bs-toggle="modal" data-bs-target="#addmodal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16" style="color: green;">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                </button>
                <table>
                    <tr >
                        <th style="padding-right: 40px;">Doctor's ID</th>
                        <th style="padding-right: 100px;">Doctor's Email</th>
                        <th style="padding-right: 40px;">Doctor's Password</th>
                        <th style="padding-right: 100px;">Doctor's Name</th>
                        <th style="padding-right: 20px;">Doctor's Specialization</th>
                        <th colspan="3" >Action</th>
                    </tr>
                    
                    <form method="POST">
                    <?php 
                    while($row = $datadoctor->fetch_assoc()) {
                    echo '<tr style="padding-bottom: 20px">
                            <td style="padding-top: 10px;" id="ID">'.$row['doctorID'].'</td>
                            <td style="padding-top: 10px;">'.$row['doctorEmail'].'</td>
                            <td style="padding-top: 10px;">'.$row['doctorPassword'].'</td>
                            <td style="padding-top: 10px;"> Dr. '.$row['doctorFirstName'].' '.$row['doctorLastName'].'</td>
                            <td style="padding-top: 10px;">'.$row['doctorSpecialization'].'</td>
                            <td><input onclick="selectedButtonEdit(this)" id="cancel" type="button" value="Edit" name = "Cancel" class="cancelbtn" style="background-color: yellow; border-color: transparent; color: black; border-radius: 5px; padding:5px; margin-right: 10px;" data-bs-toggle="modal" data-bs-target="#editmodal"></td>
                            <td><input onclick="selectedButtonRemove(this)" id="cancel" type="button" value="Remove" name = "Cancel" class="cancelbtn" style="background-color: red; border-color: transparent; color: white; border-radius: 5px; padding:5px;" data-bs-toggle="modal" data-bs-target="#removemodal"></td>
                        </tr>';
                    }
                    ?>
                    </form>
                </table>
            </div>

            <input type="radio" id="tabview" name="mytabs">
            <label for="tabview">Manage Doctor Availability</label>
            <div class="tab">
            <table>
                    <tr>
                        <th style="padding-right: 20px;">Schedule ID</th>
                        <th  style="padding-right: 150px;">Doctor</th>
                        <th  style="padding-right: 50px;">Schedule Date</th>
                        <th  style="padding-right: 80px;">Start Time</th>
                        <th  style="padding-right: 80px;">Time</th>
                        <th>Action</th>
                    </tr>
                    
                    <form method="POST">
                    <?php 
                    while($row = $dataschedule->fetch_assoc()) {
                    echo '<tr>
                            <td id="ID">'.$row['doctorSchedID'].'</td>
                            <td id="ID"> Dr. '.$row['doctorFirstName'].' '.$row['doctorLastName'].'</td>
                            <td>'.$row['doctorSchedDate'].'</td>
                            <td>'.$row['doctorSchedStartTime'].'</td>
                            <td>'.$row['doctorSchedEndTime'].'</td>
                            <td style="padding-top: 10px;"><button class="cancelbtn" style="background-color: red; border-color: transparent; color: white; border-radius: 5px; padding:5px;">Remove</button></td>
                        </tr>';
                    }
                    ?>
                    </form>
                </table>
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
                    Are you sure to remove this doctor?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: gray; border-color: transparent; color: white; border-radius: 5px; padding:10px;">Close</button>
                    <button onclick="remove()" type="button" class="btn btn-primary" style="background-color: rgb(255, 70, 70); border-color: transparent; color: white; border-radius: 5px; padding:10px;">Yes</button>
                </div>
                </div>
            </div>
        </div>

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
                        <input type="text" id="lastname"><br><br>
                        First Name<br>
                        <input type="text" id="firstname"><br><br>
                        Email<br>
                        <input type="email" id="email"><br><br>
                        Password<br>
                        <input type="password" id="password"><br><br>
                        Specialization<br>
                        <input type="text" id="specialization"><br><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: gray; border-color: transparent; color: white; border-radius: 5px; padding:10px;">Close</button>
                        <button id="add" name="add" type="button" class="btn btn-primary" style="background-color: rgb(255, 70, 70); border-color: transparent; color: white; border-radius: 5px; padding:10px;">Yes</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

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
                        <input type="email" id="email" value="<?php
                            if (isset($row['doctorEmail'])) {
                                echo $row['doctorEmail'];
                            }
                        ?>"><br><br>
                        Password<br>
                        <input type="password" id="password" value="<?php
                            if (isset($row['doctorPassword'])) {
                                echo $row['doctorPassword'];
                            }
                        ?>"><br><br>
                        Specialization<br>
                        <input type="text" id="specialization" value="<?php
                            if (isset($row['doctorSpecialization'])) {
                                echo $row['doctorSpecialization'];
                            }
                        ?>"><br><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: gray; border-color: transparent; color: white; border-radius: 5px; padding:10px;">Close</button>
                        <button id="edit" name="edit" type="button" class="btn btn-primary" style="background-color: rgb(255, 70, 70); border-color: transparent; color: white; border-radius: 5px; padding:10px;">Yes</button>
                    </div>
                </form>
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
                self.location = "http://localhost/webdeb/admin/admin_dashboard.php";
            }

            $(document).ready(function() {
                $("#add").click(function() {
                    var lastname = $("#lastname").val();
                    var firstname = $("#firstname").val();
                    var email = $("#email").val();
                    var password = $("#password").val();
                    var specialization = $("#specialization").val();

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
                            console.log(response);
                        }
                    });

                    $('#addmodal').modal('hide');
                    self.location = "http://localhost/webdeb/admin/admin_dashboard.php";
                });
            });

            var doctorID;

            function selectedButtonEdit(e) {
                const selectedElement = e.parentElement.parentElement.querySelector('#ID');

                doctorID = {'doctorID': selectedElement.textContent};

                console.log(doctorID);
            }

            function edit() {
                
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
                self.location = "http://localhost/webdeb/admin/admin_dashboard.php";
            }
        </script>
    </body>
</html>