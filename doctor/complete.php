<?php
    //Function to complete appointment
    $db = mysqli_connect("localhost","root","","medickare");

    if(isset($_POST['appointmentID'])){
        $appointmentID = $_POST['appointmentID'];
        
        //SQL to cancel the appointment and check if status is active
        $query = "UPDATE appointment SET appointmentStatus= 'Complete' WHERE appointmentID = '$appointmentID' AND appointmentStatus = 'Active'";

        //SQL query to get appointment ID 
        $id = $db->prepare("SELECT * FROM appointment WHERE appointmentID = ? limit 1");
        $id->bind_param('s', $appointmentID);
        $id->execute();
        $resultid = $id->get_result();
        $value = $resultid->fetch_object();
        
        //Declaration of variable
        $appointmentStatus = $value->appointmentStatus;
        
        //Function to complete the appointmend if appointment status is neither complete or cancelled
        if ($db->query($query) === TRUE) {
            if ($appointmentStatus === 'Complete' || $appointmentStatus === 'Cancelled') {
                echo json_encode('Appointment is either cancelled or completed');
            }
            else {
                echo json_encode('Appointment is completed');
            }
        }
        else {
            echo "fasdfasf " . $db->error;
        }
    }
?>