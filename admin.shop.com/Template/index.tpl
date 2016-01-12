<extend name="Common/index"/>
<block name="list">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>ID<input type="checkbox" class="checked_all"></th>
                <?php
                    foreach($fields as $field){
                        echo "<th>{$field['comment']}</th>";
                    }
                ?>
                <th>操作</th>
            </tr>
            <volist name="rows" id="row">
                <tr>
                    <td width="50px">{$row.id}<input type="checkbox" name="id[]" value="{$row.id}" class="ids"></td>
                    <?php
                        foreach($fields as $field){
                            if($field['field']=='name'){
                                echo '<td class="first-cell"><span>{$row.name}</span></td>';
                            }elseif($field['field']=='status'){
                                echo '<td align="center"><a class="ajax_get" href="{:U(\'changeStatus\',array(\'id\'=>$row[\'id\'],\'status\'=>1-$row[\'status\']))}"><img src="__IMG__/{$row.status}.gif"/></a></td>';
                            }else{
                                echo "<td align='center'>{\$row.{$field['field']}}</td>";
                            }
                        }
                    ?>
                    <td align="center">
                        <a href="{:U('edit',array('id'=>$row['id']))}">编辑</a> |
                        <a class='ajax_get' href="{:U('changeStatus',array('id'=>$row['id']))}">移除</a>
                    </td>
                </tr>
            </volist>
        </table>

        <!-- end brand list -->
    </div>
    <tr>
        <td align="right" nowrap="true" colspan="6">
            <!-- $Id: page.htm 14216 2008-03-10 02:27:21Z testyang $ -->
            <div id="turn-page" class="page">
                {$pageHtml}
            </div>
        </td>
    </tr>
</block>
