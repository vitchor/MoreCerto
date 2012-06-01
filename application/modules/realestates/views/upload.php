<body>
<?php $this->load->view("menu")?>
	<div class="container">
		<div class="row">
				<div class="span12">
					O arquivo deve conter as seguintes colunas:
					<ul>
						<li>thumb</li>
						<li>url</li>
						<li>address</li>
						<li>price</li>
						<li>agency</li>
						<li>rooms</li>
						<li>area</li>
						<li>kind</li>
						<li>type</li>
						<li>lat</li>						
						<li>lng</li>
					</ul>
					<p>Agency é o nome da imobiliária. Se for um imóvel de proprietário é só deixar em branco (mas é necessário ter a coluna)</p>
					<p>Rooms é o número de quartos. Se não tiver, deixe no valor 0(mesma coisa para area).</p>
					<p>Kind é o tipo do imóvel. Pode ser apt,kit,house ou room</p>
					<p>Type é o tipo da negociação. Pode ser rent ou buy.</p>
					<p>Lat e lng podem são opcionais</p>					
					<?php echo form_open_multipart('realestates/upload');?>
						<input class="blue-button" type="file" name="userfile" size="20" />
						<input class="blue-button" type="submit" value="Upload" />
					</form>
				</div>			
		</div>
	</div>	
