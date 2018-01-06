<header class="navbar navbar-fixed-top navbar-inverse">
  <div class="container">
    <div class="col-md-offset-1 col-md-10">
      <a href="{{ route('home') }}" id="logo">Sample App</a>
      <nav>
        <ul class="nav navbar-nav navbar-right">
          @if (Auth::check())
            <li><a href="{{ route('users.index') }}">用户列表</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                {{ Auth::user()->name }} <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li>
                    <a href="{{ route('users.show', Auth::user()->id) }}">个人中心</a>
                </li>
                <li><a href="{{ route('users.edit', Auth::user()->id) }}">编辑资料</a></li>
                <li class="divider"></li>
                <li>
                    <a id="logout" href="#">
                    <form action="{{ route('logout') }}" method="POST">
                        {{ csrf_field() }}
                        {{-- 浏览器不支持发送 DELETE 请求 --}}
                        {{-- Laravel 遵从 RESTful 架构，所以规范使用 DELETE 动作 --}}
                        {{-- 使用 method_field 来创建一个隐藏域，伪造 DELETE 请求 --}}
                        {{ method_field('DELETE') }} {{-- 删除会话资源 --}}
                        {{-- method_field 将会生成一个隐藏 input --}}
                        {{-- <input type="hidden" name="_method" value="DELETE"> --}}
                        <button class="btn btn-block btn-danger" type="submit" name="button">退出</button>
                    </form>
                    </a>
                </li>
              </ul>
            </li>
          @else
            <li>
              <a href="{{ route('help') }}">帮助</a>
            </li>
            <li>
              <a href="{{ route('login') }}">登陆</a>
            </li>
          @endif
        </ul>
      </nav>
    </div>
  </div>
</header>