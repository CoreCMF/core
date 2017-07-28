<?php

namespace CoreCMF\core\Support\Validator;


class Rules
{
    public $mobile;
    public $password;
    public $checkPassword;
    public $asyncField;
    public function __construct()
    {
        $this->mobile();
        $this->password();
        $this->checkPassword();
    }
    public function asyncField($url, $name)
    {
        return "
              axios({
                method: 'post',
                url:'".$url."',
                data: {
                  ".$name.":this.fromData.".$name."
                }
              }).then(function(Response){
                callback();
              })
              .catch(function (error) {
                callback(error.response.data.callback);
              });
          ";
    }
    public function mobile(
      $empty = '请输入手机号码',
      $error = '请输入正确的手机号码'
    )
    {

        return $this->mobile = "
            (rule, value, callback) => {
                if (value == undefined) {
                  callback('".$empty."');
                } else {
                    if (!/^1[3578]\d{9}$/.test(value)) {
                        callback('".$error."');
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
                  callback('".$empty."');
                } else {
                    if (!/^.{".$begin.",".$end."}$/.test(value)) {
                        callback('密码长度请控制在 ".$begin." 到 ".$end." 个字符');
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
                  callback('".$empty."');
                }
                if (value !== this.fromData['".$password."']) {
                  callback('两次输入密码不一致!');
                } else {
                  callback();
                }
            }";
    }
}
