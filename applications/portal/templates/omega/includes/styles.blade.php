<link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900,300italic,400italic,600italic' rel='stylesheet' type='text/css'>
@if(is_dev())
<link rel="stylesheet" href="{{asset_url('omega/css/bootstrap.min.css','templates')}}">
<link rel="stylesheet" href="{{asset_url('omega/css/theme.css','templates')}}">
<link rel="stylesheet" href="{{asset_url('omega/css/swatch-gray.css','templates')}}" media="screen">
<link rel="stylesheet" href="{{asset_url('omega/css/swatch-black.min.css','templates')}}" media="screen">
<link rel="stylesheet" href="{{asset_url('omega/css/swatch-ands-green.css','templates')}}" media="screen">
<link rel="stylesheet" href="{{asset_url('omega/css/fonts.min.css','templates')}}" media="screen">
<link rel="stylesheet" href="{{asset_url('lib/dynatree/src/skin/ui.dynatree.css', 'core')}}" media="screen">
<link rel="stylesheet" href="{{asset_url('lib/qtip2/jquery.qtip.css', 'core')}}" media="screen">
<link rel="stylesheet" href="{{asset_url('lib/angular-loading-bar/build/loading-bar.min.css', 'core')}}"></link>
<link rel="stylesheet" href="{{asset_url('omega/css/ands.css','templates')}}" media="screen">
<!-- LESS file for development only -->
<link rel="stylesheet/less" type="text/css" href="{{asset_url('omega/less/ands-portal.less','templates')}}" media="screen">
<!-- Graph Widget -->
<!-- <link rel="stylesheet" type="text/css" href="{{apps_url('assets/graph_widget/css/graph_widget.css')}}" media="screen"> -->
<link rel="stylesheet" type="text/css" href="{{asset_url('css/graph_widget.css', 'core')}}" media="screen">
@else
<link rel="stylesheet" type="text/css" href="{{asset_url('css/portal.combine.css', 'core')}}" media="screen">
@endif