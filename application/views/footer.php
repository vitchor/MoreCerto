<div class="modal hide" id="about">
  <div class="modal-header">
    <button class="close" data-dismiss="modal">×</button>
    <h3>Como funciona o MoreCerto?</h3>
  </div>
  <div class="modal-body">
  	<p><strong>O MoreCerto ajuda você a encontrar o melhor lugar para morar!</strong></p>
    <p>Cada imóvel tem o seu índice <strong>MoreCerto®</strong>,  que é calculado de acordo com a proximidade de determinados serviços, como: Supermercados, Restaurantes, Bares, Bancos, Postos de Gasolina, Hospitais e Farmácias.</p>
	<p>Você pode personalizar a importância destes serviços através do menu "Mais Opções" e o índice é calculado automaticamente.</p>
	<p>O objetivo do índice é ajudar você a encontrar o ímovel ideal para alugar ou comprar.</p>
	<p>Comece a usar agora mesmo e encontre o melhor lugar para morar!</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn btn-primary" data-dismiss="modal">Começar a Usar</a>
  </div>
</div>

<div class="modal hide" id="contact">
  <div class="modal-header">
    <button class="close" data-dismiss="modal">×</button>
    <h3>Entre em Contato</h3>
  </div>
  <div class="modal-body">
		<p>Quer anunciar no MoreCerto ou dar alguma sugestão?</p>
		<p>Envie uma mensagem para <a href="mailto:contato@morecerto.com.br">contato@morecerto.com.br</a>.</p>
		<p>Estamos esperando pelo contato.</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn btn-primary" data-dismiss="modal">Fechar</a>
  </div>
</div>

<?php if($this->session->flashdata("error_realestate")):?>
<div class="modal" id="error_realestate">
  <div class="modal-header">
    <button class="close" data-dismiss="modal">×</button>
    <h3>Imóvel não encontrado</h3>
  </div>
  <div class="modal-body">
  	<p>Este imóvel não foi encontrado nos nossos registros ou o usuário não cadastrou o endereço.</p>
	<p>Mas aproveite e encontre diversos outros imóveis no MoreCerto!</p>
	<p>Se você é o anunciante deste imóvel e gostaria de cadastrá-lo no MoreCerto entre em contato.</p>
	<a href="#" id="error_realestate_close" class="blue-button">Começar a Usar</a>
	<a class="blue-button" href="mailto:contato@morecerto.com.br">Entre em Contato</a>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn">Close</a>
    <a href="#" class="btn btn-primary">Save changes</a>
  </div>
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
		<?php if(isset($howitworks)):?>
			$('#about').modal('show');
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