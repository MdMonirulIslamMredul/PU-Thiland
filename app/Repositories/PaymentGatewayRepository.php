<?php

namespace App\Repositories;

use App\Models\PaymentGateway;
use Illuminate\Support\Collection;

class PaymentGatewayRepository
{
    public function all(): Collection
    {
        return PaymentGateway::orderBy('mfs_name')->get();
    }

    public function active(): Collection
    {
        return PaymentGateway::where('is_active', true)->orderBy('mfs_name')->get();
    }

    public function find(int $id): ?PaymentGateway
    {
        return PaymentGateway::find($id);
    }

    public function create(array $data): PaymentGateway
    {
        return PaymentGateway::create($data);
    }

    public function update(PaymentGateway $gateway, array $data): PaymentGateway
    {
        $gateway->update($data);

        return $gateway;
    }

    public function delete(PaymentGateway $gateway): void
    {
        $gateway->delete();
    }
}
