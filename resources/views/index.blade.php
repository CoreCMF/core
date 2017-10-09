
<!DOCTYPE html>
<html lang="en">
<head>
    <title>LaravelVue</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ config('website.icon') }}">
    <!--[if lt IE 9]>
        <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href={{ asset('/vendor/'.$model.'/css/app.min.css') }} rel=stylesheet>
    <!-- 渲染插件Css -->
@if (!empty($resources['css']))
  @foreach ($resources['css'] as $css)
  <link href={{ $css }} rel=stylesheet>
  @endforeach
@endif
    <script>
        window.config = {
            apiUrl: '/api/{{$model}}/main',
            csrfToken:'{{ csrf_token() }}',
@if (!empty($config))
            {!! $config !!},
@endif
        }
    </script>
</head>
<body>
    <div id=app></div>
    <!-- 渲染插件Html Begin -->
@if (!empty($resources['html']))
  @foreach ($resources['html'] as $html)
  {{ $html }}
  @endforeach
@endif
    <!-- 渲染插件Html End -->
    <!-- 渲染插件Js Begin -->
@if (!empty($resources['js']))
  @foreach ($resources['js'] as $js)
  <script type=text/javascript src={{ $js }}></script>
  @endforeach
@endif
  <!-- 渲染插件Js End -->
    <script type=text/javascript src={{ asset('/vendor/'.$model.'/js/manifest.min.js') }}></script>
    <script type=text/javascript src={{ asset('/vendor/'.$model.'/js/vendor.min.js') }}></script>
    <script type=text/javascript src={{ asset('/vendor/'.$model.'/js/app.min.js') }}></script>
</body>
</html>
