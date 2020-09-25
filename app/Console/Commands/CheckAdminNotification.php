<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Globals\AdminNotification;

class CheckAdminNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CheckAdminNotification:executechecking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        AdminNotification::check_notification();
        AdminNotification::update_customer_category();
    }
}
