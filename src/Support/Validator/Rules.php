<?php

namespace CoreCMF\core\Support\Validator;


class Rules
{
    public $mobile;
    public $password;
    public $checkPassword;
    public function __construct()
    {
        $this->mobile();
        $this->password();
        $this->checkPassword();
    }
    public function mobile(
      $empty = '请输入手机号码',
      $error = '请输入正确的手机号码'
    )
    {

        return $this->mobile = "
            (rule, value, callback) => {
                if (value == undefined) {
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
    public function password(
      $checkPassword = 'checkPassword',
      $begin = 6,
      $end = 16,
      $empty = '请输入密码'
    )
    {
        return $this->password = "
            (rule, value, callback) => {
                if (value == undefined) {
                  callback(new Error('".$empty."'));
                } else {
                    if (!/^.{".$begin.",".$end."}$/.test(value)) {
                        callback(new Error('密码长度请控制在 ".$begin." 到 ".$end." 个字符'));
                    }
                    if (this.fromData['".$checkPassword."'] !== '') {
                        this.\$refs.bvefrom.validateField('".$checkPassword."');
                    }
                  callback();
                }
            }";
    }
    public function checkPassword(
      $password = 'password',
      $empty = '请再次输入密码'
    )
    {
        return $this->checkPassword = "
            (rule, value, callback) => {
                if (value == undefined) {
                  callback(new Error('".$empty."'));
                }
                if (value !== this.fromData['".$password."']) {
                  callback(new Error('两次输入密码不一致!'));
                } else {
                  callback();
                }
            }";
    }
}
