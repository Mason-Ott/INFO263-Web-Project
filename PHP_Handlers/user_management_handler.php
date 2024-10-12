<?php

function sanitize_input($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

session_start();

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: admin_login.php');
    exit();
}

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

        $sql = "UPDATE costs_and_rates SET " . $parameters['rate_cost_type'] .  " = :value WHERE vehicle_category = :vehicle_category";

        // Prepare the SQL statement
        $statement = $pdo->prepare($sql);

        // Bind parameters
        $statement->bindParam(':vehicle_category', $parameters['vehicle_category'], PDO::PARAM_STR);
        // $statement->bindParam(':rate_cost_type', $parameters['rate_cost_type'], PDO::PARAM_STR);
        $statement->bindParam(':value', $parameters['value'], PDO::PARAM_INT);

        // Execute the SQL statement
        $response = $statement->execute();
        if ($response) {
            echo '<script>alert("Update Successful!!!")</script>';
        } else {
            echo '<script>alert("Update Failed...")</script>';
        }
    } catch (PDOException $e) {
        echo 'Database Error: ' . $e->getMessage();
        exit();
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

?>