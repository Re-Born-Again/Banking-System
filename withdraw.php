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


$customer_id = $_POST['customer_id'];
$withdraw_amount = $_POST['withdraw_amount'];

// Check if customer has sufficient balance
$sql = "SELECT balance FROM customers WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->bind_result($balance);
$stmt->fetch();

if ($balance >= $withdraw_amount && $withdraw_amount > 0) {
    // Update balance
    $sql = "UPDATE customers SET balance = balance - ? WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $withdraw_amount, $customer_id);
    $stmt->execute();

    // Record transaction
    $sql = "INSERT INTO transactions (customer_id, amount, transaction_type) VALUES (?, ?, 'withdrawal')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $customer_id, $withdraw_amount);
    $stmt->execute();

    echo "Withdrawal successful!";
} else {
    echo "Insufficient balance or invalid withdrawal amount.";
}

$stmt->close();
$conn->close();
?>
