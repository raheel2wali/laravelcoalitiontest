<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg: #f9fafb;
            --card-bg: #ffffff;
            --text-main: #111827;
            --text-muted: #6b7280;
            --border: #e5e7eb;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            margin: 0;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            max-width: 1000px;
            width: 100%;
        }

        .card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            margin-bottom: 2rem;
            border: 1px solid var(--border);
        }

        h1,
        h2 {
            margin-top: 0;
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        input {
            width: 100%;
            padding: 0.625rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            ring: 2px solid var(--primary);
        }

        button {
            background-color: var(--primary);
            color: white;
            padding: 0.625rem 1.25rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: var(--primary-hover);
        }

        .edit-btn {
            background-color: #f59e0b;
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
        }

        .edit-btn:hover {
            background-color: #d97706;
        }

        .delete-btn {
            background-color: #ef4444;
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
        }

        .delete-btn:hover {
            background-color: #dc2626;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            text-align: left;
            padding: 0.75rem;
            border-bottom: 1px solid var(--border);
        }

        th {
            background-color: #f3f4f6;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .total-row {
            font-weight: bold;
            background-color: #f3f4f6;
        }

        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>Product Management</h1>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}" required>
                    @error('product_name') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity in Stock</label>
                    <input type="number" step="1" name="quantity" id="quantity" value="{{ old('quantity') }}" required>
                    @error('quantity') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="price">Price per Item</label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" required>
                    @error('price') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <button type="submit">Submit Product</button>
            </form>
        </div>

        <div class="card">
            <h2>Submitted Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Datetime Submitted</th>
                        <th>Total Value</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @forelse($products as $product)
                        @php $grandTotal += $product['total_value']; @endphp
                        <tr>
                            <td>{{ $product['product_name'] }}</td>
                            <td>{{ $product['quantity'] }}</td>
                            <td>${{ number_format($product['price'], 2) }}</td>
                            <td>{{ $product['datetime_submitted'] }}</td>
                            <td>${{ number_format($product['total_value'], 2) }}</td>
                            <td class="actions">
                                <a href="{{ route('products.edit', $product['id']) }}">
                                    <button type="button" class="btn edit-btn">Edit</button>
                                </a>
                                <form action="{{ route('products.destroy', $product['id']) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn delete-btn"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted);">No products submitted yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    @if(count($products) > 0)
                        <tr class="total-row">
                            <td colspan="4" style="text-align: right;">Grand Total:</td>
                            <td>${{ number_format($grandTotal, 2) }}</td>
                            <td></td>
                        </tr>
                    @endif
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>