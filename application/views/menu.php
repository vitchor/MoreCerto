<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#"><img src="<?=base_url();?>img/logo.png"></img></a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="<?=base_url();?>">Home</a></li>
              <li><a href="#about" data-toggle="modal" >Como Funciona</a></li>
              <li><a href="#contact" data-toggle="modal">Contato</a></li>
			  <li><a href="#contact" data-toggle="modal">Anuncie</a></li>
			  <li class="like_button">
			  	<fb:like href="http://www.morecerto.com.br" send="false" layout="button_count" width="60" show_faces="false" action="like" font="" class=" fb_edge_widget_with_comment fb_iframe_widget"></fb:like>
			  </li>			  
            </ul>
            <ul class="nav">
			  <li><a href="http://www.sinapsedainovacao.com.br" target="_blank">Ganhador do Prêmio Sinapse de Inovação 2012</a></li>
			</ul>
			 <ul class="nav pull-right">
			 <?php if(loggedIn()):?>
			 <li><a href="<?=base_url()?>users/logout">Logout</a></li>
			 <?php else:?>
			 <li><a href="#login" data-toggle="modal">Login</a></li>
			  <li><a href="#register" data-toggle="modal">Cadastro</a></li>
			 <?php endif;?>			  
			</ul>
          </div>
        </div>
      </div>
    </div>