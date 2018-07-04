<?php defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$title?></title>

    <link href="<?=base_url()?>templ/site/img/favicon.ico" rel="shortcut icon"  />
    <link href="<?=base_url()?>templ/site/css/style.css" rel="stylesheet" type="text/css" media="screen" />

    <meta name="viewport" content="width=device-width">

    <script type="text/javascript" src="<?=base_url()?>templ/site/js/jquery-1.8.3.min.js"></script>
</head>
<body>

<div id="container">
    <div id="header"><?=$header?></div>
    <div id="content"><?=$content?></div>
    <div id="footer"><?=$footer?></div>
</div>

</body>
</html>