<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banking_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$customer_id = $_GET['customer_id'];

// Fetch all transactions for the customer
$sql = "SELECT transaction_id, amount, transaction_type, transaction_date FROM transactions WHERE customer_id = ? ORDER BY transaction_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->bind_result($transaction_id, $amount, $transaction_type, $transaction_date);

echo "<h3>Transaction History</h3>";
echo "<table border='1'>
        <tr>
            <th>Transaction ID</th>
            <th>Amount</th>
            <th>Transaction Type</th>
            <th>Transaction Date</th>
        </tr>";

while ($stmt->fetch()) {
    echo "<tr>
            <td>$transaction_id</td>
            <td>$amount</td>
            <td>$transaction_type</td>
            <td>$transaction_date</td>
        </tr>";
}

echo "</table>";

$stmt->close();
$conn->close();
?>
