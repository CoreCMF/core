<?php

return [
    'providers' => [
        Laravel\Passport\PassportServiceProvider::class,  //api认证
        Zizaco\Entrust\EntrustServiceProvider::class,     //权限管理
        CoreCMF\Storage\StorageServiceProvider::class,
    ],
];
