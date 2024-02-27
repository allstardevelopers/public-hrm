<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
// require_once __DIR__ . '/../../app/Helpers/CronJobQueries.php';

class EmployeeTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employee:tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track Employee Application response is it active or not';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return Command::SUCCESS;
        employeeTracking();
        $this->info('Succss');
        Log::info('Employee tracking Cron Job working');

    }
}
