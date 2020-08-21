<?php

namespace App\Console\Commands;

use App\Category;
use Illuminate\Console\Command;

class SendCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Category';

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
        if(file_exists('categories.json') == false){
            print("Файла не существует");
            exit();
        }

        $file = file_get_contents("categories.json");

        $external_id_array = [];
        $categories = Category::all();

        foreach ($categories as $category){
            array_push($external_id_array,$category->external_id);
        }

        $dates = json_decode($file);
        foreach ($dates as $date){
            $external_id = $date->external_id;
            $name = $date->name;
            if(in_array($external_id,$external_id_array)){
                $category = Category::where('external_id',$external_id)->first();
                $category->name = $name;
                $category->save();
            }
            else{

                $category = new Category;
                $category->external_id = $external_id;
                $category->name = $name;
                $category->save();
            }
        }

    }
}
