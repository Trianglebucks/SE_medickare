<html>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</html>
<?php
    $db = mysqli_connect("localhost","root","","medickare");

    echo '<script>console.log("dafsa"); </script>';
        $doctorid = $_POST['doctorid'];
        $scheddate = $_POST['scheddate'];
        $schedstarttime = $_POST['schedstarttime'];
        $schedendtime = $_POST['schedendtime'];

        $sql = "INSERT INTO schedule (doctorID, doctorSchedDate, doctorSchedStartTime, doctorSchedEndTime) VALUES ('$doctorid', '$scheddate', '$schedstarttime', '$schedendtime')";

        if ($db->query($sql) === TRUE) {
            echo "Record updated successfully";
            } else {
            echo "Error updating record: " . $db->error;
        }
?>