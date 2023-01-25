<html>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</html>
<?php
    //Function to get schedule time of doctor
    session_start();
    $db = mysqli_connect("localhost","root","","medickare");

    if(isset($_POST['selSchedDate']) ) {
        //Declaration of variable
        $schedDate = $_POST['selSchedDate'];
        $doctorID = $_POST['selDoctorID'];

        //Function to get doctors schedule time based on schedule date and doctor ID
        $readSchedTime = "SELECT doctorSchedID, doctorSchedStartTime FROM schedule WHERE doctorSchedDate = '$schedDate' AND doctorID= '$doctorID'  AND doctorSchedAvailability = 'Yes'";

        $schedTimeData = mysqli_query($db, $readSchedTime);

        //Function to display schedule times in drop down menu
        echo '<option selected hidden value=""></option>';
        while($row = $schedTimeData->fetch_assoc()) {
            echo '<option value="'.$row['doctorSchedID'].'">'.$row['doctorSchedStartTime'].'</option>';
        }
    }
?>