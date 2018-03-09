<?php

namespace CoreCMF\Core\App\Models;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\Model;

class PassportClient extends Model
{
    public $table = 'oauth_clients';

    /**
     * [getPasswordToken 获取密码授权令牌]
     * @param    [type]         $username [description]
     * @param    [type]         $password [description]
     * @param    string         $scope    [description]
     * @return   [type]                   [description]
     * @Author   bigrocs
     * @QQ       532388887
     * @Email    bigrocs@qq.com
     * @DateTime 2018-03-09
     */
    public function getPasswordToken($username, $password, $scope = '')
    {
        $http = new HttpClient();
        $clientSecret = $this->where('id', 2)->first()['secret'];
        try {
            $response = $http->post(url('oauth/token'), [
               'form_params' => [
                   'grant_type' => 'password',
                   'client_id' => '2',
                   'client_secret' => $clientSecret,
                   'username' => $username,
                   'password' => $password,
                   'scope' => $scope,
               ],
            ]);
        } catch (ClientException $e) {
            return ['statusCode' => $e->getResponse()->getStatusCode()];//返回错误代码
        }
        return json_decode((string)$response->getBody(), true);
    }
}
