# Mailtrap Email Configuration Guide

This guide will help you configure Mailtrap for testing email functionality in the E-commerce Shopping Cart application.

## What is Mailtrap?

Mailtrap is a safe email testing service that captures emails sent from development/staging environments, preventing them from reaching real recipients. It's perfect for testing email features like our low stock notifications and daily sales reports.

---

## Step 1: Create a Mailtrap Account

1. Go to [https://mailtrap.io](https://mailtrap.io)
2. Click **Sign Up** (or **Get Started**)
3. Create a free account using:
   - Email address
   - Google/GitHub account

The free plan includes:
- 100 emails/month
- 1 inbox
- Perfect for development testing

---

## Step 2: Get Your SMTP Credentials

1. Log in to your Mailtrap account
2. Navigate to **Email Testing** → **Inboxes**
3. Click on your default inbox (or create a new one)
4. In the **SMTP Settings** section, select **Laravel 9+** from the integration dropdown
5. You'll see credentials similar to:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username_here
MAIL_PASSWORD=your_password_here
MAIL_ENCRYPTION=tls
```

---

## Step 3: Update Your .env File

1. Open your `.env` file in the project root
2. Find the `MAIL_` configuration section
3. Replace with your Mailtrap credentials:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ecommerce.local
MAIL_FROM_NAME="${APP_NAME}"
```

**Important**: Replace `your_mailtrap_username` and `your_mailtrap_password` with your actual credentials from Step 2.

---

## Step 4: Clear Configuration Cache

After updating `.env`, clear Laravel's configuration cache:

```bash
php artisan config:clear
```

---

## Step 5: Test Email Configuration

### Option 1: Using Tinker (Quick Test)
```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test email from E-commerce app', function($message) {
    $message->to('admin@example.com')
            ->subject('Test Email');
});
```

Press `Ctrl+C` to exit Tinker.

### Option 2: Test Low Stock Notification

1. Ensure queue worker is running:
```bash
php artisan queue:work
```

2. In another terminal, trigger a low stock notification:
```bash
php artisan tinker
```

```php
$product = \App\Models\Product::first();
\App\Jobs\LowStockNotification::dispatch($product);
```

### Option 3: Test Daily Sales Report
```bash
php artisan report:daily-sales
```

---

## Step 6: Check Emails in Mailtrap

1. Go back to your Mailtrap inbox
2. Refresh the page
3. You should see your test email appear within seconds
4. Click on the email to view:
   - HTML preview
   - Plain text version
   - Email headers
   - Source code

---

## Troubleshooting

### Problem: Emails not appearing in Mailtrap

**Solution 1: Check Queue Worker**
```bash
# Make sure queue worker is running
php artisan queue:work

# Or check queue status
php artisan queue:monitor
```

**Solution 2: Verify Configuration**
```bash
php artisan tinker
>>> config('mail.mailers.smtp')
```

Should output your Mailtrap settings.

**Solution 3: Check Logs**
```bash
# On Windows
Get-Content storage/logs/laravel.log -Tail 50

# View last 50 lines
```

**Solution 4: Clear All Caches**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Problem: Authentication Failed Error

- Double-check your `MAIL_USERNAME` and `MAIL_PASSWORD` in `.env`
- Make sure there are no extra spaces or quotes
- Verify you're using credentials from the correct inbox

### Problem: Connection Timeout

- Check your firewall settings
- Try port `465` with `MAIL_ENCRYPTION=ssl` instead
- Ensure your internet connection is stable

---

## Complete .env Configuration Example

Here's a complete working example for local development:

```env
APP_NAME="E-commerce Cart"
APP_ENV=local
APP_KEY=base64:your-generated-key-here
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# Or MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=ecommerce
# DB_USERNAME=root
# DB_PASSWORD=

QUEUE_CONNECTION=database

# Mailtrap Configuration
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username_here
MAIL_PASSWORD=your_mailtrap_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ecommerce.local
MAIL_FROM_NAME="E-commerce Cart"
```

---

## Testing Workflow

### 1. Test Low Stock Notification

```bash
# Terminal 1: Start queue worker
php artisan queue:work

# Terminal 2: Create a test scenario
php artisan tinker
```

```php
// Create or update a product with low stock
$product = \App\Models\Product::first();
$product->update([
    'stock_quantity' => 5,
    'low_stock_threshold' => 10
]);

// Manually trigger the job
\App\Jobs\LowStockNotification::dispatch($product);
```

Check Mailtrap inbox for the low stock alert email.

### 2. Test Daily Sales Report

```bash
# Create some test orders first
php artisan tinker
```

```php
$user = \App\Models\User::first();
$product = \App\Models\Product::first();

// Create a test order for today
$order = \App\Models\Order::create([
    'user_id' => $user->id,
    'total_amount' => 99.99,
    'status' => 'completed'
]);

\App\Models\OrderItem::create([
    'order_id' => $order->id,
    'product_id' => $product->id,
    'quantity' => 2,
    'price' => 49.99
]);
```

```bash
# Exit tinker and run the report command
php artisan report:daily-sales
```

Check Mailtrap inbox for the daily sales report email.

### 3. Test Via Shopping Cart (End-to-End)

1. Register a new user or log in
2. Add products to cart
3. Checkout
4. If product stock goes below threshold, low stock email will be sent
5. Check Mailtrap inbox

---

## Mailtrap Features to Explore

### Email Validation
Mailtrap checks your emails for:
- Spam score
- HTML/CSS validation
- Broken links
- Missing alt tags on images

### Email Preview
View how your email looks in:
- Different email clients (Gmail, Outlook, Apple Mail, etc.)
- Desktop vs mobile
- Dark mode vs light mode

### Forwarding
Forward test emails to your real email for review.

### API Access
Mailtrap provides API access for automated testing.

---

## Production Email Setup

**Important**: Mailtrap is for testing only. For production, use:

### Recommended Services:
1. **SendGrid** (Free tier: 100 emails/day)
2. **Mailgun** (Free tier: 5,000 emails/month)
3. **Amazon SES** (Pay as you go)
4. **Postmark** (Free tier: 100 emails/month)

### Production .env Example (SendGrid):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="E-commerce Cart"
```

---

## Security Best Practices

1. **Never commit `.env` to Git**
   - Already included in `.gitignore`
   - Contains sensitive credentials

2. **Use environment variables**
   - Don't hardcode email credentials in code
   - Always use `config('mail.username')` etc.

3. **Rotate credentials regularly**
   - Change Mailtrap password periodically
   - Use different credentials for different environments

4. **Limit access**
   - Only share Mailtrap credentials with team members who need them
   - Use separate inboxes for different projects

---

## Quick Reference Commands

```bash
# Clear config cache
php artisan config:clear

# Start queue worker
php artisan queue:work

# Test daily sales report
php artisan report:daily-sales

# Check mail configuration
php artisan tinker
>>> config('mail')

# Send test email
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('test@example.com'))

# View failed queue jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Check schedule
php artisan schedule:list
```

---

## Support & Resources

- **Mailtrap Documentation**: https://help.mailtrap.io/
- **Laravel Mail Documentation**: https://laravel.com/docs/mail
- **Laravel Queue Documentation**: https://laravel.com/docs/queues

---

**Last Updated**: December 11, 2025
