# Windows Production Setup Guide

## 1. Task Scheduler - Daily Sales Report (Cron)

### Create Scheduled Task for Laravel Scheduler:

1. Open **Task Scheduler**
2. Click **Create Basic Task**
3. Name: `Laravel Scheduler - E-commerce`
4. Trigger: **Daily** at **12:00 AM** (midnight)
5. Action: **Start a program**
   - Program: `C:\path\to\php.exe` (e.g., `C:\xampp\php\php.exe`)
   - Arguments: `artisan schedule:run`
   - Start in: `C:\Users\lotky\OneDrive\Desktop\E-commerce`
6. Check **"Repeat task every: 1 minute"** (under Advanced settings)
7. Duration: **Indefinitely**

This will run `php artisan schedule:run` every minute, which checks if any scheduled tasks need to run (like your 6PM daily sales report).

---

## 2. Queue Worker - Continuous Background Process

### Option A: NSSM (Non-Sucking Service Manager) - Best for Production

1. **Download NSSM** from https://nssm.cc/download
2. Extract to `C:\nssm\`
3. Open PowerShell as Administrator:

```powershell
# Install the service
C:\nssm\nssm.exe install LaravelQueueWorker "C:\xampp\php\php.exe" "artisan queue:work --sleep=3 --tries=3"

# Set the application directory
C:\nssm\nssm.exe set LaravelQueueWorker AppDirectory "C:\Users\lotky\OneDrive\Desktop\E-commerce"

# Start the service
C:\nssm\nssm.exe start LaravelQueueWorker

# Check status
C:\nssm\nssm.exe status LaravelQueueWorker
```

4. The queue worker will now run automatically:
   - Starts on system boot
   - Restarts automatically if it crashes
   - Runs in the background

**To stop/restart:**
```powershell
# Stop
C:\nssm\nssm.exe stop LaravelQueueWorker

# Restart (after code changes)
C:\nssm\nssm.exe restart LaravelQueueWorker

# Remove service
C:\nssm\nssm.exe remove LaravelQueueWorker confirm
```

### Option B: Task Scheduler (Alternative)

1. Open **Task Scheduler**
2. Click **Create Basic Task**
3. Name: `Laravel Queue Worker`
4. Trigger: **When the computer starts**
5. Action: **Start a program**
   - Program: `C:\path\to\php.exe`
   - Arguments: `artisan queue:work --sleep=3 --tries=3 --timeout=60`
   - Start in: `C:\Users\lotky\OneDrive\Desktop\E-commerce`
6. Under **Conditions** tab:
   - Uncheck "Start only if on AC power"
7. Under **Settings** tab:
   - Check "If task fails, restart every: 1 minute"
   - "Attempt to restart up to: 3 times"

---

## 3. Verification

### Check if everything is running:

```powershell
# Check if queue worker service is running (NSSM)
Get-Service | Where-Object {$_.Name -like "*Laravel*"}

# Or check Task Scheduler tasks
Get-ScheduledTask | Where-Object {$_.TaskName -like "*Laravel*"}

# Test the daily sales report manually
cd C:\Users\lotky\OneDrive\Desktop\E-commerce
php artisan schedule:list
php artisan report:daily-sales

# Check queue is processing
php artisan tinker --execute="echo DB::table('jobs')->count();"
```

---

## How It All Works Automatically:

### Daily Sales Report Flow:
1. **Windows Task Scheduler** runs `php artisan schedule:run` every minute
2. Laravel checks if any scheduled tasks are due
3. At **6:00 PM daily**, it runs `report:daily-sales` command
4. Email is sent to admin via Mailtrap/SMTP

### Low Stock Notification Flow:
1. User completes checkout
2. Stock is decremented
3. If stock ≤ threshold, job is dispatched to queue
4. **NSSM service** (queue worker) picks up the job automatically
5. Email is sent to admin via Mailtrap/SMTP

---

## Restart Queue Worker After Code Changes

Whenever you update code that affects jobs:

```powershell
# If using NSSM service
C:\nssm\nssm.exe restart LaravelQueueWorker

# Or use artisan command
php artisan queue:restart
```

---

## Monitoring & Logs

```powershell
# View logs
Get-Content storage/logs/laravel.log -Tail 50 -Wait

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Clear failed jobs
php artisan queue:flush
```

---

## Summary

✅ **Task Scheduler** → Runs Laravel scheduler every minute → Daily sales report at 6 PM
✅ **NSSM Service** → Queue worker runs 24/7 → Processes low stock notifications automatically
✅ **No manual intervention needed** → Everything runs automatically in the background
