<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:fb="http://ogp.me/ns/fb#">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php if(isset($realestate)):?>
<title>Apartamento em <?=ucwords(mb_strtolower(ltrim($realestate->district),'UTF-8'));?> , <?=ucwords(mb_strtolower(ltrim($realestate->city),'UTF-8'));?> por R$<?= $realestate->price;?></title>
<meta property="fb:admins" content="100000830045313" />
<meta property="fb:page_id" content="398635916833848" />
<meta property="og:title" content="Apartamento em <?=ucwords(mb_strtolower(ltrim($realestate->district),'UTF-8'));?> , <?=ucwords(mb_strtolower(ltrim($realestate->city),'UTF-8'));?> por R$<?= $realestate->price;?>" />
<meta property="og:url" content="http://www.morecerto.com.br/realestates/show/<?=$realestate->idrealestates;?>" />
<meta property="og:description" content="Alugue um apartamento no bairro <?=ucwords(mb_strtolower(ltrim($realestate->district),'UTF-8'));?> em <?=ucwords(mb_strtolower(ltrim($realestate->city),'UTF-8'));?> por R$<?= $realestate->price;?>. Compare no mapa e saiba mais sobre este e outros imóveis de utilizando o Morecerto." />
<meta property="og:image" content="<?= $realestate->thumb?>" />
<?php elseif(isset($city)) :?>
<title>MoreCerto - Imóveis para Alugar e Comprar em <?= $city_name;?></title>
<meta property="og:title" content="MoreCerto - Imóveis para Alugar e Comprar em <?= $city_name;?>" />
<meta property="og:url" content="http://www.morecerto.com.br/#!/<?=$state;?>/<?=$city;?>" />
<meta property="og:description" content="Encontre o melhor imóvel em <?= $city_name;?> e compare no mapa de acordo com o que você acha mais importante." />
<meta property="og:image" content="http://www.morecerto.com.br/img/fb.png" />
<?php else:?>
<title>MoreCerto - Encontre o Melhor Lugar para Morar!</title>
<meta property="og:title" content="MoreCerto" />
<meta property="og:url" content="http://www.morecerto.com.br" />
<meta property="og:description" content="O Morecerto ajuda você a encontrar o melhor imóvel, de acordo com o que você acha mais importante." />
<meta property="og:image" content="http://www.morecerto.com.br/img/fb.png" />
<?php endif;?>
<meta property="og:type" content="website" />
<meta property="og:site_name" content="MoreCerto" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/cupertino/jquery-ui.css" type="text/css"/>
<link href="<?=base_url();?>css/bootstrap.css" rel="stylesheet">
<!--<link href="<?=base_url();?>css/bootstrap-responsive.css" rel="stylesheet"> -->
<link href="<?=base_url();?>css/global.css" rel="stylesheet" type="text/css" />
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<link rel="shortcut icon" href="ico/favicon.ico">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
</head>