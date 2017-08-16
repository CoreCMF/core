<?php
namespace CoreCMF\core\Support\Builder;

class Model
{
    private $request;
    private $model;
    private $orderBy = [ 'column'=>'id', 'direction'=>'ASC' ];
    private $page;
    private $pageSize;
    private $group;
    private $selectSearch;
    private $inputSearch;
    private $relations;
    private $parent;
    private $mergeTree;

    public function __construct()
    {
        $this->mergeTree = collect([]);
    }
    public function request($request)
    {
        $this->request = $request;
        return $this;
    }
    // 排序
    public function orderBy($column,$direction){
        $this->orderBy['column'] = $column;
        $this->orderBy['direction'] = $direction;
        return $this;
    }
    // 分组
    public function group($group){
        $this->group = $this->get('tabIndex',$group);
        return $this;
    }
    public function parent($name, $parent)
    {
        $this->parent['name'] = $name;
        $this->parent['parent'] = $parent;
        return $this;
    }
    // 分页
    public function pageSize($pageSize){
        $this->pageSize = $this->get('pageSize',$pageSize);
        $this->page = $this->get('page',1);
        return $this;
    }
    // 搜索
    public function search(){
        $this->selectSearch = $this->get('selectSearch','id');
        $this->inputSearch  = '%'.$this->get('inputSearch').'%';
        return $this;
    }
    // 负载关联关系
    public function load($relations)
    {
        $this->relations = $relations;
        return $this;
    }
    // 获取数据
    public function getData($model){
        $this->model = $model;
        $this->search();//搜索
        $data['total'] = $this->getTotal();
        if ($this->pageSize) {
            $data['pageSize'] = $this->pageSize;
        }
        $data['model'] = $this->modelData();
        return $data;
    }
    /**
     * 获取模型数据
     */
    public function modelData(){
        $data = $this->model->orderBy($this->orderBy['column'], $this->orderBy['direction']);
        // 分组
        if ($this->group) {
            $data->where('group', '=', $this->group);  //根据分组获取
        }
        // 搜索
        if ($this->inputSearch) {
            $data->where($this->selectSearch, 'like', $this->inputSearch); //搜索获取
        }
        //分页
        if ($this->pageSize) {
            $data->skip(($this->page-1)*$this->pageSize)->take($this->pageSize);
        }
        //获取数据
        $modelData = $data->get();
        //懒惰渴求式加载关联数据
        if ($this->relations) {
            $modelData->load($this->relations);
        }
        if ($this->parent) {
            $modelData = $this->toFormatTree($modelData);
        }
        return $modelData;
    }
    /**
     * 获取模型数据数量
     */
    public function getTotal()
    {
        $data = $this->model->orderBy($this->orderBy['column'], $this->orderBy['direction']);
        if ($this->group) {
            $data->where('group', '=', $this->group);  //根据分组获取
        }
        if ($this->inputSearch) {
            $data->where($this->selectSearch, 'like', $this->inputSearch); //搜索获取
        }
        return $data->count();
    }
    private function toFormatTree($modelData)
    {
        $dataTree= $this->dataToTree($modelData);
        return $this->_toFormatTree($dataTree,'name');
    }
    private function _toFormatTree($dataTree, $title = 'title', $level = 0)
    {
        $dataTree->map(function ($item, $key) use($dataTree,$title,$level) {
            $title_prefix = str_repeat("　", $level * 2). "┝ ";
            $item->$title   = $level == 0 ? $item->$title : $title_prefix . $item->$title;
            if ($item->subDatas) {
                $subDatas = $item->subDatas;
                unset($item->subDatas);//删除子类数据
                $this->mergeTree->push($item); //添加进合集
                $this->_toFormatTree($subDatas, $title, $level+1);//循环子类
            }else{
                $this->mergeTree->push($item); //添加进合集
            }
        });
        return $this->mergeTree;
    }
    /**
     * 数据转化成树形结构
     */
    private function dataToTree($modelData)
    {
        return $modelData->filter(function ($data, $key) use($modelData) {
                    $parent = $this->parent['parent'];
                    if (empty($data->$parent)) {
                        $data = $this->subDatas($data,$modelData);
                    }
                    return $data->$parent == null;
                });
    }
    /**
     * 循环获取子数据(可无限级设置)]
     */
    public function subDatas($data,$modelData){
        $subDatas = $modelData->filter(function ($subData, $key) use($data,$modelData) {
            $name = $this->parent['name'];
            $parent = $this->parent['parent'];
            if ($subData->$parent == $data->$name) {
                $subData = $this->subDatas($subData,$modelData);
            }
            return $subData->$parent == $data->$name;
        });
        if (!$subDatas->isEmpty()) {
            $data->subDatas = $subDatas;
        }
        return $data;
    }
    /**
     * 获取默认值
     */
    public function get($key, $default=null)
    {
        return empty($this->request->$key) ? $default : $this->request->$key;
    }

}
