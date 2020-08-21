<?php

namespace App\Console\Commands;

use App\Category;
use Illuminate\Console\Command;
use App\Product;

class SendProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Product';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(file_exists('products.json') == false){

            print("Файла не существует");
            exit();

        }

        $file = file_get_contents("products.json");

        $external_id_array = [];
        $category_id_array = [];
        $products = Product::all();

        foreach ($products as $product){

            array_push($external_id_array,$product->external_id);

        }

        $dates = json_decode($file);

        foreach ($dates as $date){



            $external_id = $date->external_id;
            $name = $date->name;
            $price = $date->price;
            $quantity = $date->quantity;
            $categories = $date -> category_id;
            foreach ($categories as $category_id){

                $category = Category::where('id', $category_id)->first();

                if(is_null($category)){
                    print("Данной категории не существует : ". $category_id);
                    exit();
                }
            }

            if(in_array($external_id,$external_id_array)){

                $product = Product::where('external_id',$external_id)->first();

                $product->name = $name;
                $product->price = $price;
                $product->quantity = $quantity;
                $product->save();

                foreach ($product->categories as $category_id){
                    array_push($category_id_array,$category_id->id);
                }

                foreach($categories as $category){


                        if(!in_array($category,$category_id_array)){

                            $product->categories()->attach($category);
                        }


                }

            }
            else{

                $product = new Product;
                $product->external_id = $external_id;
                $product->name = $name;
                $product->price = $price;
                $product->quantity = $quantity;
                $product->save();

                foreach($categories as $category){

                        $product->categories()->attach($category);

                }
            }
        }
    }
}
