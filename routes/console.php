<?php

use App\Models\Log;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('log:clear')->daily()->at('00:00')
    ->onSuccess(function () {
        Log::createLog('Logs cleared successfully.');
    })
    ->onFailure(function () {
        Log::createLog('Failed to clear logs.');
    });
