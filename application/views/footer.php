	<div class="footer">
		<div class="container">
			<span>
				<a class="simple-link white" href="<?=base_url();?>">Projeto Ganhador do Prêmio Sinapse de Inovação 2012</a>
			</span>
			<ul>
				<li><a class="simple-link white" href="<?=base_url();?>">Home</a></li>
				<li><a class="simple-link white" href="<?=base_url()?>anuncie">Anuncie</a></li>
				<li><a id="howitworks_button" class="simple-link white" href="#">Como Funciona</a></li>
				<li><a class="simple-link white" href="mailto:contato@morecerto.com.br">Entre em Contato</a></li>
				<li><a class="simple-link white" href="<?=base_url()?>blog">Blog</a></li>
			</ul>			
		</div>		
	</div>
</div>

<div id="howitworks" class="dialog_modal" title="Como funciona o MoreCerto?">
	<h2>O MoreCerto ajuda você a encontrar o melhor lugar para morar!</h2>
	<p>Cada imóvel tem o seu índice <strong>MoreCerto®</strong>,  que é calculado de acordo com a proximidade de determinados serviços, como: Supermercados, Restaurantes, Bares, Bancos, Postos de Gasolina, Hospitais e Farmácias.</p>
	<p>Você pode personalizar a importância destes serviços através do menu "Mais Opções" e o índice é calculado automaticamente.</p>
	<p>O objetivo do índice é ajudar você a encontrar o ímovel ideal para alugar ou comprar.</p>
	<p>Comece a usar agora mesmo e encontre o melhor lugar para morar!</p>
	<a href="#" id="howitworks_close" class="blue-button">Começar a Usar</a>
</div>
<?php if($this->session->flashdata("error_realestate")):?>
<div id="error_realestate" class="dialog_modal" title="Imóvel não encontrado">
	<p>Este imóvel não foi encontrado nos nossos registros ou o usuário não cadastrou o endereço.</p>
	<p>Mas aproveite e encontre diversos outros imóveis no MoreCerto!</p>
	<p>Se você é o anunciante deste imóvel e gostaria de cadastrá-lo no MoreCerto entre em contato.</p>
	<a href="#" id="error_realestate_close" class="blue-button">Começar a Usar</a>
	<a class="blue-button" href="mailto:contato@morecerto.com.br">Entre em Contato</a>
</div>
<?php endif;?>
<script type="text/javascript">
	$(function(){
		<?php if($this->session->flashdata("error_realestate")):?>
			$( "#error_realestate").dialog({modal:true,width:450,height:270});
			$( "#error_realestate_close").click(function(e){
				e.preventDefault();
				$( "#error_realestate" ).dialog("close");				
			}); 	
		<?php endif;?>
		$( "#howitworks" ).dialog({modal:true,width:600,height:420,autoOpen:false});
		$( "#howitworks_button").click(function(e){
				e.preventDefault();
				$( "#howitworks" ).dialog("open");				
		});
		$( "#howitworks_close").click(function(e){
			e.preventDefault();
			$( "#howitworks" ).dialog("close");				
		}); 	 	
			
		<?php if(isset($howitworks)):?>
			$( "#howitworks" ).dialog("open");
		<?php endif;?>
	});
</script>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-31119293-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=290007901084792";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</body>
</html>