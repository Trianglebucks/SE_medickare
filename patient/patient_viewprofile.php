<?php
    //Check if patient is logged in properly
	session_start();
	if(!isset($_SESSION['patientemail']) && !isset($_SESSION['patientpassword']))
	{
		header('location: ../index.php');
	}

    $db = mysqli_connect("localhost","root","","medickare");

    $email = $_SESSION['patientemail'];

    //SQL query to get patient information from database
    $stmt = $db->prepare("SELECT * FROM patient WHERE patientEmail = ? limit 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_object();

    //Declaraiton of variables for patient information
    $email = $value->patientEmail;
    $firstname = $value->patientFirstName;
    $lastname = $value->patientLastName;
    $address = $value->patientAddress;
    $birthday = $value->patientBirthday;
    $sex = $value->patientSex;
    $phonenumber = $value->patientPhoneNumber;
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <link rel="stylesheet" href="patient_profile.css">
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
        
        <br><br><br><br><br><br><br><br>
        
        <!-- To display information of patient !-->
        <div class="container row g-3">
            <label class="view text-center" style="font-size: 30px;">View Profile</label>
            <br><br><br><br>
            <div class="col-md-4">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control text-left" value="<?php
                    if (isset($firstname)) {
                        echo $firstname;
                    }
                ?>"required readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" value="<?php
                    if (isset($lastname)) {
                        echo $lastname;
                    }
                ?>"required readonly style="display: flex">
            </div>

            <div class="col-md-4">
                <label class="form-label">Birthday</label>
                <input type="text" class="form-control" value="<?php
                    if (isset($birthday)) {
                        echo $birthday;
                    }
                ?>"required readonly>
            </div>

            <div class="col-md-8">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" value="<?php
                    if (isset($address)) {
                        echo $address;
                    }
                ?>"required readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Sex</label>
                <input type="text" class="form-control" value="<?php
                    if (isset($sex)) {
                        echo $sex;
                    }
                ?>"required readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="text" class="form-control" value="<?php
                    if (isset($phonenumber)) {
                        echo $phonenumber;
                    }
                ?>"required readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" value="<?php
                    if (isset($email)) {
                        echo $email;
                    }
                ?>"required readonly>
            </div>
            <br><br><br><br>
        </div>
    </body>
</html>