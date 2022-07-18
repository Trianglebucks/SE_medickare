<html>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</html>
<?php
    $db = mysqli_connect("localhost","root","","medickare");

    if(isset($_POST['appointmentID'])){
        $appointmentID = $_POST['appointmentID'];
        
        $query = "UPDATE appointment SET appointmentStatus= 'Complete' WHERE appointmentID = '$appointmentID'";
        $query_run = mysqli_query($db, $query);
    }
?>