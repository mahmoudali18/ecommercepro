<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Enumerations\CategoryType;
use App\Http\Requests\GeneralProductRequest;
use App\Http\Requests\MainCategoryRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductsController extends Controller
{

    public function index()
    {

    }


    public function create()
    {
        $data=[];
        $data['brands']= Brand::active()->select('id')->get();
        $data['tags']= Tag::select('id')->get();
        $data['categories']= Category::active()->select('id')->get();
        //return $data;
        return view('dashboard.products.general.create',$data);
    }


    public function store(GeneralProductRequest $request)
    {
        try {
           // return $request;

            DB::beginTransaction();

            if (!$request->has('is_active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);


            //if user choose mainCategory then we must remove parent_id from request
            if($request ->type == CategoryType::MainCategory){  //use enum
                $request->request->add(['parent_id' => null]);
            }

            $category = Category::create($request ->except('_token'));

            //save translations
            $category -> name = $request ->name;
            $category ->save();

            DB::commit();
            return redirect()->route('admin.maincategories')->with(['success' => 'تم الاضافه بنجاح']);
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }




    public function edit($id)
    {
        $category = Category::orderBy('id','DESC')->find($id);
        if (!$category)
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);

        return view('dashboard.categories.edit', compact('category'));
    }




    public function update($id, MainCategoryRequest $request)
    {
        try {

           // return $request;

            $category = Category::find($id);
            if(!$category)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);

            DB::beginTransaction();
            //update DB
            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);


            $category ->update($request ->all());

            //save translations
            $category -> name = $request ->name;
            $category ->save();

            DB::commit();
            return redirect()->route('admin.maincategories')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }



    public function destroy($id)
    {
        try {
            $category = Category::find($id);
            if(!$category)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);

            $category ->delete();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحذف بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }


    }


}
