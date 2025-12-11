# Quick Reference Guide - Interview Q&A

## 📋 Common Interview Questions & Answers

---

## General Architecture

### Q: What architecture pattern did you use?
**A:** MVC (Model-View-Controller) pattern with Laravel on the backend and React on the frontend, connected via Inertia.js for a seamless SPA experience.

### Q: What's your tech stack?
**A:** 
- Backend: Laravel 11, PHP 8.2+
- Frontend: React 18 with TypeScript
- Styling: Tailwind CSS
- Build Tool: Vite
- Database: MySQL/SQLite
- Queue: Database driver
- Email: SMTP (Mailtrap for testing)

---

## Database & Models

### Q: How many database tables do you have?
**A:** 5 main tables: `users`, `products`, `cart_items`, `orders`, `order_items`, plus system tables like `jobs` and `failed_jobs`.

### Q: How did you handle the relationship between users and shopping carts?
**A:** One-to-many relationship. User has many CartItems, each CartItem belongs to User and Product. The cart is stored in the database (not session/localStorage) so it persists across devices.

### Q: Where is the cart data stored?
**A:** In the `cart_items` table in the database. Each cart item has `user_id`, `product_id`, and `quantity`. This is user-specific and database-driven, not session-based.

### Q: How do you prevent the same product from being added twice?
**A:** In `CartController@store`, I check if a cart item already exists for that user and product. If yes, I increment the quantity; if no, I create a new cart item.

**File:** `app/Http/Controllers/CartController.php` (lines 50-75)

### Q: How do you store historical pricing in orders?
**A:** The `order_items` table has a `price` column that stores the product price at the time of purchase, not a reference to the product's current price.

**File:** `app/Models/OrderItem.php`

---

## Shopping Cart Features

### Q: Where is the logic for adding items to cart?
**A:** `CartController@store` method handles adding products to cart with stock validation and duplicate checking.

**File:** `app/Http/Controllers/CartController.php`

### Q: How do you handle stock validation?
**A:** Before adding to cart or during checkout, I check if `product->stock_quantity >= requested_quantity`. If not, I throw a validation exception.

**Files:** 
- `app/Http/Controllers/CartController.php` (add/update)
- `app/Http/Controllers/CheckoutController.php` (checkout)

### Q: What happens during checkout?
**A:** 
1. Wrap everything in a database transaction
2. Calculate total
3. Create an Order
4. For each cart item:
   - Verify stock
   - Create OrderItem (with price snapshot)
   - Decrement product stock
   - Check if stock is low → dispatch job if yes
   - Delete cart item
5. Redirect to confirmation

**File:** `app/Http/Controllers/CheckoutController.php@store`

---

## Low Stock Notifications

### Q: When does a low stock email get sent?
**A:** When a customer completes checkout and the product's stock quantity drops to or below the `low_stock_threshold`.

### Q: Where do you check if stock is low?
**A:** In `CheckoutController@store`, after decrementing stock, I call `$product->isLowStock()` which returns true if `stock_quantity <= low_stock_threshold`.

**File:** `app/Http/Controllers/CheckoutController.php` (line 69)

### Q: How does the low stock notification work?
**A:** 
1. After checkout, if stock is low, I dispatch `LowStockNotification` job to the queue
2. Queue worker picks up the job
3. Job sends email to admin using `LowStockMail` mailable

**Files:**
- Trigger: `app/Http/Controllers/CheckoutController.php`
- Job: `app/Jobs/LowStockNotification.php`
- Email: `app/Mail/LowStockMail.php`
- Template: `resources/views/emails/low-stock.blade.php`

### Q: What queue driver do you use?
**A:** Database queue. Jobs are stored in the `jobs` table and processed by `php artisan queue:work`.

### Q: How do you run the queue worker?
**A:** `php artisan queue:work` in the terminal. In production, it runs as a background service using NSSM or Task Scheduler.

---

## Daily Sales Report

### Q: When does the daily sales report run?
**A:** Every day at 6:00 PM automatically via Laravel's task scheduler.

### Q: Where is the schedule configured?
**A:** In `routes/console.php` using `Schedule::command('report:daily-sales')->dailyAt('18:00')`

**File:** `routes/console.php` (lines 9-16)

### Q: How does the scheduler know when to run?
**A:** A cron job (or Windows Task Scheduler) runs `php artisan schedule:run` every minute. Laravel checks if any scheduled tasks are due and executes them.

### Q: What command generates the sales report?
**A:** `SendDailySalesReport` artisan command (`php artisan report:daily-sales`)

**File:** `app/Console/Commands/SendDailySalesReport.php`

### Q: How do you aggregate the sales data?
**A:** 
1. Fetch all OrderItems created today
2. Group by product_id
3. Sum quantities and calculate revenue per product
4. Calculate total revenue and items sold
5. Send email with the data

**File:** `app/Console/Commands/SendDailySalesReport.php` (lines 30-48)

### Q: What information is in the sales report?
**A:** Date, total items sold, total revenue, and a breakdown by product showing quantity sold and revenue per product.

**File:** `resources/views/emails/daily-sales-report.blade.php`

---

## Product Management

### Q: How do admins add new products?
**A:** Through the admin panel at `/admin/products`. There's a "Manage Products" link in the navigation bar.

### Q: Where is the product creation logic?
**A:** `ProductManagementController@store` handles creating new products with validation.

**File:** `app/Http/Controllers/ProductManagementController.php`

### Q: What are the product fields?
**A:** name, description, price, stock_quantity, low_stock_threshold

### Q: Can you delete products?
**A:** Yes, but only if they don't have existing orders. The controller checks `$product->orderItems()->exists()` before deletion.

