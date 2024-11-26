<?php
include '../db/database.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Manejo del método GET
        $stmt = $pdo->query("SELECT o.id, o.customer_name, o.order_date, o.total, 
            JSON_ARRAYAGG(
                JSON_OBJECT(
                    'product_name', i.product_name, 
                    'quantity', i.quantity
                )
            ) AS items
            FROM orders o
            LEFT JOIN order_items i ON o.id = i.order_id
            GROUP BY o.id");

        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($orders);
        http_response_code(200);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Manejo del método POST
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['customer_name'], $data['order_date'], $data['total'], $data['items'])) {
            echo json_encode(["message" => "Datos incompletos"]);
            http_response_code(400);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO orders (customer_name, order_date, total) VALUES (?, ?, ?)");
        $stmt->execute([$data['customer_name'], $data['order_date'], $data['total']]);
        $orderId = $pdo->lastInsertId();

        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_name, quantity) VALUES (?, ?, ?)");
        foreach ($data['items'] as $item) {
            $stmtItem->execute([$orderId, $item['product_name'], $item['quantity']]);
        }

        echo json_encode(["message" => "Pedido agregado correctamente"]);
        http_response_code(201);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // Manejo del método PUT (actualización de órdenes)
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id'], $data['customer_name'], $data['total'])) {
            echo json_encode(["message" => "Datos incompletos"]);
            http_response_code(400);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE orders SET customer_name = ?, total = ? WHERE id = ?");
        $stmt->execute([$data['customer_name'], $data['total'], $data['id']]);

        echo json_encode(["message" => "Orden actualizada correctamente"]);
        http_response_code(200);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Manejo del método DELETE
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id'])) {
            echo json_encode(["message" => "Datos incompletos"]);
            http_response_code(400);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$data['id']]);

        echo json_encode(["message" => "Orden eliminada correctamente"]);
        http_response_code(200);
    } else {
        echo json_encode(["message" => "Método no permitido"]);
        http_response_code(405);
    }
} catch (Exception $e) {
    echo json_encode(["message" => "Error al procesar la solicitud", "error" => $e->getMessage()]);
    http_response_code(500);
}