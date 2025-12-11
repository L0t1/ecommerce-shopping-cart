# 🛒 E-commerce Shopping Cart System

A full-featured e-commerce shopping cart application built with Laravel 11, React 18, TypeScript, and Inertia.js. Features include user authentication, product management, shopping cart functionality, automated email notifications, and an admin panel with role-based access control.

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![React](https://img.shields.io/badge/React-18.x-blue.svg)](https://reactjs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.x-blue.svg)](https://www.typescriptlang.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC.svg)](https://tailwindcss.com)

---

## ✨ Features

### 🛍️ Customer Features
- **Product Browsing** - View all available products with real-time stock information
- **Shopping Cart** - Add, update, and remove items with quantity management
- **Checkout** - Seamless one-step checkout process with order confirmation
- **Authentication** - Secure user registration, login, email verification, and password reset
- **Profile Management** - Update user information and change password
- **Dark Mode** - Toggle between light and dark themes
- **Responsive Design** - Fully optimized for desktop, tablet, and mobile devices

### 👨‍💼 Admin Features
- **Product Management** - Full CRUD operations (Create, Read, Update, Delete)
- **Role-Based Access** - Admin-only routes protected with middleware
- **Low Stock Alerts** - Automated email notifications when inventory runs low
- **Daily Sales Reports** - Scheduled email reports with sales summaries
- **Order Protection** - Prevents deletion of products with existing orders

### 🔧 Technical Features
- **Queue System** - Background job processing for email notifications
- **Task Scheduler** - Automated daily reports at 6 PM
- **Type Safety** - Full TypeScript implementation on frontend
- **SPA Experience** - Smooth navigation without page reloads using Inertia.js
- **Database-Driven Cart** - Persistent cart data (not session-based)
- **Email Testing** - Mailtrap integration for development

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18.x or higher
- MySQL/PostgreSQL/SQLite database

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/L0t1/ecommerce-shopping-cart.git
cd ecommerce-shopping-cart
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Set up environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database** (Edit `.env` file)
```env
DB_CONNECTION=mysql
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Configure email** (Edit `.env` file for Mailtrap)
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ecommerce.local
MAIL_FROM_NAME="E-commerce Admin"
```

6. **Run migrations and seed database**
```bash
php artisan migrate --seed
```

7. **Build frontend assets**
```bash
npm run build
```

8. **Start development servers**
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev

# Terminal 3: Queue worker (for emails)
php artisan queue:work
```

9. **Access the application**
- Open browser: http://localhost:8000
- Admin login: `admin@example.com` / `password`
- User login: `test@example.com` / `password`

---

## 📚 Documentation

- **[DOCUMENTATION.md](DOCUMENTATION.md)** - Complete technical documentation
- **[SETUP_CHECKLIST.md](SETUP_CHECKLIST.md)** - Quick setup verification steps
- **[MAILTRAP_SETUP.md](MAILTRAP_SETUP.md)** - Email configuration guide

---

## 🛠️ Tech Stack

**Backend:**
- Laravel 11.x (PHP 8.2+)
- MySQL/PostgreSQL/SQLite
- Laravel Breeze (Authentication)
- Queue System (Database driver)
- Task Scheduler

**Frontend:**
- React 18.x
- TypeScript 5.x
- Inertia.js
- Tailwind CSS 3.x
- Vite

**DevOps:**
- Git version control
- Composer (PHP dependencies)
- npm (JavaScript dependencies)
- Mailtrap (Email testing)

---

## 📋 Key Commands

### Development
```bash
# Start development server
php artisan serve

# Watch and compile frontend assets
npm run dev

# Process queue jobs
php artisan queue:work

# Run task scheduler (testing)
php artisan schedule:run
```

### Database
```bash
# Run migrations
php artisan migrate

# Refresh database with seed data
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_table_name
```

### Testing Email Notifications
```bash
# Test low stock notification
php artisan test:low-stock
php artisan queue:work --once

# Test daily sales report
php artisan report:daily-sales
```

### Production
```bash
# Build frontend for production
npm run build

# Optimize Laravel
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🗄️ Database Schema

### Tables
- **users** - User accounts with admin flag
- **products** - Product catalog with stock management
- **cart_items** - User shopping cart items
- **orders** - Completed order records
- **order_items** - Order line items
- **jobs** - Queue system
- **cache** - Application cache

See [DOCUMENTATION.md](DOCUMENTATION.md#database-schema) for detailed schema information.

---

## 🔐 Admin Access

Admin users have access to:
- Product management dashboard (`/admin/products`)
- Create, edit, and delete products
- Low stock email notifications
- Daily sales reports

**Setting up admin users:**
```bash
php artisan tinker
User::where('email', 'user@example.com')->update(['is_admin' => true]);
```

Or modify `database/seeders/DatabaseSeeder.php` before seeding.

---

## 📧 Email Notifications

### Low Stock Alerts
- Triggered when product stock falls below `low_stock_threshold`
- Sent via queue system for better performance
- Email includes product name, current stock, and threshold

### Daily Sales Reports
- Scheduled to run daily at 6 PM
- Includes total revenue and items sold
- Product-wise sales breakdown

**Configure scheduler (Production):**
```bash
# Add to crontab (Linux/Mac)
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1

# Windows Task Scheduler (see WINDOWS_PRODUCTION_SETUP.md)
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=ProductTest
```

---

## 🚢 Deployment

For production deployment instructions, see [DOCUMENTATION.md](DOCUMENTATION.md#deployment).

---

## 📝 Project Structure

```
├── app/
│   ├── Console/Commands/      # Artisan commands
│   ├── Http/Controllers/      # Application controllers
│   ├── Http/Middleware/       # Custom middleware
│   ├── Jobs/                  # Queue jobs
│   ├── Mail/                  # Email templates
│   └── Models/                # Eloquent models
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── resources/
│   ├── js/
│   │   ├── Components/        # React components
│   │   ├── Layouts/           # Layout components
│   │   ├── Pages/             # Page components
│   │   └── types/             # TypeScript definitions
│   └── views/                 # Blade templates
├── routes/
│   ├── web.php                # Web routes
│   ├── auth.php               # Auth routes
│   └── console.php            # Scheduled tasks
└── public/                    # Public assets
```

---

## 🤝 Contributing

This is a technical interview project. For educational or portfolio purposes, feel free to fork and modify.

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Author

Developed as a technical interview assignment to demonstrate proficiency in:
- Laravel framework and best practices
- React with TypeScript
- Database design and ORM
- Queue systems and background jobs
- Task scheduling and automation
- Authentication and authorization
- Modern frontend development

---

**Built with ❤️ using Laravel, React, and TypeScript**
