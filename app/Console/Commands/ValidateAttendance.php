<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AttendanceService;
use Carbon\Carbon;

class ValidateAttendance extends Command
{
    protected $signature = 'attendance:validate {--date= : Date to validate (Y-m-d)}';
    protected $description = 'Validate and finalize attendance for a specific date';

    public function handle(AttendanceService $attendanceService): int
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : Carbon::yesterday();

        $this->info("Validating attendance for: {$date->toDateString()}");

        $results = $attendanceService->validateDailyAttendance($date);

        $this->info("Processed: {$results['processed']}");
        $this->info("Valid: {$results['valid']}");
        $this->info("Invalid: {$results['invalid']}");

        return Command::SUCCESS;
    }
}
