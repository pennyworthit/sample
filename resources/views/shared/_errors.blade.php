@if (count($errors) > 0)
  <div class="alert alert-danger">
    <ul>
      {{-- 条件控制语句都以 @ 开头 --}}
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif