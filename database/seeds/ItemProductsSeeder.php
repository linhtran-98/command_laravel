<?php

use Illuminate\Database\Seeder;

class ItemProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fake  = Faker\Factory::create();
        $limit = 100;
        $status = ['pending', 'approve', 'reject'];

        for ($i = 0; $i < $limit; $i++){
            DB::table('products')->insert([
                'name' => $fake->name,
                'title' => $fake->name,
                'price' => 	$fake->numerify($string = '#######'),
                'image' => $fake->imageUrl($width = 200, $height = 200),
                'description' => $fake->sentence,
                'status' => $status[array_rand($status)],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }
    }
}
