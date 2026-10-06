<?php

namespace App\Http\Controllers;

class api_support extends Controller
{

    public function my_encrypt($data)
    {
        $key = env('APP_ENC_KEY');
        $converter = "";
        for($i = 1;$i <= $key;$i++){
            $converter = base64_encode($data);
            $data = $converter;
        }

        return $converter;
    }

    public function my_decrypt($data)
    {
        $key = env('APP_ENC_KEY');
        $converter = "";
        for($i = 1;$i <= $key;$i++){
            $converter = base64_decode($data);
            $data = $converter;
        }

        return $converter;
    }

    public function splitter($mode,$data)
    {
        $pattern = [
            '>>',
            '--'
        ];

        $arr = explode($pattern[$mode - 1],$data);
        return $arr;
    }
}
