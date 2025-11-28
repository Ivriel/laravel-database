<?php

namespace Tests\Feature;

use Error;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    protected function setUp():void
    {
        parent::setUp();
        DB::delete("delete from categories");
    }

    public function testTransactionSuccess(): void
    {
        DB::transaction(function(){
             DB::insert('insert into categories(id,name,description,created_at) values (?,?,?,?)',[
            'GADGET', 'Gadget','Gadget Category','2025-11-28 23:41:05'
        ]);

         DB::insert('insert into categories(id,name,description,created_at) values (?,?,?,?)',[
            'FOOD', 'Food','Food Category','2025-11-28 23:41:05'
        ]);
        });

        $results = DB::select("select * from categories");
        self::assertCount(2,$results); 

    }

     public function testTransactionFailed(): void
    {
          try{
            DB::transaction(function(){
             DB::insert('insert into categories(id,name,description,created_at) values (?,?,?,?)',[
            'GADGET', 'Gadget','Gadget Category','2025-11-28 23:41:05'
        ]);

         DB::insert('insert into categories(id,name,description,created_at) values (?,?,?,?)',[
            'GADGET', 'Food','Food Category','2025-11-28 23:41:05'
        ]);
        });
          } catch( QueryException $error) {

          }


        $results = DB::select("select * from categories");
        self::assertCount(0,$results); 

    }

   public function testManualTransactionSuccess(): void
    {
          try{
           DB::beginTransaction();
           DB::insert('insert into categories(id,name,description,created_at) values (?,?,?,?)',[
            'GADGET', 'Gadget','Gadget Category','2025-11-28 23:41:05'
        ]);

         DB::insert('insert into categories(id,name,description,created_at) values (?,?,?,?)',[
            'FOOD', 'Food','Food Category','2025-11-28 23:41:05'
        ]);
        DB::commit();
          } catch( QueryException $error) {
            DB::rollBack();
          }


        $results = DB::select("select * from categories");
        self::assertCount(2,$results); 

    }

    public function testManualTransactionFailed(): void
    {
          try{
           DB::beginTransaction();
           DB::insert('insert into categories(id,name,description,created_at) values (?,?,?,?)',[
            'GADGET', 'Gadget','Gadget Category','2025-11-28 23:41:05'
        ]);

         DB::insert('insert into categories(id,name,description,created_at) values (?,?,?,?)',[
            'GADGET', 'Food','Food Category','2025-11-28 23:41:05'
        ]);
        DB::commit();
          } catch( QueryException $error) {
            DB::rollBack();
          }


        $results = DB::select("select * from categories");
        self::assertCount(0,$results); 

    }

}
