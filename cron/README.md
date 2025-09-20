# Setup Cron Job
## For Windows (Task Scheduler):
```
Open Task Scheduler
Create Basic Task
Set trigger (e.g., daily at 9:00 AM)
Set action: Start a program
Program: C:\path\to\php.exe
Arguments: C:\MAMP\htdocs\Fintra\cron\reminder_runner.php
Start in: C:\MAMP\htdocs\Fintra\cron\
```
## For Linux/Mac (crontab):

```bash
# Edit crontab
crontab -e

# Add line (runs daily at 9 AM)
0 9 * * * /usr/bin/php /path/to/Fintra/cron/reminder_runner.php >> /path/to/Fintra/logs/reminder.log 2>&1
```