<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods_manage extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->_viewData['title'] = '商品管理';
    }


    //大礼包上架页面
    public function edit_group_goods_page(){
        parent::view('/admin/edit_group_goods_page');
    }

    //上架大礼包商品
    public function edit_group_goods(){

        $postData = $this->input->post();
        // 无效参数
        if (!$postData) {
            $this->response(array('code'=>102,'msg'=>'无效参数','data'=>array()));
        }

        $this->mall = $this->load->database('ccs168_mall',true);
        $list = array_filter(explode("\r\n",$postData['goods_no_list']));
        $id_list = array();

        if ($list == array()){
            $this->response(array('code'=>102,'msg'=>'请输入商品ID','data'=>array()));
        }

        //遍历是否有效商品
        foreach ($list as $value){
            $goods = $this->mall->select('id')->from('i_goods')
                ->where('goods_no',$value)
                ->get()->row_array();
            if ($goods == null){
                $this->response(array('code'=>102,'msg'=>"商品编号{$value}不存在",'data'=>array()));
            }
            $id_list[] = $goods['id'];
        }

        //加入到大礼包
        foreach ($id_list as $id){
            $this->mall->insert('i_commend_goods',array(
                'commend_id'=>10,
                'goods_id'=>$id
            ));
        }
        //管理员日志记录
        $this->db->insert('m_admin_action_log',array(
            'module_name'=>'商品管理',
            'function_name'=>'上架大礼包',
            'opera_obj_id'=>implode('|',$list),
            'action_text'=>"",
            'admin_name'=>$_SESSION['admin_name']
        ));
        $this->response(array('code'=>0,'msg'=>"上架成功",'data'=>array()));
    }

}
