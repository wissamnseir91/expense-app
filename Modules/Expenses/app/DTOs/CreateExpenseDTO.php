<?php

namespace Modules\Expenses\app\DTOs;

use Modules\Expenses\app\Enums\CategoryEnum;

readonly class CreateExpenseDTO
{
    public function __construct(
        public string $title,
        public float $amount,
        public CategoryEnum $category,
        public string $expense_date,
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            amount: (float) $data['amount'],
            category: CategoryEnum::from($data['category']),
            expense_date: $data['expense_date'],
            notes: $data['notes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'amount' => $this->amount,
            'category' => $this->category->value,
            'expense_date' => $this->expense_date,
            'notes' => $this->notes,
        ];
    }
}
