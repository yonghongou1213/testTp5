<?php
namespace app\user\controller;

use app\user\model\UserModel;
use think\Controller;
use think\Cookie;

class Basic extends Controller
{
    /**
     * 日期: 2018-03-03
     * 用途: 登录页面
     */
    public function login()
    {
        $cookie = new Cookie();
        $userName = $cookie -> get('user_cookie');
        return $this -> fetch('basic/login',['account' => $userName]);
    }


    /**
     * 日期: 2018-03-03
     * 用途: 验证账户密码
     */
    public function verify_user()
    {
        $params = array_filter($_POST);
        if($params['user']){
            $userModel = new UserModel();
            $user = $userModel -> where($params['user']) -> find();
            if($user){
                unset($user['password']);
                session('User',$user);
                if($params['saveMe']){
                    $cookie = new Cookie();
                    $cookie -> set('user_cookie',$user['account'],259200);
                }
                $result['success'] = true;
                $result['message'] = '登录成功';
            }else{
                $result['success'] = false;
                $result['message'] = '账户或密码错误';
            }
        }else{
            $result['success'] = false;
            $result['message'] = '未填写账户和密码';
        }
        return json($result);
    }
}
