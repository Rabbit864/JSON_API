<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductsResource;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ProductsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @param $request
     * @return ProductsResource|JsonResponse
     */
    public function index($id, Request $request)
    {
        $category = Category::find($id);

        if(is_null($category)){

            return Response::json(['success' => false,'error' => "Данной категории не существует"]);

        }

        $sorts = explode(', ', $request->input( 'sort', ''));



        $products = $category->products()->paginate(50);

        foreach($sorts as $sortColumn){

            $sortDirection = $sortColumn;
            $sortColumn = ltrim($sortColumn, '-');
            $products = (Str::startsWith($sortDirection, '-')) ? $products -> sortByDesc($sortColumn)
                : $products -> sortBy($sortColumn);


        }

        return new ProductsResource($products);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $category_id
     * @return JsonResponse
     */
    public function store(Request $request,$category_id)
    {
        $request->validate([
            'name'=>'required|max:200',
            'external_id' => 'required',
            'description' => 'required|max:1000',
        ]);

        $category = Category::find($category_id);

        if(is_null($category)){

            return Response::json(['success' => false,'error' => "Данной категории не существует"]);

        }


        $product = $category->products;
        $checkProduct = $product -> where('external_id',$request -> input('external_id'));

        if($checkProduct -> count()){

            return Response::json(['success' => false,'error' => "Данный продукт уже существует"]);

        }



        $products = Product::create($request -> only(['name','description','date_creation','price','quantity','external_id']));

        $products->categories()->attach($category_id);


        return Response::json(['success' => true,'payload' => []]);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param $id_product
     * @return ProductResource|JsonResponse
     */
    public function show($id,$id_product)
    {

        $category = Category::find($id);
        if(is_null($category)){

            return Response::json(['success' => false,'error' => "Данной категории не существует"]);

        }

        $product = $category->products;
        $product = $product -> find($id_product);

        if(is_null($product)){

           return Response::json(['success' => false,'error' => "Данный продукт не существует"]);
        }

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id_category
     * @param $id_product
     * @return JsonResponse
     */
    public function destroy($id_category,$id_product)
    {
        $category = Category::find($id_category);

        if(is_null($category)){

            return Response::json(['success' => false,'error' => "Данной категории не существует"]);

        }

        $product = Product::find($id_product);

        if(is_null($category)){

            return Response::json(['success' => false,'error' => "Данного продукта нет"]);

        }

        $product->delete();
    }
}
