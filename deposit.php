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
$deposit_amount = $_POST['deposit_amount'];

if ($deposit_amount > 0) {
    // Update balance
    $sql = "UPDATE customers SET balance = balance + ? WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $deposit_amount, $customer_id);
    $stmt->execute();

    // Record transaction
    $sql = "INSERT INTO transactions (customer_id, amount, transaction_type) VALUES (?, ?, 'deposit')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $customer_id, $deposit_amount);
    $stmt->execute();

    echo "Deposit successful!";
} else {
    echo "Invalid deposit amount.";
}

$stmt->close();
$conn->close();
?>
