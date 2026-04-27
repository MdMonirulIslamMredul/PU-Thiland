<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->productQuery($request);

        $products = $query->paginate(10)->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => ProductCategory::orderBy('sort_order')->get(),
            'search' => $request->input('search', ''),
            'categoryId' => $request->input('category_id', ''),
            'status' => $request->input('status', ''),
            'sortBy' => $request->input('sort_by', 'created_at'),
            'order' => $request->input('order', 'desc'),
        ]);
    }

    public function exportExcel(Request $request)
    {
        $products = $this->productQuery($request)->get();

        return Excel::download(new ProductsExport($products), 'products.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $products = $this->productQuery($request)->get();

        $pdf = Pdf::loadView('admin.products.export-pdf', compact('products'));

        return $pdf->download('products.pdf');
    }

    public function report(Request $request)
    {
        $products = $this->productQuery($request)->get();

        return view('admin.products.report', [
            'products' => $products,
            'categories' => ProductCategory::orderBy('sort_order')->get(),
            'search' => $request->input('search', ''),
            'categoryId' => $request->input('category_id', ''),
            'status' => $request->input('status', ''),
            'sortBy' => $request->input('sort_by', 'created_at'),
            'order' => $request->input('order', 'desc'),
        ]);
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => ProductCategory::orderBy('sort_order')->get(),
            'subcategories' => ProductSubcategory::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => ProductCategory::orderBy('sort_order')->get(),
            'subcategories' => ProductSubcategory::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validatedData($request, $product->id);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }

    private function productQuery(Request $request)
    {
        $query = Product::with(['category', 'subcategory']);

        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('product_category_id', $categoryId);
        }

        if ($status = $request->input('status')) {
            if (in_array($status, ['0', '1'], true)) {
                $query->where('status', $status);
            }
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $order = $request->input('order', 'desc');
        $allowedSorts = ['title', 'price', 'status', 'created_at'];

        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'created_at';
        }

        if (!in_array($order, ['asc', 'desc'], true)) {
            $order = 'desc';
        }

        return $query->orderBy($sortBy, $order);
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'array'],
            'title.en' => ['nullable', 'string', 'max:255'],
            'title.bn' => ['required', 'string', 'max:255'],
            'title.zh' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,' . ($id ?? 'NULL') . ',id'],
            'short_description' => ['required', 'array'],
            'short_description.en' => ['nullable', 'string', 'max:255'],
            'short_description.bn' => ['required', 'string', 'max:255'],
            'short_description.zh' => ['required', 'string', 'max:255'],
            'description' => ['required', 'array'],
            'description.en' => ['nullable', 'string'],
            'description.bn' => ['required', 'string'],
            'description.zh' => ['required', 'string'],
            'price' => ['nullable', 'numeric'],
            'grade' => ['nullable', 'string', 'max:255'],
            'specification' => ['nullable', 'string'],
            'open_price' => ['nullable', 'numeric'],
            'quantity' => ['nullable', 'numeric'],
            'unit_type' => ['nullable', 'string', 'in:piece,weight'],
            'unit_name' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'numeric'],
            'product_category_id' => ['nullable', 'exists:product_categories,id'],
            'product_subcategory_id' => ['nullable', 'exists:product_subcategories,id'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['slug'] = $data['slug'] ?? $this->slugFromTranslation($data['title']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['status'] = $request->boolean('status', false);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
