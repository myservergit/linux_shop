<extend name="Common/edit"/>
<block name="form">
    <div class="main-div">
        <form method="post" action="{:U()}">
            <table cellspacing="1" cellpadding="3" width="100%">
                <?php foreach($fields as $field): ?>
                    <tr>
                        <td class="label"><?php echo $field['comment'] ?></td>
                        <td>
                            <?php
                            if($field['field_type']=='textarea'){
                                echo "<textarea name=\"textarea\" cols=\"60\" rows=\"4\">{\$intro}</textarea>";
                            }elseif($field['field_type']=='radio'){
                                foreach($field['option_values'] as $k=>$v){
                                    echo "<input type='radio' class='{$field['field']}' name='{$field['field']}' value='{$k}'/>{$v}";
                                }
                            }elseif($field['field']=='sort'){
                                echo '<input type="text" name="sort" maxlength="40" size="15" value="{$sort|default=20}"/>';
                            }elseif($field['field_type']=='file'){
                                echo "<input type='file' name='{$field['field']}'/>";
                            }else{
                                echo "<input type='text' name='{$field['field']}' maxlength='60' value='{\${$field['field']}}'/>";
                            }
                            ?>
                            <span class="require-field">*</span>
                        </td>
                    </tr>
                <?php endforeach ?>
                    <td colspan="2" align="center"><br/>
                        <input type="hidden" name="id" value="{$id}">
                        <input type="submit" class="button ajax_post" value=" 确定 "/>
                        <input type="reset" class="button" value=" 重置 "/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</block>
