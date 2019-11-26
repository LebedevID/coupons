<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;




class LoadCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:coupons {proxy?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loading coupons';

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
		$result = $loader->load_coupons($proxy) ;
		
		if ($result) {
			echo 'Loading ok.';
		}
		else
		{
			echo 'Loading error.';
		}
    }
}
