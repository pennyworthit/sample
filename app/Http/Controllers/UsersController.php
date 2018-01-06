<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Auth;
use Mail;

class UsersController extends Controller
{
    //

    public function __construct() {
        // 应用中间件

        $this->middleware('auth', [
            // except 指定的动作不进行中间件过滤
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail'],

            // 同时还有，only, 指定的动作才会进行过滤，但使用 except 是最佳实践
        ]);

        // 只允许未登录用户访问注册页面
        $this->middleware('guest', [
            'only' => ['create'],
        ]);
    }

    public function index() {
        // $users = User::all();

        // 分页
        $users = User::paginate(10);

        return view('users.index', compact('users'));
    }

    public function create() {
        return view('users.create');
    }

    public function show(User $user) {
        // $user 通过 compact 方法转化为一个关联数组，传到 view
        return view('users.show', compact('user'));
    }

    public function store(Request $request) {
        // validate 由 App\Http\Controllers\Controller 类中的 ValidatesRequests 定义
        // arg1: 用户输入的数据
        // arg2: 数据的验证规则
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // 获取请求的所有数据 $request->all()

        // 注册后自动登录
        // Auth::login($user);

        // 使用 session() 访问会话实例
        // 存入一条缓存数据，并只在下一次请求内有效，使用 flash
        // flash, arg1: 会话的 key, arg2: 会话的 value; 实际上就是一个字典咯
        // session()->flash('success', 'welcome, you will start a new journey here');

        // return redirect()->route('users.show', [$user]);

        // route 会自动获取 model 的主键
        // return redirect()->route('users.show', [$user->id]);

        // 注册后发送邮件
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', 'check your email');
        return redirect('/');
    }

    public function edit(User $user) {

        // authorize 用于检验用户授权策略
        // authorize 来自与 App\Http\Controllers\Controller 的 AuthorizesRequests, 是存在于 Laravel 中的
        // 当 authorize 时，无权限时，将抛出异常
        // arg1 授权策略名称
        // arg2 授权验证的数据
        $this->authorize('update', $user);

        // compact — 建立一个数组，包括变量名和它们的值, php 内置方法
        // http://php.net/manual/zh/function.compact.php
        return view('users.edit', compact('user'));
    }

    public function update(User $user, Request $request) {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6',
        ]);

        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password); // bcrypt Laravel 内置方法
        }
        $user->update($data);

        session()->flash('success', 'info updated');
        return redirect()->route('users.show', $user->id);
    }

    public function destroy(User $user) {

        // 检查授权状态
        $this->authorize('destroy', $user);

        $user->delete();
        session()->flash('success', 'deleted!');
        return back();
    }

    protected function sendEmailConfirmationTo($user) {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'aufree@yousails.com';
        $name = 'Aufree';
        $to = $user->email;
        $subject = 'Thanks for registering, confirm your email';

        Mail::send($view, $data, function($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token) {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', 'congratulations, activation succeeded');
        return redirect()->route('users.show', [$user]);
    }
}
