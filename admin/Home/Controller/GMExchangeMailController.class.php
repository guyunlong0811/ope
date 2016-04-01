<?php
namespace Home\Controller;

use Think\Controller;

class GMExchangeMailController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->vIcon = 'wrench';
        $this->vTitle = 'gm_exchange_mail';
        $this->vFieldConfig = C('FIELD');
        $this->vItemConfig = D('Static')->access('item');
        $partner = D('Static')->access('partner_group');
        foreach ($partner as $key => $value) {
            $arr = current($value);
            $config[$key] = $arr['name'];
        }
        $this->vPartnerConfig = $config;
        $this->vEmblemConfig = D('Static')->access('emblem');
    }

    //显示列表
    public function index()
    {

        //查询所有信息
        $model = D('DMail');
        $where['behave'] = 'exchange';
        $order['index'] = 'asc';
        $page = $this->page($model, 'sql', $where);
        $list = $model->page($this->pg . ',' . $page->listRows)->where($where)->order($order)->select();
        foreach ($list as $key => $value) {
            $list[$key]['type'] = $value['type'] == 1 ? L('notice_mail') : L('annex_mail');
            $list[$key]['expires_value'] = $value['expires_value'] / 3600;
            for ($i = 1; $i <= 4; ++$i) {
                if ($list[$key]['annex_type_' . $i] == 0) {
                    $list[$key]['annex' . $i] = L($this->mBonusType[$value['annex_type_' . $i]]);
                } else {
                    $list[$key]['annex' . $i] = L($this->mBonusType[$value['annex_type_' . $i]]) . '-' . $this->getBonusId($value['annex_type_' . $i], $value['annex_type_' . $i . '_value_1']) . ':' . $value['annex_type_' . $i . '_value_2'] . ';';
                }
            }
        }

        //显示
        $this->vTable = $list;
        $this->display();//显示页面

    }

    //新增
    public function add()
    {

        if (!empty($_POST)) {

            //生成邮件内容
            $mail['name'] = I('post.name');
            $mail['from'] = I('post.from');
            $mail['des'] = trimTT(I('post.des'));
            if ($mail['type'] == 1) {//附件
                for ($i = 1; $i <= 4; ++$i) {
                    $mail['annex_type_' . $i] = 0;
                    $mail['annex_type_' . $i . '_value_1'] = 0;
                    $mail['annex_type_' . $i . '_value_2'] = 0;
                }
            } else {
                for ($i = 1; $i <= 4; ++$i) {
                    $mail['annex_type_' . $i] = I('post.annex_type_' . $i);
                    if ($mail['annex_type_' . $i] == '0') {
                        $mail['annex_type_' . $i . '_value_1'] = 0;
                        $mail['annex_type_' . $i . '_value_2'] = 0;
                    } else {
                        $mail['annex_type_' . $i . '_value_1'] = I('post.annex_type_' . $i . '_value_1');
                        $mail['annex_type_' . $i . '_value_2'] = I('post.annex_type_' . $i . '_value_2');
                    }
                }
            }
            $mail['expires_value'] = I('post.expires_value') * 3600;

            //获取最大index
            $mail['index'] = D('DMail')->getExchangeIncIndex();

            //返回结果
            if (false === D('DMail')->CreateData($mail)) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
                $this->clearApc('d_mail');
            }
        }

        $this->alert = get_error();
        $this->assign('bonus_type', $this->mBonusType);
        $this->display();//显示页面

    }

    //编辑
    public function edit()
    {

        if (!empty($_POST)) {

            //生成邮件内容
            $mail['name'] = I('post.name');
            $mail['from'] = I('post.from');
            $mail['des'] = trimTT(I('post.des'));
            if ($mail['type'] == 1) {//附件
                for ($i = 1; $i <= 4; ++$i) {
                    $mail['annex_type_' . $i] = 0;
                    $mail['annex_type_' . $i . '_value_1'] = 0;
                    $mail['annex_type_' . $i . '_value_2'] = 0;
                }
            } else {
                for ($i = 1; $i <= 4; ++$i) {
                    $mail['annex_type_' . $i] = I('post.annex_type_' . $i);
                    if ($mail['annex_type_' . $i] == '0') {
                        $mail['annex_type_' . $i . '_value_1'] = 0;
                        $mail['annex_type_' . $i . '_value_2'] = 0;
                    } else {
                        $mail['annex_type_' . $i . '_value_1'] = I('post.annex_type_' . $i . '_value_1');
                        $mail['annex_type_' . $i . '_value_2'] = I('post.annex_type_' . $i . '_value_2');
                    }
                }
            }
            $mail['expires_value'] = I('post.expires_value') * 3600;

            //获取最大index
            $where['index'] = I('post.index');

            //返回结果
            if (false === D('DMail')->UpdateData($mail, $where)) {
                C('G_ERROR', 'fail');
            } else {
                C('G_ERROR', 'success');
                $this->clearApc('d_mail');
            }

        }

        $data = D('DMail')->where("`index`='" . I('get.index') . "'")->find();
        $data['expires_value'] /= 3600;
        $this->vData = $data;

        $this->alert = get_error();
        $this->assign('bonus_type', $this->mBonusType);
        $this->display();//显示页面

    }

}