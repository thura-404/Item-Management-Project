<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 3px;
        }

        td {
            width: 1.5rem;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Items List</h1>
    <table>
        <thead>
            <tr>
                <th>Item ID</th>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Safety Stock</th>
                <th>Received Date</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
            <tr>
                <td>{{ $item->item_id }}</td>
                <td>{{ $item->item_code }}</td>
                <td>{{ $item->item_name }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->safety_stock }}</td>
                <td>{{ $item->received_date }}</td>
                <td style="max-width: 5rem;">{{ $item->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
