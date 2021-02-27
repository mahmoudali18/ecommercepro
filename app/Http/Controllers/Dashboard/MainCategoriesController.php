<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Enumerations\CategoryType;
use App\Http\Requests\MainCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MainCategoriesController extends Controller
{

    public function index()
    {
        $categories = Category::with('_parent')->orderBy('id','DESC')->paginate(PAGINATION_COUNT);
        return view('dashboard.categories.index', compact('categories'));
    }


    public function create()
    {
        $categories =   Category::select('id','parent_id')->get();   //name return by default by package
        return view('dashboard.categories.create',compact('categories'));
    }


    public function store(MainCategoryRequest $request)
    {
        try {

            DB::beginTransaction();

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            //save photo
            $fileName ="";
            if($request->has('photo')){
                $fileName = uploadImage('categories',$request->photo);
            }


            //if user choose mainCategory then we must remove parent_id from request
            if($request ->type == CategoryType::MainCategory){  //use enum
                $request->request->add(['parent_id' => null]);
            }

            $category = Category::create($request ->except('_token','photo'));

            //save translations
            $category -> name = $request ->name;
            $category -> photo = $fileName;
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
        $category = Category::find($id);
        if (!$category)
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);

        return view('dashboard.categories.edit', compact('category'));
    }




    public function update(MainCategoryRequest $request,$id)
    {
        try {

            // return $request;

            $category = Category::find($id);
            if(!$category)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);

            DB::beginTransaction();
            //update photo if return in request
            if($request->has('photo')){
                $fileName = uploadImage('categories',$request->photo);
                Category::where('id',$id)
                    ->update([
                        'photo' =>$fileName
                    ]);
            }

            //update DB
            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);


            $category ->update($request ->except('id','_token','photo'));

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
