<html>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</html>
<?php
    //Function to get name of doctor
    session_start();
    $db = mysqli_connect("localhost","root","","medickare");

    if(isset($_POST['doctorSpecialization'])) {
        unset($doctorIDArray);

        $doctorIDArray = array();

        //Declaration of variable
        $doctorSpecialization = $_POST['doctorSpecialization'];

        echo $doctorSpecialization;

        //Function to get doctors name based on specialization
        $readDoctorName = "SELECT doctorID, doctorLastName, doctorFirstName FROM doctor WHERE doctorSpecialization = '$doctorSpecialization'";

        $doctorData = mysqli_query($db, $readDoctorName);

        //Function to display doctors in drop down menu
        echo '<option selected hidden value=""></option>';
        while($row = $doctorData->fetch_assoc()) {
            echo '<option value="'.$row['doctorID'].'"> Dr. '.$row['doctorFirstName'].' '.$row['doctorLastName'].'</option>';
        }
    }
?>