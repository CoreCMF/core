<?php

namespace CoreCMF\Core\Support\Package;

use Artisan;
use CoreCMF\Core\App\Models\Package;
use CoreCMF\Core\App\Models\PackageConfig;
use CoreCMF\Core\Support\Package\Psr4;
use Illuminate\Filesystem\Filesystem;

class Manage
{
    protected $psr4;
    protected $packageModel;
    protected $packageConfigModel;
    protected $files;
    public function __construct(Psr4 $psr4, Package $packageRepo, PackageConfig $packageConfigRepo, Filesystem $files)
    {
        $this->psr4 = $psr4;
        $this->packageModel = $packageRepo;
        $this->packageConfigModel = $packageConfigRepo;
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
            $package['providers'] = json_encode($package['providers']);
            if (!empty($package['aliases'])) {
                $package['aliases'] = json_encode($package['aliases']);
            }
            $this->packageModel->firstOrCreate(['name' => $package['name']], $package);//不存在时创建
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
    public function install($id)
    {
        $package = $this->packageModel->find($id);
        if ($package->status == 'uninstall') {
            foreach (json_decode($package->providers) as $provider) {
                app()->register($provider);//注册服务
            }
            Artisan::call($package->install);//通过artisan命令安装模块
            $package->status = 'close';
            return $package->save();
        } else {
            return false;
        }
    }
    /**
     * [uninstall 卸载安装包]
     * @param    [type]         $package [description]
     * @return   [type]                  [description]
     * @Author   bigrocs
     * @QQ       532388887
     * @Email    bigrocs@qq.com
     * @DateTime 2018-01-25
     */
    public function uninstall($package)
    {
        foreach (json_decode($package->providers) as $provider) {
            app()->register($provider);//注册服务
        }
        //删除包配置数据
        $this->packageConfigModel->uninstallPackage($package->name);
        return  Artisan::call($package->uninstall);//通过artisan命令卸载模块
    }
}
