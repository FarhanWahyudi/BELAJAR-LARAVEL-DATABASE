<?php

namespace Tests\Feature;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class QueryBuilderTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        DB::delete('DELETE FROM categories');
    }

    public function testInsertCategories()
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

    public function testInsert()
    {
        DB::table('categories')->insert([
            'id' => 'GADGET',
            'name' => 'gadget'
        ]);
        DB::table('categories')->insert([
            'id' => 'FOOD',
            'name' => 'food'
        ]);

        $result = DB::select('select count(id) as total from categories');
        $this->assertEquals(2, $result[0]->total);
    }

    public function testSelect()
    {
        $this->testInsert();

        $collection = DB::table('categories')->select(['id', 'name'])->get();
        $this->assertNotNull($collection);

        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testWhere()
    {
        $this->testInsertCategories();

        $collection = DB::table('categories')->where(function (Builder $builder) {
            $builder->where('id', '=', 'SMARTPHONE');
            $builder->orWhere('id', '=', 'LAPTOP');
        })->get();

        $this->assertCount(2, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });     
    }

    public function testWhereBetween()
    {
        $this->testInsertCategories();

        $collection = DB::table('categories')->whereBetween('created_at', ['2020-09-10 10:10:10', '2020-11-10 10:10:10'])->get();

        $this->assertCount(4, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });     
    }

    public function testWhereIn()
    {
        $this->testInsertCategories();

        $collection = DB::table('categories')->whereIn('id', ['SMARTPHONE', 'LAPTOP'])->get();

        $this->assertCount(2, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });     
    }

    public function testWhereNull()
    {
        $this->testInsertCategories();

        $collection = DB::table('categories')->whereNull('description')->get();

        $this->assertCount(4, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });     
    }

    public function testWhereDate()
    {
        $this->testInsertCategories();

        $collection = DB::table('categories')->whereDate('created_at', '2020-10-10')->get();

        $this->assertCount(4, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });     
    }
}
