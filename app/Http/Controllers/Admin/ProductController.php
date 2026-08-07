<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('supplier')
            ->when($request->boolean('low'), fn ($q) => $q->whereColumn('stock_qty', '<=', 'low_stock_threshold'))
            ->orderBy('name')->paginate(15)->withQueryString();

        $lowCount = Product::whereColumn('stock_qty', '<=', 'low_stock_threshold')->count();

        return view('admin.products.index', compact('products', 'lowCount'));
    }

    public function create()
    {
        return view('admin.products.form', ['product' => new Product(), 'suppliers' => Supplier::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->resolveImage($request, 'image_file', 'image_url');
        $data['is_active'] = $request->boolean('is_active');
        $product = Product::create($data);

        if ($product->stock_qty > 0) {
            $product->movements()->create(['type' => 'in', 'qty' => $product->stock_qty, 'reason' => 'Opening stock']);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', ['product' => $product, 'suppliers' => Supplier::orderBy('name')->get()]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);
        $data['image'] = $this->resolveImage($request, 'image_file', 'image_url', $product->image);
        $data['is_active'] = $request->boolean('is_active');
        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    /** Quick stock in / out / adjust */
    public function stock(Request $request, Product $product)
    {
        $data = $request->validate([
            'type' => ['required', 'in:in,out,adjust'],
            'qty' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string'],
        ]);

        if ($data['type'] === 'in') {
            $product->increment('stock_qty', $data['qty']);
        } elseif ($data['type'] === 'out') {
            $product->decrement('stock_qty', $data['qty']);
        } else {
            $product->update(['stock_qty' => $data['qty']]);
        }

        $product->movements()->create([
            'type' => $data['type'],
            'qty' => $data['qty'],
            'reason' => $data['reason'] ?? 'Manual adjustment',
        ]);

        return back()->with('success', 'Stock updated.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string'],
            'barcode' => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'cost_price' => ['nullable', 'numeric'],
            'selling_price' => ['nullable', 'numeric'],
            'stock_qty' => ['nullable', 'integer'],
            'low_stock_threshold' => ['nullable', 'integer'],
            'expiry_date' => ['nullable', 'date'],
        ]);
    }
}
