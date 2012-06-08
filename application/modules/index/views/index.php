<style type="text/css">
	html, body { height: 100%; }
	html {overflow: hidden;}
</style>
 <body id="main">
 	<?php $this->load->view("menu.php");?>
    <div class="full_container">
		<div class="search">
			<div class="tabbable tabs-below">
				  <div class="tab-content">
						<input type="text" id="search" class="input-xlarge" placeholder="Digite um local"></input>
						<a href="#" id="search_button" class="btn btn-primary">Pesquisar</a>
						<a href="#" class="white" id="more_options">Mais Opções</a>
						<div class="options hide">
							<h3>O que é mais importante para você?</h3>
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
							<a href="#" class="btn hide" id="save_params">Salvar Preferências</a>
						</div>						
				  </div>				  
				  <ul class="nav nav-tabs" id="type">
						<li class="active"><a href="#" data-toggle="tab">Aluguel</a></li>
						<li><a href="#" data-toggle="tab">Compra</a></li>						
				  </ul>
			</div>				
		  </div>
   		  <a id="show_icon" href="#" class="">Ver Lista</a>
		  <div class="left_menu">
				<div class="filter">
						<span id="qty_realestate">Nenhum imóvel</span> em 
							<select id="city_select">
								<option value="sc/florianopolis">Florianópolis,SC</option>
								<option value="sp/sao_paulo">São Paulo,SP</option>
								<option value="pr/curitiba">Curitiba,PR</option>
								<option value="rs/porto_alegre">Porto Alegre,RS</option>
								<option value="sc/joinville">Joinville,SC</option>
								<option value="sc/sao_jose">São José,SC</option>
								<option value="df/brasilia">Brasília,DF</option>
							</select>
							<span id="district_wrapper" style="visibility: visible; ">
								<span>próximo a <span id="district">Centro</span>
							</span>
						</span>
						<a href="#" id="hide_left">
							<img src="<?=base_url();?>img/left_arrow.png" title="Ocultar Lista"></img>
						</a>
				</div>
			<div id="search_results"></div>		
		  </div>		  
		  <a href="#" class="next btn btn-primary btn-large">»</a>
		  <a href="#" class="previous btn btn-primary btn-large">«</a>
		 <div id="map_canvas"></div>
    </div> 	
<!-- modals -->
<!-- template -->
<div class="row hide template">
	<div class="span5 real_estate">		
		<ul class="thumbnails">
			<li class="span2">
				<a href="#" class="thumbnail">
					<img src="http://placehold.it/160x120" alt="">
				</a>
			</li>
			<li class="span3">
				<h4>Aluguel de Apartamento no bairro Carvoeira em Florianópolis </h4>
				<span class="price">R$ 400,00</span>
				<div class="alert alert-info index_div">
					<div class="classification">
						<div class="cover"></div>
						<div class="star" style="width: 91%;"></div>
						<span class="index">91</span>
					</div>
					<span class="indexName">Excelente</span>
				</div>
			</li>
		</ul>
	</div>
  </div>
<!-- modals -->
<!-- modal_login -->
<?php $this->load->view("login")?>;
<!-- modal_profile -->
<?php $this->load->view("profile")?>;
<!-- modal_register -->
<?php $this->load->view("register")?>;
<!-- modal_confirm -->
<?php 
if($this->session->flashdata("notconfirmed"))
	$this->load->view("notconfirmed");
else if($this->session->flashdata("confirmed"))
	$this->load->view("confirmed");
?>
<!-- scripts -->
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" type="text/javascript"></script>	
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places"></script>
<script src="<?=base_url();?>js/bootstrap.min.js"></script>  
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
	$('#type a').click(function (e) {
		  e.preventDefault();
		  if($(this).text()=="Aluguel") filter.type ="rent";  
		  else filter.type ="buy";
		  addRealEstate(searchMarker.getPosition(),searchMarker.radius);
	});
</script>
<script type="text/javascript" src="<?=base_url();?>js/index/index.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/jquery.sortElements.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/jquery.scrollTo-min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/maps.js?<?=RELEASE_KEY;?>"></script>
<script type="text/javascript" src="<?=base_url();?>js/googlemaps.widgets.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/main.js?<?=RELEASE_KEY;?>"></script>
<script type="text/javascript" src="<?=base_url();?>js/analytics.js?<?=RELEASE_KEY;?>"></script>