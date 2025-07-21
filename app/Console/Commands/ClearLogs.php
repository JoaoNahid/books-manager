<?php

namespace App\Console\Commands;

use App\Models\Log;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear records from logs table older than 30 days';

    /**
     * Execute the console command.
     */
    public function handle() {
        $date = now()->subDays(30);
        $clear = Log::where('created_at', '<', $date)->delete();

        // Create log file if necessary
        $directory = 'storage/logs';
        if (!File::exists($directory . '/clear_logs.log')) {
            File::put($directory . '/clear_logs.log', '');
            chmod($directory . '/clear_logs.log', 0644);
        }
        // Write the output of the command in a log storage file
        $timestamp = now()->toDateTimeString();
        $message = 'Successfully deleted logs older than 30 days.';
        if (!$clear) {
            $message = 'Failed to delete logs older than 30 days.';
        } 

        File::append($directory . '/clear_logs.log', "[$timestamp] $message\n");

        $this->info($clear . ' log(s) deleted successfully.');
    }
}
