<?php

namespace App\Services;

use App\Models\VipAudit;
use App\Models\User;
use App\Repositories\VipAuditRepository;

class VipAuditService
{
    public function __construct(protected VipAuditRepository $repository) {}

    public function logChange(User $user, ?string $oldLevel, ?string $newLevel, string $changedBy = 'system', string $reason = ''): VipAudit
    {
        return $this->repository->create([
            'user_id' => $user->id,
            'old_level' => $oldLevel,
            'new_level' => $newLevel,
            'changed_by' => $changedBy,
            'reason' => $reason,
        ]);
    }
}
