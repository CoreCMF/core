<?php

namespace CoreCMF\Core\Support\Package;

use Artisan;
use CoreCMF\Core\App\Models\Package;
use CoreCMF\Core\Support\Package\Psr4;
use Illuminate\Filesystem\Filesystem;

class Manage
{
    protected $psr4;
    protected $packageModel;
    protected $files;
    public function __construct(Psr4 $psr4, Package $packageRepo, Filesystem $files)
    {
        $this->psr4 = $psr4;
        $this->packageModel = $packageRepo;
        $this->files = $files;
    }
    /**
     * [updatePackage 更新数据库包]
     * @return   [type]         [description]
     * @Author   bigrocs
     * @QQ       532388887
     * @Email    bigrocs@qq.com
     * @DateTime 2018-01-03
     */
    public function updatePackage()
    {
        $packages = $this->getPackage();
        foreach ($packages as $package) {
            $package['providers'] = serialize($package['providers']);
            $this->packageModel->create($package);
        }
    }
    /**
     * [getPackage 获取corecmf包扩展信息]
     * @return   [type]         [description]
     * @Author   bigrocs
     * @QQ       532388887
     * @Email    bigrocs@qq.com
     * @DateTime 2018-01-03
     */
    public function getPackage()
    {
        $packages = [];
        if ($this->files->exists($path = app()->basePath().'/vendor/composer/installed.json')) {
            $packages = json_decode($this->files->get($path), true);
        }
        return collect($packages)->mapWithKeys(function ($package) {
            return [$this->format($package['name']) => $package['extra']['corecmf'] ?? []];
        })->filter()->all();
    }

    /**
     * Format the given package name.
     *
     * @param  string  $package
     * @return string
     */
    protected function format($package)
    {
        return str_replace(app()->basePath().'/', '', $package);
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
            if (empty($this->packageModel->checkName($config['name']))) {
                $this->packageModel->create($config);
                app()->register($config['serviceProvider']);//注册服务
                Artisan::call($config['install']);//通过artisan命令安装模块
                return [
                          'message'   => '模块安装成功.',
                          'type'      => 'success',
                      ];
            } else {
                return [
                          'message'   => '模块插件已存在请勿重复安装.',
                          'type'      => 'warning',
                      ];
            }
        } else {
            return [
                      'message'   => $check,
                      'type'      => 'warning',
                  ];
        }
    }
    public function uninstall($artisan)
    {
        return  Artisan::call($artisan);//通过artisan命令卸载模块
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
            } else {
                return [
                            'message'   => '未能找到插件配置文件!请确认您的插件包是否正确。',
                            'type'      => 'error',
                        ];
            }
        } else {
            return [
                        'message'   => '未能找到插件命名空间!请确认您输入的命名空间是否正确。',
                        'type'      => 'error',
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
        $keys = ['name','title','description','author','version','serviceProvider','install','uninstall'];
        foreach ($keys as $key) {
            if (array_key_exists($key, $config)) {
                $status = true;
            } else {
                $message .= ' \''.$key.'\'';
                $status = false;
            }
        }
        return $status? true: $message;
    }
}
