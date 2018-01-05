<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    //

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

        // 使用 session() 访问会话实例
        // 存入一条缓存数据，并只在下一次请求内有效，使用 flash
        // flash, arg1: 会话的 key, arg2: 会话的 value; 实际上就是一个字典咯
        session()->flash('success', 'welcome, you will start a new journey here');

        return redirect()->route('users.show', [$user]);

        // route 会自动获取 model 的主键
        // return redirect()->route('users.show', [$user->id]);
    }
}
