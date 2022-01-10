<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateAllSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:allSeed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'run all the migrations at once';

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
        $this->call('migrate', ['--database' => 'mysql', '--path' => 'database/migrations']);
        $this->call('migrate', ['--database' => 'common_database', '--path' => 'database/migrations/common_database']);
        $this->call('db:seed');
        Artisan::call('defaultsPermissions');
        return Command::SUCCESS;
    }
}
