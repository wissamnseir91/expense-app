## 🚀 Setup Instructions

### Installation Steps

1. **Clone the repository**

    ```bash
    git clone https://github.com/wissamnseir91/expense-app
    cd expense-app
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Set up environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Configure database**

    Update your `.env` file with MySQL credentials:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=expense_app
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

5. **Create database**

    Create the MySQL database:

    ```bash
    mysql -u your_username -p -e "CREATE DATABASE expense_app;"
    ```

6. **Run migrations**

    ```bash
    php artisan migrate
    ```

7. **Start the development server**
    ```bash
    php artisan serve
    ```

## 📁 Project Structure

```
expense-app/
├── Modules/
│   └── Expenses/                    # Expense Management Module
│       ├── app/
│       │   ├── Controllers/         # API Controllers
│       │   ├── DTOs/               # Data Transfer Objects
│       │   ├── Enums/              # Category Enum
│       │   ├── Events/              # Domain Events
│       │   ├── Listeners/           # Event Listeners
│       │   ├── Models/              # Eloquent Models
│       │   ├── Providers/           # Service Providers
│       │   ├── Repositories/        # Data Access Layer
│       │   │   └── Contracts/      # Repository Interfaces
│       │   ├── Requests/            # Form Request Validation
│       │   ├── Resources/           # API Resources
│       │   └── Services/            # Business Logic Layer
│       ├── database/
│       │   └── migrations/          # Database Migrations
│       ├── routes/
│       │   └── api.php              # Module Routes
│       └── tests/
│           ├── Feature/             # Feature Tests
│           └── Unit/                # Unit Tests
├── app/                             # Core Laravel Application
├── config/                          # Configuration Files
├── database/                        # Core Database Migrations
├── routes/                          # Core Routes
└── tests/                           # Core Tests
```

## 🏗️ Architecture Decisions

### 1. **Modular Architecture**

The application follows a modular structure with the Expenses functionality encapsulated in its own module (`Modules/Expenses/`). This approach:

-   **Separation of Concerns**: Each module is self-contained with its own controllers, models, routes, and tests
-   **Scalability**: Easy to add new modules (e.g., Users, Reports) without polluting the main app directory
-   **Maintainability**: Clear boundaries make it easier to understand and modify code

### 2. **Layered Architecture (Service-Repository Pattern)**

```
Controller → Service → Repository → Model → Database
```

-   **Controllers**: Handle HTTP requests/responses, delegate to services
-   **Services**: Contain business logic and orchestration
-   **Repositories**: Handle data access, abstract database operations
-   **Models**: Represent database entities with Eloquent

**Benefits:**

-   **Testability**: Easy to mock repositories for unit testing
-   **Flexibility**: Can swap data sources without changing business logic
-   **Single Responsibility**: Each layer has a clear purpose

### 3. **DTOs (Data Transfer Objects)**

DTOs (`CreateExpenseDTO`, `UpdateExpenseDTO`) are used to:

-   **Type Safety**: Ensure correct data structure throughout the application
-   **Validation**: Centralize data validation rules
-   **Clarity**: Make data transformations explicit

### 4. **Event-Driven Architecture**

Events and Listeners are implemented for:

-   **Decoupling**: Business logic (expense creation) is decoupled from side effects (logging)
-   **Extensibility**: Easy to add new listeners (email notifications, analytics) without modifying core code
-   **Testability**: Events can be faked in tests

Current implementation:

-   `ExpenseCreated` event dispatched when an expense is created
-   `LogExpenseNotification` listener logs the expense creation

### 5. **Enum for Categories**

Using PHP 8.1+ enums (`CategoryEnum`) for:

-   **Type Safety**: Prevents invalid category values
-   **Maintainability**: Easy to add/modify categories
-   **Self-Documenting**: Enum cases clearly show available options

### 6. **Form Request Validation**

Laravel Form Requests (`StoreExpenseRequest`, `UpdateExpenseRequest`) for:

-   **Validation Logic**: Centralized validation rules
-   **Reusability**: Can be reused across different endpoints
-   **Automatic Error Responses**: Laravel handles validation errors automatically

### 7. **API Resources**

`ExpenseResource` transforms model data for API responses:

-   **Consistent Format**: Ensures all endpoints return data in the same structure
-   **Data Transformation**: Handles enum to array conversion, date formatting
-   **Future-Proof**: Easy to modify response structure without changing models

### 8. **UUID Primary Keys**

Expenses use UUIDs instead of auto-incrementing integers:

-   **Security**: UUIDs don't reveal business metrics (e.g., number of expenses)
-   **Distributed Systems**: UUIDs can be generated independently across servers
-   **Best Practice**: Modern applications prefer UUIDs for public-facing IDs

## 🔌 API Endpoints

All endpoints are prefixed with `/api/expenses`

| Method    | Endpoint             | Description                                     | Auth Required |
| --------- | -------------------- | ----------------------------------------------- | ------------- |
| GET       | `/api/expenses`      | List all expenses (with pagination and filters) | No            |
| POST      | `/api/expenses`      | Create a new expense                            | No            |
| GET       | `/api/expenses/{id}` | Get a specific expense                          | No            |
| PUT/PATCH | `/api/expenses/{id}` | Update an expense                               | No            |
| DELETE    | `/api/expenses/{id}` | Delete an expense                               | No            |

### Query Parameters

**GET `/api/expenses`** supports:

-   `per_page` (default: 15): Number of items per page
-   `category`: Filter by category (e.g., `food`, `transport`)
-   `start_date`: Start date for date range filter (YYYY-MM-DD)
-   `end_date`: End date for date range filter (YYYY-MM-DD)

**Example:**

```
GET /api/expenses?category=food&start_date=2025-01-01&end_date=2025-01-31&per_page=10
```

### Request/Response Examples

**Create Expense:**

```json
POST /api/expenses
{
  "title": "Lunch",
  "amount": 25.50,
  "category": "food",
  "expense_date": "2025-01-15",
  "notes": "Team lunch meeting"
}

