# E-commerce Shopping Cart System - Documentation

## 📋 Table of Contents
1. [Project Overview](#project-overview)
2. [Tech Stack](#tech-stack)
3. [Features](#features)
4. [Installation & Setup](#installation--setup)
5. [Database Schema](#database-schema)
6. [Application Architecture](#application-architecture)
7. [Key Functionalities](#key-functionalities)
8. [Email Notifications](#email-notifications)
9. [Scheduled Tasks](#scheduled-tasks)
10. [API Endpoints](#api-endpoints)
11. [Testing](#testing)
12. [Deployment](#deployment)

---

## 🎯 Project Overview

A simple e-commerce shopping cart system built with Laravel and React that allows authenticated users to browse products, manage their shopping cart, and complete purchases. The system includes automated low stock notifications and daily sales reporting.

### Requirements Fulfilled
- ✅ Product browsing with name, price, and stock_quantity
- ✅ Shopping cart functionality (add, update, remove items)
- ✅ User-based cart persistence (database-driven, not session/localStorage)
- ✅ Laravel authentication using Breeze starter kit
- ✅ Low stock email notifications via Laravel Jobs/Queues
- ✅ Daily sales report via scheduled cron job
- ✅ React frontend with Tailwind CSS
- ✅ Laravel best practices and conventions

---

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 11.x
- **PHP**: 8.2+
- **Database**: MySQL/PostgreSQL/SQLite
- **Queue**: Database driver (configurable to Redis/RabbitMQ)
- **Email**: Mailtrap (SMTP)

### Frontend
- **Framework**: React 18.x with TypeScript
- **State Management**: Inertia.js
- **Styling**: Tailwind CSS
- **Build Tool**: Vite

### Development Tools
- **Version Control**: Git
- **Package Manager**: Composer (PHP), npm (JavaScript)
- **Code Quality**: Laravel Pint, ESLint

---

## ✨ Features

### User Features
1. **Authentication System**
   - User registration and login
   - Email verification
   - Password reset functionality
   - Profile management

2. **Product Browsing**
   - View all available products
   - See product details (name, description, price, stock quantity)
   - Real-time stock availability

3. **Shopping Cart Management**
   - Add products to cart with quantity selection
   - Update item quantities
   - Remove items from cart
   - View cart total
   - Stock validation before adding/updating

4. **Checkout Process**
   - One-step checkout
   - Automatic stock deduction
   - Order confirmation page
   - Cart clearing after successful checkout

5. **Theme Support**
   - Dark mode toggle
   - Persistent theme preference
   - Fully styled dark/light components

### Admin Features
1. **Role-Based Access Control**
   - Admin users identified by `is_admin` flag in database
   - Protected routes with custom `EnsureUserIsAdmin` middleware
   - Conditional UI elements (admin navigation only visible to admins)
   - Backend authorization preventing unauthorized access

2. **Product Management Dashboard**
   - **Create Products**: Add new products with name, description, price, stock, and threshold
   - **Edit Products**: Update existing product information
   - **Delete Products**: Remove products (protected - cannot delete products with existing orders)
   - **View All Products**: Paginated table view with edit/delete actions
   - Accessible at `/admin/products` route

3. **Automated Email Notifications**
   - **Low Stock Alerts**: Automatic email when product stock falls below threshold
   - Queued job processing for performance
   - Detailed product information in email
   - Triggered during checkout process

4. **Daily Sales Reports**
   - Scheduled report every evening at 6 PM
   - Summary of all products sold that day
   - Total revenue and items sold
   - Product-wise breakdown with quantities

---

## 📦 Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18.x or higher
- npm or yarn
- MySQL/PostgreSQL/SQLite database
- Git

### Step 1: Clone Repository
```bash
git clone <repository-url>
cd E-commerce
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 3: Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Environment Variables
Edit `.env` file with your settings:

```env
# Application
APP_NAME="E-commerce Cart"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database (SQLite - Default)
DB_CONNECTION=sqlite

# OR Database (MySQL - Uncomment to use)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=ecommerce
# DB_USERNAME=root
# DB_PASSWORD=

# Mail Configuration (Mailtrap)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ecommerce.local
MAIL_FROM_NAME="${APP_NAME}"

# Queue Configuration
QUEUE_CONNECTION=database
```

### Step 5: Database Setup
```bash
# Run migrations
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed
```

### Step 6: Create Admin User
```bash
# Using Tinker
php artisan tinker

# Then run:
User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'is_admin' => true,
    'password' => bcrypt('password'),
    'email_verified_at' => now()
]);
```

**Note**: The database seeder automatically creates an admin user. If you ran `php artisan db:seed`, the admin user already exists with:
- Email: `admin@example.com`
- Password: `password`
- Admin access: enabled (`is_admin = true`)
    'password' => bcrypt('password'),
    'email_verified_at' => now()
]);
```

### Step 7: Build Frontend Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### Step 8: Start Queue Worker
```bash
# In a separate terminal
php artisan queue:work
```

### Step 9: Configure Task Scheduler
Add to your crontab (Linux/Mac) or Task Scheduler (Windows):
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Step 10: Start Development Server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 🗄️ Database Schema

### Users Table
```sql
- id (bigint, primary key)
- name (string)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string)
- remember_token (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Products Table
```sql
- id (bigint, primary key)
- name (string)
- description (text)
- price (decimal 10,2)
- stock_quantity (integer)
- low_stock_threshold (integer, default: 10)
- created_at (timestamp)
- updated_at (timestamp)
```

### Cart Items Table
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key -> users.id)
- product_id (bigint, foreign key -> products.id)
- quantity (integer)
- created_at (timestamp)
- updated_at (timestamp)

Indexes:
- unique(user_id, product_id)
```

### Orders Table
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key -> users.id)
- total_amount (decimal 10,2)
- status (string, default: 'pending')
- created_at (timestamp)
- updated_at (timestamp)
```

### Order Items Table
```sql
- id (bigint, primary key)
- order_id (bigint, foreign key -> orders.id)
- product_id (bigint, foreign key -> products.id)
- quantity (integer)
- price (decimal 10,2) -- Stored price at time of order
- created_at (timestamp)
- updated_at (timestamp)
```

### Jobs Table (Queue)
```sql
- id (bigint, primary key)
- queue (string)
- payload (longtext)
- attempts (tinyint)
- reserved_at (integer, nullable)
- available_at (integer)
- created_at (integer)
```

---

## 🏗️ Application Architecture

### Directory Structure
```
app/
├── Console/
│   └── Commands/
│       └── SendDailySalesReport.php    # Daily sales report command
├── Http/
│   ├── Controllers/
│   │   ├── CartController.php          # Shopping cart operations
│   │   ├── CheckoutController.php      # Order processing
│   │   ├── ProductController.php       # Product listing
│   │   └── ProfileController.php       # User profile
│   ├── Middleware/                     # Authentication, CSRF, etc.
│   └── Requests/                       # Form validation
├── Jobs/
│   └── LowStockNotification.php        # Low stock email job
├── Mail/
│   ├── DailySalesReportMail.php        # Daily report email
│   └── LowStockMail.php                # Low stock alert email
├── Models/
│   ├── CartItem.php                    # Shopping cart model
│   ├── Order.php                       # Order model
│   ├── OrderItem.php                   # Order line items
│   ├── Product.php                     # Product model
│   └── User.php                        # User model
└── Providers/
    └── AppServiceProvider.php          # Service bindings

resources/
├── js/
│   ├── Components/                     # Reusable React components
│   ├── Layouts/                        # Page layouts
│   ├── Pages/
│   │   ├── Cart/
│   │   │   └── Index.tsx              # Shopping cart page
│   │   ├── Products/
│   │   │   └── Index.tsx              # Products listing page
│   │   └── Orders/
│   │       └── Confirmation.tsx       # Order confirmation
│   └── app.tsx                         # React app entry
└── views/
    └── emails/
        ├── daily-sales-report.blade.php
        └── low-stock.blade.php

routes/
├── auth.php                            # Authentication routes
├── console.php                         # Console/scheduled routes
└── web.php                             # Web application routes
```

### Design Patterns Used

1. **MVC Pattern**
   - Models: Data and business logic
   - Views: Inertia.js/React components
   - Controllers: Request handling and response

2. **Repository Pattern** (Implicit via Eloquent ORM)
   - Models act as repositories
   - Eloquent provides query abstraction

3. **Job Queue Pattern**
   - Asynchronous email processing
   - Deferred task execution

4. **Service Container**
   - Dependency injection
   - Service binding and resolution

---

## 🔑 Key Functionalities

### 1. Product Management

#### Product Model (`app/Models/Product.php`)
```php
// Key Methods:
- isLowStock(): bool              // Check if stock is below threshold
- decrementStock(int $qty): void  // Reduce stock quantity
- incrementStock(int $qty): void  // Increase stock quantity
- cartItems()                     // Relationship to cart items
- orderItems()                    // Relationship to order items
```

#### Product Controller (`app/Http/Controllers/ProductController.php`)
- **GET /products** - Lists all products with stock information

### 2. Shopping Cart System

#### Cart Model (`app/Models/CartItem.php`)
- User-specific cart items
- Relationship to user and product
- Automatic timestamps

#### Cart Controller (`app/Http/Controllers/CartController.php`)

**GET /cart** - View Shopping Cart
- Retrieves authenticated user's cart items
- Calculates total price
- Returns Inertia response with cart data

**POST /cart** - Add Item to Cart
```php
Request: {
    product_id: integer,
    quantity: integer (min: 1)
}
```
- Validates stock availability
- Merges with existing cart item if present
- Creates new cart item if not exists
- Returns success/error message

**PATCH /cart/{cartItem}** - Update Cart Item Quantity
```php
Request: {
    quantity: integer (min: 1)
}
```
- Authorization check (user owns cart item)
- Stock validation
- Updates quantity

**DELETE /cart/{cartItem}** - Remove Cart Item
- Authorization check
- Soft delete cart item

### 3. Checkout Process

#### Checkout Controller (`app/Http/Controllers/CheckoutController.php`)

**POST /checkout** - Process Order
1. Validates cart is not empty
2. Starts database transaction
3. Calculates order total
4. Creates order record
5. For each cart item:
   - Verifies stock availability
   - Creates order item
   - Decrements product stock
   - Checks if stock is low → dispatches job
   - Deletes cart item
6. Commits transaction
7. Redirects to confirmation page

Error Handling:
- Stock validation errors
- Transaction rollback on failure
- User-friendly error messages

### 4. Order Management

#### Order Model (`app/Models/Order.php`)
- Belongs to user
- Has many order items
- Status tracking

#### Order Item Model (`app/Models/OrderItem.php`)
- Belongs to order and product
- Stores price at time of purchase (price history)

---

## 📧 Email Notifications

### 1. Low Stock Alert Email

#### Trigger
Automatically dispatched when product stock falls below `low_stock_threshold` during checkout.

#### Job: `app/Jobs/LowStockNotification.php`
```php
public function __construct(
    public Product $product
) {}

public function handle(): void
{
    $admin = User::where('email', 'admin@example.com')->first();
    
    if ($admin) {
        Mail::to($admin->email)->send(new LowStockMail($this->product));
    }
}
```

#### Email Template: `resources/views/emails/low-stock.blade.php`
Contains:
- Product name and description
- Current stock quantity (highlighted in red)
- Low stock threshold
- Product price
- Alert styling

#### Flow:
1. User completes checkout
2. Stock is decremented
3. System checks if `stock_quantity <= low_stock_threshold`
4. If true, `LowStockNotification::dispatch($product)`
5. Job queued for processing
6. Queue worker sends email to admin

### 2. Daily Sales Report Email

#### Trigger
Scheduled to run daily at 6:00 PM via Laravel scheduler.

#### Command: `app/Console/Commands/SendDailySalesReport.php`
```php
protected $signature = 'report:daily-sales';
protected $description = 'Send daily sales report to admin';

public function handle(): int
{
    // Get today's order items
    $orderItems = OrderItem::with('product')
        ->whereDate('created_at', today())
        ->get();
    
    // Group by product and calculate totals
    $salesData = $orderItems->groupBy('product_id')->map(...);
    
    // Send email to admin
    Mail::to('admin@example.com')->send(
        new DailySalesReportMail($salesData, $totalRevenue, $totalItemsSold, today())
    );
}
```

#### Email Template: `resources/views/emails/daily-sales-report.blade.php`
Contains:
- Report date
- Total items sold
- Total revenue
- Product-wise breakdown table
- Summary section
- Professional styling

#### Data Aggregation:
```php
$salesData = [
    [
        'name' => 'Product Name',
        'quantity' => 15,      // Total units sold
        'revenue' => 299.85    // Total revenue for this product
    ],
    ...
]
```

---

## ⏰ Scheduled Tasks

### Configuration

#### Schedule Definition
Add to `routes/console.php` or `app/Console/Kernel.php`:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('report:daily-sales')
    ->dailyAt('18:00')  // Run at 6:00 PM
    ->timezone('America/New_York')
    ->onSuccess(function () {
        Log::info('Daily sales report sent successfully');
    })
    ->onFailure(function () {
        Log::error('Failed to send daily sales report');
    });
```

#### Server Configuration

**Linux/Mac (Crontab)**
```bash
* * * * * cd /path/to/ecommerce && php artisan schedule:run >> /dev/null 2>&1
```

**Windows (Task Scheduler)**
1. Open Task Scheduler
2. Create Basic Task
3. Trigger: Daily at midnight
4. Action: Start a program
5. Program: `php`
6. Arguments: `artisan schedule:run`
7. Start in: `C:\path\to\ecommerce`

### Manual Execution
```bash
# Test the daily sales report
php artisan report:daily-sales

# Run all scheduled tasks
php artisan schedule:run

# List all scheduled tasks
php artisan schedule:list
```

### Queue Processing

The low stock notifications use Laravel queues. Ensure queue worker is running:

```bash
# Development
php artisan queue:work

# Production (with supervisor)
php artisan queue:work --sleep=3 --tries=3 --max-time=3600

# Check queue status
php artisan queue:monitor

# Clear failed jobs
php artisan queue:flush
```

---

## 🔌 API Endpoints

### Authentication Routes
```
POST   /register              - User registration
POST   /login                 - User login
POST   /logout                - User logout
POST   /forgot-password       - Send password reset link
POST   /reset-password        - Reset password
GET    /verify-email/{id}     - Verify email
POST   /email/verification    - Resend verification
```

### Product Routes (Authenticated)
```
GET    /products              - List all products
```

### Shopping Cart Routes (Authenticated)
```
GET    /cart                  - View shopping cart
POST   /cart                  - Add item to cart
PATCH  /cart/{cartItem}       - Update cart item quantity
DELETE /cart/{cartItem}       - Remove item from cart
```

### Checkout Routes (Authenticated)
```
POST   /checkout              - Process order
GET    /order/confirmation    - Order confirmation page
```

### Profile Routes (Authenticated)
```
GET    /profile               - View profile
PATCH  /profile               - Update profile
DELETE /profile               - Delete account
```

---

## 🧪 Testing

### Setup Testing Environment
```bash
# Copy test environment
cp .env .env.testing

# Configure testing database
# Edit .env.testing:
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/CartTest.php
```

### Test Examples

#### Feature Test: Cart Functionality
```php
public function test_user_can_add_product_to_cart()
{
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_quantity' => 10]);
    
    $response = $this->actingAs($user)->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('cart_items', [
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);
}

public function test_cannot_add_more_than_available_stock()
{
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_quantity' => 5]);
    
    $response = $this->actingAs($user)->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 10,
    ]);
    
    $response->assertSessionHasErrors('quantity');
}
```

#### Unit Test: Product Model
```php
public function test_product_is_low_stock()
{
    $product = Product::factory()->create([
        'stock_quantity' => 5,
        'low_stock_threshold' => 10,
    ]);
    
    $this->assertTrue($product->isLowStock());
}

public function test_decrement_stock_reduces_quantity()
{
    $product = Product::factory()->create(['stock_quantity' => 100]);
    
    $product->decrementStock(10);
    
    $this->assertEquals(90, $product->fresh()->stock_quantity);
}
```

---

## 🚀 Deployment

### Production Environment Setup

#### 1. Server Requirements
- PHP 8.2+
- MySQL 8.0+ or PostgreSQL 13+
- Redis (recommended for cache/queue)
- Nginx or Apache
- SSL certificate
- Supervisor (for queue workers)

#### 2. Environment Configuration
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=strong-password

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail (Mailtrap or production SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
```

#### 3. Deployment Steps
```bash
# 1. Pull latest code
git pull origin main

# 2. Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# 3. Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Run migrations
php artisan migrate --force

# 5. Restart queue workers
php artisan queue:restart

# 6. Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 4. Supervisor Configuration (Queue Workers)
Create `/etc/supervisor/conf.d/ecommerce-worker.conf`:
```ini
[program:ecommerce-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/ecommerce/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/ecommerce/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start ecommerce-worker:*
```

#### 5. Cron Job Setup
```bash
* * * * * cd /path/to/ecommerce && php artisan schedule:run >> /dev/null 2>&1
```

#### 6. Nginx Configuration
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/ecommerce/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 📝 Additional Notes

### Security Best Practices Implemented
- CSRF protection on all forms
- SQL injection prevention via Eloquent ORM
- XSS protection through React and Blade escaping
- Authentication middleware on protected routes
- Password hashing with bcrypt
- Email verification
- Rate limiting on auth routes

### Performance Optimizations
- Database indexing on foreign keys
- Eager loading relationships to prevent N+1 queries
- Queue jobs for email sending
- Asset compilation and minification
- Route and config caching in production

### Code Quality Standards
- PSR-12 coding standards
- Type hinting
- Meaningful variable and method names
- Comments for complex logic
- Validation on all user inputs
- Database transactions for critical operations

---

## 🆘 Troubleshooting

### Common Issues

**Queue jobs not processing**
```bash
# Check if queue worker is running
ps aux | grep queue:work

# Start queue worker
php artisan queue:work
```

**Emails not sending**
```bash
# Check mail configuration
php artisan config:clear
php artisan tinker
>>> config('mail.mailers.smtp')

# Test email
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('admin@example.com'); });
```

**Schedule not running**
```bash
# Check cron setup
crontab -l

# Test schedule manually
php artisan schedule:run

# Check schedule list
php artisan schedule:list
```

**Permission errors**
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Inertia.js Documentation](https://inertiajs.com)
- [React Documentation](https://react.dev)
- [Tailwind CSS Documentation](https://tailwindcss.com)
- [Laravel Queues](https://laravel.com/docs/queues)
- [Laravel Task Scheduling](https://laravel.com/docs/scheduling)

---

## 👨‍💻 Development Team

This project was developed as a technical interview assignment to demonstrate proficiency in:
- Laravel framework and best practices
- React with TypeScript
- Database design and relationships
- Queue systems and background jobs
- Task scheduling
- Email systems
- Authentication and authorization
- Code organization and documentation

---

**Last Updated**: December 11, 2025
**Version**: 1.0.0
