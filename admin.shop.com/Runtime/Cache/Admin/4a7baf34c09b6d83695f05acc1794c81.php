<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ECSHOP 管理中心 - <?php echo ($meta_title); ?>列表 </title>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="http://admin.shop.com/Public/Admin/css/general.css" rel="stylesheet" type="text/css"/>
    <link href="http://admin.shop.com/Public/Admin/css/main.css" rel="stylesheet" type="text/css"/>
    <link href="http://admin.shop.com/Public/Admin/css/page.css" rel="stylesheet" type="text/css"/>
    <!--为以后子模板中的css留一个位置-->
</head>
<body>

<h1>
    <span class="action-span"><a href="<?php echo U('add');?>">添加<?php echo ($meta_title); ?></a></span>
    <span class="action-span1"><a href="index.php?act=main">ECSHOP 管理中心</a> </span><span id="search_id"
                                                                                         class="action-span1"> - <?php echo ($meta_title); ?>列表 </span>

    <div style="clear:both"></div>
</h1>

    <div class="form-div">
        <form action="<?php echo U();?>" name="searchForm">
            <img src="http://admin.shop.com/Public/Admin/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH"/>
            <input type="text" name="keyword" value='<?php echo ($_GET['keyword']); ?>' size="15"/>
            <input type="submit" value=" 搜索 " class="button"/>
        </form>
    </div>


    <input type="button" value="删除" class="ajax_post" href="<?php echo U('changeStatus');?>" class="button">
    <input type="button" value="显示" class="ajax_post" href="<?php echo U('changeStatus',array('status'=>1));?>" class="button">
    <input type="button" value="隐藏" class="ajax_post" href="<?php echo U('changeStatus',array('status'=>0));?>" class="button">


    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>ID<input type="checkbox" class="checked_all"></th>
                <th>属性名称</th><th>商品类型</th><th>属性类型</th><th>录入方式</th><th>可选值</th><th>排序</th><th>是否显示</th><th>分类简介</th>                <th>操作</th>
            </tr>
            <?php if(is_array($rows)): $i = 0; $__LIST__ = $rows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><tr>
                    <td width="50px"><?php echo ($row["id"]); ?><input type="checkbox" name="id[]" value="<?php echo ($row["id"]); ?>" class="ids"></td>
                    <td class="first-cell"><span><?php echo ($row["name"]); ?></span></td><td align='center'><?php echo ($row["goods_type_id"]); ?></td><td align='center'><?php echo ($row["type"]); ?></td><td align='center'><?php echo ($row["input_type"]); ?></td><td align='center'><?php echo ($row["option_values"]); ?></td><td align='center'><?php echo ($row["sort"]); ?></td><td align="center"><a class="ajax_get" href="<?php echo U('changeStatus',array('id'=>$row['id'],'status'=>1-$row['status']));?>"><img src="http://admin.shop.com/Public/Admin/images/<?php echo ($row["status"]); ?>.gif"/></a></td><td align='center'><?php echo ($row["intro"]); ?></td>                    <td align="center">
                        <a href="<?php echo U('edit',array('id'=>$row['id']));?>">编辑</a> |
                        <a class='ajax_get' href="<?php echo U('changeStatus',array('id'=>$row['id']));?>">移除</a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
        <div id="turn-page" class="page">
            <?php echo ($pageHtml); ?>
        </div>
        <!-- end brand list -->
    </div>

<div id="footer">
    共执行 3 个查询，用时 0.014502 秒，Gzip 已禁用，内存占用 1.334 MB<br/>
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
</div>
<script type="text/javascript" src="http://admin.shop.com/Public/Admin/js/jquery-1.11.3.js"></script>
<script type="text/javascript" src="http://admin.shop.com/Public/Admin/layer/layer.js"></script>
<script type="text/javascript" src="http://admin.shop.com/Public/Admin/js/common.js"></script>
<!--为以后的子模板预留js的位置-->
</body>
</html>