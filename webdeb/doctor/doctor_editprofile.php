<?php
	session_start();
	if(!isset($_SESSION['doctoremail']) && !isset($_SESSION['doctorpassword']))
	{
		header('location: ../index.php');
	}

    $db = mysqli_connect("localhost","root","","medickare");

    $email = $_SESSION['doctoremail'];

    $stmt = $db->prepare("SELECT * FROM doctor WHERE doctorEmail = ? limit 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_object();

    $firstname = $value->doctorFirstName;
    $lastname = $value->doctorLastName;
    $email = $value->doctorEmail;
    $password= $value->doctorPassword;
    $specialization = $value->doctorSpecialization;

    if(isset($_POST['edit'])) {
        $editspecialization = $_POST['editspecialization'];
        $editpassword = $_POST['editpassword'];
        $confirmedit = $_POST['confirmedit'];

        if ($confirmedit === $password) {
            $query = "UPDATE doctor SET doctorSpecialization = '$editspecialization',  doctorPassword = '$editpassword' WHERE doctorEmail = '$email'";
            $query_run = mysqli_query($db, $query);
            header("location: doctor_editprofile.php");
        }

        else {
            echo '<div class="alert alert-danger " role="alert">
                    <br><br><br><br><br>
                    Wrong password
                    </div>
                    <nobr><nobr><nobr><nobr><nobr>';
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <link rel="stylesheet" href="doctor_profile.css">
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
                            echo "Doctor ".$lastname;
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
        
        <br><br><br><br><br><br>
        
        <form method="POST">
            <div class="container row g-4" style="padding-top: -100px;">
                <label class="view text-center" style="font-size: 30px;">Edit Profile</label>
                <br><br><br><br>
                <div class="col-md-8">
                    <label class="form-label">Specialization</label>
                    <input type="text" class="form-control text-left" name="editspecialization" value="<?php
                    if (isset($specialization)) {
                        echo $specialization;
                    }
                ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="editpassword" value="<?php
                    if (isset($password)) {
                        echo $password;
                    }
                ?>">
                </div>

                <div class="col-md-12"></div>

                <div class="col-md-5">
                    <label class="form-label">Enter password to confirm changes</label>
                    <input type="password" class="form-control" name="confirmedit" required>
                </div>

                <div class="col-md-7"></div>

                <div class="d-flex justify-content-center" id="button">
                    <input type="submit" value="EDIT" name="edit" class="btn">
                </div>
            </div>
        </form>
    </body>
</html>