<?php

namespace CoreCMF\core\Support\Validator;


class Rules
{
    public $mobile;
    public $password;
    public $CheckPassword;
    public function __construct()
    {
        $this->mobile();
    }
    public function mobile(
      $empty = '请输入手机号码',
      $error = '请输入正确的手机号码'
    )
    {

        return $this->mobile = "
            (rule, value, callback) => {
                if (value === '') {
                  callback(new Error('".$empty."'));
                } else {
                    if (!/^1[3578]\d{9}$/.test(value)) {
                        callback(new Error('".$error."'));
                    }
                    callback();
                }
            }
        ";
    }
}
