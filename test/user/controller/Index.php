<?php
namespace app\user\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        if(!session('User')){
            return $this -> redirect('basic/login');
        }
        return $this -> fetch();
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
