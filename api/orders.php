<?php
require '../db/database.php';

// Obtener todos los pedidos
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM orders");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

// Agregar un nuevo pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Insertar el pedido principal
    $stmt = $pdo->prepare("INSERT INTO orders (customer_name, order_date, total) VALUES (?, ?, ?)");
    $stmt->execute([$data['customer_name'], $data['order_date'], $data['total']]);
    $orderId = $pdo->lastInsertId();

    // Insertar los ítems del pedido
    foreach ($data['items'] as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_name, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$orderId, $item['product_name'], $item['quantity']]);
    }

    echo json_encode(["message" => "Pedido agregado"]);
}

// Eliminar un pedido
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(["message" => "Pedido eliminado"]);
}
?>
