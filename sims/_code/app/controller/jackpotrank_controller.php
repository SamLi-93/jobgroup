<?php
// $Id$

/**
 * Controller_Jackpotrank 控制器
 */
class Controller_Jackpotrank extends Controller_Abstract
{

	function actionIndex()
	{
//		$person = new Person();
//		$ww1 = $person->getYideng();
//		$ww2 = $person->getErdeng();
//		$ww3 = $person->getSandeng();
////		dump($ww3);exit;
//		$ww0 = $person->getTedeng();
//
//		$w = $person->getArray($ww0,$ww1,$ww2,$ww3);
//
//		$arr = array_merge($ww1, $ww0, $ww2, $ww3);

		$sql0 = 'SELECT openid, COUNT(openid) FROM tedeng GROUP BY openid';
		$dbo = QDB::getConn();
		$questype0 = $dbo->getAll($sql0);


		$sql1 = 'SELECT openid, COUNT(openid) FROM yideng GROUP BY openid';
		$questype1 = $dbo->getAll($sql1);

		$sql2 = 'SELECT openid, COUNT(openid) FROM erdeng GROUP BY openid';
		$questype2 = $dbo->getAll($sql2);

		$sql3 = 'SELECT openid, COUNT(openid) FROM sandeng GROUP BY openid';
		$questype3 = $dbo->getAll($sql3);

		$arr = array_merge($questype0, $questype1, $questype2, $questype3);
		$length = count($arr);

		for ($i = 0; $i < $length; $i++) {
			for ($j = $i + 1; $j < $length - 1; $j++) {
				if ($arr[$i]['openid'] == $arr[$j]['openid']) {
					$arr[$i]['COUNT(openid)'] += $arr[$j]['COUNT(openid)'];

					$arr[$j]['COUNT(openid)'] = NULL;
				}
			}
		}

		foreach ($arr as $k => $v) {
			if ($v['COUNT(openid)'] == 0) {
				unset($arr[$k]);
			}
		}

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

		//echo $page;exit;
        // 为 $this->_view 指定的值将会传递数据到视图中
		# $this->_view['text'] = 'Hello!';
		$q = Jackpot::find();

		//查找activity里的activityname字段
		$q1 = Activity::find();
		$list1 = $q1->getAll()->toHashmap('id', 'activityname');
		$list = $q->getAll();

		$length_list = count($list);
		$length_show = count($show);
		$countArray =array();

		for($i=0;$i<$length_show;$i++) {
			for($j=0;$j<$length_list;$j++){
				if($show[$i]['openid'] == $list[$j]['openid']) {
					$countArray[$i]['nickname'] = $show[$i]['nickname'];
					$countArray[$i]['openid'] = $show[$i]['openid'];
					$countArray[$i]['activity_id'] = $show[$i]['activity_id'];
					$countArray[$i]['count'] = $show[$i]['count'];
				}
			}

		}
		$countArray = Helper_Array::sortByCol($countArray, 'count', SORT_DESC);


//		$countArray = array();
//		foreach($list as $key =>$value) {
//			foreach($w as $k => $v) {
//				if($v['openid'] == $value['openid']) {
//					$countArray[] = $w[$k];
//				}
//			}
//		}
//		dump($countArray);exit;

//		 dump($list);exit;
		//----------------	-----------------------------------------------------------------
//		dump($list1);exit;
		$nickname = '';
		$activity_id='';
		$openid = '';
		$count ='';

		$this->_view['nickname'] = stripslashes($nickname);
		$this->_view['openid'] = stripslashes($openid);
		$this->_view['activity_id'] = $activity_id;
		$this->_view['count'] =$count;

		$this->_view['list'] = $list;
		$this->_view['list1'] = $list1;
		$this->_view['countArray'] = $countArray;
		$this->_view['pager'] = $q->getPagination();
		$this->_view['start'] = 0;
		$this->_view['subject'] = "中奖人员管理111";






	}
}


