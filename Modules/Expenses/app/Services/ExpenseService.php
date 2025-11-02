<?php


namespace Modules\Expenses\app\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Expenses\app\DTOs\CreateExpenseDTO;
use Modules\Expenses\app\DTOs\UpdateExpenseDTO;
use Modules\Expenses\app\Events\ExpenseCreated;
use Modules\Expenses\app\Models\Expense;
use Modules\Expenses\app\Repositories\Contracts\ExpenseRepositoryInterface;
use Illuminate\Pagination\Paginator;

class ExpenseService
{
    public function __construct(
        private readonly ExpenseRepositoryInterface $repository
    ) {}

    public function createExpense(CreateExpenseDTO $dto): Expense
    {
        $expense = $this->repository->create($dto->toArray());

        ExpenseCreated::dispatch($expense);

        return $expense;
    }

    public function getExpense(string $id): ?Expense
    {
        return $this->repository->findById($id);
    }

    public function getAllExpenses(int $perPage = 15, ?string $category = null, ?string $startDate = null, ?string $endDate = null): LengthAwarePaginator
    {
        return $this->repository->all($perPage, $category, $startDate, $endDate);
    }

    public function updateExpense(string $id, UpdateExpenseDTO $dto): ?Expense
    {
        $expense = $this->getExpense($id);

        if (!$expense) {
            return null;
        }

        $updatedExpense = $this->repository->update($expense, $dto->toArray());

        return $updatedExpense;
    }

    public function deleteExpense(string $id): bool
    {
        $expense = $this->getExpense($id);

        if (!$expense) {
            return false;
        }

        $deleted = $this->repository->delete($expense);


        return $deleted;
    }
}
