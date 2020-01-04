<?php

class M_address extends CI_Model {

    public $mall = false;
    function __construct(){
        $this->mall = $this->load->database('ccs168_mall',true);
    }

    //获取臻之选收货地址
    public function get_address_list($mobile){
        $res = $this->mall->select('id')->where('username',$mobile)->get('i_user')->row_array();
        if ($res == null){
            return array();
        }
        $address_list = $this->mall->select('*')->where('user_id',$res['id'])->get('i_address')->result_array();
        foreach ($address_list as $key=>$address){
            $province_name = $this->get_area_name($address['province']);
            $city_name = $this->get_area_name($address['city']);
            $area_name = $this->get_area_name($address['area']);
            $address_list[$key]['details'] = $province_name.' '.$city_name.' '.$area_name.' '.$address['address'];
        }
        return $address_list;
    }

    /* 获取地区名称 */
    public function get_area_name($area_id){
        $res = $this->mall->select('area_name')->where('area_id',$area_id)->get('i_areas')->row_array();
        return $res == null ? '' : $res['area_name'];
    }



    /* 获取所有地址列表 */
    public function get_list($user_id){
        $list = $this->db->select('*')->where('user_id',$user_id)->get('user_address')->result_array();
        return $list;
    }

    //新增收货地址
    public function add($mobile,$param){

        $res = $this->mall->select('id')->where('username',$mobile)->get('i_user')->row_array();
        if ($res == null){
            return array('code'=>1001,'msg'=>'请先在商城注册',array());
        }
        $user_id = $res['id'];

        //参数校验
        if (!isset($param['consignee']) || $param['consignee'] == ''){
            return array('code'=>1001,'msg'=>'请输入收件人姓名',array());
        }
        if ((!isset($param['mobile'])) || (!is_phone($param['mobile']))){
            return array('code'=>1002,'msg'=>'收件人手机号码错误',array());
        }
        if (!isset($param['province_id']) || !isset($param['city_id']) || !isset($param['area_id'])){
            return array('code'=>1003,'msg'=>'请选择地址',array());
        }
        if (!isset($param['details'])){
            return array('code'=>1003,'msg'=>'请输入详细的地址',array());
        }

        //保存地址
        $insert_attr = array();
        $insert_attr['user_id'] = $user_id;
        $insert_attr['accept_name'] = $param['consignee'];
        $insert_attr['mobile'] = $param['mobile'];
        $insert_attr['province'] = $param['province_id'];
        $insert_attr['city'] = $param['city_id'];
        $insert_attr['area'] = $param['area_id'];
        $insert_attr['address'] = $param['details'];

        if ($this->mall->insert('i_address',$insert_attr)){
            return array('code'=>0,'msg'=>'','data'=>array());
        }
        return array('code'=>106,'msg'=>'保存失败，请稍后重试',array());
    }

    //编辑地址
    public function edit($address_id){
        $address_info = $this->mall->select('*')->where('id',$address_id)->get('i_address')->row_array();
        if (empty($address_info)){
            return array('code'=>1001,'msg'=>'该地址不存在',array());
        }

        $province_name = $this->get_area_name($address_info['province']);
        $city_name = $this->get_area_name($address_info['city']);
        $area_name = $this->get_area_name($address_info['area']);
        $address_info['region_name'] = $province_name.' '.$city_name.' '.$area_name;
        return array('code'=>0,'msg'=>'',array('address_info'=>$address_info));
    }

    //编辑地址 - 提交
    public function edit_submit($param){

        //参数校验
        if(!isset($param['address_id'])){
            return array('code'=>102,'msg'=>'参数缺失',array());
        }

        //是否存在该地址
        $address = $this->mall->select('id')->where('id',$param['address_id'])->get('i_address')->row_array();
        if (empty($address)){
            return array('code'=>102,'msg'=>'该地址不存在',array());
        }
        if (!isset($param['consignee']) || $param['consignee'] == ''){
            return array('code'=>1001,'msg'=>'请输入收件人姓名',array());
        }
        if ((!isset($param['mobile'])) || (!is_phone($param['mobile']))){
            return array('code'=>1002,'msg'=>'收件人手机号码错误',array());
        }
        if (!isset($param['province_id']) || !isset($param['city_id']) || !isset($param['area_id'])){
            return array('code'=>1003,'msg'=>'请选择地址',array());
        }
        if (!isset($param['details']) || $param['details'] == ''){
            return array('code'=>1003,'msg'=>'请输入详细的地址',array());
        }

        //保存地址
        $update_attr = array();
        $update_attr['accept_name'] = $param['consignee'];
        $update_attr['mobile'] = $param['mobile'];
        $update_attr['province'] = $param['province_id'];
        $update_attr['city'] = $param['city_id'];
        $update_attr['area'] = $param['area_id'];
        $update_attr['address'] = $param['details'];

        $res = $this->mall->where('id',$param['address_id'])->update('i_address',$update_attr);
        if ($res){
            return array('code'=>0,'msg'=>'','data'=>array());
        }
        return array('code'=>106,'msg'=>'保存失败，请稍后重试',array());
    }


    //删除地址
    public function delete($address_id){
        $address = $this->mall->select('id')->where('id',$address_id)->get('i_address')->row_array();
        if (empty($address)){
            return array('code'=>1001,'msg'=>'该地址不存在',array());
        }
        $this->mall->where('id',$address_id)->delete('i_address');
        return array('code'=>0,'msg'=>'删除成功','data'=>array());
    }

    //设为默认地址
    public function set_default($mobile,$address_id){
        $res = $this->mall->select('id')->where('username',$mobile)->get('i_user')->row_array();
        $this->mall->where('user_id',$res['id'])->where('id',$address_id)->update('i_address',array('default'=>1));
        $this->mall->where('user_id',$res['id'])->where('id <>',$address_id)->update('i_address',array('default'=>0));
        return array('code'=>0,'msg'=>'',array());
    }
}