<?php

namespace App\Services;

use App\Models\PaymentGateway;
use App\Repositories\PaymentGatewayRepository;
use Illuminate\Support\Collection;

class PaymentGatewayService
{
    public function __construct(protected PaymentGatewayRepository $repository) {}

    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    public function getActive(): Collection
    {
        return $this->repository->active();
    }

    public function find(int $id): ?PaymentGateway
    {
        return $this->repository->find($id);
    }

    public function create(array $data): PaymentGateway
    {
        return $this->repository->create($data);
    }

    public function update(PaymentGateway $gateway, array $data): PaymentGateway
    {
        return $this->repository->update($gateway, $data);
    }

    public function delete(PaymentGateway $gateway): void
    {
        $this->repository->delete($gateway);
    }
}
