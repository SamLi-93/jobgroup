<? $this->_extends("_layouts/main_layout"); ?>
<? $this->_block("contents"); ?>
<script type="text/javascript">
<!--
    function check() {
        return confirm('确定删除吗？');

    }
//-->
</script>
<div class="row-fluid sortable" style="width:95%">		
    <div class="box span12">
        <div class="box-content">
            <div class="row-fluid">
                <form class="fsimple" id="form_news_search" name="form_news_search" action="" method="get" enctype="application/x-www-form-urlencoded" qform_group_id="" >	
                    <div class="span10" style="margin-bottom:7px;margin-left:40px;">
                        <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                <span><input name="nickname" type="text" aria-controls="DataTables_Table_0" name="nickname"  id="nickname" value="<?php echo $nickname; ?>" placeholder="微信名包含文字" /></span>


                                    <!-- <select  id="activity_id" name="activity_id" >
                                    <?php
                                    echo '<option value="">选择活动</option>';
                                    foreach ($list1 as $k => $v) {
                                        // var_dump($list1);exit;
                                        $showlist = 1;
                                        if ($showlist == 1) {
                                            if ($activity_id == $k) {
                                                $sel = 'selected';
                                            } else {
                                                $sel = '';
                                            }
                                            echo '<option value="' . $k . '" ' . $sel . '>' . $v['activity_id'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select> -->
                                    
                                    <select  id="activity_id" name="activity_id" >
                                        <?php
                                        echo '<option value="">选择活动</option>';

                                        foreach ($list1 as $k => $v) {
                                            // if ($myData['activity_id'] == $k) {
                                            if (strlen($activity_id) && $activity_id == $k) {
                                                $sel = 'selected';
                                            } else {
                                                $sel = '';
                                            }

                                            echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                                        }
                                        ?>
                                    </select>

                                <div>
                                    <div class="btn2 ml20" onclick="$('.fsimple').submit();"><span class="shadow white">查询</span></div>
                                    <div class="btn2 ml20" onclick="window.location.href='<?php echo url('');?>';"><span class="shadow white">重置</span></div>
        

                                </div>
                        </div>
                    </div>
                </form>		
            </div>

                <table class="list_table" width="100%" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="width:10%">序号</th>
                        <th style="width:20%" >微信名</th>
                        <th style="width:10%" >性别</th>
                        <th style="width:30%">身份号</th>
                        <th style="width:10%">摇动总次数</th>
                        <th style="width:20%">活动</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
//                    print_r($show);
                    // dump($list[0]->activity);exit;
//                    dump($show);exit;

                    foreach ($show as $i => $row) {
//                        dump($i);
                        // dump($row->activity->activityname);exit;
                        ?>
                        <tr>
                            <td><?php echo $i + $start + 1 ?></td>
                            <td><?php echo stripslashes(str_replace($nickname, '<font color=red>' . $nickname . '</font>', $row['nickname'])); ?></td>
                            <td><?php  stripslashes(str_replace($sex, '<font color=red>' . $sex . '</font>', $row['sex'])); ?>
                                <?php switch ($row['sex']) {
                                    case '0':
                                        echo "其他";
                                        break;
                                    case '1':
                                        echo "男";
                                        break;
                                    case '2':
                                        echo "女";
                                        break;

                                    default:
                                        # code...
                                        break;
                                }?></td>
                            <td><?php echo stripslashes(str_replace($openid, '<font color=red>' . $openid . '</font>', $row['openid'])); ?></td>
                            <td><?php echo stripslashes(str_replace($count, '<font color=red>' . $count . '</font>', ($row['count'])*5)); ?></td>
                            <td><?php echo stripslashes(str_replace($list1[$row['activity_id']],  $list1[$row['activity_id']] , $list1[$row['activity_id']])); ?></td>

                        </tr> 
            <? } ?>
                    </tbody>
                </table>

            <br/>
    <? $this->_control("pagination", "", array('pagination' => $pager)); ?>

        </div>
    </div>

<? $this->_endblock(); ?>
