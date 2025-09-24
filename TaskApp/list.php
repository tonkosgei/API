<?php
//create database connection
$servername = "localhost";
$username = "root";
$password = "0000";
$dbname = "dbproject";

//Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//Check connection
if ($conn->connect_error){
    die("Connection failed:". $conn->connect_error);
}
else{
echo "Connected succesfully to " . $dbname;
}

// Fetch users in ascending order by id
$sql = "SELECT id, name, email FROM users ORDER BY id ASC";
$result = $conn->query($sql);

echo "<h2>Registered Users</h2>";

if ($result->num_rows > 0) {
    echo "<ol>"; // ordered list = automatic numbering
    
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['name']) . 
             " (" . htmlspecialchars($row['email']) . ")</li>";
    }
    
    echo "</ol>";
} else {
    echo "No users found.";
}