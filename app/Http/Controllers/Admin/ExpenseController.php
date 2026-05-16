<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['expenseCategory', 'creator']);

        if ($request->filled('expense_category_id')) {
            $query->where('expense_category_id', $request->expense_category_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('expense_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('expense_date', '<=', $request->to_date);
        }

        $totalExpense = (clone $query)->sum('amount');

        $expenses = $query->latest()->paginate(15)->withQueryString();

        return view('admin.expenses.index', [
            'expenses' => $expenses,
            'categories' => ExpenseCategory::active()->orderBy('id')->get(),
            'totalExpense' => $totalExpense,
        ]);
    }

    public function create()
    {
        return view('admin.expenses.create', [
            'categories' => ExpenseCategory::active()->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['created_by'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('expenses/attachments', 'public');
        }

        Expense::create($data);

        return redirect()->route('admin.expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        return view('admin.expenses.edit', [
            'expense' => $expense,
            'categories' => ExpenseCategory::active()->orderBy('id')->get(),
        ]);
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('expenses/attachments', 'public');
        }

        $expense->update($data);

        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'Expense deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'expense_category_id' => ['required', 'exists:expense_categories,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'expense_date' => ['required', 'date'],
            'reference' => ['nullable', 'string', 'max:255'],
            'description' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (! is_null($value) && ! is_string($value) && ! is_array($value)) {
                        $fail('The ' . str_replace('_', ' ', $attribute) . ' must be a string or an array.');
                    }
                },
            ],
            'description.*' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,csv'],
        ]);
    }
}
