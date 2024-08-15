
Copy
<!DOCTYPE html>
<html>
<head>
    <title>Low Stock Alert</title>
</head>
<body>
    <h1>Low Stock Alert</h1>
    <p>Dear Supplier,</p>
    <p>This is to inform you that the stock level for {{ $ingredient->name }} is currently below 50% of its reorder level.</p>
    <p>Current stock: {{ $ingredient->current_stock }}</p>
    <p>Please arrange for a resupply as soon as possible.</p>
    <p>Thank you for your prompt attention to this matter.</p>
</body>
</html>
