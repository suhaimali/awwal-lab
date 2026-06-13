<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AuditLog;
use Carbon\Carbon;

class CleanupAuditLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:cleanup {--days=180 : The number of days to keep audit logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old audit logs to prevent database bloat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $date = Carbon::now()->subDays($days);
        
        $this->info("Deleting audit logs older than {$days} days ({$date->toDateString()})...");
        
        $deletedCount = AuditLog::where('created_at', '<', $date)->delete();
        
        $this->info("Successfully deleted {$deletedCount} old audit logs.");
    }
}
