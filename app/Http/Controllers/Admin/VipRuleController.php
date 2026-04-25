<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VipRule;
use App\Services\VipRuleService;
use Illuminate\Http\Request;

class VipRuleController extends Controller
{
    public function __construct(protected VipRuleService $vipRuleService) {}

    public function index()
    {
        return view('admin.vip-rules.index', [
            'rules' => $this->vipRuleService->getAllRules(),
        ]);
    }

    public function create()
    {
        return view('admin.vip-rules.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateRule($request);
        $this->vipRuleService->createRule($data);

        return redirect()->route('admin.vip-rules.index')->with('success', 'VIP rule created successfully.');
    }

    public function edit(VipRule $vipRule)
    {
        return view('admin.vip-rules.edit', compact('vipRule'));
    }

    public function update(Request $request, VipRule $vipRule)
    {
        $data = $this->validateRule($request, $vipRule->id);
        $this->vipRuleService->updateRule($vipRule, $data);

        return redirect()->route('admin.vip-rules.index')->with('success', 'VIP rule updated successfully.');
    }

    public function destroy(VipRule $vipRule)
    {
        $this->vipRuleService->deleteRule($vipRule);

        return back()->with('success', 'VIP rule deleted successfully.');
    }

    protected function validateRule(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'level_name' => ['required', 'string', 'max:255', 'unique:vip_rules,level_name,' . ($id ?? 'NULL') . ',id'],
            'discount_per_kg' => ['required', 'numeric', 'min:0'],
            'min_sales_kg' => ['required', 'numeric', 'min:0'],
            'max_sales_kg' => ['nullable', 'numeric', 'gte:min_sales_kg'],
            'min_recharge_amount' => ['required', 'numeric', 'min:0'],
            'priority' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'expiry_days' => ['required', 'integer', 'min:1'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
