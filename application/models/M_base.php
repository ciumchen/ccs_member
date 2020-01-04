<?php
/** 
 * 最基础模型 -  数据库最基础的增删改查，避免代码重复
 */
class M_base extends CI_Model {
    
    public function __construct() 
    {
        parent::__construct();
    }

    /** 添加记录
     * @param $table 数据表名
     * @param $data  数据
     * @return bool
     */
    function I($table, $data)
    {
        if($this->db->insert($table,$data))
        {
            return $this->db->insert_id();
        }

        return FALSE;
    }

    /** 删除记录
     * @param $table 数据表名
     * @param $where 条件语句
     * @return bool
     */
    function D($table, $where)
    {
        if(!is_array($where) || !$where)
        {
            return FALSE;
        }
        if($this->db->where($where)->delete($table))
        {
            return TRUE;
        }

        return FALSE;
    }

    /** 更改记录
     * @param $table 数据表名
     * @param $where 条件语句
     * @param $data  修改数据
     * @return bool
     */
    function U($table, $where, $data)
    {
        if($this->db->where($where)->update($table, $data))
        {
            return TRUE;
        }

        return FALSE;
    }

    /** 基础查询记录
     * @param $table 表名
     * @param $where 条件语句
     * @param bool $field 查询的字段 "name,email,mobile"
     * @param array $order_by 排序语句 array('name'=>'desc','id'=>'asc')
     * @param bool $result_type 默认FALSE：单个结果集，TRUE：多条结果集
     * @return mixed
     */
    function S($table, $where, $field = FALSE, $result_type = FALSE, $order_by = array())
    {
        if($field !== FALSE)
        {
            $this->db->select($field);
        }

        $this->db->where($where);

        if(is_array($order_by) && $order_by)
        {
            foreach ($order_by as $key=>$value)
            {

                $this->db->order_by($key, $value);
            }
        }

        $result = $result_type === FALSE ?  'row_array' : 'result_array';

        return $this->db->get($table)->$result();
    }

    /** 统计记录
     * @param $table 表名
     * @param bool $where 查询条件
     * @return mixed
     */
    function C($table, $where = FALSE)
    {
        if($where !== FALSE)
        {
            $this->db->where($where);
        }

        return $this->db->from($table)->count_all_results();
    }

    /** 求和统计
     * @param $table 表名
     * @param $where 条件
     * @param $filed 求和的字段 例如：goods_number
     * @return mixed
     */
    function SUM($table, $where, $filed)
    {
        $sum = $this->db
                   ->select_sum($filed)
                   ->where($where)
                   ->get($table)->row_array();

        return $sum[$filed] ? $sum[$filed] : 0 ;
    }
    
}