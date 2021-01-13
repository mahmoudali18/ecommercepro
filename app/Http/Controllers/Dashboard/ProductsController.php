<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Enumerations\CategoryType;
use App\Http\Requests\GeneralProductRequest;
use App\Http\Requests\MainCategoryRequest;
use App\Http\Requests\ProductPriceRequest;
use App\Http\Requests\ProductStockRequest;
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
        $products = Product::select('id','slug','price','created_at')->paginate(PAGINATION_COUNT); //name return default by package
        return view('dashboard.products.general.index',compact('products'));
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
            //return $request;

            DB::beginTransaction();

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);


            $product = Category::create([
                'slug' =>$request->slug,
                'brand_id' =>$request->brand_id,
                'is_active' =>$request->is_active,
            ]);

            //save translations
            $product -> name = $request ->name;
            $product -> description = $request ->description;
            $product -> short_description = $request ->short_description;
            $product ->save();

            //save product categories
            $product ->categories()->attach($request->categories); //attach use to save array

            //save product tags
            $product->tags()->attach($request->tags);

            DB::commit();                       //admin.products.price
            return redirect()->route('admin.maincategories')->with(['success' => 'تم الاضافه بنجاح']);
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }



    public function getPrice($product_id)
    {
        return view('dashboard.products.prices.create')->with('id', $product_id);

    }



    public function saveProductPrice(ProductPriceRequest $request)
    {

        try {

            Product::whereId($request->product_id)->update($request->except(['_token','product_id']));

            return redirect()->route('admin.products')->with(['success' => 'تم الاضافه بنجاح']);

        }catch (\Exception $ex){
            return redirect()->route('admin.products')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }



    public function getStock($product_id){
        $product = Product::find($product_id);
        return view('dashboard.products.stock.create',compact('product'))->with('id',$product_id);
    }


    public function saveProductStock(ProductStockRequest $request)
    {

        try {

            //other method instead of use validation to handle  'qty' =>'required_if:manage_stock,==,1',    or use custom rule
            /*
            if ($request->manage_stock == 1 && $request->qty == null){

                   return redirect()->route('admin.products.stock', $request->product_id)->with(['error' => 'qty is required with management stock']);
            }
            */

            Product::whereId($request->product_id)->update($request->except(['_token','product_id']));

            return redirect()->route('admin.products')->with(['success' => 'تم الاضافه بنجاح']);

        }catch (\Exception $ex){
            return redirect()->route('admin.products')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }



    public function addImages($product_id){
        return view('dashboard.products.images.create')->withId($product_id);
    }





    /////////////////////////////////////start edit products //////////////////////////////

    public function edit($product_id)
    {

        $tags = Tag::select('id')->orderBy('id', 'DESC')->get();

        $brands = Brand::active()->select('id')->orderBy('id', 'DESC')->get();

        $categories = Category::active()->select('id')->orderBy('id', 'DESC')->get();

         $product = Product::find($product_id);

        return view('dashboard.products.general.edit', compact(['tags', 'brands', 'categories', 'product']));

    }//end of edit




    public function update(GeneralProductRequest $request, $product_id)
    {

        try {

            DB::beginTransaction();

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);



            $product = Product::find($product_id);

            $product->update($request->except('categories', 'tags'));  //save in products table

            //save many-to-many with categories and tags tables
            $product->categories()->sync($request->categories);

            $product->tags()->sync($request->tags);

            DB::commit();

            return redirect()->route('admin.products.price.edit', $product_id);

        } catch (\Exception $ex) {

            DB::rollback();

            return redirect()->route('admin.products')->with(['error' => 'حدث خطا ما برجاء المحاوبه لاحقا']);
        }

    }//end of update


    ///////////////////////////////////// end edit products //////////////////////////////










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
