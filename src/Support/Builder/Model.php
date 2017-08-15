<?php
namespace CoreCMF\core\Support\Builder;

class Model
{
    private $request;
    private $model;
    private $total = false;
    private $orderBy = [ 'column'=>'id', 'direction'=>'ASC' ];
    private $page;
    private $pageSize;
    private $group;
    private $selectSearch;
    private $inputSearch;

    public function __construct()
    {
    }
    public function request($request)
    {
        $this->request = $request;
        return $this;
    }
    public function total(){
        $this->total = true;
        return $this;
    }
    public function orderBy($column,$direction){
        $this->orderBy['column'] = $column;
        $this->orderBy['direction'] = $direction;
        return $this;
    }
    public function group($group){
        $this->group = $this->get('tabIndex',$group);
        return $this;
    }
    public function page($pageSize){
        $this->pageSize = $this->get('pageSize',$pageSize);
        $this->page = $this->get('page',1);
        return $this;
    }
    public function search(){
        $this->selectSearch = $this->get('selectSearch','id');
        $this->inputSearch  = '%'.$this->get('inputSearch').'%';
        return $this;
    }
    public function getData($model){
        $this->model = $model;
        if ($this->total) {
            $data['total'] = $this->getTotal();
        }
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
        if ($this->group) {
            $data->where('group', '=', $this->group);  //根据分组获取
        }
        if ($this->inputSearch) {
            $data->where($this->selectSearch, 'like', $this->inputSearch); //搜索获取
        }
        if ($this->pageSize) {
            $data->skip(($this->page-1)*$this->pageSize)->take($this->pageSize);
        }
        return $data->get();
    }
    /**
     * 获取模型数据量
     */
    public function getTotal()
    {
        $data = $this->model->orderBy($this->orderBy['column'], $this->orderBy['direction']);
        if ($this->group) {
            $data->where('group', '=', $this->group);  //根据分组获取
        }
        if ($this->inputSearch != '%%') {
            $data->where($this->selectSearch, 'like', $this->inputSearch); //搜索获取
        }
        return $data->count();
    }
    /**
     * 获取默认值
     */
    public function get($key, $default=null)
    {
        return empty($this->request->$key) ? $default : $this->request->$key;
    }

}
