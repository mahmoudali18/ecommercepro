<?php

namespace App\Http\Requests;

use App\Http\Enumerations\CategoryType;
use Illuminate\Foundation\Http\FormRequest;

class MainCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' =>'required',
            //'type' =>'required|in:1,2',
            'type'  => 'required|in:'. CategoryType::MainCategory . "," . CategoryType::SubCategory,    // by use enum instead of 1 , 2
            'slug' =>'required|unique:categories,slug,'.$this->id,
            'photo' =>'required_without:id|mimes:jpg,jpeg,png',
        ];
    }
}
