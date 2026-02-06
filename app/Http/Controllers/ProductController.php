<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private $jsonFile = 'products.json';

    public function index()
    {
        $products = $this->getProducts();
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $products = $this->getProducts();

        $newProduct = [
            'id' => uniqid(),
            'product_name' => $validated['product_name'],
            'quantity' => (float) $validated['quantity'],
            'price' => (float) $validated['price'],
            'datetime_submitted' => now()->toDateTimeString(),
            'total_value' => (float) $validated['quantity'] * (float) $validated['price'],
        ];

        $products[] = $newProduct;
        $this->saveProducts($products);

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    public function edit($id)
    {
        $products = $this->getProducts();
        $product = collect($products)->firstWhere('id', $id);

        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found!');
        }

        return view('products.edit', compact('product', 'products'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $products = $this->getProducts();
        $index = collect($products)->search(function ($product) use ($id) {
            return $product['id'] === $id;
        });

        if ($index === false) {
            return redirect()->route('products.index')->with('error', 'Product not found!');
        }

        $products[$index]['product_name'] = $validated['product_name'];
        $products[$index]['quantity'] = (float) $validated['quantity'];
        $products[$index]['price'] = (float) $validated['price'];
        $products[$index]['total_value'] = (float) $validated['quantity'] * (float) $validated['price'];

        $this->saveProducts($products);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $products = $this->getProducts();
        $products = array_values(array_filter($products, function ($product) use ($id) {
            return $product['id'] !== $id;
        }));

        $this->saveProducts($products);

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    private function getProducts()
    {
        if (!Storage::exists($this->jsonFile)) {
            return [];
        }

        $content = Storage::get($this->jsonFile);
        $products = json_decode($content, true) ?? [];

        // Sort by datetime_submitted in descending order (newest first)
        usort($products, function ($a, $b) {
            return strtotime($b['datetime_submitted']) - strtotime($a['datetime_submitted']);
        });

        return $products;
    }

    private function saveProducts($products)
    {
        Storage::put($this->jsonFile, json_encode($products, JSON_PRETTY_PRINT));
    }
}
