<?php

namespace App\Console\Commands;

use App\Models\Shared\Permission;
use Illuminate\Console\Command;

class DefaultPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'defaultsPermissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default permissions';

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
        $configPermissions = collect(config('permissions.names'));
        $modelPermissions = collect(Permission::pluck('name')->toArray());
        $diffs = $modelPermissions->diff($configPermissions)->all();
        foreach ($diffs as $diff) {
            Permission::deleteByName($diff);
        }
        foreach ($configPermissions as $permission) {
            Permission::findOrCreate($permission);
        }
        $this->info('default permissions updated');
    }
}
