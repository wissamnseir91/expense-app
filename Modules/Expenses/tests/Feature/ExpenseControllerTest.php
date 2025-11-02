<?php

namespace Modules\Expenses\tests\Feature;

use Modules\Expenses\app\Models\Expense;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpenseControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating an expense via API
     */
    public function test_can_create_expense_via_api(): void
    {
        $payload = [
            'title' => 'Lunch at Restaurant',
            'amount' => 25.50,
            'category' => 'food',
            'expense_date' => '2025-01-15',
            'notes' => 'Team lunch meeting',
        ];

        $response = $this->postJson('/api/expenses', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'amount',
                    'category',
                    'expense_date',
                    'notes',
                    'created_at',
                    'updated_at',
                ],
                'message',
            ])
            ->assertJsonPath('data.title', 'Lunch at Restaurant')
            ->assertJsonPath('data.amount', '25.50')
            ->assertJsonPath('data.category.value', 'food')
            ->assertJsonPath('message', 'Expense created successfully');

        // Verify the expense was saved in the database
        $this->assertDatabaseHas('expenses', [
            'title' => 'Lunch at Restaurant',
            'category' => 'food',
            'notes' => 'Team lunch meeting',
        ]);
        
        // Verify amount and date separately (handle precision/format differences)
        $expense = Expense::where('title', 'Lunch at Restaurant')->first();
        $this->assertNotNull($expense);
        $this->assertEquals('25.50', number_format((float) $expense->amount, 2, '.', ''));
        $this->assertEquals('2025-01-15', $expense->expense_date->format('Y-m-d'));
    }
}

