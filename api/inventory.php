<?php
require '../db/database.php';

// Obtener todos los productos
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM inventory");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

// Agregar un nuevo producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO inventory (product_name, quantity, price) VALUES (?, ?, ?)");
    $stmt->execute([$data['product_name'], $data['quantity'], $data['price']]);
    echo json_encode(["message" => "Producto agregado"]);
}

// Actualizar un producto existente
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("UPDATE inventory SET product_name = ?, quantity = ?, price = ? WHERE id = ?");
    $stmt->execute([$data['product_name'], $data['quantity'], $data['price'], $data['id']]);
    echo json_encode(["message" => "Producto actualizado"]);
}

// Eliminar un producto
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $stmt = $pdo->prepare("DELETE FROM inventory WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(["message" => "Producto eliminado"]);
}
?>
