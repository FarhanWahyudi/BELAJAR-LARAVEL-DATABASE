<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        DB::delete('DELETE FROM categories');
    }

    public function testTransactionSuccess()
    {
        DB::transaction(function () {
            DB::insert('insert into categories(id, name, description, created_at) values (?, ?, ?, ?)', [
                'GADGET', 'gadget', 'gadget category', '2020-10-10 10:10:10'
            ]);
            DB::insert('insert into categories(id, name, description, created_at) values (?, ?, ?, ?)', [
                'FOOD', 'food', 'food category', '2020-10-10 10:10:10'
            ]);
        });

        $result = DB::select('select * from categories');
        $this->assertCount(2, $result);
    }

    public function testTransactionFailed()
    {
        try {
            DB::transaction(function () {
                DB::insert('insert into categories(id, name, description, created_at) values (?, ?, ?, ?)', [
                    'GADGET', 'gadget', 'gadget category', '2020-10-10 10:10:10'
                ]);
                DB::insert('insert into categories(id, name, description, created_at) values (?, ?, ?, ?)', [
                    'GADGET', 'food', 'food category', '2020-10-10 10:10:10'
                ]);
            });
        } catch (QueryException $error) {

        }

        $result = DB::select('select * from categories');
        $this->assertCount(0, $result);
    }

    public function testManualTransactionSuccess()
    {
        try {
            DB::beginTransaction();
            DB::insert('insert into categories(id, name, description, created_at) values (?, ?, ?, ?)', [
                'GADGET', 'gadget', 'gadget category', '2020-10-10 10:10:10'
            ]);
            DB::insert('insert into categories(id, name, description, created_at) values (?, ?, ?, ?)', [
                'food', 'food', 'food category', '2020-10-10 10:10:10'
            ]);
            DB::commit();
        } catch (QueryException $error) {
            DB::rollBack();
        }

        $result = DB::select('select * from categories');
        $this->assertCount(2, $result);
    }

    public function testManualTransactionFailed()
    {
        try {
            DB::beginTransaction();
            DB::insert('insert into categories(id, name, description, created_at) values (?, ?, ?, ?)', [
                'GADGET', 'gadget', 'gadget category', '2020-10-10 10:10:10'
            ]);
            DB::insert('insert into categories(id, name, description, created_at) values (?, ?, ?, ?)', [
                'GADGET', 'food', 'food category', '2020-10-10 10:10:10'
            ]);
            DB::commit();
        } catch (QueryException $error) {
            DB::rollBack();
        }

        $result = DB::select('select * from categories');
        $this->assertCount(0, $result);
    }
}
