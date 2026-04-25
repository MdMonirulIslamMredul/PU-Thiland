<?php

namespace App\Repositories;

use App\Models\VipAudit;

class VipAuditRepository
{
    public function create(array $data): VipAudit
    {
        return VipAudit::create($data);
    }
}
