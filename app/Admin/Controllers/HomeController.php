<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Box;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $register_num = '[12, 19, 48]';//注册
        $login_num = '[30, 45, 87]';//登录
        return $content
            ->header('统计图表')
            ->description('今日/本周/本月数据对比')
            ->body(new Box('注册/登录图表', view('admin.chart', compact('register_num', 'login_num'))));
    }
}
