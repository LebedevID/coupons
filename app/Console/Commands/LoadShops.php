<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LoadShops extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:shops {proxy?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loading shops (all coupons will be delete)';

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
		$proxy = $this->argument('proxy');
        $loader = new \App\Http\Controllers\LoaderController();
		$loader->load_shops($proxy);
    }
}
