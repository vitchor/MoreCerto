
<table border="0" cellspacing="1" cellpadding="3">
	<tr>
		<th>Clicks</th>
		<th>Url</th>
		<th>Ações</th>
	</tr>
	<?php foreach ($trending as $t): ?>
	<tr>
		<td><?=$t->clicks?></td>
		<td><a target="_blank" href="<?=base_url()?>realestates/delete/<?=$t->fidrealestate?>">Deletar <?=$t->fidrealestate?></a></td>
		<td>
			<a target="_blank"  href="<?=$t->url?>"><?=$t->url?></a>	
		</td>
	</tr>
	<?php endforeach;?>	
</table>