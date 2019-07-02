<?php

namespace app\admin\controller;

use think\Controller;
use think\Loader;
use think\Request;
use think\Db;

class User extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function add()
    {
        return $this->fetch();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     */
    public function get_users($map='')
    {
        if(Request::instance()->isGet()){
            //获取分页page和limit参数
            $page = input("get.page") ? input("get.page") : 1;
            $page = intval($page);
            $limit = input("get.limit") ? input("get.limit") : 1;
            $limit = intval($limit);
            $start = $limit*($page-1);
            $adminname = input("get.adminname") ? trim(input("get.adminname"),' ') : '';
            if($adminname) {
                $map['adminname'] = array('like','%'.$adminname.'%');
            }
            //分页查询
            $count = Db::name("admin")->where($map)->count();
            $user_list = Db::name("admin")->where($map)->order("auid asc")->limit($start,$limit)->select();
            $list["msg"] = "";
            $list["code"] = 0;
            $list["count"] = $count;
            $list["data"] = $user_list;
            return json($list);
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    // 添加用户操作
    public function do_add(){
        if(Request::instance()->isPost()){
            $data = input('post.');
            $map['adminname'] = $data['adminname'];
            $is_user =  Db::name('admin')->where($map)->find();
            if($is_user){
                return json(["status"=>0,"msg"=>"添加失败，用户名已存在！"]);
            }
            //调用验证器自动验证
            $validate = Loader::validate('Admin');
            $validateData = ['adminname' => $data['adminname'], 'passwd' => $data['passwd']];
            if (!$validate->check($validateData)) {
                return json(["status"=>0,"msg"=>$validate->getError()]);
            }
            $data['passwd'] = md5($data['passwd']);
            $re =  Db::name('admin')->insert($data);
            if($re){
                return json(["status"=>1,"msg"=>"用户添加成功！"]);
            }else{
                return json(["status"=>0,"msg"=>"用户添加失败！"]);
            }
        }
    }

    /**
     * 删除指定资源
     *
     */
    public function do_del($uid)
    {
        if(Request::instance()->isPost()){
            $uid = input('uid');
            if($uid==1){
                return json(["status"=>0,"msg"=>"非法操作,admin不允许删除！"]);
            }
            $re = Db::name('admin')->delete($uid);
            if($re){
                return json(["status"=>1,"msg"=>"删除成功！"]);
            }else{
                return json(["status"=>0,"msg"=>"删除失败！"]);
            }
        }
    }

    // 删除选中用户操作
    public function do_del_all(){
        if(Request::instance()->isPost()){
            $uids = input('auids');
            $re =  Db::name('admin')->delete($uids);
            if($re){
                return json(["status"=>1,"msg"=>"删除成功！"]);
            }else{
                return json(["status"=>0,"msg"=>"删除失败！"]);
            }
        }
    }

}
