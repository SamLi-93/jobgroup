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

		$page = (int)$this->_context->page;
		if ($page == 0)
			$page++;

		$limit = $this->_context->limit ? $this->_context->limit : 15;

		$q = Personnel::find()->order('id desc')->limitPage($page, $limit);
		$q1 = Activity::find()->limitPage($page, $limit);
		$list1 = $q1->getAll()->toHashmap('id', 'activityname');

		$prize = '';
		$count = '';

		$this->_view['pager'] = $q->getPagination();
		$this->_view['list1'] = $list1;
		$this->_view['start'] = ($page - 1) * $limit;
		$this->_view['subject'] = "1111";
		$this->_view['countPerson'] = $countPerson;

	}
}


