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
                $sql4 = "select nickname from user WHERE openid = '$openid'";
                $dbo = QDB::getConn();
                $result = $dbo->getAll($sql4);
                $nickname = $result[0]['nickname'];
                $show[$i]['openid'] = $openid;
                $show[$i]['count'] = $count;
                $show[$i]['nickname'] = $nickname;
            }
        }
        $show = Helper_Array::sortByCol($show, 'count', SORT_DESC);
//        dump($show);
//        dump($show);exit;

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

        $gender = Q::ini('appini/gender');
        $this->_view['gender'] = $gender;
        $page = (int)$this->_context->page;
        if ($page == 0)
            $page++;
//echo $page;exit;
        $limit = $this->_context->limit ? $this->_context->limit : 15;

//搜索
        $search_where = "";
        $search_list_temp = array();
        $nickname = '';
        $activity_id = '';
        if (isset($_GET['activity_id'])) {
            $activity_id = addslashes(trim($_GET['activity_id']));
            if (strlen($activity_id)) {
                array_push($search_list_temp, " activity_id like '%$activity_id%'");
            }
        }
        if (isset($_GET['nickname'])) {
            $nickname = addslashes(trim($_GET['nickname']));
            $sex = addslashes(trim($_GET['sex']));
// $activity_id = $_GET['activity_id'];
            if (isset($_GET['activity_id'])) {
                $activity_id = addslashes(trim($_GET['activity_id']));
            }

// $search_list = array();
            if (strlen($nickname)) {
                array_push($search_list_temp, " nickname like '%$nickname%'");
            }
            if (strlen($sex)) {
                array_push($search_list_temp, " sex like '%$sex%'");
            }
            if (strlen($activity_id)) {
                array_push($search_list_temp, " activity_id like '%$activity_id%'");
            }
        }
        $sex = '';
        $openid = '';

        $this->_view['nickname'] = stripslashes($nickname);
        $this->_view['sex'] = stripslashes($sex);
        $this->_view['openid'] = stripslashes($openid);
        $this->_view['activity_id'] = $activity_id;
// $this->_view['activityname'] = $activityname;
// dump($activity_id);
// dump($_REQUEST);
//得到该用户可操作的新闻类型

//-----------------------------------------------
        $search_where = implode(' and ', $search_list_temp);
        $q = Personnel::find($search_where)->order('id desc')->limitPage($page, $limit);

//q1是查询activity的activity_id字段
        $q1 = Activity::find()->limitPage($page, $limit);
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
// dump($list1);
// dump($list1);exit;
//------------------------------------------------------------------------
// dump($list);exit;
        $list = $q->getAll();
// dump($q->getOne()->activity);exit();
 dump($list);exit;
        $this->_view['pager'] = $q->getPagination();
        $this->_view['list'] = $list;
        $this->_view['list1'] = $list1;
        $this->_view['start'] = ($page - 1) * $limit;
        $this->_view['subject'] = "人员管理1";
    }

    function actionCreate()
    {

        $q1 = Activity::find();
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
        $this->_view['list1'] = $list1;
        //-----------------------------------------------
        $gender = Q::ini('appini/gender');
        // $rootdir = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR;

        $alert = '';
        $this->_view['gender'] = $gender;
        // var_dump($gender);exit();
        // $private = Q::ini('appini/private');

        $this->_view['subject'] = "人员管理";

        if ($this->_context->isPOST() && isset($_POST['nickname'])) {
            @extract($_POST);
            $nickname = addslashes(trim($nickname));
            $sex = addslashes(trim($sex));
            $openid = addslashes(trim($openid));
            $activity_id = addslashes(trim($activity_id));

            // $private=intval($private);
            $user = $this->_app->currentUser();
            $form_value = array(
                'nickname' => $nickname,
                'sex' => $sex,
                'openid' => $openid,
                'activity_id' => $activity_id,
            );
            $personnel = new Personnel($form_value);
            $id = $personnel->save()->id;

            $alert = "<script language='javascript'>if(confirm('人员添加成功，是否继续添加？')){window.open('" . url('personnel/create') . "','_self');}else{window.open('" . url('personnel') . "','_self');}</script>";
        }
        $this->_view['alert'] = $alert;
    }

    function actionEdit()
    {

        $q1 = Activity::find();
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
        $this->_view['list1'] = $list1;
        //-----------------------------------------------
        $gender = Q::ini('appini/gender');
        $alert = '';
        $this->_view['gender'] = $gender;

        // $private = Q::ini('appini/private');
        // $this->_view['private'] = $private;


        $this->_view['subject'] = "人员管理";

        $id = $this->_context->id;
        $personnel = Personnel::find()->getById($id);
        if ($this->_context->isPOST() && isset($_POST['nickname'])) {
            @extract($_POST);
            $nickname = addslashes(trim($nickname));
            $sex = addslashes(trim($sex));
            $openid = addslashes(trim($openid));
            $activity_id = addslashes(trim($activity_id));

            // $top_flag=$top_flag;
            // $home_flag=$home_flag;
            // $now = time();
            $user = $this->_app->currentUser();
            $form_value = array(
                'nickname' => $nickname,
                'sex' => $sex,
                'openid' => $openid,
                'activity_id' => $activity_id,
                // 'top_flag' => $top_flag,
                // 'home_flag' => $home_flag,
            );

            // print_r($form_value);exit()
            // $log_rec = Helper_Util::toArray($news);
            $personnel->changeProps($form_value);
            $personnel->save();
            //Log::addlog(1, 'personnel', $personnel->id(), $log_rec, '修改新闻：' . $personnel->name, NULL, 'personnel');

            $alert = "<script language='javascript'>if(confirm('该条信息修改成功，是否继续修改？')){window.open('" . url('personnel/edit', array('id' => $id)) . "','_self');}else{window.open('" . url('personnel') . "','_self');}</script>";
        }

        $myData = $personnel->toArray();
//        dump($myData);
        $this->_view['myData'] = $myData;
        $this->_view['alert'] = $alert;
    }

    function actionDelete()
    {
        $personnel = Personnel::find('id = ?', $this->_context->id)->query();
        $personnel->destroy();
        return $this->_redirect(url('personnel'));
    }
}



