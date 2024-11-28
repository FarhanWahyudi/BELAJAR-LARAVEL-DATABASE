<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'id' => 'SMARTPHONE',
            'name' => 'smartphone',
            'created_at' => '2020-10-10 10:10:10'
        ]);
        DB::table('categories')->insert([
            'id' => 'FOOD',
            'name' => 'food',
            'created_at' => '2020-10-10 10:10:10'
        ]);
        DB::table('categories')->insert([
            'id' => 'LAPTOP',
            'name' => 'laptop',
            'created_at' => '2020-10-10 10:10:10'
        ]);
        DB::table('categories')->insert([
            'id' => 'FASHION',
            'name' => 'fashion',
            'created_at' => '2020-10-10 10:10:10'
        ]);
    }
}
