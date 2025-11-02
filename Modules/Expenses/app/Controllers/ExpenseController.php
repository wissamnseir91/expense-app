<?php


namespace Modules\Expenses\app\Controllers;

use Modules\Expenses\app\DTOs\CreateExpenseDTO;
use Modules\Expenses\app\DTOs\UpdateExpenseDTO;
use Modules\Expenses\app\Requests\StoreExpenseRequest;
use Modules\Expenses\app\Requests\UpdateExpenseRequest;
use Modules\Expenses\app\Resources\ExpenseResource;
use Modules\Expenses\app\Services\ExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ExpenseController extends Controller
{
    public function __construct(
        private readonly ExpenseService $expenseService
    ) {}

    /**
     * @response 200 {
     *   "data": [
     *     {
     *       "id": "uuid",
     *       "title": "Lunch",
     *       "amount": 15.50,
     *       "category": {"value": "food", "label": "Food & Dining"},
     *       "expense_date": "2025-01-01",
     *       "notes": null,
     *       "created_at": "2025-01-01T12:00:00Z",
     *       "updated_at": "2025-01-01T12:00:00Z"
     *     }
     *   ],
     *   "meta": {
     *     "total": 10,
     *     "per_page": 15,
     *     "current_page": 1,
     *     "last_page": 1
     *   }
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $category = $request->query('category');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $expenses = $this->expenseService->getAllExpenses($perPage, $category, $startDate, $endDate);

        $meta = [
            'total' => $expenses->total(),
            'per_page' => $expenses->perPage(),
            'current_page' => $expenses->currentPage(),
            'last_page' => $expenses->lastPage(),
        ];

        // Add filter information to meta if filters are applied
        if ($category) {
            $meta['category'] = $category;
        }
        if ($startDate && $endDate) {
            $meta['start_date'] = $startDate;
            $meta['end_date'] = $endDate;
        }

        return response()->json([
            'data' => ExpenseResource::collection($expenses->items()),
            'meta' => $meta,
        ], Response::HTTP_OK);
    }

    /**
     * @response 201 {
     *   "data": {
     *     "id": "uuid",
     *     "title": "Lunch",
     *     "amount": 15.50,
     *     "category": {"value": "food", "label": "Food & Dining"},
     *     "expense_date": "2025-01-01",
     *     "notes": "Had lunch with team",
     *     "created_at": "2025-01-01T12:00:00Z",
     *     "updated_at": "2025-01-01T12:00:00Z"
     *   },
     *   "message": "Expense created successfully"
     * }
     */
    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $dto = CreateExpenseDTO::fromArray($request->validated());
        $expense = $this->expenseService->createExpense($dto);

        return response()->json([
            'data' => new ExpenseResource($expense),
            'message' => 'Expense created successfully',
        ], Response::HTTP_CREATED);
    }

    /**
     * @response 200 {
     *   "data": {
     *     "id": "uuid",
     *     "title": "Lunch",
     *     "amount": 15.50,
     *     "category": {"value": "food", "label": "Food & Dining"},
     *     "expense_date": "2025-01-01",
     *     "notes": "Had lunch with team",
     *     "created_at": "2025-01-01T12:00:00Z",
     *     "updated_at": "2025-01-01T12:00:00Z"
     *   }
     * }
     */
    public function show(string $id): JsonResponse
    {
        $expense = $this->expenseService->getExpense($id);

        if (!$expense) {
            return response()->json([
                'message' => 'Expense not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => new ExpenseResource($expense),
        ], Response::HTTP_OK);
    }

    /**
     * @response 200 {
     *   "data": {
     *     "id": "uuid",
     *     "title": "Lunch",
     *     "amount": 20.00,
     *     "category": {"value": "food", "label": "Food & Dining"},
     *     "expense_date": "2025-01-01",
     *     "notes": "Updated notes",
     *     "created_at": "2025-01-01T12:00:00Z",
     *     "updated_at": "2025-01-01T12:30:00Z"
     *   },
     *   "message": "Expense updated successfully"
     * }
     */
    public function update(string $id, UpdateExpenseRequest $request): JsonResponse
    {
        $dto = UpdateExpenseDTO::fromArray($request->validated());
        $expense = $this->expenseService->updateExpense($id, $dto);

        if (!$expense) {
            return response()->json([
                'message' => 'Expense not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => new ExpenseResource($expense),
            'message' => 'Expense updated successfully',
        ], Response::HTTP_OK);
    }

    /**
     * @response 204
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->expenseService->deleteExpense($id);

        if (!$deleted) {
            return response()->json([
                'message' => 'Expense not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(
            ['message' => 'Expense deleted successfully'],
            Response::HTTP_OK
        );
    }

}
