<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Products Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 6px;
        }

        th {
            background: #f0f0f0;
        }
    </style>
</head>

<body>
    <h2>Products Export</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Slug</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Price</th>
                <th>Open Price</th>
                <th>Quantity</th>
                <th>Unit Type</th>
                <th>Unit Name</th>
                <th>Grade</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->slug }}</td>
                    <td>{{ $product->category?->name }}</td>
                    <td>{{ $product->subcategory?->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->open_price }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ ucfirst($product->unit_type) }}</td>
                    <td>{{ $product->unit_name }}</td>
                    <td>{{ $product->grade }}</td>
                    <td>{{ $product->status ? 'Active' : 'Inactive' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
