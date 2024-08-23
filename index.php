<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// CREATE Operation
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $sql = "INSERT INTO items (name, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $description);
    if ($stmt->execute()) {
        echo "New record created successfully<br>";
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
    $stmt->close();
}

// READ Operation
if (isset($_GET['action']) && $_GET['action'] == 'view') {
    $sql = "SELECT * FROM items";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<h2>Items List</h2>";
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"]. " - Name: " . $row["name"]. " - Description: " . $row["description"]. "<br>";
        }
    } else {
        echo "0 results<br>";
    }
}

// UPDATE Operation
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $sql = "UPDATE items SET name=?, description=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $description, $id);
    if ($stmt->execute()) {
        echo "Record updated successfully<br>";
    } else {
        echo "Error updating record: " . $stmt->error . "<br>";
    }
    $stmt->close();
}

// DELETE Operation
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM items WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Record deleted successfully<br>";
    } else {
        echo "Error deleting record: " . $stmt->error . "<br>";
    }
    $stmt->close();
}

$conn->close();
?>

<!-- HTML forms for Create, Update, and Delete operations -->
<h2>Create Item</h2>
<form method="post" action="">
    Name: <input type="text" name="name" required><br>
    Description: <textarea name="description" required></textarea><br>
    <input type="submit" name="create" value="Create Item">
</form>

<h2>Update Item</h2>
<form method="post" action="">
    ID: <input type="number" name="id" required><br>
    Name: <input type="text" name="name" required><br>
    Description: <textarea name="description" required></textarea><br>
    <input type="submit" name="update" value="Update Item">
</form>

<h2>Delete Item</h2>
<form method="post" action="">
    ID: <input type="number" name="id" required><br>
    <input type="submit" name="delete" value="Delete Item">
</form>

<h2>View Items</h2>
<form method="get" action="">
    <input type="submit" name="action" value="view">
</form>
