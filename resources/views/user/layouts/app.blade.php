<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title')</title>
@include('user.partials.style')
@yield('custom_style')
</head>
<body>
<div class="main-wrapper">
   @include('user.partials.header')
   @include('user.partials.sidebar')
   <div class="page-wrapper">
      @yield('contents')
      @include('user.partials.footer')
   </div>
</div>
@include('user.partials.script')
@yield('custom_script')
</body>
</html>