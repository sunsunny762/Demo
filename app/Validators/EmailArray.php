<?php
namespace App\Validators;

use Validator;

class EmailArray
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        $array = explode(',', $value);
        
        foreach($array as $email)
        {
            $email_to_validate['alert_email'][]=$email;
        }

        $rules = array('alert_email.*'=>'email:rfc,dns');
       
        $messages = array(
                'alert_email.*'=>trans('validation.email_array')
        );

        $validator = Validator::make($email_to_validate,$rules,$messages);
        if ($validator->passes()) {
            return true;
        } else {
            return false;
        }
    }
}