<body>
<div class="">
	<div class="header">
		<div class="container">
			<div class="search">
				<div class="logo">
					<h1>
					<a href="<?=base_url();?>"><img src="<?=base_url();?>images/logo.png"/></a>
					</h1>		
					<div class="like_div">
						<fb:like href="http://www.morecerto.com.br" send="false" layout="box_count" width="60" show_faces="false" action="like" font="" class=" fb_edge_widget_with_comment fb_iframe_widget"></fb:like>
					</div>		
				</div>			
				</div>				
			</div>			
		</div>	
	</div>
	<div class="main">
		<div class="top-border"></div>
				<div class="container">
			<br><br>
			<br><br>
				<button id="submit">Enviar Dados</button>
				<p id="info">Verifique se os dados estão corretos e Clique em Enviar</p>
				
				<table border="0" cellspacing="1" cellpadding="3">
					<tr>
						<?php foreach ($csv->titles as $value): ?>
						<th><?php echo $value; ?></th>
						<?php endforeach; ?>
					</tr>
					<?php foreach ($csv->data as $key => $row): ?>
					<tr>
						<?php foreach ($row as $value): ?>
						<td><?php echo $value; ?></td>
						<?php endforeach; ?>
					</tr>
					<?php endforeach; ?>
				</table>
	<br><br>
	<br><br>
	</div>			
	</div>	

		
<script type="text/javascript">
var data = <?=$json;?>;

$("#submit").click( function(){
	addRealEstate(0);
});

function addRealEstate(index){
	if(index>=data.length) {
		alert('complete');
		return;		
	}
	$("#info").text("Carregando número " + index);
	$.post("<?=base_url();?>"+"realestates/add",
			{"thumb":data[index].thumb,
			"url":data[index].url,
			"price":data[index].price,
			"address":data[index].address,
			"type":data[index].type,
			"agency":data[index].agency,
			"rooms":data[index].rooms,
			"area" :data[index].area,
			"kind" :data[index].kind,
			},
		function(response){
			setTimeout(function(){addRealEstate(index+1);},100);
		});
}
</script>		