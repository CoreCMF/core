<?php

namespace CoreCMF\Core\Support\Module;

use CoreCMF\Core\Models\Module as moduleModule;
use CoreCMF\Core\Support\Module\Psr4;

class Module
{
    protected $psr4;
    protected $moduleModel;
    public function __construct(Psr4 $psr4,moduleModule $moduleRepo)
    {
        $this->psr4 = $psr4;
        $this->moduleModel = $moduleRepo;
    }

    public function namespaceDir($namespace)
    {
        $psr4 = require base_path().'/vendor/composer/autoload_psr4.php';
        $namespace = str_replace('\\\\',"\\",$namespace.'\\');//防止忘记加／
        return array_key_exists($namespace,$psr4)? $psr4[$namespace][0]: false;
    }
    /**
     * [namespaceInstall 通过命名空间安装模块]
     * @param  [type] $namespace [description]
     * @return [type]            [description]
     */
    public function namespaceInstall($namespace)
    {
        $namespaceDire = $this->psr4->namespaceDir($namespace);
        if ($namespaceDire) {
            $configDir = $namespaceDire.'/Config/config.php';
            if (is_file($configDir)) {
                $config = include $configDir;
                return $this->install($config);
            }else{
                return [
                            'message'   => '未能找到插件配置文件!请确认您的插件包是否正确。',
                            'type'      => 'error',
                        ];
            }
        }else{
            return [
                        'message'   => '未能找到插件命名空间!请确认您输入的命名空间是否正确。',
                        'type'      => 'error',
                    ];
        }
    }
    /**
     * [install 通过配置信息安装模块]
     * @param  [type] $config [description]
     * @return [type]         [description]
     */
    public function install($config)
    {
        $check = $this->checkConfig($config);
        if ($check === true) {
            $name = $this->moduleModel->where('name', $config['name'])->first();
            $module = $this->moduleModel->create($config);
                    dd($addon);
        }else{
            return [
                      'message'   => $check,
                      'type'      => 'warning',
                  ];
        }
    }
    /**
     * [checkConfig 检查配置文件参数]
     * @param  [type] $config [description]
     * @return [type]         [description]
     */
    public function checkConfig($config)
    {
        $status = false;
        $message = '配置文件缺少下面参数';
        $keys = ['name','title','description','author','version','serviceProvider'];
        foreach ($keys as $key) {
            if (array_key_exists($key,$config)) {
                $status = true;
            }else{
                $message .= ' \''.$key.'\'';
                $status = false;
            }
        }
        return $status? true: $message;
    }

}
