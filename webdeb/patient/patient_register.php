<?php 
    $db = mysqli_connect("localhost","root","","medickare");

    if(isset($_POST['register'])) {
        $lastname = $_POST["lastname"];
        $firstname = $_POST["firstname"];
        $address = $_POST["address"];
        $birthday = $_POST["birthday"];
        $sex = $_POST["sex"];
        $phone_no = $_POST["phone_no"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirmpassword = $_POST["confirmpassword"];

        $checkregister = mysqli_query($db, "SELECT * FROM patient WHERE patientEmail = '$email'");
        if(mysqli_num_rows($checkregister) > 0) {
            echo '<div class="alert alert-danger " role="alert">
                Email already exists
                </div>';
        }

        else {
            if ($password === $confirmpassword) {
                $register = $db -> prepare("insert into patient(patientEmail, patientPassword, patientFirstName, patientLastName, patientAddress, patientBirthday, patientSex, patientPhoneNumber) values(?, ?, ?, ?, ?, ?, ?, ?)");
                $register -> bind_param("ssssssss", $email, $password, $firstname, $lastname, $address, $birthday, $sex, $phone_no);
                $register -> execute();
                $register -> close();
                echo '<div class="alert alert-success " role="alert">
                    Registration success
                    </div>';
                header ("location: patient_login.php");
            }

            else {
                echo '<div class="alert alert-danger " role="alert">
                    Password do not match
                    </div>';
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <link rel="stylesheet" href="style_patient_login_register.css">
    </head>
        <div class="container-fluid d-absolute" id="back">
            <div class="d-xl-none"><br><br><br><br><br><br><br><br><br><br><br></div>
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-chevron-compact-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 0 1 .223.67L6.56 8l2.888 5.776a.5.5 0 1 1-.894.448l-3-6a.5.5 0 0 1 0-.448l3-6a.5.5 0 0 1 .67-.223z"/>\
            </svg>
            <a href="patient_login.php">BACK</a>
        </div>

        <div class="container d-flex justify-content-center">
            <a class="navbar-brand" href="index.html">
                <img src="../pictures/logo.png" alt="" width="100px" height="100px" class="d-inline-block align-text-top justify-content-center">
                <label>MedicKare Health Care</label>
            </a>
        </div>
        <div class="container d-flex justify-content-center px-5">
            <div class="row">
                <div>
                    <br>
                    <center class="display-6" style="font-weight: 500;">REGISTER FORM</center>
                    <br>
                    <form method="POST" class="needs-validation row g-4" novalidate>
                        <div class="col-md-6">
                            <label for="validationCustom" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="validationCustom" name="firstname" required>
                            <div class="invalid-feedback">
                                Please enter your first name.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="validationCustom" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="validationCustom" name="lastname" required>
                            <div class="invalid-feedback">
                                Please enter your last name.
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label for="validationCustom" class="form-label">Address</label>
                            <input type="text" class="form-control" id="validationCustom" name="address" required>
                            <div class="invalid-feedback">
                                Please enter your address.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="validationCustom" class="form-label">Birthday</label>
                            <input type="date" class="form-control" id="validationCustom" name="birthday" required>
                            <div class="invalid-feedback">
                                Please enter your birtday.
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="validationCustom" class="form-label">Sex</label>
                            <select name="sex" class="form-control" id="validationCustom" required>
                                <option selected hidden value=""></option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <div class="invalid-feedback">
                                Please enter your sex.
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label for="validationCustom" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="validationCustom" name="phone_no" required>
                            <div class="invalid-feedback">
                                Please enter your phone number.
                            </div>
                        </div>

                        <div class="col-md-7">
                            <label for="validationCustom" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="validationCustom" name="email" required>
                            <div class="invalid-feedback">
                                Please enter your email address.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="validationCustom02" class="form-label">Password</label>
                            <input type="password" class="form-control" id="validationCustom02" name="password"required>
                            <div class="invalid-feedback">
                                Please enter thepassword.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="validationCustom03" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="validationCustom03" name="confirmpassword" required>
                            <div class="invalid-feedback">
                                Please confirm the password.
                            </div>
                        </div>

                        <div class="container d-flex justify-content-center">
                            <input type="submit" value="REGISTER" name="register" class="btn mx-3 mt-4">
                            <input type="reset" value="RESET" class="btn mx-3 mt-4">
                        </div>
                    </form>
                    <br>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
        <script>
            (() => {
                'use strict'

                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                const forms = document.querySelectorAll('.needs-validation')

                // Loop over them and prevent submission
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