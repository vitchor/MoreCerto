<h1>Morecerto</h1>
<a href="<?=base_url();?>#"><img src="<?=base_url();?>images/logo.png"/></a>
<h2>Encontre e compare imóveis para Alugar em <?=$city;?></h2>
<ul>
<?php foreach($realestates->result() as $r):?>
<li>
	<img src="<?php realestateImage($r);?>">Imagem do Apartamento</img>
	<?php if($r->district == NULL || $r->district == ""):?>
	<h2>Aluguel de apartamento em  <?=$r->city;?>,<?=$r->state;?></h2>
	<?php else:?>
	<h2>Aluguel de apartamento no bairro <?=$r->district;?> em  <?=$r->city;?>,<?=$r->state;?></h2>
	<?php endif;?>
	<h3>Preço R$ <?=$r->price;?></h3>
 	<span>Indice Morecerto de Qualidade do Imóvel </span>
	<span></span>
</li>
<?php endforeach;?>
</ul>
