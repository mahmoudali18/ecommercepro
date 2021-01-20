<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Enumerations\CategoryType;
use App\Http\Requests\GeneralProductRequest;
use App\Http\Requests\MainCategoryRequest;
use App\Http\Requests\OptionsRequest;
use App\Http\Requests\ProductImagesRequest;
use App\Http\Requests\ProductPriceRequest;
use App\Http\Requests\ProductStockRequest;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Option;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class OptionsController extends Controller
{

    public function index()
    {
        $options = Option::with(['product' => function ($prod) {
            $prod->select('id');     //name , description ,short-description   return default by package
        }, 'attribute' => function ($attr) {
            $attr->select('id');
        }])->select('id', 'attribute_id', 'product_id', 'price')->paginate(PAGINATION_COUNT); //name return default by package
        return view('dashboard.options.index', compact('options'));
    }


    public function create()
    {
        $data = [];
        $data['products'] = Product::active()->select('id')->orderBy('id', 'DESC')->get();
        $data['attributes'] = Attribute::select('id')->orderBy('id', 'DESC')->get();
        //return $data;
        return view('dashboard.options.create', $data);
    }


    public function store(OptionsRequest $request)
    {
        try {
            //return $request;

            DB::beginTransaction();

            $option = Option::create($request->except(['_token']));

            //save translations
            $option->name = $request->name;
            $option->save();


            DB::commit();
            return redirect()->route('admin.options')->with(['success' => 'تم الاضافه بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.options')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }



    public function edit($option_id)
    {
        $data = [];
        $data['option'] = Option::find($option_id);
        $data['products'] = Product::active()->select('id')->orderBy('id', 'DESC')->get();
        $data['attributes'] = Attribute::select('id')->orderBy('id', 'DESC')->get();
       // return $data;
        return view('dashboard.options.edit', $data);


    }


    public function update(OptionsRequest $request,$option_id){

        try {

            $option= Option::find($option_id);
            if(!$option)
                return redirect()->route('admin.options')->with(['error' => 'this option doesn\'t exists']);

            DB::beginTransaction();

            $option->update($request->only(['product_id','attribute_id','price']));

            //save translations
            $option->name = $request->name;
            $option->save();

            DB::commit();
            return redirect()->route('admin.options')->with(['success' => 'تم التحديث بنجاح']);
        }catch(\Exception $ex){
           // return $ex;
            DB::rollBack();
            return redirect()->route('admin.options')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }
    }




}
