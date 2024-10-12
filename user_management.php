<?php
require_once 'db.php';
require_once 'PHP_Handlers/user_management_handler.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <link rel="stylesheet" href="CSS_Files/user_management.css">
</head>
<body>
    <h2>Database Admin User Management System</h2>
    <h3> Costs and Rates Table Data</h3>

    <?php
    $sql = "SELECT * FROM costs_and_rates";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $costs_and_rates = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($costs_and_rates)) { ?>
        <table>
            <thead>
                <tr>
                    <th>Vehicle Category</th>
                    <th>Daily Hire Rate</th>
                    <th>Flat Maintenance Rate</th>
                    <th>Hourly Relocation Rate</th>
                    <th>Purchase Cost</th>
                    <th>Monthly Lease Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($costs_and_rates as $record) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['vehicle_category']); ?></td>
                        <td><?php echo htmlspecialchars($record['daily_hire_rate']); ?></td>
                        <td><?php echo htmlspecialchars($record['flat_maintenance_rate']); ?></td>
                        <td><?php echo htmlspecialchars($record['hourly_relocation_rate']); ?></td>
                        <td><?php echo htmlspecialchars($record['purchase_cost']); ?></td>
                        <td><?php echo htmlspecialchars($record['monthly_lease_cost']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No records found.</p>
    <?php } ?>

    <h3>Costs and Rates Table Update Form</h3>

    <div class="form-container">
        <form method="post">
            <div class="form-group">
                <label for="vehicle_category">Vehicle Category</label>
                <select required name="vehicle_category" id="vehicle_category">
                    <option value="">--Select Vehicle Category--</option>
                    <option value="ECONOMY_CAR">ECONOMY_CAR</option>
                    <option value="COMPACT_AUTO">COMPACT_AUTO</option>
                    <option value="FULL_SIZE_SEDAN">FULL_SIZE_SEDAN</option>
                    <option value="COMPACT_SUV">COMPACT_SUV</option>
                    <option value="INTERMEDIATE_SUV">INTERMEDIATE_SUV</option>
                    <option value="FULL_SIZE_SUV">FULL_SIZE_SUV</option>
                    <option value="OTHER">OTHER</option>
                </select>
            </div>

            <div class="form-group">
                <label for="rate_cost_type">Rate/Cost Type</label>
                <select required name="rate_cost_type" id="rate_cost_type">
                    <option value="">--Select Rate/Cost Type--</option>
                    <option value="daily_hire_rate">Daily Hire Rate</option>
                    <option value="flat_maintenance_rate">Flat Maintenance Rate</option>
                    <option value="hourly_relocation_rate">Hourly Relocation Rate</option>
                    <option value="purchase_cost">Purchase Cost</option>
                    <option value="monthly_lease_cost">Monthly Lease Cost</option>
                </select>
            </div>

            <div class="form-group">
                <label for="value">Value</label>
                <input required type="number" min="0" name="value" id="value">
            </div>

            <div class="form-group">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
    
    <div class="logout">
        <form method="post">
            <input type="hidden" name="logout" value="true">
            <input type="submit" value="Logout">
        </form>
    </div>
</body>
</html>