<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeRequest;
use App\Http\Requests\BrandRequest;
use App\Models\Attribute;
use App\Models\Brand;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;

class AttributesController extends Controller
{
    public function index()
    {
        $attributes = Attribute::orderBy('id','DESC')->paginate(PAGINATION_COUNT);
        return view('dashboard.attributes.index', compact('attributes'));
    }



    public function create()
    {
        return view('dashboard.attributes.create');
    }



    public function store(AttributeRequest $request)
    {
        try {

            DB::beginTransaction();

            $attribute = Attribute::create($request ->except('_token'));

            //save translations
            $attribute -> name = $request ->name;
            $attribute ->save();

            DB::commit();
            return redirect()->route('admin.attributes')->with(['success' => 'تم الاضافه بنجاح']);
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->route('admin.attributes')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }



    public function edit($id)
    {
        $attribute = Attribute::find($id);
        if (!$attribute)
            return redirect()->route('admin.attributes')->with(['error' => 'هذا العنصر غير موجود']);

        return view('dashboard.attributes.edit', compact('attribute'));
    }



    public function update($id, AttributeRequest $request)
    {
        try {

            // return $request;

            $attribute = Attribute::find($id);
            if(!$attribute)
                return redirect()->route('admin.attributes')->with(['error' => 'هذا الخاصيه غير موجود']);


            DB::beginTransaction();


            //update DB

            //save translations
            $attribute -> name = $request ->name;
            $attribute ->save();

            DB::commit();
            return redirect()->route('admin.attributes')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.attributes')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }



    public function destroy($id)
    {
        try {
            $attribute = Attribute::find($id);
            if(!$attribute)
                return redirect()->route('admin.attributes')->with(['error' => 'هذا الخاصيه غير موجود']);

            $attribute->translations()->delete();
            $attribute ->delete();

            return redirect()->route('admin.attributes')->with(['success' => 'تم الحذف بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('admin.attributes')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }


}
