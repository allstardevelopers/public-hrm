<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Log;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class DailySchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Schedule Every Day one time';

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
        $userId = 1;
        $popupMessage = 'Hello!';

        // Store the popup message in Redis with the user's ID as the key
        // Redis::set('popup:' . $userId, $popupMessage);
        // $this->info('Word of the Day sent to All Users');
        prob_complete_notif();
        markAbsent();
        markshortLeave();
        Log::info('Hello From Daily Schedule');

    }
}
