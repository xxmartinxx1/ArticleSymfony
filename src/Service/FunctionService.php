<?php
namespace App\Service;

class FunctionService
{
    //Func return only positive number
    public function nagitive_check(int $value){
        if (isset($value)){
            if (substr(strval($value), 0, 1) == "-"){
                return 0;
            } else {
                return intval(abs($value));
            }
        }
    }

}
