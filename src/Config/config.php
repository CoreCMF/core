<?php

return [
    'providers' => [
        CoreCMF\admin\AdminServiceProvider::class,  //api认证
        Laravel\Passport\PassportServiceProvider::class,  //api认证
        Zizaco\Entrust\EntrustServiceProvider::class,     //权限管理
    ],
];
