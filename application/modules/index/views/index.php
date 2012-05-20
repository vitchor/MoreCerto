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
							<a href="#" class="blue-button" style="display:none" id="save_params">Salvar Preferências</a>
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
	
<div id="create_account" class="dialog_modal" title="Cadastro no MoreCerto">
	<h2>Quer salvar suas preferências e imóveis favoritos?</h2>
	<p>Então você precisa ter uma conta no MoreCerto!</p>
	<p>Digite seu email abaixo:</p>
	<input type="text" name="email" id="email_input" class="email_input"></input>
	<a href="#" id="want_account" class="blue-button">Quero ter uma conta do MoreCerto</a>
</div>
<div id="alert_account" class="dialog_modal" title="Cadastro no MoreCerto">
	<h2>Muito obrigado pelo seu interesse!</h2>
	<p>Agora estamos testando o sistema apenas com alguns usuários, mas assim que tivermos alguma conta disponível entraremos em contato.</p>
	<a href="#" id="alert_account_close" class="blue-button">Continuar usando o MoreCerto</a>
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
var originAccountIntention=null;
$( "#create_account" ).dialog({modal:true,width:350,height:270,autoOpen:false});
$( "#alert_account" ).dialog({modal:true,width:400,height:230,autoOpen:false});
$( "#save_params").click(function(e){
	e.preventDefault();
	originAccountIntention="Params";
	trackIntention('Params');
	$( "#create_account" ).dialog("open");				
});
$( "#alert_account_close").click(function(e){
	e.preventDefault();
	$( "#alert_account" ).dialog("close");				
}); 
</script>
<script type="text/javascript" src="<?=base_url();?>js/jquery.sortElements.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/jquery.scrollTo-min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/maps.js?d68f7889a7d71"></script>
<script type="text/javascript" src="<?=base_url();?>js/googlemaps.widgets.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/main.js?d68f5513f49144c07357a7d733"></script>
<script type="text/javascript" src="<?=base_url();?>js/analytics.js?t68d39913f901h3ddf49a7d739"></script>