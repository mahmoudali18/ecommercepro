<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Admin;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function editProfile()
    {
        // return  auth('admin')-> user();
        $admin = Admin::find(auth('admin')->user()->id);
        return view('dashboard.profile.edit', compact('admin'));
    }


    public function updateProfile(ProfileRequest $request)
    {
        try {

            //return $request;

            $admin = Admin::find(auth('admin')->user()->id);

            //update password if exist
            if($request -> filled('password'))          //if request has password and value of password
                 $request -> merge(['password' => bcrypt($request->password)]);


            unset($request['id']);                              //to delete id from return request
            unset($request['password_confirmation']);          //to delete password_confirmation from return request
            $admin->update($request->all());

            return redirect()->back()->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            // return $ex;
            return redirect()->back()->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }



}
