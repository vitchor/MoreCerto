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
				O arquivo deve conter as seguintes colunas:
				<ul>
					<li>thumb</li>
					<li>url</li>
					<li>address</li>
					<li>price</li>
					<li>agency</li>
					<li>rooms</li>
				</ul>
				<p>Agency é o nome da imobiliária. Se for um imóvel de proprietário é só deixar em branco (mas é necessário ter a coluna)</p>
				<p>Rooms é o número de quartos. Se não tiver, deixe no valor 0.</p>
				<?php echo form_open_multipart('realestates/upload');?>
				<input class="blue-button" type="file" name="userfile" size="20" />
				<input class="blue-button" type="submit" value="Upload" />
				</form>
	<br><br>
	<br><br>
	</div>			
	</div>	
