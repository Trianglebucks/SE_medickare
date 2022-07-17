<?php

    $db = mysqli_connect("localhost","root","","medickare");


    if(isset($_POST['appointmentID'])){
    $appointmentID = $_POST['appointmentID'];
     echo $appointmentID;

     $sql = "UPDATE appointment SET appointmentStatus='Cancelled' WHERE appointmentID=$appointmentID";

     if ($db->query($sql) === TRUE) {
        echo "Record updated successfully";
        } else {
        echo "Error updating record: " . $db->error;
        }
    }
?>