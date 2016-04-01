<?php
namespace Home\Controller;

use Think\Controller;

class TestController extends BaseController
{

    public function index()
    {

        $starttime1 = strtotime('2015-05-13 00:00:00');
        $endtime1 = strtotime('2015-05-13 23:59:59');
        $where1['ctime'] = array('between', array($starttime1, $endtime1));
        $list1 = D('UCLogin')->where($where1)->group('uid')->getField('uid', true);

        $starttime2 = strtotime('2015-05-14 00:00:00');
        $endtime2 = strtotime('2015-05-14 23:59:59');
        $where2['ctime'] = array('between', array($starttime2, $endtime2));
        $list2 = D('UCLogin')->where($where2)->group('uid')->getField('uid', true);

        $list = array_intersect($list1, $list2);

        C('G_SID', 101);
        $dbConfig = change_db_server(101);
        $model = M()->db(101, $dbConfig);

        $create = $model->db(101)->table('g_team')->where("`ctime`>'0'")->getField('uid', true);

        $arr = array_diff($list, $create);
        dump($arr);
        dump(count($arr));


        $endtime3 = $starttime2 + 3 * 3600;
        $select = $model->db(101)->table('g_team')->field('`tid`,`uid`,`last_login_time`')->where("`ctime`>'0' && `ctime`<'{$endtime1}' && `last_login_time` between '{$starttime2}' and '{$endtime3}'")->select();
        dump($model->db(101)->getLastSql());
        dump($select);


    }

    public function gogo()
    {
        C('G_SID', 101);
        $dbConfig = change_db_server(101);
        $model = M()->db(101, $dbConfig);
        $now = time();
        $starttime = strtotime('2015-05-14 00:00:00');
        $endtime = $starttime + 3 * 3600;
        $select = $model->db(101)->table('g_team')->field('`tid`,`uid`,`last_login_time`')->where("`ctime`>'0' && `ctime`<'{$starttime}' && `last_login_time` between '{$starttime}' and '{$endtime}'")->select();

        $data['last_login_time'] = $now;
        $model->db(101)->table('g_team')->where("`tid` = '{$select[0]['tid']}'")->save($data);
        D('Predis')->cli('game', 101)->setex('u:' . $select[0]['uid'], 3600, md5($now));
        dump($select);
        dump($select[0]);
    }

    public function gogo2()
    {
        C('G_SID', 101);
        $dbConfig = change_db_server(101);
        $model = M()->db(101, $dbConfig);
        $now = time();
        $starttime = strtotime('2015-05-14 00:00:00');
        $select = $model->db(101)->table('g_team')->field('`tid`,`uid`,`last_login_time`')->where("`level`>10 && `ctime`>'0' && `ctime`<'{$starttime}' && `last_login_time` < '{$starttime}'")->select();
        dump($select);
        dump($model->db(101)->getLastSql());
        $count = count($select);
        $rand = rand(0, $count - 1);
        $row = $select[$rand];

        $data['last_login_time'] = $now;
        $data['vality_utime'] = $now + rand(300, 1200);
        $model->db(101)->table('g_team')->where("`tid` = '{$row['tid']}'")->save($data);
        D('Predis')->cli('game', 101)->setex('u:' . $row['uid'], 3600, md5($now));
        dump($count);
        dump($row);
    }

    public function gogo3()
    {
        C('G_SID', 101);
        $dbConfig = change_db_server(101);
        $model = M()->db(101, $dbConfig);
        $now = time();
        $starttime = strtotime('2015-05-14 00:00:00');
        $select = $model->db(101)->table('g_team')->field('`tid`,`uid`,`last_login_time`')->where("`level`<=10 && `level`>=5 && `ctime`>'0' && `ctime`<'{$starttime}' && `last_login_time` < '{$starttime}'")->select();
        dump($select);
        dump($model->db(101)->getLastSql());
        $count = count($select);
        $rand = rand(0, $count - 1);
        $row = $select[$rand];

        $data['last_login_time'] = $now;
        $data['vality_utime'] = $now + rand(300, 1200);
        $model->db(101)->table('g_team')->where("`tid` = '{$row['tid']}'")->save($data);
        D('Predis')->cli('game', 101)->setex('u:' . $row['uid'], 3600, md5($now));
        dump($count);
        dump($row);
    }

    public function gogo_sql()
    {
        $sql = "update `g_team` set `vality_utime`=`last_login_time`+1253 where `last_login_time`-`vality_utime`>50000;";
        C('G_SID', 101);
        $dbConfig = change_db_server(101);
        $model = M()->db(101, $dbConfig);
        $model->db(101)->execute($sql);
    }

    public function gogo_sql2()
    {

        C('G_SID', 101);
        $dbConfig = change_db_server(101);
        $model = M()->db(101, $dbConfig);
        $end = strtotime('2015-05-14 00:00:00') - 1;
        $tidStr = "101000046,101000056,101000057,101000082,101000084,101000091,101000095,101000102,101000118,101000120,101000127,101000129,101000133,101000136,101000149,101000152,101000157,101000161,101000164,101000165,101000168,101000172,101000173,101000193,101000195,101000199,101000213,101000214,101000216,101000217,101000220,101000223,101000242,101000255,101000260,101000267,101000268,101000287,101000294,101000303,101000323,101000324,101000382,101000395,101000404,101000420,101000483,101000514,101000548,101000570,101000624,101000631,101000638,101000646,101000653,101000661,101000664,101000666,101000693,101000718,101000744,101000755";
        $tidList = explode(',', $tidStr);
        $where['tid'] = array('in', $tidList);
        $select = $model->db(101)->field("`tid`,`ctime`")->where($where)->select();
        foreach ($select as $value) {
            $info[$value['tid']] = $value['ctime'];
        }

        foreach ($tidList as $tid) {
            $login = rand($info[$tid] + 600, $end);
            $vality = $login + rand(300, 1200);
            $sql = "update `g_team` set `last_login_time`='{$login}',`vality_utime`='{$vality}' where `tid`='{$tid}'";
            $model->db(101)->execute($sql);
        }

    }


}