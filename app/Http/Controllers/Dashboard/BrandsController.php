<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;

class BrandsController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('id','DESC')->paginate(PAGINATION_COUNT);
        return view('dashboard.brands.index', compact('brands'));
    }



    public function create()
    {
        return view('dashboard.brands.create');
    }



    public function store(BrandRequest $request)
    {
        try {

            DB::beginTransaction();

            if (!$request->has('is_active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);


            //save photo
            $fileName ="";
            if($request->has('photo')){
                $fileName = uploadImage('brands',$request->photo);
            }

            $brand = Brand::create($request ->except('_token','photo'));

            //save translations
            $brand -> name = $request ->name;
            $brand -> photo = $fileName;
            $brand ->save();

            DB::commit();
            return redirect()->route('admin.brands')->with(['success' => 'تم الاضافه بنجاح']);
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->route('admin.brands')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }



    public function edit($id)
    {
        $brand = Brand::find($id);
        if (!$brand)
            return redirect()->route('admin.brands')->with(['error' => 'هذا الماركه غير موجود']);

        return view('dashboard.brands.edit', compact('brand'));
    }




    public function update($id, BrandRequest $request)
    {
        try {

            // return $request;

            $brand = Brand::find($id);
            if(!$brand)
                return redirect()->route('admin.brands')->with(['error' => 'هذا الماركه غير موجود']);


            DB::beginTransaction();
            //update photo if return in request
            if($request->has('photo')){
                $fileName = uploadImage('brands',$request->photo);
                Brand::where('id',$id)
                    ->update([
                        'photo' =>$fileName
                    ]);
            }


            //update DB
            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);


            $brand ->update($request ->except('_token','id','photo'));

            //save translations
            $brand -> name = $request ->name;
            $brand ->save();

            DB::commit();
            return redirect()->route('admin.brands')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.brands')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }



    public function destroy($id)
    {
        try {
            $brand = Brand::find($id);
            if(!$brand)
                return redirect()->route('admin.brands')->with(['error' => 'هذا الماركه غير موجود']);

            //delete image from folder assets/images/brands
            $image = Str::after($brand->photo,'assets/');     // assets/   after assets in path
            $image = base_path('public/assets/'.$image);
            unlink($image);  //method for delete

            $brand ->delete();

            return redirect()->route('admin.brands')->with(['success' => 'تم الحذف بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('admin.brands')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }


}
