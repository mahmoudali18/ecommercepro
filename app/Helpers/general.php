<?php

define('PAGINATION_COUNT',10);


//method to switch admin theme
function getFolder(){
    return app()->getLocale() === 'ar' ? 'css-rtl' : 'css';
}


//to save image
function uploadImage($folder,$image){
    $image->store('/',$folder);
    $filename = $image->hashName();
    return $filename;
}
