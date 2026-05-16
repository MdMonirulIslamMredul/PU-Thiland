<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        return view('admin.expense-categories.index', [
            'categories' => ExpenseCategory::latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.expense-categories.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        ExpenseCategory::create($data);

        return redirect()->route('admin.expense-categories.index')->with('success', 'Expense category created successfully.');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('admin.expense-categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $expenseCategory->update($this->validatedData($request, $expenseCategory->id));

        return redirect()->route('admin.expense-categories.index')->with('success', 'Expense category updated successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();

        return back()->with('success', 'Expense category deleted successfully.');
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.bn' => ['nullable', 'string', 'max:255'],
            'name.zh' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:128', 'unique:expense_categories,code,' . ($id ?? 'NULL') . ',id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
