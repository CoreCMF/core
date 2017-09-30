<?php

namespace CoreCMF\Core\App\Models;

use File;
use Auth;
use Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Upload.
 */
class Upload extends Model
{

    public $table = 'core_uploads';

    public $fillable = [
        'name',
        'path',
        'extension',
        'size',
        'md5',
        'sha1',
        'disk',
        'download',
        'sort',
        'status',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string',
    ];

    /**
     * [$imageRules 验证图片格式].
     *
     * @var [type]
     */
    public static $imageRules = [
        'file' => 'required|mimes:png,gif,jpeg,jpg,bmp',
    ];
    /**
     * [$imageMessages 验证图片错误返回信息].
     *
     * @var [type]
     */
    public static $imageMessages = [
        'file.mimes' => '上传图片格式错误',
        'file.required' => '图片不存在',
    ];

    /**
     * [$fileRules 验证文件格式].
     *
     * @var [type]
     */
    public static $fileRules = [
        'file' => 'required|mimes:doc,zip,rar',
    ];
    /**
     * [$fileMessages 验证文件错误返回信息].
     *
     * @var [type]
     */
    public static $fileMessages = [
        'file.mimes' => '上传文件格式错误',
        'file.required' => '文件不存在',
    ];

    protected $appends = ['url'];

    /**
     * [getUrlAttribute 根据驱动 通过path获取图片url]
     * @return [type] [description]
     */
    public function getUrlAttribute()
    {
        return Storage::disk($this->attributes['disk'])->url($this->attributes['path']);
    }
    /**
     * [imageRemote 图片远程保存]
     * @param  [type] $imageUrl [远程图片url]
     * @param  [type] $path     [保存本地路径]
     * @return [type]           [返回图片成功失败信息以及图片数据库信息]
     */
    public function imageRemote($imageUrl, $imageName ,$path=null)
    {
        try {
            $client = new Client();
            $imageData = $client->request('get',$imageUrl)->getBody()->getContents();
            $extension = strpos('.png.gif.jpeg.jpg.bmp',strrchr($imageUrl, "."))? strrchr($imageUrl, "."): 'jpg';//文件类型检测
            return $this->storageFile($imageData, $imageName, 'images'.DIRECTORY_SEPARATOR.$path, $extension);//图片保存
        } catch (Exception $e) {
            return;
        }
    }
    /**
     * [imageUpload 上传图片模型].
     */
    public function imageUpload($image,$path=null)
    {
        /**
         * [$validator 验证图片格式].
         *
         * @var [type]
         */
        $validator = Validator::make($image, self::$imageRules, self::$imageMessages);
        if ($validator->fails()) {
            return [
                'error' => true,
                'type' => 'error',
                'message' => $validator->messages()->first(),
                'code' => 400,
            ];
        }
        $response = $this->upload($image, 'images'.DIRECTORY_SEPARATOR.$path);

        return $response;
    }

