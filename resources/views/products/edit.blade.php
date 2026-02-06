<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
            max-width: 600px;
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

        h1 {
            margin-top: 0;
            color: var(--primary);
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
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
        }

        .btn-group {
            display: flex;
            gap: 1rem;
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

        .cancel-btn {
            background-color: #f3f4f6;
            color: #374151;
            text-decoration: none;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-block;
            text-align: center;
        }

        .cancel-btn:hover {
            background-color: #e5e7eb;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>Edit Product</h1>

            <form action="{{ route('products.update', $product['id']) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" name="product_name" id="product_name"
                        value="{{ old('product_name', $product['product_name']) }}" required>
                    @error('product_name') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity in Stock</label>
                    <input type="number" step="1" name="quantity" id="quantity"
                        value="{{ old('quantity', $product['quantity']) }}" required>
                    @error('quantity') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="price">Price per Item</label>
                    <input type="number" step="0.01" name="price" id="price"
                        value="{{ old('price', $product['price']) }}" required>
                    @error('price') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="btn-group">
                    <button type="submit">Update Product</button>
                    <a href="{{ route('products.index') }}" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>