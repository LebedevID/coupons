<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LoadAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:all {proxy?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loading shops and coupons (all shops and coupons will be rewrite)';

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
		$loader->load_coupons($proxy);
    }
}