    /**
     * [imageUpload 上传文件模型].
     */
    public function fileUpload($file,$path=null,$fileExtension=[])
    {
        /**
         * [$validator 验证文件格式].
         *
         * @var [type]
         */
        $extension = $file['file']->getClientOriginalExtension();
        $validator = Validator::make($file, self::$fileRules, self::$fileMessages);
        if ($fileExtension) {
            if (!in_array($extension,$fileExtension)) {
              return [
                  'error' => true,
                  'type' => 'error',
                  'message' => $validator->messages()->first(),
                  'code' => 400,
              ];
            }
        }else{
            if ($validator->fails()) {
                return [
                    'error' => true,
                    'type' => 'error',
                    'message' => $validator->messages()->first(),
                    'code' => 400,
                ];
            }
        }

        $response = $this->upload($file, 'file'.DIRECTORY_SEPARATOR.$path);

        return $response;
    }
    /**
     * [upload 上传文件模型]
     * @param  [type] $file [description]
     * @param  [type] $path [description]
     * @return [type]       [description]
     */
    public function upload($file, $path)
    {
        $fileRealPath = $file['file']->getRealPath();
        $fileName     = $file['file']->getClientOriginalName();
        $extension    = $file['file']->getClientOriginalExtension();
        return $this->storageFile(file_get_contents($fileRealPath), $fileName, $path, $extension);//存储文件
    }
    /**
     * [storageFile 文件保存模型]
     * @param  [type] $imageData [文件数据流]
     * @param  [type] $path      [保存路径]
     * @param  [type] $extension [保存类型]
     * @return [type]            [返回图片成功失败信息以及图片数据库信息]
     */
    public function storageFile($fileData, $imageName, $path=null ,$extension)
    {
        $fileInfo['name']= $imageName;
        $fileInfo['md5']= md5($fileData);
        $fileInfo['sha1'] = sha1($fileData);
        $fileInfo['size'] = strlen($fileData);
        $fileInfo['extension'] = $extension;
        $fileInfo['path'] = $path.DIRECTORY_SEPARATOR.$fileInfo['md5'].'.'.$extension; //路径
        $fileInfo['disk'] = config('filesystems.default');//此处后期开发上传文件驱动选择 接口 创建监控事件

        $fileObject = $this->checkFile($fileData, $fileInfo['md5'], $fileInfo['sha1']);//检查文件是否存在数据库中
        if ($fileObject) {
            return [
                'title' => '上传成功',
                'message' => '上传成功!发现相同文件直接返回存储文件数据!',
                'uploadData' => $fileObject,
                'type'      => 'success',
            ];
        }else{
            //保存文件
            if ($this->putFile($fileData, $fileInfo['path'])) {
                // $fileInfo['url'] = Storage::url($fileInfo['path']);//获取访问url
                $uploadObject = $this->createFileInfo($fileInfo);//文件信息写入数据库
                return [
                    'message' => '文件上传成功!',
                    'uploadData' => $uploadObject,
                    'type'      => 'success',
                ];
            }else{
                return [
                    'message' => '文件上传失败!不要问我为什么我也不知道!要不你问下程序猿？',
                    'type'      => 'error',
                ];
            }
        }
    }
    /**
     * [checkFile 检查文件是否存在]
     * @param  [type] $fileData [文件数据流]
     * @return [type]           [description]
     */
    public function checkFile($fileData, $md5, $sha1){
        $fileObject = $this->where('md5', $md5)->where('sha1', $sha1)->first();
        return $fileObject? $fileObject :false;
    }
    /**
     * [putFile 增加保存文件]
     * @param  [type] $fileData [文件数据流]
     * @param  [type] $path     [文件路径]
     * @return [type]           [description]
     */
    public function putFile($fileData, $path){
        return Storage::put($path, $fileData); //保存文件
    }
    /**
     * [createFileInfo 存储数据库文件信息]
     * @param  [type] $fileInfo [description]
     * @return [type]           [description]
     */
    public function createFileInfo($fileInfo)
    {
        $fileInfo['uid'] = Auth::id(); //后期必须验证用户ID
        $fileInfo['download'] = 0;
        $fileInfo['status'] = 1;
        $fileInfo['sort'] = 0;
        return $this->create($fileInfo); //把上传文件信息写入数据库
    }
    /**
     * [fileDelete 根据文件ID删除文件].
     */
    public function fileDelete($ids)
    {
        if (empty($ids)) {
            return [
                'message' => '请选择要操作的数据',
                'status' => 0,
                'code' => 200,
            ];
        }
        if (!is_array($ids)) {
            $ids = [$ids]; //转化为数组
        }
        //获取数据表主键
        $modelKeyName = $this->getKeyName();
        $fileObject = $this->whereIn($modelKeyName, $ids)->get();
        foreach ($fileObject as $key => $file) {
            Storage::delete($file->path);
        }
    }
    /**
     * [getUploadWhereOne 获取上传文件其中一个].
     */
    public function getUploadWhereFirst($id)
    {
        $uploadObject = $this->where(['id' => $id])->first();
        if (!$uploadObject) {
            $uploadObject = (object) [
                'code' => 404,
                'name' => '未找到图片',
                'url' => asset('storage/core/img/404.jpg'),
            ];
        }
        return $uploadObject;
    }
}
