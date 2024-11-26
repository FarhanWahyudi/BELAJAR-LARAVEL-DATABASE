<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QueryBuilderTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        DB::delete('DELETE FROM categories');
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
}
