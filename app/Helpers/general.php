<?php

//method to switch admin theme
function getFolder(){
    return app()->getLocale() === 'ar' ? 'css-rtl' : 'css';
}