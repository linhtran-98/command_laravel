<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackupDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:backup {limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract product data to csv file (limit min = 10, max = 100)';

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
        $id = 0;
        $i = 1;
        $limit = $this->argument('limit');

        // validate limit
        if(!is_numeric($limit) || $limit < 10)
        {
            $limit = 10;
        }
        elseif ($limit > 100)
        {
            $limit = 100;
        }
        
        // progress bars
        $bar = $this->output->createProgressBar();
        $bar->start();

        do {
            $products = DB::table('products')->select('product_id', 'name', 'status', 'price')->orderBy('product_id')->where('product_id', '>', $id)->limit($limit)->get();
            // count record
            $count = $products->count();
            
            // $id = last record id
            $id = $products[$count-1]->product_id;

            // save to csv file
            $filename = 'Backup/'.date('Y_m_d-h_i_s').'-'.$i.'_producs.csv';
            $handle = fopen($filename, 'w');
            fputcsv($handle, array('id', 'name', 'status', 'price'));

            foreach ($products as $key => $value)
            {
                fputcsv($handle, array($value->product_id, $value->name, $value->status, $value->price));
                $bar->advance();
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            // avoid file name duplication
            $i++;

        // if $count != $limit exit loop
        } while ($count == $limit);
        
        $bar->finish();

        $this->line('');
        $this->info('Extract successful, file save at: app/Backup/');
        $this->line('');
    }
}
