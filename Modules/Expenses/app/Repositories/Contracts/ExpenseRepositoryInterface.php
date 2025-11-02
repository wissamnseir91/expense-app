<?php


namespace Modules\Expenses\app\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Expenses\app\Models\Expense;
use Illuminate\Pagination\Paginator;

interface ExpenseRepositoryInterface
{
    public function create(array $data): Expense;

    public function findById(string $id): ?Expense;

    public function all(int $perPage = 15, ?string $category = null, ?string $startDate = null, ?string $endDate = null): LengthAwarePaginator;

    public function update(Expense $expense, array $data): Expense;

    public function delete(Expense $expense): bool;
}
