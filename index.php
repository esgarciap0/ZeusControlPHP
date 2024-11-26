<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zeus Control</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        h1 {
            color: #333;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Bienvenido a Zeus Control</h1>
    <p>Seleccione una opción para gestionar el sistema:</p>
    <div>
        <button onclick="location.href='api/orders.php'">Ver Órdenes</button>
        <button onclick="location.href='api/inventory.php'">Ver Inventario</button>
    </div>
</body>
</html>
