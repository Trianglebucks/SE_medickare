<html>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</html>
<?php
    $db = mysqli_connect("localhost","root","","medickare");

    echo '<script>console.log("dafsa"); </script>';

        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $specialization = $_POST['specialization'];

        $sql = "INSERT INTO doctor (doctorEmail, doctorPassword, doctorLastName, doctorFirstName, doctorSpecialization) VALUES ('$email', '$password', '$lastname', '$firstname', '$specialization')";

        if ($db->query($sql) === TRUE) {
            echo "Record updated successfully";
            } else {
            echo "Error updating record: " . $db->error;
        }
?>