<?php
    //Function to cancel appointment
    $db = mysqli_connect("localhost","root","","medickare");

    session_start();

    if(isset($_POST['appointmentID'])){
        $appointmentID = $_POST['appointmentID'];

        //SQL to cancel the appointment and check if status is active
        $sql = "UPDATE appointment SET appointmentStatus='Cancelled' WHERE appointmentID= $appointmentID && appointmentStatus = 'Active'";

        //SQL query to get appointment ID 
        $id = $db->prepare("SELECT * FROM appointment WHERE appointmentID = ? limit 1");
        $id->bind_param('s', $appointmentID);
        $id->execute();
        $resultid = $id->get_result();
        $value = $resultid->fetch_object();

        //Declaration of variable
        $doctorSchedID = $value->doctorSchedID;
        $appointmentStatus = $value->appointmentStatus;

        //Function to change availability of schedule
        $availbility = "UPDATE schedule SET doctorSchedAvailability = 'Yes' WHERE doctorSchedID = '$doctorSchedID'";

        $availbility_run = mysqli_query($db, $availbility);
        
        //Function to cancel the appointmend if appointment status is neither complete or cancelled
        if ($db->query($sql) === TRUE) {
            if ($appointmentStatus === 'Complete' || $appointmentStatus === 'Cancelled') {
                echo json_encode('Appointment is either cancelled or completed');
            }
            else {
                echo json_encode('Appointment is cancelled');
            }
        }
        else {
            echo "fasdfasf " . $db->error;
        }
    }
?>