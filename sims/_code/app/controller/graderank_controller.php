<?php
// $Id$

/**
 * Controller_Graderank 控制器
 */
class Controller_Graderank extends Controller_Abstract
{

	function actionIndex()
	{
		$sql0 = 'SELECT openid, COUNT(openid) FROM tedeng GROUP BY openid';
		$dbo = QDB::getConn();
		$questype0 = $dbo->getAll($sql0);

		$sql1 = 'SELECT openid, COUNT(openid) FROM yideng GROUP BY openid';
		$questype1 = $dbo->getAll($sql1);

		$sql2 = 'SELECT openid, COUNT(openid) FROM erdeng GROUP BY openid';
		$questype2 = $dbo->getAll($sql2);

		$sql3 = 'SELECT openid, COUNT(openid) FROM sandeng GROUP BY openid';
		$questype3 = $dbo->getAll($sql3);

		$questype0 = Helper_Array::sortByCol($questype0, 'COUNT(openid)', SORT_DESC);
		$questype1 = Helper_Array::sortByCol($questype1, 'COUNT(openid)', SORT_DESC);
		$questype2 = Helper_Array::sortByCol($questype2, 'COUNT(openid)', SORT_DESC);
		$questype3 = Helper_Array::sortByCol($questype3, 'COUNT(openid)', SORT_DESC);

		for($i=0;$i<count($questype0);$i++) {
			$openid = $questype0[$i]['openid'];
			$sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
			$dbo = QDB::getConn();
			$result = $dbo->getAll($sql4);
			$activity_id = $result[0]['activity_id'];
			$nickname = $result[0]['nickname'];
			$sex = $result[0]['sex'];
			$questype0[$i]['activity_id'] =$activity_id;
			$questype0[$i]['nickname'] = $nickname;
			$questype0[$i]['sex'] = $sex;
			$questype0[$i]['prize'] = '特等奖';
		}

		for($i=0;$i<count($questype1);$i++) {
			$openid = $questype1[$i]['openid'];
			$sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
			$dbo = QDB::getConn();
			$result = $dbo->getAll($sql4);
			$activity_id = $result[0]['activity_id'];
			$nickname = $result[0]['nickname'];
			$sex = $result[0]['sex'];
			$questype1[$i]['activity_id'] =$activity_id;
			$questype1[$i]['nickname'] = $nickname;
			$questype1[$i]['sex'] = $sex;
		}

		for($i=0;$i<count($questype2);$i++) {
			$openid = $questype2[$i]['openid'];
			$sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
			$dbo = QDB::getConn();
			$result = $dbo->getAll($sql4);
			$activity_id = $result[0]['activity_id'];
			$nickname = $result[0]['nickname'];
			$sex = $result[0]['sex'];
			$questype2[$i]['activity_id'] =$activity_id;
			$questype2[$i]['nickname'] = $nickname;
			$questype2[$i]['sex'] = $sex;
		}

		for($i=0;$i<count($questype3);$i++) {
			$openid = $questype3[$i]['openid'];
			$sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
			$dbo = QDB::getConn();
			$result = $dbo->getAll($sql4);
			$activity_id = $result[0]['activity_id'];
			$nickname = $result[0]['nickname'];
			$sex = $result[0]['sex'];
			$questype3[$i]['activity_id'] =$activity_id;
			$questype3[$i]['nickname'] = $nickname;
			$questype3[$i]['sex'] = $sex;
		}

//		dump($questype0);
		$this->_view['questype0'] = $questype0;
		$this->_view['questype1'] = $questype1;
		$this->_view['questype2'] = $questype2;
		$this->_view['questype3'] = $questype3;




		$page = (int)$this->_context->page;
		if ($page == 0)
			$page++;

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
			$sex = addslashes(trim($_GET['sex']));
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
		$count = '';
		$this->_view['nickname'] = stripslashes($nickname);
//		$this->_view['count'] = stripcslashes($count);
		$this->_view['sex'] = stripslashes($sex);
		$this->_view['openid'] = stripslashes($openid);
		$this->_view['activity_id'] = $activity_tag;


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
// dump($list);exit;
//        dump($show);exit;
		$this->_view['pager'] = $q->getPagination();
		$this->_view['list'] = $list;

//        dump($show);exit;
		$this->_view['list1'] = $list1;
		$this->_view['start'] = ($page - 1) * $limit;
		$this->_view['subject'] = "人员管理1";
	}

}


