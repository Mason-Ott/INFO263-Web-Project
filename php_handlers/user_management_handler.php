<?php

// Function to sanitize input
function sanitize_input($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

session_start();

// Check if user clicked the logout button
if (isset($_POST['logout'])) {
    session_destroy();
    // Return to the login page
    header('Location: admin_login.php');
    exit();
}

// Check what value the user wants to update
if (isset($_POST['vehicle_category'], $_POST['rate_cost_type'], $_POST['value'])) {
    $vehicle_category = sanitize_input($_POST['vehicle_category']);
    $rate_cost_type = sanitize_input($_POST['rate_cost_type']);
    $value = sanitize_input($_POST['value']);

    try {
        $parameters = [
            'vehicle_category' => $vehicle_category,
            'rate_cost_type' => $rate_cost_type,
            'value' => $value
        ];
        
        // Setup the database query to update the record
        $sql = "UPDATE costs_and_rates SET " . $parameters['rate_cost_type'] .  " = :value WHERE vehicle_category = :vehicle_category";

        // Prepare the SQL statement
        $statement = $pdo->prepare($sql);

        // Bind parameters
        $statement->bindParam(':vehicle_category', $parameters['vehicle_category'], PDO::PARAM_STR);
        $statement->bindParam(':value', $parameters['value'], PDO::PARAM_INT);

        // Execute the SQL statement
        $response = $statement->execute();

        // Check if the update was successful
        if ($response) {
            echo '<script>alert("Update Successful!!!")</script>';
        } else {
            echo '<script>alert("Update Failed...")</script>';
        }
    } catch (PDOException $e) {
        echo 'Database Error: ' . $e->getMessage();
        exit();
    }

    // Redirect to the same page to refresh the data
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

?>