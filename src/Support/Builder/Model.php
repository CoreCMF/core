<?php
namespace CoreCMF\Core\Support\Builder;

class Model
{
    private $request;
    private $model;
    private $orderBy = [ 'column'=>'id', 'direction'=>'ASC' ];
    private $page;
    private $pageSize;
    private $group = null;
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
    public function orderBy($column, $direction)
    {
        $this->orderBy['column'] = $column;
        $this->orderBy['direction'] = $direction;
        return $this;
    }
    /**
     * 分组
     * group tabIndex 允许获取这两个post字段为分组
     * tabIndex tabs的请求字段
     * group 常规请求字段
     */
    public function group($group)
    {
        $requestGroup = $this->request->group? $this->request->group: $this->request->tabIndex;
        $this->group = $requestGroup? $requestGroup: $group;
        return $this;
    }
    public function parent($name, $parent, $indentField='name')
    {
        $this->parent['name'] = $name;
        $this->parent['parent'] = $parent;
        $this->parent['indentField'] = $indentField;
        return $this;
    }
    // 分页
    public function pageSize($pageSize)
    {
        $this->pageSize = $this->get('pageSize', $pageSize);
        $this->page = $this->get('page', 1);
        return $this;
    }
    // 搜索
    public function search()
    {
        $this->selectSearch = $this->get('selectSearch', 'id');
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
    public function getData($model)
    {
        $this->model = $model;
        $this->search();//搜索
        $data['total'] = $this->getTotal();
        if ($this->pageSize) {
            $data['pageSize'] = $this->pageSize;
        }
        $modelData = $this->modelData();
        if ($this->parent) {
            $modelData = $this->toFormTree($modelData);
        }
        $data['model'] = $modelData;
        return $data;
    }
    /**
     * 获取树形结构数据
     */
    public function getDataTree($model)
    {
        $this->model = $model;
        return $this->dataToTree($this->modelData());
    }
    /**
     * 获取模型数据
     */
    public function modelData()
    {
        $data = $this->model->orderBy($this->orderBy['column'], $this->orderBy['direction']);
        // 分组
        if (!empty($this->group)||$this->group ==='0') {
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
        return $modelData;
    }
    /**
     * 集合数据转Select选择数据
     * $modelData 集合数据
     * $key 选择后提交的数据字段名
     * $name 显示名称的数据字段名
     */
    public function toSelectData($modelData, $key, $name)
    {
        $selectData = [];
        foreach ($modelData as $value) {
            $selectData[$value->name] = $value->display_name;
        }
        return $selectData;
    }
    /**
     * 获取模型数据数量
     */
    public function getTotal()
    {
        $data = $this->model->orderBy($this->orderBy['column'], $this->orderBy['direction']);
        if (!empty($this->group)||$this->group ==='0') {
            $data->where('group', '=', $this->group);  //根据分组获取
        }
        if ($this->inputSearch) {
            $data->where($this->selectSearch, 'like', $this->inputSearch); //搜索获取
        }
        return $data->count();
    }
    private function toFormTree($modelData)
    {
        $dataTree= $this->dataToTree($modelData);
        return $this->_toFormTree($dataTree, $this->parent['indentField']);
    }
    /**
     * 循环数结构改为数组并且增加字段缩进
     * 可以转化无限级树形结构为数组并且缩进
     * $dataTree 数据
     * $level 循环次数
     * $indentField 缩进字段
     */
    private function _toFormTree($dataTree, $indentField, $level = 0)
    {
        $dataTree->map(function ($item, $key) use ($dataTree,$indentField,$level) {
            $title_prefix = str_repeat("　", $level). "┝ ";
            $item->$indentField   = $level == 0 ? $item->$indentField : $title_prefix . $item->$indentField;
            if ($item->children) {
                $children = $item->children;
                unset($item->children);//删除子类数据
                $this->mergeTree->push($item); //添加进合集
                $this->_toFormTree($children, $indentField, $level+1);//循环子类
            } else {
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
        return $modelData->filter(function ($data, $key) use ($modelData) {
            $parent = $this->parent['parent'];
            if (empty($data->$parent)) {
                $data = $this->children($data, $modelData);
            }
            return $data->$parent == null;
        });
    }
    /**
     * 循环获取子数据(可无限级设置)]
     */
    public function children($data, $modelData)
    {
        $children = $modelData->filter(function ($subData, $key) use ($data,$modelData) {
            $name = $this->parent['name'];
            $parent = $this->parent['parent'];
            if ($subData->$parent == $data->$name) {
                $subData = $this->children($subData, $modelData);
            }
            return $subData->$parent == $data->$name;
        });
        if (!$children->isEmpty()) {
            $data->children = $children;
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

    /**
     * 数据批量保存
     * $model 模型
     * $name 保存字段
     */
    public function save($model, $name=null)
    {
        if ($name) {
            foreach ($name as $key) {
                $model->$key =  $this->request->$key;
            }
            return $model->save();
        } else {
            return $model->create($this->request->all());
        }
    }
    /**
     * 数据保存
     */
    public function update($model, $name=null)
    {
        $input = $this->request->all();
        return $model->find($this->request->id)->fill($input)->save();
    }
    /**
     * 数据批量删除
     */
    public function delete($model)
    {
        $input = $this->request->all();
        foreach ($input as $id => $value) {
            if ($value == 'delete') {
                $response = $model->find($id)->forceDelete();
            }
        }
        return true;
    }
    /**
     * [status 批量更改数状态]
     * @param    [type]         $model [description]
     * @return   [type]                [description]
     * @Author   bigrocs
     * @QQ       532388887
     * @Email    bigrocs@qq.com
     * @DateTime 2018-01-25
     */
    public function status($model)
    {
        $input = $this->request->all();
        foreach ($input as $id => $value) {
            if ($value == 'close' || $value == 'open') {
                $response = $model->where('id', '=', $id)->update(['status' => $value]);
            }
        }
        return $value;
    }
}
