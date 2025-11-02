<?php

namespace Modules\Expenses\app\DTOs;

use Modules\Expenses\app\Enums\CategoryEnum;

readonly class UpdateExpenseDTO
{
    public function __construct(
        public ?string $title = null,
        public ?float $amount = null,
        public ?CategoryEnum $category = null,
        public ?string $expense_date = null,
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            amount: isset($data['amount']) ? (float) $data['amount'] : null,
            category: isset($data['category']) ? CategoryEnum::from($data['category']) : null,
            expense_date: $data['expense_date'] ?? null,
            notes: $data['notes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'amount' => $this->amount,
            'category' => $this->category?->value,
            'expense_date' => $this->expense_date,
            'notes' => $this->notes,
        ], fn($value) => $value !== null);
    }
}