Response (201):
{
  "data": {
    "id": "uuid-here",
    "title": "Lunch",
    "amount": "25.50",
    "category": {
      "value": "food",
      "label": "Food & Dining"
    },
    "expense_date": "2025-01-15",
    "notes": "Team lunch meeting",
    "created_at": "2025-01-15T12:00:00Z",
    "updated_at": "2025-01-15T12:00:00Z"
  },
  "message": "Expense created successfully"
}
```

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

```

## 🎯 Assumptions

1. **No Authentication Required**: The API is open and doesn't require authentication.

2. **MySQL Database**: The project uses MySQL for the main application.

3. **Category Enum**: Categories are hardcoded as an enum.

4. **Synchronous Event Processing**: Event listeners run synchronously.

## ⏱️ Time Spent

### Development Breakdown

-   **Project Setup & Configuration**: ~1 hour

    -   Laravel installation
    -   Module structure setup
    -   Database configuration

-   **Core Development**: ~4-5 hours

    -   Model and migration creation
    -   Repository pattern implementation
    -   Service layer development
    -   Controller implementation
    -   DTO creation
    -   Form Request validation
    -   API Resource transformation
    -   Enum implementation

-   **Event System**: ~1 hour

    -   Event creation
    -   Listener implementation
    -   Service provider registration

-   **API Endpoints**: ~2 hours

    -   CRUD operations
    -   Filtering logic
    -   Pagination
    -   Error handling
    -   Response formatting

-   **Testing**: ~2-3 hours

    -   Feature test creation
    -   Test configuration
    -   Debugging test issues

-   **Documentation**: ~1 hour
    -   README creation
    -   Code comments
    -   Architecture decisions documentation

**Total Estimated Time: ~11-13 hours**