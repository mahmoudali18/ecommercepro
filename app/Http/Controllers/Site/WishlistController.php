<?php

namespace App\Http\Controllers\Site;


use App\Http\Controllers\Controller;

class WishlistController extends Controller
{

    public function store()
    {

        if (!auth()->user()->wishlistHas(request('productId'))) {
            auth()->user()->wishlist()->attach(request('productId'));

            return response()->json(['status' => true, 'wished' => true]);

        } else {
            return response()->json(['status' => true, 'wished' => false]);
        }
    }


}
