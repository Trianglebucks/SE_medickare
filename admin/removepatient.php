<html>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</html>
<?php
    //Function to remove patient
    $db = mysqli_connect("localhost","root","","medickare");

    if(isset($_POST['patientID'])){
        //Declaration of variables
        $patientID = $_POST['patientID'];
        echo $patientID;

        //SQL query to remove patient
        $sql = "DELETE FROM patient WHERE patientID = $patientID";

        if ($db->query($sql) === TRUE) {
            echo "Record updated successfully";
            } else {
            echo "Error updating record: " . $db->error;
        }
    }
?>