**File:** `app/Http/Controllers/ProductManagementController.php@destroy`

---

## Frontend (React)

### Q: How does the frontend communicate with the backend?
**A:** Using Inertia.js router. It makes AJAX requests and receives data without full page reloads, creating a SPA experience.

### Q: Where is the product listing page?
**A:** `resources/js/Pages/Products/Index.tsx`

### Q: Where is the shopping cart page?
**A:** `resources/js/Pages/Cart/Index.tsx`

### Q: How do you handle form submissions?
**A:** Using Inertia's `useForm` hook for form state management and `router.post/patch/delete` for submissions.

**Example:** `resources/js/Pages/Admin/Products/Create.tsx`

### Q: How do you prevent users from adding out-of-stock items?
**A:** The "Add to Cart" button is disabled if `product.stock_quantity === 0`

**File:** `resources/js/Pages/Products/Index.tsx` (line 105)

---

## Email System

### Q: What email service do you use?
**A:** Mailtrap for development/testing. In production, you'd use SendGrid, Mailgun, or SES.

### Q: Where is the email configuration?
**A:** 
- Config: `config/mail.php`
- Environment: `.env` file (MAIL_HOST, MAIL_PORT, etc.)

### Q: What emails does the system send?
**A:** 
1. Low Stock Alert - when product stock is low
2. Daily Sales Report - sent at 6 PM daily

### Q: Where are the email templates?
**A:** `resources/views/emails/`
- `low-stock.blade.php`
- `daily-sales-report.blade.php`

---

## Routes

### Q: What are the main routes?
**A:**
- `GET /products` - Browse products
- `GET /cart` - View cart
- `POST /cart` - Add to cart
- `PATCH /cart/{id}` - Update cart item
- `DELETE /cart/{id}` - Remove from cart
- `POST /checkout` - Process order
- `GET /admin/products` - Manage products (admin)

**File:** `routes/web.php`

### Q: How do you protect authenticated routes?
**A:** Using the `auth` and `verified` middleware in the route group.

**File:** `routes/web.php` (line 22)

---

## Security & Best Practices

### Q: How do you prevent SQL injection?
**A:** Using Eloquent ORM and parameter binding. Never concatenating user input into queries.

### Q: How do you handle CSRF protection?
**A:** Laravel's built-in CSRF middleware validates tokens on all state-changing requests (POST, PATCH, DELETE).

### Q: How do you ensure users can only modify their own cart?
**A:** In `CartController`, I check `if ($cartItem->user_id !== auth()->id())` before allowing updates or deletions.

**File:** `app/Http/Controllers/CartController.php` (lines 102, 124)

### Q: How do you prevent race conditions during checkout?
**A:** Using database transactions (`DB::transaction()`) to ensure all operations are atomic. If any step fails, everything rolls back.

**File:** `app/Http/Controllers/CheckoutController.php` (line 31)

---

## Testing & Development

### Q: How do you test the low stock notification manually?
**A:** Run `php artisan test:low-stock` then `php artisan queue:work --once`

**File:** `app/Console/Commands/TestLowStockNotification.php`

### Q: How do you test the daily sales report?
**A:** Run `php artisan report:daily-sales` manually

### Q: How do you check pending queue jobs?
**A:** Check the `jobs` table in the database or run `php artisan queue:monitor`

### Q: How do you see failed jobs?
**A:** Run `php artisan queue:failed`

---

## Deployment & Production

### Q: How do you run the queue worker in production?
**A:** As a background service using:
- **Windows:** NSSM (Non-Sucking Service Manager)
- **Linux:** Supervisor

**Documentation:** `WINDOWS_PRODUCTION_SETUP.md`

### Q: How do you activate the scheduler in production?
**A:** Set up a cron job (Linux) or Task Scheduler (Windows) to run `php artisan schedule:run` every minute.

### Q: What needs to be running for the app to work fully?
**A:**
1. Web server (Laravel)
2. Queue worker (for low stock emails)
3. Scheduler cron (for daily reports)
4. Frontend build (Vite in dev, compiled assets in production)

---

## Quick File Reference

**Controllers:**
- `app/Http/Controllers/ProductController.php` - Product listing
- `app/Http/Controllers/CartController.php` - Shopping cart CRUD
- `app/Http/Controllers/CheckoutController.php` - Order processing
- `app/Http/Controllers/ProductManagementController.php` - Admin product management

**Models:**
- `app/Models/User.php`
- `app/Models/Product.php`
- `app/Models/CartItem.php`
- `app/Models/Order.php`
- `app/Models/OrderItem.php`

**Jobs:**
- `app/Jobs/LowStockNotification.php` - Low stock email job

**Commands:**
- `app/Console/Commands/SendDailySalesReport.php` - Daily sales report
- `app/Console/Commands/TestLowStockNotification.php` - Test low stock

**Mail:**
- `app/Mail/LowStockMail.php`
- `app/Mail/DailySalesReportMail.php`

**Frontend Pages:**
- `resources/js/Pages/Products/Index.tsx` - Product listing
- `resources/js/Pages/Cart/Index.tsx` - Shopping cart
- `resources/js/Pages/Admin/Products/Index.tsx` - Admin product list
- `resources/js/Pages/Admin/Products/Create.tsx` - Create product
- `resources/js/Pages/Admin/Products/Edit.tsx` - Edit product

**Routes:**
- `routes/web.php` - Web routes
- `routes/console.php` - Scheduler configuration

**Config:**
- `config/mail.php` - Email configuration
- `config/queue.php` - Queue configuration

---

**Last Updated**: December 11, 2025
