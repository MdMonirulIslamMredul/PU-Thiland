<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\VipCalculationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckVipExpiry extends Command
{
    protected $signature = 'vip:expiry-check';
    protected $description = 'Check user VIP expiry and recalculate or downgrade expired users.';

    public function __construct(protected VipCalculationService $vipCalculationService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $expiredUsers = User::whereNotNull('vip_expiry_date')
            ->where('vip_expiry_date', '<=', Carbon::now())
            ->cursor();

        foreach ($expiredUsers as $user) {
            $this->vipCalculationService->recalculateForUser($user, 'system', 'Scheduled VIP expiry check');
        }

        $this->info('Processed VIP expiry checks.');

        return self::SUCCESS;
    }
}
