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
    <!--预留css的位置-->
</head>
<body>

<h1>
    <span class="action-span"><a href="<?php echo U('index');?>"><?php echo mb_substr($meta_title,2,null,'utf-8');?>列表</a></span>
    <span class="action-span1"><a href="index.php?act=main">ECSHOP 管理中心</a> </span><span id="search_id"
                                                                                         class="action-span1"> - <?php echo ($meta_title); ?> </span>

    <div style="clear:both"></div>
</h1>

    <div class="main-div">
        <form method="post" action="<?php echo U();?>">
            <table cellspacing="1" cellpadding="3" width="100%">
                <tr>
                    <td class="label">品牌名称</td>
                    <td>
                        <input type='text' name='name' maxlength='60' value='<?php echo ($name); ?>'/> <span
                            class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">品牌网址</td>
                    <td>
                        <input type='text' name='url' maxlength='60' value='<?php echo ($url); ?>'/> <span
                            class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">品牌LOGO</td>
                    <td>
                        <input type='hidden' class="logo" name='logo' maxlength='60' value="<?php echo ($logo); ?>"/>
                        <input id="logo_uploader" name="logo_uploader" type="file" multiple="true">
                        <div class="upload-img-box" <?php if(empty($id)): ?>style="display: none"<?php endif; ?>>
                            <div class="upload-pre-item">
                                <img src="http://brand-logo.b0.upaiyun.com/<?php echo ($logo); ?>!mini">
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="label">排序</td>
                    <td>
                        <input type="text" name="sort" maxlength="40" size="15" value="<?php echo ((isset($sort) && ($sort !== ""))?($sort):20); ?>"/> <span
                            class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">品牌描述</td>
                    <td>
                        <textarea name="intro" cols="60" rows="4"><?php echo ($intro); ?></textarea> <span
                            class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">是否显示</td>
                    <td>
                        <input type='radio' class='status' name='status' value='1'/>是<input type='radio' class='status'
                                                                                            name='status' value='0'/>否
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <td colspan="2" align="center"><br/>
                    <input type="hidden" name="id" value="<?php echo ($id); ?>">
                    <input type="submit" class="button ajax_post" value=" 确定 "/>
                    <input type="reset" class="button" value=" 重置 "/>
                </td>
                </tr>
            </table>
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

    <script src="http://admin.shop.com/Public/Admin/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function () {
            $("#logo_uploader").uploadify({
                height: 30,
                width: 120,
                'buttonText' : '上传图片',
                'debug': true,
                'fileSizeLimit' : '1MB',
                'fileTypeExts' : '*.gif; *.jpg; *.png', //文件后缀名的限制
//                'formData'      : {'dir' : 'brand'},    //上传文件时额外传递的参数
                'formData'      : {'dir' : 'brand-logo'},
                swf: 'http://admin.shop.com/Public/Admin/uploadify/uploadify.swf',
                uploader: "<?php echo U('Upload/index');?>",   //处理上传插件上传上来的文件
                'onUploadSuccess' : function(file, data, response) {
                    $('.upload-img-box').show();
//                    $('.upload-img-box .upload-pre-item img').attr('src','/Uploads/'+data);
                    $('.upload-img-box .upload-pre-item img').attr('src','http://brand-logo.b0.upaiyun.com/'+data);
                    $('.logo').val(data);
                },
                'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                    alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
                }
            });

        });
    </script>

</body>
</html>