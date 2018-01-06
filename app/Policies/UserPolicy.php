<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $currentUser, User $user) {
        // 比较 当前登录用户 和 要进行授权的用户
        // id 不一致抛 403
        // 不需要判断 $currentUser 是否为 NULL, 如果为未登录用户，所有权限都会返回 false
        // 调用时，不需要传递当前登录用户到方法中，laravel 会自动加载当前登录用户
        return $currentUser->id === $user->id;
    }

    public function destroy(User $currentUser, User $user) {
        // 只有当前登录用户为管理员才能执行删除
        // 删除对象不能为自己，管理员页不能把自己删除
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
