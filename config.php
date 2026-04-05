<?php
$conn = mysqli_connect("localhost", "root", "KAVYA", "Crude_project");

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>