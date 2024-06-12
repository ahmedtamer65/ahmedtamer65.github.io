<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $form_type = isset($_POST['form_type']) ? $_POST['form_type'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $cycle_code = isset($_POST['cycle_code']) ? $_POST['cycle_code'] : '';
    $hours = isset($_POST['hours']) ? htmlspecialchars($_POST['hours']) : '';
    $note = isset($_POST['note']) ? $_POST['note'] : '';

    // Database connection parameters
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "bike2";

    // Create a new MySQLi connection
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    } else {
        if ($form_type === 'book') {
            // Prepare an SQL statement for booking
            $stmt = $conn->prepare("INSERT INTO bookings (name, user_id, phone, cycle_code, note, hours) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssssss", $name, $id, $phone, $cycle_code, $note , $hours);
            }
        } else if ($form_type === 'repair') {
            // Prepare an SQL statement for repair
            $stmt = $conn->prepare("INSERT INTO repairs (name, cycle_code, problem) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sss", $name, $cycle_code, $note);
            }
        }

        // Check if the statement was prepared successfully
        if ($stmt) {
            // Execute the statement
            $execval = $stmt->execute();
            if ($execval) {
                echo "Submission successful...";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }

        $conn->close();
    }
} else {
    echo "No form data submitted.";
}
?>
