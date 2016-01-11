<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/11
 * Time: 15:08
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

class BaseModel extends Model{
    // 是否批处理验证
    protected $patchValidate = true;

    /**
     * 得到分页要准备的数据
     * @return array
     */
    public function getPageResult($keyword) {
        $wheres = array();
        //搜索功能
        if (!empty($keyword)) {
            $wheres['name'] = array('like', "$keyword%");
        }
        //排除伪删除的数据
        $wheres['status'] = array('gt', -1);
        //每页显示记录数和
        $pageSize = 2;
        //伪删除之外的总的记录数
        $totalRows = $this->where($wheres)->count();
        //分页工具的实例化
        $page = new Page($totalRows, $pageSize);
        //分页主题设置
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');

        //组装分页链接
        $pageHtml = $page->show();
        //当前页也数超过总页数的处理
        if ($totalRows == 0) {//当总页数为0的情况
            $page->firstRow = 0;
        } elseif ($page->firstRow >= $page->totalRows) {//当前页的起始行数大于等于总行数就显示最后一页的数据
            $page->firstRow = $totalRows - $page->listRows;
        }
        //每页的要显示的数据
        $rows = $this->where($wheres)->limit($page->firstRow, $page->listRows)->select();
        return array('rows' => $rows, 'pageHtml' => $pageHtml);
    }

    /**
     * 获取状态大于-1的供货商数据
     * @return mixed
     */
    public function getList() {
        return $this->where(array('status' => array('gt', -1)))->select();
    }

    /**
     * 根据id更改供货商状态
     * @param $id   数据ID
     * @param int $status 数据状态,默认值为-1(移除)
     * @return bool
     */
    public function changeStatus($id, $status = -1) {
        $data = array('id' => array('in', $id), 'status' => $status);

        //如果状态改为-1(移除),就将供货商的名称后面加一个'_del'的后缀
        if ($status == -1) {
            $data['name'] = array('exp', "concat(name,'_del')");
        }

        return $this->save($data);
    }
}