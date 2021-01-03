<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Requests\TagsRequest;
use App\Models\Brand;
use App\Models\Tag;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('id','DESC')->paginate(PAGINATION_COUNT);
        return view('dashboard.tags.index', compact('tags'));
    }



    public function create()
    {
        return view('dashboard.tags.create');
    }



    public function store(TagsRequest $request)
    {
        try {

            DB::beginTransaction();

            $tag = Tag::create(['slug' => $request ->slug]);

            //save translations
            $tag -> name = $request ->name;
            $tag ->save();

            DB::commit();
            return redirect()->route('admin.tags')->with(['success' => 'تم الاضافه بنجاح']);
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->route('admin.tags')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }



    public function edit($id)
    {
        $tag = Tag::find($id);
        if (!$tag)
            return redirect()->route('admin.tags')->with(['error' => 'هذا العلامه غير موجود']);

        return view('dashboard.tags.edit', compact('tag'));
    }




    public function update($id, TagsRequest $request)
    {
        try {

            // return $request;

            $tag = Tag::find($id);
            if(!$tag)
                return redirect()->route('admin.tags')->with(['error' => 'هذا العلامه غير موجود']);


            DB::beginTransaction();

            //update DB
            $tag ->update($request->except('_token','id'));  //update only for slug column

            //update translations
            $tag->name = $request->name;
            $tag ->save();

            DB::commit();
            return redirect()->route('admin.tags')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.tags')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }



    public function destroy($id)
    {
        try {
            $tag = Tag::find($id);
            if(!$tag)
                return redirect()->route('admin.tags')->with(['error' => 'هذا العلامه غير موجود']);

            // $tag ->makeVisible(['translations']);

            // to delete translations
            $tag ->translations()->delete();

            $tag ->delete();

            return redirect()->route('admin.tags')->with(['success' => 'تم الحذف بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('admin.tags')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }


}
