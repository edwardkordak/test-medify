<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('masterItems');

        if ($request->search) {

            $query->where(
                'name',
                'like',
                '%' . $request->search . '%'
            );
        }

        if ($request->kode) {

            $query->where('kode', $request->kode);
        }

        $categories = $query->paginate(10);

        return view(
            'master_items.category.index',
            compact('categories')
        );
    }

    public function create()
    {
        return view('master_items.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:10|unique:categories,kode',
        ]);

        Category::create($request->only('name', 'kode'));

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('master_items.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:10|unique:categories,kode,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->only('name'));

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }

    public function exportPdf(Request $request)
    {
        $query = Category::with('masterItems');

        if ($request->search) {
            $query->where(
                'name',
                'like',
                '%' . $request->search . '%'
            );
        }

        if ($request->kode) {
            $query->where('kode', $request->kode);
        }

        $categories = $query->get();

        $pdf = Pdf::loadView(
            'master_items.category.pdf',
            compact('categories')
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('kategori.pdf');
    }
}
