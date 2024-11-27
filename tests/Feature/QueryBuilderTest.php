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
        DB::delete('DELETE FROM products');
        DB::delete('DELETE FROM categories');
    }

    public function InsertCategories()
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

    public function insertManyCategories()
    {
        for ($i = 0; $i < 100; $i++) {
            DB::table('categories')->insert([
                'id' => $i,
                'name' => "$i",
                'created_at' => '2020-10-10 10:10:10'
            ]);
        };

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
        $this->InsertCategories();

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
        $this->InsertCategories();

        $collection = DB::table('categories')->whereBetween('created_at', ['2020-09-10 10:10:10', '2020-11-10 10:10:10'])->get();

        $this->assertCount(4, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });     
    }

    public function testWhereIn()
    {
        $this->InsertCategories();

        $collection = DB::table('categories')->whereIn('id', ['SMARTPHONE', 'LAPTOP'])->get();

        $this->assertCount(2, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });     
    }

    public function testWhereNull()
    {
        $this->InsertCategories();

        $collection = DB::table('categories')->whereNull('description')->get();

        $this->assertCount(4, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });     
    }

    public function testWhereDate()
    {
        $this->InsertCategories();

        $collection = DB::table('categories')->whereDate('created_at', '2020-10-10')->get();

        $this->assertCount(4, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });     
    }

    public function testUpdate()
    {
        $this->InsertCategories();

        DB::table('categories')->where('id', '=', 'SMARTPHONE')->update([
            'name' => 'handphone'
        ]);

        $collection = DB::table('categories')->where('name', '=', 'handphone')->get();
        $this->assertCount(1, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        }); 
    }

    public function testUpsert()
    {
        DB::table('categories')->updateOrInsert([
            'id' => 'VOUCHER'
        ], [
            'name' => 'voucher',
            'created_at' => '2020-10-10 10:10:10'
        ]);

        $collection = DB::table('categories')->where('id', '=', 'VOUCHER')->get();
        $this->assertCount(1, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testIncrement()
    {
        DB::table('couters')->where('id', '=', 'sample')->increment('couter', 1);
        
        $collection = DB::table('couters')->where('id', '=', 'sample')->get();
        $this->assertCount(1, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testDelete()
    {
        $this->InsertCategories();

        DB::table('categories')->where('id', '=', 'SMARTPHONE')->delete();

        $collection = DB::table('categories')->where('id', '=', 'SMARTPHONE')->get();
        $this->assertCount(0, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function insertProducts()
    {
        $this->InsertCategories();

        DB::table('products')->insert([
            'id' => '1',
            'name' => 'iphone 14 pro max',
            'category_id' => 'SMARTPHONE',
            'price' => 20000000
        ]);
        DB::table('products')->insert([
            'id' => '2',
            'name' => 'samsung galaxy s21 ultra',
            'category_id' => 'SMARTPHONE',
            'price' => 18000000
        ]);
    }

    public function testJoin()
    {
        $this->insertProducts();

        $collection = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id', 'products.name', 'categories.name as category_name', 'products.price')
            ->get();

        $this->assertCount(2, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testOrdering()
    {
        $this->insertProducts();

        $collection = DB::table('products')->orderBy('price', 'desc')->orderBy('name', 'asc')->get();
        $this->assertCount(2, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testPaging()
    {
        $this->InsertCategories();

        $collection = DB::table('categories')
            ->skip(2)
            ->take(2)
            ->get();

        $this->assertCount(2, $collection);
        $collection->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    public function testChunk()
    {
        $this->insertManyCategories();

        DB::table('categories')->orderBy('id')
            ->chunk(10, function ($categories) {
                $this->assertNotNull($categories);
                Log::info('chunk start');
                $categories->each(function ($category) {
                    Log::info(json_encode($category));
                });
                Log::info('chunk end');
            });
    }

    public function testLazy()
    {
        $this->insertManyCategories();

        $collection = DB::table('categories')->orderBy('id')->lazy(10);
        $this->assertNotNull($collection);

        $collection->each(function ($category) {
            Log::info(json_encode($category));
        });
    }

    public function testCursor()
    {
        $this->insertManyCategories();

        $collection = DB::table('categories')->orderBy('id')->cursor();
        $this->assertNotNull($collection);

        $collection->each(function ($category) {
            Log::info(json_encode($category));
        });
    }

    public function testAggregrate()
    {
        $this->insertProducts();

        $result = DB::table('products')->count('id');
        $this->assertEquals(2, $result);

        $result = DB::table('products')->min('price');
        $this->assertEquals(18000000, $result);

        $result = DB::table('products')->max('price');
        $this->assertEquals(20000000, $result);

        $result = DB::table('products')->avg('price');
        $this->assertEquals(19000000, $result);

        $result = DB::table('products')->sum('price');
        $this->assertEquals(38000000, $result);
    }
}
