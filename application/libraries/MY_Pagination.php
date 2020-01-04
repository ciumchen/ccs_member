<?php
/** 
 *　重写分类中的方法
 * @author: jason
 */
class MY_Pagination extends CI_Pagination {

    function initialize(array $params = array()) 
    {
        $params['page_query_string'] = TRUE;
        $params['query_string_segment'] = 'page';
        $params['use_page_numbers'] = TRUE;
        $params['first_link'] = "首页";
        $params['last_link'] = "尾页";
        $params['prev_link'] = '&laquo;';
        $params['next_link'] = '&raquo;';
        $params['full_tag_open'] = '';
        $params['full_tag_close'] = '';
        $params['first_tag_open'] = '<li>';
        $params['first_tag_close'] = '</li>';
        $params['last_tag_open'] = '<li>';
        $params['last_tag_close'] = '</li>';
        $params['cur_tag_open'] = '<li class="active"><a>';
        $params['cur_tag_close'] = '</a></li>';
        $params['num_tag_open'] = '<li>';
        $params['num_tag_close'] = '</li>';
        $params['next_tag_open'] = '<li>';
        $params['next_tag_close'] = '</li>';
        $params['prev_tag_open'] = '<li>';
        $params['prev_tag_close'] = '</li>';
        $params['per_page'] = isset($params['per_page'])?$params['per_page']:10;
        $config['use_global_url_suffix'] = FALSE;
        
        parent::initialize($params);
    }
    
    function create_links() 
    {
        $output = parent::create_links() ;
//        if(($this->total_rows) / ($this->per_page) > 3){
//            $output .= "<li style='display: inline-block;margin-left: 10px;'>";
//            $output .= "<div class='input-group'>";
//            $output .= "<input type='text' class='form-control pager-num' style='width: 60px;height: 36px; text-indent: 0;' placeholder='页数'>";
//            $output .= "<span class='input-group-btn' style='cursor: pointer;width: auto;'><button class='btn btn-xs btn-pager btn-info' style='height: 36px;' type='button'>跳转到此页</button></span>";
//            $output .= "</div></li>";
//        }
        return $output;
    }

}