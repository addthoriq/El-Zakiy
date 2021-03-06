<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>E-Learning | @yield('title')</title>
    @include('layouts.links.style')
    @yield('style')

</head>

<body>
    <div id="wrapper">

        {{-- Header Section --}}
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0;">
            @include('layouts.parts.navbar')
        </nav>

        {{-- Sidebar Section --}}
        <nav class="navbar-default navbar-static-side" role="navigation">
            @include('layouts.parts.sidebar')
        </nav>

        {{-- Content Section --}}
        <div id="page-wrapper" class="gray-bg dashbard-1">
            @yield('content')
        
            @include('layouts.parts.footer')
        </div>

    </div>

@include('layouts.links.script') 
@yield('script')   
</body>
</html>
