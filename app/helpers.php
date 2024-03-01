<?php

use GuzzleHttp\Client;
use Hashids\Hashids;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

if (!function_exists('validateMsisdn')){
    function validateMsisdn($phone, $region = 'KE'){
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $kenyaNumberProto = $phoneUtil->parse($phone, $region);
            $isValid = $phoneUtil->isValidNumber($kenyaNumberProto);
            if ($isValid) {
                $phone = $phoneUtil->format($kenyaNumberProto, PhoneNumberFormat::E164);
                return [
                    'isValid' => $isValid,
                    'msisdn' => substr($phone, 1)
                ];
            }
            return [
                'isValid' => $isValid,
                'msisdn' => $phone
            ];
        } catch (NumberParseException $e) {
            return [
                'isValid' => false,
                'msisdn' => $phone
            ];
        }
    }
}

// Generate random integer
if (!function_exists('genRandInt')){
    function genRandInt($charCount = 6){
        $i = 0;
        $pin = "";
        while($i < $charCount){
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin;
    }
}

// Generate greetings
if (!function_exists('genGreetings')){
    function genGreetings(){
        $currentTime = Carbon::now();
        if ($currentTime->hour >= 5 && $currentTime->hour < 12) {
            return 'Good Morning';
        } elseif ($currentTime->hour >= 12 && $currentTime->hour < 18) {
            return 'Good Afternoon';
        } else {
            return 'Good Evening';
        }
    }
}

// Generate slug
if (!function_exists('genSlug')){
    function genSlug($title, $model, $field = 'slug', $separator = '-'){
        $slug = Str::slug($title, $separator);
        $originalSlug = $slug;
        $count = 1;

        while ($model::where($field, '=', $slug)->exists()) {
            $slug = $originalSlug . $separator . $count;
            $count++;
        }

        return $slug;
    }
}

if (!function_exists('encodeString')){
    function encodeString($id){
        $hashids = new Hashids('vehicles-public', 8);
        return $hashids->encode($id);
    }
}

if (!function_exists('decodeString')){
    function decodeString($string){
        $hashids = new Hashids('vehicles-public', 8);
        return $hashids->decode($string);
    }
}
