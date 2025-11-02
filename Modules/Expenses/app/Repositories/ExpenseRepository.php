<?php


namespace Modules\Expenses\app\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Expenses\app\Models\Expense;
use Modules\Expenses\app\Repositories\Contracts\ExpenseRepositoryInterface;
use Illuminate\Pagination\Paginator;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    public function create(array $data): Expense
    {
        return Expense::create($data);
    }

    public function findById(string $id): ?Expense
    {
        return Expense::find($id);
    }

    public function all(int $perPage = 15, ?string $category = null, ?string $startDate = null, ?string $endDate = null): LengthAwarePaginator
    {
        $query = Expense::query();

        if ($category) {
            $query->where('category', $category);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('expense_date', [$startDate, $endDate]);
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(Expense $expense, array $data): Expense
    {
        $expense->update($data);
        return $expense->refresh();
    }

    public function delete(Expense $expense): bool
    {
        return $expense->delete();
    }
}
