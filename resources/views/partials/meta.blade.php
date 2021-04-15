<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="@yield('site_description')">
<meta name="keywords" content="@yield('site_keywords')">
<meta name="author" content="@yield('site_author')">
<meta property="og:title" content="@yield('title')" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?=url()->current()?>" />
<meta property="og:image" content="<?=url('/')?>@yield('site_image')" />
<meta property="og:description" content="@yield('site_description')"/>
<meta property="og:keywords" content="@yield('site_keywords')">
<meta property="fb:app_id" content="@yield('fb_app_id')"/>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<!--[if lt IE 9]>
	<script src="bower_components/html5shiv/dist/html5shiv.js"></script>
<![endif]-->
