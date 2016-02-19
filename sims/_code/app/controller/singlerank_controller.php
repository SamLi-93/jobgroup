<?php
// $Id$

/**
 * Controller_Singlerank 控制器
 */
class Controller_Singlerank extends Controller_Abstract
{

    function actionIndex()
    {
// 为 $this->_view 指定的值将会传递数据到视图中
# $this->_view['text'] = 'Hello!';afa
//-------------------
// $sql = 'select openid from tedeng';
// $sql = 'SELECT openid,CNT FROM (SELECT COL,COUNT(*) AS CNT FROM @TB GROUP BY COL)A ORDER BY CNT DESC';
// $sql0 ='select a.type_id,b.name,sum(a.score) as score,count(a.id) as n from exam_stu_question a left join exam_question_type b on a.type_id=b.id WHERE a.page_id = \'$page_id\' GROUP BY a.type_id ORDER BY b.list';

        $sql0 = 'SELECT openid, COUNT(openid) FROM tedeng GROUP BY openid';
        $dbo = QDB::getConn();
        $questype0 = $dbo->getAll($sql0);


        $sql1 = 'SELECT openid, COUNT(openid) FROM yideng GROUP BY openid';
        $questype1 = $dbo->getAll($sql1);

        $sql2 = 'SELECT openid, COUNT(openid) FROM erdeng GROUP BY openid';
        $questype2 = $dbo->getAll($sql2);

        $sql3 = 'SELECT openid, COUNT(openid) FROM sandeng GROUP BY openid';
        $questype3 = $dbo->getAll($sql3);

// $sql4 = "select nickname from user WHERE openid = 'oLbL9jtMtL9kOGHfg8EUTp8IImIE'";
// $result = $dbo->getAll($sql4);
// dump($result[0][nickname]);

        $arr = array_merge($questype0, $questype1, $questype2, $questype3);
// dump($arr);exit;
        $length = count($arr);
// dump( $arr[0]['COUNT(openid)']);
// dump( $arr[1]['COUNT(openid)']);
        for ($i = 0; $i < $length; $i++) {
            for ($j = $i + 1; $j < $length - 1; $j++) {
                if ($arr[$i]['openid'] == $arr[$j]['openid']) {
                    $arr[$i]['COUNT(openid)'] += $arr[$j]['COUNT(openid)'];
// dump($arr[$i]['COUNT(openid)']);exit;
                    $arr[$j]['COUNT(openid)'] = NULL;
                }
            }
        }

        foreach ($arr as $k => $v) {
            if ($v['COUNT(openid)'] == 0) {
                unset($arr[$k]);
            }
        }
//        dump($arr);exit;
        $length_new = count($arr);
//        dump($arr);exit;
// dump($length_new);exit;     // 数组arr长度为307 + 1
// $person = new Person();
// for($i = 0; $i < $length_new; $i++) {
// $openid = $arr[$i]['openid'];
// $sql4 = "select nickname from user WHERE openid = '$openid'";
// $dbo = QDB::getConn();
// $result = $dbo->getAll($sql4);
// $nickname = $result[$i]['nickname'];
// $count = $arr[$i]['COUNT(openid)'];
// $person->setOpenid($openid);
// $person->setNickname($nickname);
// $person->setCount($count);
// }
        $show = array();
        for ($i = 0; $i < 957; $i++) {
            if(isset($arr[$i])) {
                $openid = $arr[$i]['openid'];
                $count = $arr[$i]['COUNT(openid)'];
                $sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
                $dbo = QDB::getConn();
                $result = $dbo->getAll($sql4);
                $activity_id = $result[0]['activity_id'];
                $nickname = $result[0]['nickname'];
                $sex = $result[0]['sex'];
                $show[$i]['openid'] = $openid;
                $show[$i]['count'] = $count;
                $show[$i]['nickname'] = $nickname;
                $show[$i]['sex'] = $sex;
                $show[$i]['activity_id'] = $activity_id;
            }
        }
        $show = Helper_Array::sortByCol($show, 'count', SORT_DESC);

//        dump($show);
//        dump(count($show));exit;  //307条数据 +1

// $result = array();
// foreach($arr as $val){
// dump($val);
// foreach($val as $k=>$v){
//
// }
// }
// $result = Tedeng::find();
// $list2 = $result->getAll();
// dump($questype0);exit;
// $list1 = $q1->getAll()->toHashmap('id', 'activityname');



        $page = (int)$this->_context->page;
        if ($page == 0)
            $page++;
//echo $page;exit;
        $limit = $this->_context->limit ? $this->_context->limit : 15;

//搜索
        $search_list_temp = array();
        $nickname = '';
        $activity_tag = '';
//        dump(empty($activity_id));exit;
        if (isset($_GET['activity_id'])) {
            $activity_tag = addslashes(trim($_GET['activity_id']));
            if (strlen($activity_tag)) {
                array_push($search_list_temp, " activity_id like '%$activity_tag%'");
            }
        }
        if (isset($_GET['nickname'])) {
            $nickname = addslashes(trim($_GET['nickname']));
// $activity_id = $_GET['activity_id'];
            if (isset($_GET['activity_id'])) {
                $activity_tag = addslashes(trim($_GET['activity_id']));
            }
// $search_list = array();
            if (strlen($nickname)) {
                array_push($search_list_temp, " nickname like '%$nickname%'");
            }
            if (strlen($sex)) {
                array_push($search_list_temp, " sex like '%$sex%'");
            }
            if (strlen($activity_tag)) {
                array_push($search_list_temp, " activity_id like '%$activity_tag%'");
            }
        }
        $sex = '';
        $openid = '';

        $this->_view['nickname'] = stripslashes($nickname);
        $this->_view['count'] = stripcslashes($count);
        $this->_view['sex'] = stripslashes($sex);
        $this->_view['openid'] = stripslashes($openid);
        $this->_view['activity_id'] = $activity_tag;

// $this->_view['activityname'] = $activityname;
// dump($activity_id);
// dump($_REQUEST);
//得到该用户可操作的新闻类型

//-----------------------------------------------
        $search_where = implode(' and ', $search_list_temp);
        $this->_view['show'] = $show;
//        dump(isset($activity_id));exit;
//        dump($activity_tag);
       $show_search = array();
//       dump($nickname);
        foreach($show as $key =>$value) {
            $name = $value['nickname'];
            if(!empty($name)&&!empty($nickname)){
                if(strstr($name, $nickname)) {
                    if($show[$key]['activity_id'] ==$activity_tag ) {
                        $show_search[$key] = $show[$key];
                        $this->_view['show'] = $show_search;
                    }elseif($show[$key]['activity_id'] != $activity_tag ) {
                        $this->_view['show'] = null;
                    }
                }
            }elseif(empty($nickname)&&!empty($activity_tag)) {
                if($show[$key]['activity_id'] ==$activity_tag ) {
                        $show_search[$key] = $show[$key];
                        $this->_view['show'] = $show_search;
                }elseif($show[$key]['activity_id'] != $activity_tag ) {
                        $this->_view['show'] = null;
                    }
             }
        }

//        $result = $show;
//        if(empty($activity_tag)&&!empty($nickname)){
//            $name = $value['nickname'];
//            foreach ($show as $k1 => $v1) {
//                if(strstr($name, $nickname)) {
//                    $show_search[$k1] = $show[$k1];
//                }
//            }
//            $result = $show_search;
//        }
//        if(!empty($activity_tag)&&empty($nickname)){
//            $name = $value['nickname'];
//            foreach ($show as $k2 => $v2) {
//                if($show[$k2]['activity_id'] ==$activity_tag ) {
//                    $show_search[$k2] = $show[$k2];
//                }
//            }
//            $result = $show_search;
//        }
//        if(!empty($activity_tag)&&!empty($nickname)){
//            $name = $value['nickname'];
//            foreach ($show as $k3 => $v3) {
//                if(strstr($name, $nickname)) {
//                    if($show[$k3]['activity_id'] ==$activity_tag ) {
//                        $show_search[$k3] = $show[$k3];
//                    }
//                }
//            }
//            $result = $show_search;
//        }
//        $this->_view['show'] = $result;

//        $pageSize = 14;
//        $pnum = ceil(count($show) / $pageSize);
//        if(isset($_GET['page'])) {
//            $page = intval($_GET['page']);
//            $page = $page > $pnum? $pnum: $page;
//        }else {
//            $page = 1;
//        }
//        $page = empty($_GET['page']) ? '1' : $_GET['page'];
//        for($i=($page-1)*$pageSize;$i<$page*$pageSize;$i++) {
//            if(!isset($arr_click[$i]))break;
//            $arr = $show[($page - 1) * $pageSize + $i];
//        }
//        dump($show_search);

        $q = Personnel::find($search_where)->order('id desc')->limitPage($page,$limit);
//q1是查询activity的activity_id字段
        $q1 = Activity::find();
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
// dump($list1);
// dump($list1);exit;
//------------------------------------------------------------------------
// dump($list);exit;
        $list = $q->getAll();
//        dump(count($show));
//        dump($limit);
//        dump($list);exit;
// dump($q->getOne()->activity);exit();
// dump($list);exit;
//        dump($show);exit;
        $this->_view['pager'] = $q->getPagination();

//        $this->_view['list'] = $list;
//        dump($q->getPagination());exit;
        $this->_view['list1'] = $list1;
        $this->_view['start'] = 0;
        $this->_view['subject'] = "人员管理1";

    }
}



