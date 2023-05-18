<?php

// Define the database connection details
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Establish a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create a class for the product
class Product {
    public $sku;
    public $name;
    public $price;
    public $attributeName;
    public $attributeValue;
    
    public function __construct($sku, $name, $price, $attributeName, $attributeValue) {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->attributeName = $attributeName;
        $this->attributeValue = $attributeValue;
    }
}

// Fetch the products from the database and sort by primary key
$sql = "SELECT * FROM products ORDER BY id";
$result = $conn->query($sql);

$products = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Determine the product-specific attribute and its value based on the product type
        $attributeName = "";
        $attributeValue = "";

        if ($row["product_type"] === "DVD") {
            $attributeName = "Size (MB)";
            $attributeValue = $row["size_mb"];
        } elseif ($row["product_type"] === "Book") {
            $attributeName = "Weight (Kg)";
            $attributeValue = $row["weight_kg"];
        } elseif ($row["product_type"] === "Furniture") {
            $attributeName = "Dimensions (HxWxL)";
            $attributeValue = $row["dimensions"];
        }

        // Create a new Product object and add it to the array
        $product = new Product($row["sku"], $row["name"], $row["price"], $attributeName, $attributeValue);
        $products[] = $product;
    }
}

// Convert the array of products to JSON
$response = json_encode($products);

// Return the response
echo $response;

// Close the database connection
$conn->close();
