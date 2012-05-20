<style type="text/css">html {overflow:hidden;}</style>

<body id="main">
<div class="">
	<div class="header">
		<div class="container">
			<div class="search">
				<div class="logo">
					<h1>
					<a href="<?=base_url();?>#"><img src="<?=base_url();?>images/logo.png"/></a>
					</h1>		
					<div class="like_div">
						<fb:like href="http://www.morecerto.com.br" send="false" layout="box_count" width="60" show_faces="false" action="like" font="" class=" fb_edge_widget_with_comment fb_iframe_widget"></fb:like>
					</div>		
				</div>
				<div class="search_wrapper">
					<input type="text" id="search"></input>
					<div style="display:inline-block;width:240px;">
						<button id="search_button" class="blue-button" type="button">Pesquisar</button>
						<a href="javascript:showOptions()" style="width:130px;display:inline-block;" id="more_options" class="simple-link white">Mais Opc&otilde;es</a>
					</div>		
					<div class="options hidden" >
					<h3>O que &eacute; mais importante para você?</h3>
						<table>
							<tbody>
								<tr>
									<td class="name">
										Bares
									</td>
									<td>
										<div class="slider" id="bar" ></div>
									</td>
									<td>
										<a class="remove" href=""></a>
									</td>
									<td class="name">
										Restaurantes
									</td>
									<td>
										<div class="slider" id="restaurant" ></div>
									</td>
									<td>
										<a class="remove" href=""></a>
									</td>
								</tr>	
								<tr>
									<td class="name">
										Banco
									</td>
									<td>
										<div class="slider" id="bank"></div>
									</td>
									<td>
										<a class="remove" href=""></a>
									</td>
									<td class="name">
										Supermercados
									</td>
									<td>
										<div class="slider" id="market" ></div>
									</td>
									<td>
										<a class="remove" href=""></a>
									</td>
								</tr>	
								<tr>
									<td class="name">
										Hospitais e Farm&aacute;cias
									</td>
									<td>
										<div class="slider" id="health" ></div>
									</td>
									<td>
										<a class="remove" href=""></a>
									</td>
									<td class="name">
										Postos de Gasolina
									</td>
									<td>
										<div class="slider" id="gas_station"></div>
									</td>
									<td>
										<a class="remove" href=""></a>
									</td>
								</tr>
								<tr>
									<td class="name">
										Lojas
									</td>
									<td>
										<div class="slider" id="store" ></div>
									</td>
									<td>
										<a class="remove" href=""></a>
									</td>
									<td class="name">
										Preço
									</td>
									<td>
										<div class="slider" id="price_avaliation" ></div>
									</td>
									<td>
										<a class="remove" href=""></a>
									</td>									
								</tr>									
							</tbody>
						</table>
						<div class="end">							
							<div class="checkboxes blue-button" style="display:none"</div>
								Exibir
								<input type="checkbox" checked="true" id="real_estate_cb">Im&oacute;veis</input>
								<input type="checkbox" id="services_cb">Servi&ccedil;os</input>
							</div>							 					
						</div>			
					</div>					
				</div>
				
			</div>			
		</div>	
	</div>
	<div class="main">
		<div class="top-border"></div>		
		<div class="middle-border">
				<div class="hidden">
					<div id="search_item_template" class="search_item" >
						<span class="name"></span>
						<span class="index"></span>					
						<a class="view icon-button hidden" href="#"></a>
						<a class="remove icon-button hidden" href="#"></a>
					</div>
				</div>
			</div>
			<div class="map">
				<div class="left-menu" >	
					<div class="menu_header">
						<span id="qty_realestate">Nenhum imóvel</span> em 
							<select id="city_select">
								<option value="sc/florianopolis">Florianópolis,SC</option>
								<option value="sp/sao_paulo">São Paulo,SP</option>
								<option value="pr/curitiba">Curitiba,PR</option>
								<option value="sc/joinville">Joinville,SC</option>
								<option value="sc/sao_jose">São José,SC</option>
								<option value="df/brasilia">Brasília,DF</option>
							</select>
							<span id="district_wrapper" style="visibility:hidden">
								<span>próximo a <span id="district"></span>
							</span>
					</div>
					<div class="search_results"></div>
				</div>
				<div class="next"><a href="#" class="blue-button">Pr&oacute;ximo</a></div>
				<div class="previous"><a href="#" class="blue-button">Anterior</a></div>				
			   <div id="map_canvas" style="height:510px;"></div>
			</div>	
	</div>	

<script type="text/javascript">var base_url = "<?=base_url();?>";</script>
<script type="text/javascript">
<?php if(isset($realestate)):?>
var defaultMarkerGeocode = new Array(<?= $realestate->lat?>,<?= $realestate->lng?>);
var idRealEstate= "<?=$realestate->idrealestates;?>";
<?php else:?>
var defaultMarkerGeocode = null;
var idRealEstate= null;
<?php endif;?>
</script>
<script type="text/javascript" src="<?=base_url();?>js/jquery.sortElements.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/jquery.scrollTo-min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/maps.js?d68f39987h3ddf44c07357a7d71"></script>
<script type="text/javascript" src="<?=base_url();?>js/googlemaps.widgets.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/main.js?d68f39913f9944144c07357a7d733"></script>
<script type="text/javascript" src="<?=base_url();?>js/analytics.js?t9968f39913f901h3ddf49a7d739"></script>