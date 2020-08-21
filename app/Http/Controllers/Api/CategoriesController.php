<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Resources\CategoriesResource;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CategoriesResource
     */
    public function index()
    {
        return new CategoriesResource(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([

            'name'=>'required|max:200',
            'external_id' => 'required',

        ]);

        $category = Category::where('external_id',$request -> input('external_id'));

        if($category -> count()){

            return Response::json(['success' => false,'error' => "Данная категория уже существует"]);

        }

        return Category::create($request -> only(['name','external_id','parent_id']));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return CategoriesResource|JsonResponse
     */
    public function show($id)
    {

        $category = Category::where("id",$id)->first();
        if(is_null($category)){
            return Response::json(['success' => false,'error' => "Данной категории не существует"]);
        }

        $categories = Category::find($id)->children;

        return new CategoriesResource($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $category_id
     * @return JsonResponse
     */
    public function update(Request $request, $category_id)
    {
        $category = Category::find($category_id);
        if(is_null($category)){
            return Response::json(['success' => false,'error' => "Данной категории не существует"]);
        }
        $category -> update($request->only(['name','parent_id']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if(is_null($category)){
            return Response::json(['success' => false,'error' => "Данной категории не существует"]);
        }

        $category ->delete();
    }
}
