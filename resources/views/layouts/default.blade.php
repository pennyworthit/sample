<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'Sample') - Laravel 入门教程</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>
    @include('layouts._header')

    <div class="container">
      <div class="col-md-offset-1 col-md-10">
        {{-- @include 传入一个文件路径来引用局部视图 --}}
        @include('shared._messages')
        @yield('content')
        @include('layouts._footer')
      </div>
    </div>

    <script src="/js/app.js"></script>
  </body>
</html>