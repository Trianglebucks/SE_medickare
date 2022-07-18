<html>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</html>
<?php
    $db = mysqli_connect("localhost","root","","medickare");

    if(isset($_POST['doctorID'])){
        $doctorID = $_POST['doctorID'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $specialization = $_POST['specialization'];

        $sql = "UPDATE doctor SET doctorEmail = '$email', doctorPassword = '$password', doctorSpecialization = '$specialization' WHERE doctorID = '$doctorID'";

        if ($db->query($sql) === TRUE) {
            header("location: admin_dashboard.php");
            } else {
            echo "Error updating record: " . $db->error;
        }
    }
?>