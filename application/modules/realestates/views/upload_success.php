<body>
<?php $this->load->view("menu")?>
	<div class="container">
		<div class="row">
				<div class="span12">
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
				</div>			
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
			"lat" :data[index].lat,
			"lng" :data[index].lng
			},
		function(response){
			setTimeout(function(){addRealEstate(index+1);},100);
		});
}
</script>		