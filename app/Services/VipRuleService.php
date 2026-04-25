<?php

namespace App\Services;

use App\Models\VipRule;
use App\Repositories\VipRuleRepository;
use Illuminate\Support\Collection;

class VipRuleService
{
    public function __construct(protected VipRuleRepository $repository) {}

    public function getAllRules(): Collection
    {
        return $this->repository->allOrdered();
    }

    public function getActiveRules(): Collection
    {
        return $this->repository->allActiveOrdered();
    }

    public function find(int $id): ?VipRule
    {
        return $this->repository->find($id);
    }

    public function createRule(array $data): VipRule
    {
        return $this->repository->create($data);
    }

    public function updateRule(VipRule $rule, array $data): VipRule
    {
        return $this->repository->update($rule, $data);
    }

    public function deleteRule(VipRule $rule): void
    {
        $this->repository->delete($rule);
    }

    public function findMatchingRule(float $monthlySalesKg, float $monthlyRecharge): ?VipRule
    {
        return $this->getActiveRules()->first(function (VipRule $rule) use ($monthlySalesKg, $monthlyRecharge) {
            return $this->matchesRule($rule, $monthlySalesKg, $monthlyRecharge);
        });
    }

    protected function matchesRule(VipRule $rule, float $monthlySalesKg, float $monthlyRecharge): bool
    {
        $matchesSales = $monthlySalesKg >= (float) $rule->min_sales_kg
            && ($rule->max_sales_kg === null || $monthlySalesKg <= (float) $rule->max_sales_kg);

        $matchesRecharge = $monthlyRecharge >= (float) $rule->min_recharge_amount;

        return $matchesSales || $matchesRecharge;
    }
}
