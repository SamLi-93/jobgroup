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

		$page = (int)$this->_context->page;
		if ($page == 0)
			$page++;
		$limit = $this->_context->limit ? $this->_context->limit : 15;

//搜索
		$search_list_temp = array();

		$this->_view['countArray'] = $countArray;

		$nickname = '';
		$activity_tag = '';
//        dump(empty($activity_id));exit;
		if (isset($_GET['activity_id'])) {
			$activity_tag = addslashes(trim($_GET['activity_id']));
		}
		if (isset($_GET['nickname'])) {
			$nickname = addslashes(trim($_GET['nickname']));
		}

		$this->_view['activity_id'] = $activity_tag;

//       dump($nickname);

//		foreach($countArray as $key =>$value) {
//			$name = $value['nickname'];
//			if(!empty($name)&&!empty($nickname)){
//				if(strstr($name, $nickname)) {
//					if($countArray[$key]['activity_id'] ==$activity_tag ) {
//						$show_search[$key] = $countArray[$key];
//						$this->_view['$countArray'] = $show_search;
//					}elseif($countArray[$key]['activity_id'] != $activity_tag ) {
//						$this->_view['$countArray'] = null;
//					}
//				}
//			}elseif(empty($nickname)&&!empty($activity_tag)) {
//				if($countArray[$key]['activity_id'] ==$activity_tag ) {
//					$show_search[$key] = $countArray[$key];
//					$this->_view['$countArray'] = $show_search;
//				}elseif($countArray[$key]['activity_id'] != $activity_tag ) {
//					$this->_view['$countArray'] = null;
//				}
//			}
//		}

//		foreach($countArray as $key =>$value) {
//			$name = $value['nickname'];
////			dump($countArray[$key]['activity_id']);
//			if (!empty($name) && !empty($nickname)) {
//				if (strstr($name, $nickname)) {
//					$show_search[$key] = $countArray[$key];
//					$this->_view['countArray'] = $show_search;
//
//					if ($countArray[$key]['activity_id'] == $activity_tag) {
//						$show_search[$key] = $countArray[$key];
//						$this->_view['countArray'] = $show_search;
//					} elseif ($countArray[$key]['activity_id'] != $activity_tag) {
//						$this->_view['countArray'] = null;
//					}
//				}
//			}
//		}


		$activity_id='';
		$openid = '';
		$count ='';

		$show_search = array();
		foreach($countArray as $key => $value) {
			$name = $value['nickname'];
			if(!empty($nickname)&&!empty($name)) {
				if(strstr($name,$nickname)) {
					if($countArray[$key]['activity_id'] == $activity_tag) {
						$show_search[$key] = $countArray[$key];
//						$this->_view['countArray'] = $show_search;
					}elseif($countArray[$key]['activity_id'] != $activity_tag ) {
//						$this->_view['countArray'] = null;
						$show_search = null;
					}
				}
			}elseif(empty($nickname)&&!empty($activity_tag)) {
				if($countArray[$key]['activity_id'] == $activity_tag ) {
					$show_search[$key] = $countArray[$key];
//					$this->_view['countArray'] = $show_search;
				}elseif($show[$key]['activity_id'] != $activity_tag ) {
//					$this->_view['countArray'] = null;
					$show_search = null;
				}
			} elseif(empty($nickname)&&empty($activity_tag)) {
				$show_search = $countArray;
			}
		}

//		dump($countArray);exit;
//		 dump($list);exit;
//----------------	-----------------------------------------------------------------
//		dump($list1);exit;

		$this->_view['nickname'] = stripslashes($nickname);
		$this->_view['openid'] = stripslashes($openid);
		$this->_view['count'] =$count;

		$this->_view['list'] = $list;
		$this->_view['list1'] = $list1;
		$this->_view['pager'] = $q->getPagination();
		$this->_view['start'] = 0;
		$this->_view['subject'] = "中奖人员摇动次数统计";

		$page = $this->_context->page;
		if($page ==0 ) $page++;

		$limit = 15;
		$num = count($show_search);
		$start = ($page-1)*$limit;
		if(!empty($show_search)){
			$listshow = array_slice($show_search,$start,$limit);
			$this->_view['list'] = $listshow;
		}else $this->_view['list'] = null;
//		dump($show_search);
		$help_string = new Helper_String();
		$pager = $help_string->getPage($num,$limit,$page);
		$this->_view['pager'] = $pager;
		$this->_view['start'] = $start;
//		dump($list1);exit;
	}
}


