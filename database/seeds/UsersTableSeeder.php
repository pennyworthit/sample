<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        // times, make 是 Laravel 的 FactoryBuilder 的 API
        // times 生成的模型数量
        // make 创建一个集合
        $users = factory(User::class)->times(50)->make();

        // makeVisible 临时显示 User 模型里指定的隐藏属性 $hidden
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());

        $user = User::find(1);
        $user->name = 'Aufree';
        $user->email = 'aufree@yousails.com';
        $user->password = bcrypt('password');
        $user->is_admin = true;
        $user->save();
    }
}
