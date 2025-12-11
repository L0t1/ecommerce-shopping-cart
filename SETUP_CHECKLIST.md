# 🚀 Quick Setup Checklist

Use this checklist to ensure your E-commerce Shopping Cart application is properly configured.

## ✅ Initial Setup

- [ ] **Clone repository**
  ```bash
  git clone <repository-url>
  cd E-commerce
  ```

- [ ] **Install PHP dependencies**
  ```bash
  composer install
  ```

- [ ] **Install JavaScript dependencies**
  ```bash
  npm install
  ```

- [ ] **Create environment file**
  ```bash
  cp .env.example .env
  ```

- [ ] **Generate application key**
  ```bash
  php artisan key:generate
  ```

---

## 📧 Mailtrap Email Configuration

- [ ] **Create Mailtrap account** at [mailtrap.io](https://mailtrap.io)

- [ ] **Get SMTP credentials** from Mailtrap dashboard

- [ ] **Update .env file** with Mailtrap credentials:
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=sandbox.smtp.mailtrap.io
  MAIL_PORT=2525
  MAIL_USERNAME=your_username
  MAIL_PASSWORD=your_password
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS=noreply@ecommerce.local
  ```

- [ ] **Clear configuration cache**
  ```bash
  php artisan config:clear
  ```

- [ ] **Test email configuration**
  ```bash
  php artisan tinker
  >>> Mail::raw('Test', fn($m) => $m->to('admin@example.com'))
  ```

---

## 🗄️ Database Setup

- [ ] **Configure database** in .env:
  ```env
  # For SQLite (easiest for testing):
  DB_CONNECTION=sqlite
  
  # Or for MySQL:
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=ecommerce
  DB_USERNAME=root
  DB_PASSWORD=
  ```

- [ ] **Create database** (if using MySQL):
  ```bash
  # MySQL command line
  CREATE DATABASE ecommerce;
  ```

- [ ] **Run migrations**
  ```bash
  php artisan migrate
  ```

- [ ] **Create admin user**
  ```bash
  php artisan tinker
  ```
  ```php
  User::create([
      'name' => 'Admin User',
      'email' => 'admin@example.com',
      'password' => bcrypt('password'),
      'email_verified_at' => now()
  ]);
  ```

- [ ] **Seed database (optional)**
  ```bash
  php artisan db:seed
  ```

---

## 📦 Queue Configuration

- [ ] **Set queue connection** in .env:
  ```env
  QUEUE_CONNECTION=database
  ```

- [ ] **Create jobs table** (should be done with migrations)
  ```bash
  php artisan queue:table
  php artisan migrate
  ```

- [ ] **Start queue worker** in a separate terminal:
  ```bash
  php artisan queue:work
  ```

---

## ⏰ Task Scheduler Setup

- [ ] **Verify schedule configuration** in `routes/console.php`
  - Should contain: `Schedule::command('report:daily-sales')->dailyAt('18:00')`

- [ ] **Add cron entry** (Linux/Mac):
  ```bash
  crontab -e
  # Add this line:
  * * * * * cd /path/to/E-commerce && php artisan schedule:run >> /dev/null 2>&1
  ```

- [ ] **Or setup Windows Task Scheduler**:
  - Program: `php`
  - Arguments: `artisan schedule:run`
  - Start in: `C:\path\to\E-commerce`
  - Trigger: Daily at midnight

- [ ] **Test schedule manually**:
  ```bash
  php artisan schedule:list
  php artisan report:daily-sales
  ```

---

## 🎨 Frontend Build

- [ ] **Build assets for development**
  ```bash
  npm run dev
  ```

- [ ] **Or build for production**
  ```bash
  npm run build
  ```

---

## 🧪 Testing

- [ ] **Test low stock notification**:
  ```bash
  # Ensure queue worker is running first
  php artisan tinker
  ```
  ```php
  $product = Product::first();
  $product->update(['stock_quantity' => 5, 'low_stock_threshold' => 10]);
  \App\Jobs\LowStockNotification::dispatch($product);
  ```
  - Check Mailtrap inbox for email

- [ ] **Test daily sales report**:
  ```bash
  php artisan report:daily-sales
  ```
  - Check Mailtrap inbox for email

- [ ] **Test shopping cart workflow**:
  - [ ] Register a new user
  - [ ] Browse products
  - [ ] Add items to cart
  - [ ] Update quantities
  - [ ] Remove items
  - [ ] Complete checkout
  - [ ] Verify low stock email if triggered

---

## 🚀 Start Application

- [ ] **Start development server**
  ```bash
  php artisan serve
  ```

- [ ] **Access application** at `http://localhost:8000`

- [ ] **Verify all features work**:
  - [ ] User registration/login
  - [ ] Product listing
  - [ ] Add to cart
  - [ ] Cart management
  - [ ] Checkout process
  - [ ] Email notifications

---

## 🔍 Verification Commands

Run these to ensure everything is configured correctly:

```bash
# Check Laravel version
php artisan --version

# Check configuration
php artisan config:show mail

# Check routes
php artisan route:list

# Check scheduled tasks
php artisan schedule:list

# Check queue status
php artisan queue:monitor

# Check database connection
php artisan migrate:status

# Run tests (if available)
php artisan test
```

---

## 📝 Important Files to Review

- [ ] `.env` - Environment configuration
- [ ] `routes/console.php` - Scheduled task configuration
- [ ] `routes/web.php` - Application routes
- [ ] `DOCUMENTATION.md` - Full project documentation
- [ ] `MAILTRAP_SETUP.md` - Email setup guide

---

## 🐛 Common Issues & Solutions

### Queue not processing
```bash
# Restart queue worker
php artisan queue:restart
php artisan queue:work
```

### Emails not sending
```bash
# Clear config
php artisan config:clear

# Verify mail config
php artisan tinker
>>> config('mail.mailers.smtp')
```

### Schedule not running
```bash
# Test manually
php artisan schedule:run

# Check cron/task scheduler setup
```

### Database errors
```bash
# Fresh migration
php artisan migrate:fresh

# With seeding
php artisan migrate:fresh --seed
```

### Asset build errors
```bash
# Clear npm cache
npm cache clean --force

# Reinstall
rm -rf node_modules package-lock.json
npm install
```

---

## 📚 Next Steps

After completing this checklist:

1. **Review Documentation**: Read `DOCUMENTATION.md` for detailed information
2. **Configure Mailtrap**: Follow `MAILTRAP_SETUP.md` for email testing
3. **Create Sample Data**: Add products and test the cart functionality
4. **Test All Features**: Ensure low stock alerts and sales reports work
5. **Deploy** (Optional): Follow deployment guide in documentation

---

## ✨ Quick Test Scenario

Complete this scenario to verify everything works:

1. **Register** a new user account
2. **Create** some products via Tinker:
   ```php
   Product::create([
       'name' => 'Test Product',
       'description' => 'A test product',
       'price' => 29.99,
       'stock_quantity' => 8,
       'low_stock_threshold' => 10
   ]);
   ```
3. **Login** with your account
4. **Add** products to cart
5. **Checkout** - should trigger low stock email
6. **Check Mailtrap** for low stock notification
7. **Run** `php artisan report:daily-sales`
8. **Check Mailtrap** for sales report

---

## 🎯 Interview Requirements Checklist

Verify all interview requirements are met:

- [x] **Laravel starter kit** (Breeze with React)
- [x] **User authentication** (built-in)
- [x] **Product browsing** with name, price, stock_quantity
- [x] **Shopping cart** (add, update, remove)
- [x] **User-based cart** (not session/localStorage)
- [x] **Low stock notification** via Laravel Job/Queue
- [x] **Daily sales report** via scheduled cron job
- [x] **Tailwind CSS** styling
- [x] **Laravel best practices** followed
- [x] **Email functionality** configured (Mailtrap)

---

**Ready to go! 🎉**

If you've checked all boxes above, your E-commerce Shopping Cart application should be fully functional and ready for demonstration.
