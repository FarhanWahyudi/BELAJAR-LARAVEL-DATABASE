<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RawQueryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        DB::delete('DELETE FROM categories');
    }
    
    public function testCrud()
    {
        DB::insert('insert into categories(id, name, description, created_at) values (?, ?, ?, ?)', [
            'GADGET', 'gadget', 'gadget category', '2020-10-10 10:10:10'
        ]);

        $result = DB::select('select * from categories where id = ?', ['GADGET']);

        $this->assertCount(1, $result);
        $this->assertEquals('GADGET', $result[0]->id);
        $this->assertEquals('gadget', $result[0]->name);
        $this->assertEquals('gadget category', $result[0]->description);
        $this->assertEquals('2020-10-10 10:10:10', $result[0]->created_at);
    }

    public function testCrudNamedParameter()
    {
        DB::insert('insert into categories(id, name, description, created_at) values (:id, :name, :description, :created_at)', [
            'id' => 'GADGET',
            'name' => 'gadget',
            'description' => 'gadget category',
            'created_at' => '2020-10-10 10:10:10'
        ]);

        $result = DB::select('select * from categories where id = ?', ['GADGET']);

        $this->assertCount(1, $result);
        $this->assertEquals('GADGET', $result[0]->id);
        $this->assertEquals('gadget', $result[0]->name);
        $this->assertEquals('gadget category', $result[0]->description);
        $this->assertEquals('2020-10-10 10:10:10', $result[0]->created_at);
    }
}
