<?php
// $Id$

/**
 * Controller_Personnelrank 控制器
 */
class Controller_Personnelrank extends Controller_Abstract
{

	function actionIndex()
	{

		$sql3 = "SELECT openid, COUNT(openid),activity_id FROM sandeng WHERE activity_id = '1' GROUP BY openid ";
		$dbo = QDB::getConn();
		$questype3 = $dbo->getAll($sql3);
		$countSandeng = count($questype3);

		$sql2 = "SELECT openid, COUNT(openid),activity_id FROM erdeng WHERE activity_id = '1' GROUP BY openid";
		$questype2 = $dbo->getAll($sql2);
		$countErdeng = count($questype2);

		$sql1 = 'SELECT openid, COUNT(openid),activity_id FROM yideng WHERE activity_id = \'1\' GROUP BY openid';
		$questype1 = $dbo->getAll($sql1);
		$countYideng = count($questype1);

		$sql0 = 'SELECT openid, COUNT(openid),activity_id FROM tedeng WHERE activity_id = \'1\' GROUP BY openid';
		$questype0 = $dbo->getAll($sql0);
		$countTedeng = count($questype0);
		$year = $questype0[0]['activity_id'];
		$countPerson = array();
		$countPerson[$year]['特等奖'] = $countTedeng;
		$countPerson[$year]['一等奖'] = $countYideng;
		$countPerson[$year]['二等奖'] = $countErdeng;
		$countPerson[$year]['三等奖'] = $countSandeng;

		$showSandeng = array();
		for($i=0;$i<$countSandeng;$i++){
			$openid = $questype3[$i]['openid'];
			$sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
			$dbo = QDB::getConn();
			$result = $dbo->getAll($sql4);
			$activity_id = $result[0]['activity_id'];
			$nickname = $result[0]['nickname'];
			$sex = $result[0]['sex'];
			$showSandeng[$i]['openid'] = $openid;
			$showSandeng[$i]['nickname'] = $nickname;
			$showSandeng[$i]['sex'] = $sex;
			$showSandeng[$i]['activity_id'] = $activity_id;
			$showSandeng[$i]['prize'] = '三等奖';
		}

		$showErdeng = array();
		for($i=0;$i<$countErdeng;$i++){
			$openid = $questype2[$i]['openid'];
			$sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
			$dbo = QDB::getConn();
			$result = $dbo->getAll($sql4);
			$activity_id = $result[0]['activity_id'];
			$nickname = $result[0]['nickname'];
			$sex = $result[0]['sex'];
			$showErdeng[$i]['openid'] = $openid;
			$showErdeng[$i]['nickname'] = $nickname;
			$showErdeng[$i]['sex'] = $sex;
			$showErdeng[$i]['activity_id'] = $activity_id;
			$showErdeng[$i]['prize'] = '二等奖';
		}

		$showYideng = array();
		for($i=0;$i<$countYideng;$i++){
			$openid = $questype1[$i]['openid'];
			$sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
			$dbo = QDB::getConn();
			$result = $dbo->getAll($sql4);
			$activity_id = $result[0]['activity_id'];
			$nickname = $result[0]['nickname'];
			$sex = $result[0]['sex'];
			$showYideng[$i]['openid'] = $openid;
			$showYideng[$i]['nickname'] = $nickname;
			$showYideng[$i]['sex'] = $sex;
			$showYideng[$i]['activity_id'] = $activity_id;
			$showYideng[$i]['prize'] = '一等奖';
		}

		$showTedeng = array();
		for($i=0;$i<$countTedeng;$i++){
			$openid = $questype0[$i]['openid'];
			$sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
			$dbo = QDB::getConn();
			$result = $dbo->getAll($sql4);
			$activity_id = $result[0]['activity_id'];
			$nickname = $result[0]['nickname'];
			$sex = $result[0]['sex'];
			$showTedeng[$i]['openid'] = $openid;
			$showTedeng[$i]['nickname'] = $nickname;
			$showTedeng[$i]['sex'] = $sex;
			$showTedeng[$i]['activity_id'] = $activity_id;
			$showTedeng[$i]['prize'] = '特等奖';
		}

		$page = (int)$this->_context->page;
		if ($page == 0)
			$page++;

		//搜索
		$nickname = '';
		$activity_tag ='';

		if (isset($_GET['nickname'])) {
			$nickname = addslashes(trim($_GET['nickname']));
		}
		if (isset($_GET['activity_id'])) {
			$activity_tag = addslashes(trim($_GET['activity_id']));
		}
		if (strlen($nickname)) {
			//  xxxxxxxxxxx;
		}



		$limit = $this->_context->limit ? $this->_context->limit : 15;

		$q = Personnel::find()->order('id desc')->limitPage($page, $limit);
		$q1 = Activity::find()->limitPage($page, $limit);
		$list1 = $q1->getAll()->toHashmap('id', 'activityname');

		$prize = '';
		$count = '';

		$this->_view['nickname'] = stripslashes($nickname);
		$this->_view['pager'] = $q->getPagination();
		$this->_view['list1'] = $list1;
		$this->_view['start'] = ($page - 1) * $limit;
		$this->_view['subject'] = "1111";
		$this->_view['countPerson'] = $countPerson;

	}
}


