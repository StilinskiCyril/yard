<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use libphonenumber\PhoneNumberToCarrierMapper;
use libphonenumber\PhoneNumberUtil;

class ValidateMsisdn implements ValidationRule
{
    public $uniqueCheck;
    public $model;
    public $region;
    public $safaricomCheck;
    public $column;
    public $sourceParam;

    public function __construct($uniqueCheck = true, $model = 'User', $region = 'KE', $safaricomCheck = false, $column = 'msisdn', $sourceParam = 'msisdn')
    {
        $this->uniqueCheck = $uniqueCheck;
        $this->model = 'App\\Models\\'.$model;
        $this->region = $region;
        $this->safaricomCheck = $safaricomCheck;
        $this->column = $column;
        $this->sourceParam = $sourceParam;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validation = validateMsisdn($value, $this->region);

        if (!$validation['isValid']) {
            $fail('Phone number '.$validation['msisdn']. ' is invalid');
        }

        if ($this->uniqueCheck){
            $count = $this->model::where($this->column, $validation['msisdn'])->first();
            if ($count) {
                $fail('Phone number '.$validation['msisdn']. ' already taken');
            }
        }
        if ($this->safaricomCheck){
            $carrierMapper = PhoneNumberToCarrierMapper::getInstance();
            $chNumber = PhoneNumberUtil::getInstance()->parse($validation['msisdn'], "KE");
            $msisdn_network = $carrierMapper->getNameForNumber($chNumber, 'en');
            if (strtolower($msisdn_network) != 'safaricom') {
                $fail('Phone number '.$validation['msisdn']. ' is not a safaricom number');
            }
        }
        request()->merge([$this->sourceParam => $validation['msisdn']]);
    }
}
