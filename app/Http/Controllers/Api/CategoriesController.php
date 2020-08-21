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
            'external_id' => 'required|numeric',
            'parent_id' => 'nullable|numeric'

        ],[
         'name.required' => 'Поле Название категории обязательно для заполнения',
          'name.max'  => 'Поле не должно быть больше 200 символов',
          'external_id.required' => 'Поле external_id обязательно для заполнения'
        ]);



        $category = Category::where('external_id',$request -> input('external_id'));

        //Проверка на существование категории
        if($category -> count()){

            return Response::json(['success' => false,'error' => "Данная категория уже существует"]);

        }

        $category =  Category::create($request -> only(['name','external_id','parent_id']));

        return response()->json($category,201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return CategoryResource
     */
    public function show($id)
    {

        $category = Category::find($id);
        if(is_null($category)){

            return Response::json(['success' => false,'error' => "Данной категории не существует"]);

        }

        $children_categories = $category->children;

        if($children_categories -> isEmpty()){

            return new CategoryResource($category);

        }

        return new CategoriesResource($children_categories);
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

        return response()->json($category, 200);
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

        return response()->json(null, 204);
    }
}
