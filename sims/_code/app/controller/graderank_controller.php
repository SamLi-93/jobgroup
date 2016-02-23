<?php
// $Id$

/**
 * Controller_Graderank 控制器
 */
class Controller_Graderank extends Controller_Abstract
{

	function actionIndex()
	{

		$prize = Q::ini('appini/prize');
		$this->_view['prize'] = $prize;

//		foreach ($prize as $k => $v)  {
//			dump($k);
//			dump($v);
//		}

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

		for ($i = 0; $i < count($questype0); $i++) {
			$openid = $questype0[$i]['openid'];
			$sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
			$dbo = QDB::getConn();
			$result = $dbo->getAll($sql4);
			$activity_id = $result[0]['activity_id'];
			$nickname = $result[0]['nickname'];
			$sex = $result[0]['sex'];
			$questype0[$i]['activity_id'] = $activity_id;
			$questype0[$i]['nickname'] = $nickname;
			$questype0[$i]['sex'] = $sex;
			$questype0[$i]['prize'] = '4';
		}

		for ($i = 0; $i < count($questype1); $i++) {
			$openid = $questype1[$i]['openid'];
			$sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
			$dbo = QDB::getConn();
			$result = $dbo->getAll($sql4);
			$activity_id = $result[0]['activity_id'];
			$nickname = $result[0]['nickname'];
			$sex = $result[0]['sex'];
			$questype1[$i]['activity_id'] = $activity_id;
			$questype1[$i]['nickname'] = $nickname;
			$questype1[$i]['sex'] = $sex;
			$questype1[$i]['prize'] = '3';
		}

		for ($i = 0; $i < count($questype2); $i++) {
			$openid = $questype2[$i]['openid'];
			$sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
			$dbo = QDB::getConn();
			$result = $dbo->getAll($sql4);
			$activity_id = $result[0]['activity_id'];
			$nickname = $result[0]['nickname'];
			$sex = $result[0]['sex'];
			$questype2[$i]['activity_id'] = $activity_id;
			$questype2[$i]['nickname'] = $nickname;
			$questype2[$i]['sex'] = $sex;
			$questype2[$i]['prize'] = '2';
		}

		for ($i = 0; $i < count($questype3); $i++) {
			$openid = $questype3[$i]['openid'];
			$sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
			$dbo = QDB::getConn();
			$result = $dbo->getAll($sql4);
			$activity_id = $result[0]['activity_id'];
			$nickname = $result[0]['nickname'];
			$sex = $result[0]['sex'];
			$questype3[$i]['activity_id'] = $activity_id;
			$questype3[$i]['nickname'] = $nickname;
			$questype3[$i]['sex'] = $sex;
			$questype3[$i]['prize'] = '1';
		}

//		dump($questype0);
		$arr = array_merge($questype0, $questype1, $questype2, $questype3);

		$this->_view['questype0'] = $questype0;
		$this->_view['questype1'] = $questype1;
		$this->_view['questype2'] = $questype2;
		$this->_view['questype3'] = $questype3;
//		$this->_view['arr'] = $arr;


//搜索
		$search_list_temp = array();
		$nickname = '';
		$activity_tag = '';
		$search_prize = '';
//        dump(empty($activity_id));exit;
		//get需要的值
		if (isset($_GET['activity_id'])) {
			$activity_tag = addslashes(trim($_GET['activity_id']));
		}
		if (isset($_GET['nickname'])) {
			$nickname = addslashes(trim($_GET['nickname']));
		}
		if (isset($_GET['prize'])) {
			$search_prize = addslashes(trim($_GET['prize']));
		}

		$sex = '';
		$openid = '';
		$count = '';
		$this->_view['nickname'] = stripslashes($nickname);
//		$this->_view['count'] = stripcslashes($count);
		$this->_view['sex'] = stripslashes($sex);
		$this->_view['openid'] = stripslashes($openid);
		$this->_view['activity_id'] = $activity_tag;
		$this->_view['search_prize'] = $search_prize;

		$show_search = array();
		foreach ($arr as $key => $value) {
			$name = $value['nickname'];
			if (!empty($nickname) && !empty($name) && !empty($search_prize)) {
				if (strstr($name, $nickname)) {
					if ($arr[$key]['activity_id'] == $activity_tag) {
						if ($arr[$key]['prize'] == $search_prize) {
							$show_search[$key] = $arr[$key];
//							$this->_view['arr'] = $show_search;
						}
					} elseif ($arr[$key]['activity_id'] != $activity_tag) {
//						$this->_view['arr'] = null;
						$show_search = null;
					}
				}
			} elseif (empty($nickname) && !empty($activity_tag) && empty($search_prize)) {
				if ($arr[$key]['activity_id'] == $activity_tag) {
					$show_search[$key] = $arr[$key];
//					$this->_view['arr'] = $show_search;
				} elseif ($arr[$key]['activity_id'] != $activity_tag) {
//					$this->_view['arr'] = null;
					$show_search =null;
				}
			} elseif (empty($nickname) && !empty($activity_tag) && !empty($search_prize)) {
				if ($arr[$key]['activity_id'] == $activity_tag && $arr[$key]['prize'] == $search_prize) {
					$show_search[$key] = $arr[$key];
//					$this->_view['arr'] = $show_search;
				} elseif ($arr[$key]['activity_id'] != $activity_tag) {
//					$this->_view['arr'] = null;
					$show_search = null;
				}
//				else{$this->_view['arr'] = null; }
			} elseif (!empty($nickname) && !empty($activity_tag) && empty($search_prize)) {
				if ($arr[$key]['activity_id'] == $activity_tag) {
					if (strstr($name, $nickname)) {
						$show_search[$key] = $arr[$key];
//						$this->_view['arr'] = $show_search;
					}
				} else {
//					$this->_view['arr'] = null;
					$show_search = null;
				}
			} elseif (!empty($nickname) && empty($activity_tag) && empty($search_prize)) {
				if (strstr($name, $nickname)) {
					$show_search[$key] = $arr[$key];
//					$this->_view['arr'] = $show_search;
				}
			} elseif(empty($nickname) && empty($activity_tag) && empty($search_prize)) {
				$show_search = $arr;
			}
		}


//-----------------------------------------------
//		$search_where = implode(' and ', $search_list_temp);

//		$q = Personnel::find($search_where)->order('id desc')->limitPage($page, $limit);
//q1是查询activity的activity_id字段
		$q1 = Activity::find();
		$list1 = $q1->getAll()->toHashmap('id', 'activityname');
// dump($list1);
// dump($list1);exit;
//------------------------------------------------------------------------
// dump($list);exit;
//		$list = $q->getAll();
// dump($q->getOne()->activity);exit();
// dump($list);exit;
//        dump($show);exit;
//		$this->_view['pager'] = $q->getPagination();
//		$this->_view['list'] = $list;

//        dump($show);exit;
		$this->_view['list1'] = $list1;
//		$this->_view['start'] = ($page - 1) * $limit;
		$this->_view['subject'] = "各奖项摇动次数统计";

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


