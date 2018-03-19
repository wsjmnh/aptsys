<?php

use Illuminate\Database\Seeder;
use App\Models\PostType;

class PostTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PostType::create(['Post', 'created_at' => '2018-01-24', 'updated_at' => '2018-01-25']);
        PostType::create(['Image', 'created_at' => '2018-01-24', 'updated_at' => '2018-01-25']);
        PostType::create(['Audio', 'created_at' => '2018-01-24', 'updated_at' => '2018-01-25']);
    }
}
