<?php

namespace Tests\Feature;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class QueryBuilderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from categories");
    }
    public function testInsert(): void // masukkan data
    {
        DB::table("categories")->insert([
            "id"=>"GADGET",
            "name"=>"Gadget"
        ]);

        DB::table("categories")->insert([
            "id"=>"FOOD",
            "name"=>"Food"
        ]);

        $result = DB::select("select count(id) as total from categories");
        self::assertEquals(2,$result[0]->total);
    }

    public function testSelect()// select data
    {
        $this->testInsert();
        $collection = DB::table("categories")->select(["id","name"])->get();
        self::assertNotNull($collection);

        $collection->each(function($item) {
            Log::info(json_encode($item));
        });
    }

    public function insertCategories()// insert data
    {
        DB::table("categories")->insert([
            "id"=>"SMARTPHONE",
            "name"=> "Smartphone",
            "created_at" => "2025-11-29 10:10:10"
        ]);

         DB::table("categories")->insert([
            "id"=>"FOOD",
            "name"=> "Food",
            "created_at" => "2025-11-29 10:10:10"
        ]);

         DB::table("categories")->insert([
            "id"=>"LAPTOP",
            "name"=> "Laptop",
            "created_at" => "2025-11-29 10:10:10"
        ]);

         DB::table("categories")->insert([
            "id"=>"FASHION",
            "name"=> "Fashion",
            "created_at" => "2025-12-29 10:10:10"
        ]);
    }

    public function testWhere()// where data
    {
        $this->insertCategories();

       $collection = DB::table("categories")->where(function(Builder $builder) {
            $builder->where("id","=","SMARTPHONE");
            $builder->orWhere("id","=","LAPTOP");
            // SELECT * FROM CATEGORIES WHERE (id = SMARTPHONE OR id = LAPTOP$result =)
        })->get();

        self::assertCount(2,$collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function testWhereBetween() // where data dari-sampai (between)
    {
        $this->insertCategories();
        $collection = DB::table("categories")
        ->whereBetween("created_at",["2025-11-29 10:10:10","2025-12-29 10:10:10"])->get();
        self::assertCount(4,$collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function testWhereIn() // where data dalam (in) dengan id yang valuenya adalah...
    {
         $this->insertCategories();
        $collection = DB::table("categories")
        ->whereIn("id",["SMARTPHONE","LAPTOP"])->get();
        self::assertCount(2,$collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function testWhereNullMethod() // where data dimana kolomnya adalah null
    {
         $this->insertCategories();
        $collection = DB::table("categories")
        ->whereNull("description")->get();
        self::assertCount(4,$collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

     public function testWhereDate() // where data dimana kolomnya adalah date
    {
         $this->insertCategories();
        $collection = DB::table("categories")
        ->whereDate("created_at","2025-12-29")->get();
        self::assertCount(1,$collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function testUpdate() // update data dengan id yang valuenya adalah ...
    {
        $this->insertCategories();
        DB::table("categories")->where("id","=","SMARTPHONE")->update([
            "name"=> "Handphone"
        ]);

        $collection = DB::table("categories")->where("name","=","Handphone")->get();
        self::assertCount(1,$collection);
         $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function testUpsert()// update data. jika data tidak ada maka insert data baru
    {
        $this->insertCategories();
        DB::table("categories")->updateOrInsert([
            "id"=>"VOUCHER"
        ],[
            "name"=>"Voucher",
            "description"=>"Ticket And Voucher",
            "created_at"=>"2025-12-29"
        ]);

        $collection = DB::table("categories")->where("id","=","VOUCHER")->get();
        self::assertCount(1,$collection);
         $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function testIncrement()
    {
        DB::table("counters")
        ->where("id","=","sample")
        ->increment("counter",1);

        $collection = DB::table("counters")->where("id","=","sample")->get();
        self::assertCount(1,$collection);
        $collection->each(function($item){
            Log::info(json_encode($item));
        });
    }

    public function testDelete(){
        $this->insertCategories();
        DB::table("categories")->where("id","=","SMARTPHONE")->delete();

        $collection = DB::table("categories")->where("id","=","SMARTPHONE")->get();
        self::assertCount(0, $collection);
    }
}
