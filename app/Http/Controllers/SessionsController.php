<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    //

    public function __construct() {
        // 只允许未登录的用户访问登录页面
        $this->middleware('guest', [
            'only' => ['create'],
        ]);
    }

    public function create() {
        return view('sessions.create');
    }

    public function store(Request $request) {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        // 验证用户身份
        // attemp 逻辑
        // 1.将传入的 password 进行哈希加密，再与数据库的相应字段进行对比
        // 2.数据匹配，则创建一个 session 给用户，同时创建一个 `laravel_session` 的 HTTP Cookie, 记录用户的登录状态，返回 true
        // 3.数据不匹配，返回 false
        // 4. remember 记住我，Laravel 已默认为用户生成的迁移文件中已经包含了 remember_token 字段
        if (Auth::attempt($credentials, $request->has("remember"))) {
            // 检验邮箱是否已经验证
            if (Auth::user()->activated) {
                session()->flash('success', 'Welcome back!');
                // return redirect()->route('users.show', [Auth::user()]);

                // intended 将页面重定向到上一次请求尝试访问的页面
                // arg1 默认跳转地址，若上一次请求记录为空，则跳转到默认地址上
                return redirect()->intended(route('users.show', [Auth::user()]));
                // Auth::user() Laravel 提供来获取当前登录用户信息
            } else {
                Auth::logout();
                session()->flash('warning', 'Account has not been activated');
                return redirect('/');
            }
        } else {
            session()->flash('danger', 'Sorry, email and password do not match');
            return redirect()->back();
        }

        return;
    }

    public function destroy() {
        Auth::logout();
        session()->flash('success', 'You have logged out');
        return redirect('login');
    }
}
