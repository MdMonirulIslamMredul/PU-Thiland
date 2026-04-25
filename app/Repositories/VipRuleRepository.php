<?php

namespace App\Repositories;

use App\Models\VipRule;
use Illuminate\Support\Collection;

class VipRuleRepository
{
    public function allOrdered(): Collection
    {
        return VipRule::orderByDesc('priority')->orderBy('min_sales_kg')->get();
    }

    public function allActiveOrdered(): Collection
    {
        return VipRule::active()->orderByDesc('priority')->orderBy('min_sales_kg')->get();
    }

    public function find(int $id): ?VipRule
    {
        return VipRule::find($id);
    }

    public function create(array $data): VipRule
    {
        return VipRule::create($data);
    }

    public function update(VipRule $rule, array $data): VipRule
    {
        $rule->update($data);
        return $rule;
    }

    public function delete(VipRule $rule): void
    {
        $rule->delete();
    }
}
