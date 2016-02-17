<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ECSHOP 管理中心 - <?php echo ($meta_title); ?> </title>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="http://admin.shop.com/Public/Admin/css/general.css" rel="stylesheet" type="text/css"/>
    <link href="http://admin.shop.com/Public/Admin/css/main.css" rel="stylesheet" type="text/css"/>
    <link href="http://admin.shop.com/Public/Admin/css/common.css" rel="stylesheet" type="text/css"/>
    
    <link rel="stylesheet" href="http://admin.shop.com/Public/Admin/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">

</head>
<body>

<h1>
    <span class="action-span"><a href="<?php echo U('index');?>"><?php echo mb_substr($meta_title,2,null,'utf-8');?>列表</a></span>
    <span class="action-span1"><a href="index.php?act=main">ECSHOP 管理中心</a> </span><span id="search_id"
                                                                                         class="action-span1"> - <?php echo ($meta_title); ?> </span>

    <div style="clear:both"></div>
</h1>

    <div id="tabbar-div">
        <p>
            <span class="tab-front">通用信息</span>
            <span class="tab-back">详细描述</span>
            <span class="tab-back">会员价格</span>
            <span class="tab-back">商品属性</span>
            <span class="tab-back">商品相册</span>
            <span class="tab-back">关联文章</span>
        </p>
    </div>
    <div class="main-div">
        <form method="post" action="<?php echo U();?>">
            <table cellspacing="1" cellpadding="3" width="100%">
                <tr>
                    <td class="label">商品名称</td>
                    <td>
                        <input type='text' name='name' maxlength='60' value='<?php echo ($name); ?>'/> <span
                            class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">简称</td>
                    <td>
                        <input type='text' name='short_name' maxlength='60' value='<?php echo ($short_name); ?>'/> <span
                            class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品分类</td>
                    <td>
                        <input type="hidden" class="goods_category_id" name="goods_category_id" value="0">
                        <input type='text' class="goods_category_name" name='goods_category_name' maxlength='60' value='必须选择最子分类' disabled/>
                        <span class="require-field">*</span>
                        <style type="text/css">
                            .ztree {
                                margin-top: 10px;
                                border: 1px solid #617775;
                                background: #f0f6e4;
                                width: 220px;
                                height: auto;
                                overflow-y: scroll;
                                overflow-x: auto;
                            }
                        </style>
                        <ul id="treeDemo" class="ztree"></ul>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品品牌</td>
                    <td>
                        <?php echo arr2select('brand_id',$brands,$brand_id);?>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">供货商</td>
                    <td>
                        <?php echo arr2select('supplier_id',$suppliers,$supplier_id);?>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">本店价格</td>
                    <td>
                        <input type='text' name='shop_price' maxlength='60' value='<?php echo ($shop_price); ?>'/> <span
                            class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">市场价格</td>
                    <td>
                        <input type='text' name='market_price' maxlength='60' value='<?php echo ($market_price); ?>'/> <span
                            class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品LOGO</td>
                    <td>
                        <input type='hidden' class="logo" name='logo' maxlength='60' value="<?php echo ($logo); ?>"/>
                        <input id="goods_logo" name="goods_logo" type="file">
                        <div class="upload-img-box" <?php if(empty($id)): ?>style="display: none"<?php endif; ?>>
                            <div class="upload-pre-item">
                                <img src="http://itsource-goods.b0.upaiyun.com/<?php echo ($logo); ?>!m">
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="label">库存</td>
                    <td>
                        <input type='text' name='stock' maxlength='60' value='<?php echo ($stock); ?>'/> <span
                            class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品状态</td>
                    <td>
                        <input type='checkbox' class='goods_status' name='goods_status[]' value='1'/>推荐商品
                        <input type='checkbox' class='goods_status' name='goods_status[]' value='2'/>新品上架
                        <input type='checkbox' class='goods_status' name='goods_status[]' value='4'/>热卖商品
                        <input type='checkbox' class='goods_status' name='goods_status[]' value='8'/>疯狂抢购
                        <input type='checkbox' class='goods_status' name='goods_status[]' value='16'/>猜您喜欢
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">是否显示</td>
                    <td>
                        <input type='radio' class='status' name='status' value='1'/>是
                        <input type='radio' class='status' name='status' value='0'/>否
                        <span class="require-field">*</span>
                    </td>
                </tr>
            </table>
            <table style="display: none" cellspacing="1" cellpadding="3" width="100%">
                <tr>
                    <td>
                        <textarea id="editor" name="intro"><?php echo ($intro); ?></textarea>
                    </td>
                </tr>
            </table>
            <table style="display: none" cellspacing="1" cellpadding="3" width="100%">
                <?php if(is_array($members)): $i = 0; $__LIST__ = $members;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$member): $mod = ($i % 2 );++$i;?><tr>
                        <td class="label"><?php echo ($member["name"]); ?></td>
                        <td>
                            <input type='text' name='goods_member_prices[<?php echo ($member["id"]); ?>]' maxlength='60' value='<?php echo ($goods_member_prices[$member['id']]); ?>'/> <span class="require-field">*</span>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
            <table style="display: none" cellspacing="1" cellpadding="3" width="100%">
                <tr>
                    <td class="label">商品属性</td>
                    <td>
                        <input type='text' name='name3' maxlength='60' value='<?php echo ($name); ?>'/> <span
                            class="require-field">*</span>
                    </td>
                </tr>
            </table>
            <style type="text/css">
                .upload-img-gallery .upload-pre-item{
                    float: left;
                    margin: 5px;
                    position: relative;
                    width: 125px;
                    height: 125px;
                }
                .upload-img-gallery .upload-pre-item a{
                    position: absolute;
                    right: 0px;
                    top: 0px;
                    color: yellow;
                }
            </style>
            <table style="display: none" cellspacing="1" cellpadding="3" width="100%">
                <tr>
                    <td>
                        <div class="upload-img-gallery">
                            <?php if(is_array($goods_photos)): $i = 0; $__LIST__ = $goods_photos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods_photo): $mod = ($i % 2 );++$i;?><div class="upload-pre-item">
                                    <img src="http://itsource-goods.b0.upaiyun.com/<?php echo ($goods_photo["path"]); ?>!m">
                                    <a href="javascript:;" photoId="<?php echo ($goods_photo["id"]); ?>"><img src="http://admin.shop.com/Public/Admin/images/0.gif"/></a>
                                </div><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <hr/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="goods_gallery" name="goods_gallery" type="file">
                    </td>
                </tr>
            </table>
            <table cellspacing="1" cellpadding="3" width="100%" style="display: none">
                <tr>
                    <td colspan="3">
                        <img src="http://admin.shop.com/Public/Admin/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH">
                        文章标题 <input type="text" name="keyword" class="keyword">
                        <input type="button" value=" 搜索 " class="button searchArticle">
                    </td>
                </tr>
                <tr>
                    <th>可选文章</th>
                    <th>操作</th>
                    <th>跟该商品关联的文章</th>
                </tr>
                <tr>
                    <td width="45%">
                        <select class="left" size="20" style="width:100%" multiple="multiple">
                        </select>
                    </td>
                    <td align="center">
                        <p><input type="button" value=">>" class="leftO2right"></p>
                        <p><input type="button" value=">" class="left2right"></p>
                        <p><input type="button" value="<" class="right2left"></p>
                        <p><input type="button" value="<<" class="rightO2left"></p>
                    </td>
                    <td width="45%">
                        <select class="right" size="20" style="width:100%" multiple="multiple">
                            <?php if(is_array($goods_articles)): $i = 0; $__LIST__ = $goods_articles;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods_article): $mod = ($i % 2 );++$i;?><option value="<?php echo ($goods_article["article_id"]); ?>"><?php echo ($goods_article["article_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <div class="goods_article_id">
                            <?php if(is_array($goods_articles)): $i = 0; $__LIST__ = $goods_articles;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods_article): $mod = ($i % 2 );++$i;?><input type='hidden' name='article_ids[]' value='<?php echo ($goods_article["article_id"]); ?>'><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </td>
                </tr>
            </table>
            <div style="text-align: center">
                <input type="hidden" name="id" value="<?php echo ($id); ?>">
                <input type="submit" class="button ajax_post" value=" 确定 "/>
                <input type="reset" class="button" value=" 重置 "/>
            </div>
        </form>
    </div>


<div id="footer">
    共执行 1 个查询，用时 0.012502 秒，Gzip 已禁用，内存占用 1.337 MB<br/>
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
</div>
<script type="text/javascript" src="http://admin.shop.com/Public/Admin/js/jquery-1.11.3.js"></script>
<script type="text/javascript" src="http://admin.shop.com/Public/Admin/layer/layer.js"></script>
<script type="text/javascript" src="http://admin.shop.com/Public/Admin/js/common.js"></script>
<script type="text/javascript" src="http://admin.shop.com/Public/Admin/js/jquery.form.js"></script>
<script>
    $(function(){
        $('.status').val([<?php echo ((isset($status) && ($status !== ""))?($status):1); ?>]);
    });
</script>

    <script type="text/javascript" src="http://admin.shop.com/Public/Admin/zTree/js/jquery.ztree.core-3.5.js"></script>
    <script src="http://admin.shop.com/Public/Admin/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
    <script type="text/javascript" charset="utf-8" src="http://admin.shop.com/Public/Admin/uedit/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="http://admin.shop.com/Public/Admin/uedit/ueditor.all.min.js"> </script>

    <script type="text/javascript">
        $(function () {
            /******************商品的添加页面切换效果    开始***************************/
            $('#tabbar-div span').click(function(){
                $('#tabbar-div span').removeClass('tab-front').addClass('tab-back')
                $(this).removeClass('tab-back').addClass('tab-front');

                var index=$(this).index();
                $('.main-div form table').hide();
                $('.main-div form>table').eq(index).show();
                if(index==1){
                    var ue = UE.getEditor('editor',{
                        initialFrameHeight :400
                    });
                }
            });
            /******************商品的添加页面切换效果    结束***************************/

            /******************分类的树结构    开始*************************************/
            //树结构的设置
            var setting = {
                data: {
                    simpleData: {
                        enable: true,
                        pIdKey: "parent_id",
                    }
                },
                callback: {
                    beforeClick:function(treeId, treeNode){
                        if(treeNode.isParent){
                            layer.msg('必须选中最子分类!', {
                                time:1000,  //等待时间后关闭
                                offset: 0,  //设置位置
                                icon:2,  //设置提示框中的图标
                            });
                        }
                        return !treeNode.isParent;
                    },
                    onClick: function (event, treeId, treeNode) {
                        $('.goods_category_id').val(treeNode.id);
                        $('.goods_category_name').val(treeNode.name);
                    }
                }

            };
            //树的节点数据
            var zNodes = <?php echo ($zNodes); ?>;
            //得到一个树状结构
            var treeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            <?php if(empty($id)): ?>treeObj.expandAll(true);
            <?php else: ?>
                var node = treeObj.getNodeByParam("id", <?php echo ($goods_category_id); ?>);
                $('.goods_category_id').val(node.id);
                $('.goods_category_name').val(node.name);
                treeObj.selectNode(node);<?php endif; ?>
            /******************分类的树结构    结束*************************************/

            /******************图片上传插件    开始*************************************/
            $("#goods_logo").uploadify({
                height: 30,
                width: 120,
                'buttonText' : '上传图片',
//                'debug': true,
                'fileSizeLimit' : '1MB',
                'fileTypeExts' : '*.gif; *.jpg; *.png', //文件后缀名的限制
//                'formData'      : {'dir' : 'brand'},    //上传文件时额外传递的参数
                'formData'      : {'dir' : 'itsource-goods'},
                swf: 'http://admin.shop.com/Public/Admin/uploadify/uploadify.swf',
                uploader: "<?php echo U('Upload/index');?>",   //处理上传插件上传上来的文件
                'onUploadSuccess' : function(file, data, response) {
                    $('.upload-img-box').show();
//                    $('.upload-img-box .upload-pre-item img').attr('src','/Uploads/'+data);
                    $('.upload-img-box .upload-pre-item img').attr('src','http://itsource-goods.b0.upaiyun.com/'+data);
                    $('.logo').val(data);
                },
                'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                    alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
                }
            });
            /******************图片上传插件    结束*************************************/

            /******************商品状态的回显    开始*************************************/
            <?php if(!empty($id)): ?>var goods_status_arr=[];
            var goods_status=<?php echo ($goods_status); ?>;
            if(goods_status&1){
                goods_status_arr.push(1);
            }
            if(goods_status&2){
                goods_status_arr.push(2);
            }
            if(goods_status&4){
                goods_status_arr.push(4);
            }
            if(goods_status&8){
                goods_status_arr.push(8);
            }
            if(goods_status&16){
                goods_status_arr.push(16);
            }
            $('.goods_status').val(goods_status_arr);<?php endif; ?>
            /******************商品状态的回显    结束*************************************/

            /******************商品相册    开始*************************************/
            $("#goods_gallery").uploadify({
                height: 30,
                width: 120,
                'buttonText' : '上传图片',
//                'debug': true,
                'fileSizeLimit' : '1MB',
                'fileTypeExts' : '*.gif; *.jpg; *.png', //文件后缀名的限制
//                'formData'      : {'dir' : 'brand'},    //上传文件时额外传递的参数
                'formData'      : {'dir' : 'itsource-goods'},
                swf: 'http://admin.shop.com/Public/Admin/uploadify/uploadify.swf',
                uploader: "<?php echo U('Upload/index');?>",   //处理上传插件上传上来的文件
                'onUploadSuccess' : function(file, data, response) {
                    $galleryHtml='<div class="upload-pre-item">\
                    <img src="http://itsource-goods.b0.upaiyun.com/'+data+'!m">\
                    <input type="hidden" name="goods_photo_paths[]" maxlength="60" value="'+data+'"/>\
                    <a href="javascript:;"><img src="http://admin.shop.com/Public/Admin/images/0.gif"/></a>\
                            </div>';
                    $('.upload-img-gallery').append($galleryHtml);
                },
                'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                    alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
                }
            });

            //照片的删除
            $('.upload-img-gallery').on('click','a',function(){
                var photo_id=$(this).attr('photoId');
                var that=$(this);
                if(photo_id){
                    $.post('<?php echo U("GoodsPhoto/remove");?>',{id:photo_id},function(data){
                        if(data.status==0){
                            layer.msg(data.info,{
                                icon: 2
                            });
                        }else{
                            that.closest('div').remove();
                        }
                    });
                }else{
                    that.closest('div').remove();
                }
            });
            /******************商品相册    结束*************************************/

            /******************相关文章    开始*************************************/
            $('.searchArticle').click(function(){
                var kw=$('.keyword').val();
                $('.left').empty();
                $.getJSON('<?php echo U("Article/search");?>',{keyword:kw},function(data){
                    if(data){
                        $(data).each(function(){
                            $('.left').append('<option value="'+this.id+'">'+this.name+'</option>');
                        });
                    }
                });
            });

            //相关文章的移动
            // 全部从左到右
            $('.leftO2right').click(function(){
                $('.left option').appendTo('.right');
                option2Hidden();
            });
            // 全部从右到左
            $('.rightO2left').click(function(){
                $('.right option').appendTo('.left');
                option2Hidden();
            });

            // 将左边选中的放到右边
            $('.left2right').click(function(){
                $('.left option:selected').appendTo('.right');
                option2Hidden();
            });

            // 将右边选中的放到左边
            $('.right2left').click(function(){
                $('.right option:selected').appendTo('.left');
                option2Hidden();
            });

            //双击将左边选中的放到右边
            $('.left').on('dblclick','option',function(){
                $(this).appendTo('.right');
                option2Hidden();
            });

            //双击将右边选中的放到左边
            $('.right').on('dblclick','option',function(){
                $(this).appendTo('.left');
                option2Hidden();
            });

            function option2Hidden(){
                $('.goods_article_id').empty();
                $('.right option').each(function(){
                    $("<input type='hidden' name='article_ids[]' value='"+this.value+"'>").appendTo('.goods_article_id');
                });
            }
            /******************相关文章    结束*************************************/
        });

    </script>

</body>
</html>