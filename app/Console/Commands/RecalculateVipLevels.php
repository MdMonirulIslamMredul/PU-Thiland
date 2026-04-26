<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\VipCalculationService;
use Illuminate\Console\Command;

class RecalculateVipLevels extends Command
{
    protected $signature = 'vip:recalculate';
    protected $description = 'Recalculate VIP levels for all users based on the latest rules and activity.';

    public function __construct(protected VipCalculationService $vipCalculationService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        User::cursor()->each(function (User $user) {
            $this->vipCalculationService->recalculateForUser($user, 'system', 'Scheduled monthly VIP recalculation');
        });

        $this->info('VIP levels recalculated for all users.');

        return self::SUCCESS;
    }
}
