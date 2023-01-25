<html>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</html>
<?php
    //Function to edit patient information
    $db = mysqli_connect("localhost","root","","medickare");

    if(isset($_POST['patientID'])){
        //Declaration of variables
        $patientID = $_POST['patientID'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $address = $_POST['address'];
        $phonenumber = $_POST['phonenumber'];

        //SQL query to edit patient information
        $sql = "UPDATE patient SET patientEmail = '$email', patientPassword = '$password', patientAddress = '$address', patientPhoneNumber = '$phonenumber' WHERE patientID = '$patientID'";

        if ($db->query($sql) === TRUE) {
            header("location: admin_dashboard.php");
            } else {
            echo "Error updating record: " . $db->error;
        }
    }
?>