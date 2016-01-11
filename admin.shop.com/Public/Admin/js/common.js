/**
 * ajax的通用提交
 */
$(function () {
    //全选
    $('.checked_all').click(function(){
        $('.ids').prop('checked',$('.checked_all').prop('checked'));
    });
    //批量选择
    $('.ids').click(function(){
        $('.checked_all').prop('checked',!$('.ids:not(:checked)').length);
    });
    //ajax的get提交
    $('.ajax_get').click(function () {
        var url = $(this).attr('href'); //获取请求地址url
        $.get(url, show_ajax_layer);
        return false;
    });
    //ajax的post提交
    $('.ajax_post').click(function () {
        var form = $(this).closest('form'); //获取表单的ajax对象
        //var url=form.attr('action');  //获取表单的请求地址url
        //var params=form.serialize();  //获取表单提交的数据并序列化
        //$.post(url,params,get_ajax_layer);
        if(form.length!=0){
            form.ajaxSubmit({success: show_ajax_layer}); //表单的异步提交
        }else{
            var url=$(this).attr('href');
            var params=$('.ids:checked').serialize();
            $.post(url,params,show_ajax_layer);
        }
        return false;
    });
});
/**
 * 获取提示框和页面刷新
 * @param data
 */
function show_ajax_layer(data) {
    var icon;
    if (data.status) {
        icon = 1;   //成功符号
    } else {
        icon = 2;   //错误符号
    }
    //显示一个提示框
    layer.msg(data.info, {
        time: 1000,  //等待时间后关闭
        offset: 0,  //设置位置
        icon: icon,  //设置提示框中的图标
        //shift: 6  //设置动画
    }, function () {    //提示框隐藏之后该函数执行
        //如果data中有url地址才转向
        if (data.url) {
            location.href = data.url;
        }
    });
}