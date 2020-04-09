<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>
<?php include_once('../settings/config.php'); ?>
<?php include_once('../settings/database.php'); ?>
<?php
    /*
    	fetch list of districts
    */
	$database = new Database();
	$conn = $database->getConnection();
	if (!$conn) die("error");
	$query="select DESIGNACAO from rnpc_natureza_juridica";
	$result=$conn->query($query);
	$json = array();
	foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row)
 	{
 		 $json[]= array('designacao' => $row['DESIGNACAO']);
 	}
 	$nat_juridica = json_encode($json);

	$query = "
		  SELECT 
		    DISTINCT(distrito) AS nuts, 
		    DESIGNACAO_DISTRITO AS distrito
		  FROM 
		    nuts
		  WHERE
		    DESIGNACAO_DISTRITO IS NOT NULL
	";
	$result=$conn->query($query);
	$json = array();
	foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row)
 	{
    	$json[]= array('distrito' => $row['distrito']);
	}
	$distritos = json_encode($json);
?>



<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/newentity.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="crossorigin=""/>



<div class="container-fluid">    

	<div class="loading" style="display:none;">Loading&#8230;</div>


	<br><!-- bg-primary text-light-->
	<h2 class="text-center p-2 text-primary" style="margin-left: 40%;margin-right: 40%; border-style: solid; border-radius: 15px;">NOVA ENTIDADE			
	</h2>
	<br>

	<div class="row">

	<div class="col-sm-1"></div>
    <div class="col-sm-9 project-tab"> 

		<nav>
			<div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
				<a class="nav-item nav-link active" id="nav-identification-tab" data-toggle="tab" href="#nav-identification" role="tab" aria-controls="nav-identification" aria-selected="true">Identificação</a>
				<a class="nav-item nav-link" id="nav-information-tab" data-toggle="tab" href="#nav-information" role="tab" aria-controls="nav-information" aria-selected="false">Informação</a>	
				<a class="nav-item nav-link" id="nav-location-tab" data-toggle="tab" href="#nav-location" role="tab" aria-controls="nav-location" aria-selected="false" onclick="updateMap();">Localização</a>                			
			</div>
		</nav>

		<div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
			<div class="tab-pane fade show active" id="nav-identification" role="tabpanel" aria-labelledby="nav-identification-tab">
	            <form>
					<div class="row">
						<div class="col-sm-8">
							<div class="form-group">
				    			<label for="name">Nome</label>
				    			<input type="text" class="form-control" id="name" aria-describedby="name" name="name">
				  			</div>
			  			</div>
			  			<div class="col-sm-3">
							<div class="form-group">
								<label for="nif">NIF/NIPC</label>
								<input type="text" class="form-control" id="nif" name="nif">
							</div>
						</div>
			  		</div>

					<div class="row">
						<div class="col-sm-2">
							<div class="form-group">
				    			<label for="telephone">Telefone</label>
				    			<input type="text" class="form-control" id="telephone" aria-describedby="telephone" name="telephone">
				  			</div>
			  			</div>
			  			<div class="col-sm-2">
							<div class="form-group">
								<label for="mobile">Telemóvel</label>
								<input type="text" class="form-control" id="mobile" name="mobile">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label for="fax">Fax</label>
								<input type="text" class="form-control" id="fax" name="fax">
							</div>
						</div>
						<div class="col-sm-5">
							<div class="form-group">
				    			<label for="email">Email</label>
								<input type="email" class="form-control" id="email" name="email">
				  			</div>
			  			</div>
			  		</div>



			  		<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
				    			<label for="site">Site</label>
				    			<input type="text" class="form-control" id="site" aria-describedby="site" name="site">
				  			</div>
			  			</div>
			  		</div>
				</form>

			</div>

			<div class="tab-pane fade show" id="nav-information" role="tabpanel" aria-labelledby="nav-information-tab">

			<form>
				<div class="row">
					<div class="col-sm-5">
						<div class="form-group">
				    		<label for="activity">Tipo de Actividade</label>
				    		<input type="text" class="form-control" id="activity" aria-describedby="activity" name="activity">
				  		</div>
			  		</div>
			  		<div class="col-sm-4">
						<div class="form-group">
							<label for="juridica">Natureza Jurídica</label>
							<select class="form-control" id="juridica" name="juridica">
								<option value=""></option>
							</select>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label for="init_act">Início de actividade</label>
							<input type="date" class="form-control" id="init_act" aria-describedby="init_act" name="init_act">
						</div>
					</div>				
			  	</div>

			  	<div class="row">
					<div class="col-sm-2">
						<div class="form-group">
				    		<label for="cae">CAE</label>
				    		<input type="text" class="form-control" id="cae" aria-describedby="cae" name="cae">
				  		</div>
			  		</div>
			  		<div class="col-sm-5">
						<div class="form-group">
				    		<label for="cae_desc">CAE Descrição</label>
				    		<input type="text" class="form-control" id="cae_desc" aria-describedby="cae_desc" name="cae_desc">
				  		</div>
			  		</div>
			  		<div class="col-sm-2">
			  			<div class="form-group">
				  			<label for="is_pai">Tipo</label>
  							<select class="form-control" id="is_pai" name="is_pai">
								<option value="F" selected>Estabelecimento</option>
								<option value="P">Sede</option>
  							</select>
				  		</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
				  			<label for="state">Estado</label>
  							<select class="form-control" id="state" name="state">
								<option value="-">-</option>
								<option value="actividade" selected>EM ACTIVIDADE</option>
								<option value="revitalizacao">EM REVITALIZAÇÃO</option>
								<option value="liquidacao">EM LIQUIDAÇÃO</option>
								<option value="encerrada">ENCERRADA</option>
  							</select>
				  		</div>				  		
			  		</div>
			  	</div>

				<div class="row">					
			  		<div class="col-sm-4">
						<div class="form-group">
							<label for="schedule">Horário de funcionamento</label>
							 <textarea class="form-control" rows="8" name="schedule" id="schedule">
							 </textarea>
						</div>
					</div>
			  	</div>			

			</form>

			</div>

			<div class="tab-pane fade show" id="nav-location" role="tabpanel" aria-labelledby="nav-location-tab">

				<form>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
				    			<label for="address">Morada</label>
				    			<input type="text" class="form-control" id="address" aria-describedby="address" name="address">
				  			</div>
			  			</div>
			  		</div>

			  		<div class="row">
						<div class="col-sm-2">
							<div class="form-group">
				    			<label for="country">País</label>
				    			<input type="text" class="form-control" id="country" aria-describedby="country" name="country" value="Portugal">
				  			</div>
			  			</div>
						<div class="col-sm-3">
							<div class="form-group">
				    			<label for="city">Localidade</label>
				    			<input type="text" class="form-control" id="city" aria-describedby="city" name="city">
				  			</div>
			  			</div>
			  			<div class="col-sm-2">
							<div class="form-group">
				    			<label for="cp">Código Postal</label>
				    			<input type="text" class="form-control" id="cp" aria-describedby="cp" placeholder="0000-000" name="cp">
				  			</div>
			  			</div>
			  		</div>

					<div class="row">
			  			<div class="col-sm-3">
							<div class="form-group">
				    			<label for="distrito">Distrito</label>
								<select class="form-control" id="distrito" name="distrito">
								<option value="-" selected>-</option>
								</select>
				    			<!--
				    			<input type="text" class="form-control" id="distrito" aria-describedby="distrito" name="distrito">
				    			-->
				  			</div>
			  			</div>
			  			<div class="col-sm-3">
							<div class="form-group">
				    			<label for="concelho">Concelho</label>
				    			<select class="form-control" id="concelho" name="concelho">
								<option value="-" selected>-</option>
								</select>
				    			<!--
				    			<input type="text" class="form-control" id="concelho" aria-describedby="concelho" name="concelho">
				    			-->
				  			</div>
			  			</div>
			  			<div class="col-sm-6">
							<div class="form-group">
				    			<label for="freguesia">Freguesia</label>
				    			<select class="form-control" id="freguesia" name="freguesia">
								<option value="-" selected>-</option>
								</select>
				    			<!--
				    			<input type="text" class="form-control" id="freguesia" aria-describedby="freguesia" name="freguesia">
				    			-->
				  			</div>
			  			</div>
			  		</div>


			  		<div class="row">
						<div class="col-sm-4"></div>
			  			<div class="col-sm-2">
							<div class="form-group">
				    			<label for="latitude">Latitude</label>
				    			<input type="text" class="form-control" id="latitude" aria-describedby="latitude" name="latitude">
				  			</div>
			  			</div>
			  			<div class="col-sm-2">
							<div class="form-group">
				    			<label for="longitude">Longitude</label>
				    			<input type="text" class="form-control" id="longitude" aria-describedby="longitude" name="longitude">
				  			</div>
			  			</div>
			  		</div>

					<div class="row">
						<div class="col-sm-1"></div>						
			  			<div class="col-sm-10">			  				
							<div class="form-group">
								<h6 class="text-center"><strong>(Insira as coordenadas ou clique directamente no mapa)</strong></h6>
				    			<div id="mapid" style="height: 400px;"></div>
				  			</div>				  			
			  			</div>
			  		</div>

			  	</form>

			  	
			</div>		

		</div>

		

		<br>
		
	    <div class="row" id="warnings" style="display:none;">
	      	<div class="col-sm-1"></div>
	        <div class="col-sm-10 text-justify text-dark" style="background-color: rgba(240, 190, 140, 0.5); border-style: solid; border-radius: 25px; border-color:rgba(255, 99, 71, 1);  font-weight: bold;">
	            Avisos:
	            <ul class="warnings-list">
	            </ul>
	        </div>
		</div>


    </div> 

    <div class="col-sm-2">

    	<br><br>

    	<div class="card" style="background-color: #EEEEEE;">
	    	<div class="card-body">
		    	<div class="btn-group-vertical btn-block nuts_grp">
		    		<button type="button" class="btn btn-danger btn-lg fas" id="btn_scraping" style = " margin-top: 5px; border-color: black; border-width: 2px;" disabled>Scraping</button>
		    		<button type="button" class="btn btn-danger btn-lg fas" id="btn_google" style = " margin-top: 5px; border-color: black; border-width: 2px;" disabled>Google</button>
		    		<button type="button" class="btn btn-danger btn-lg fas" id="btn_geocode" style = " margin-top: 5px; border-color: black; border-width: 2px;" disabled>Geocode</button>
		    		<button type="button" class="btn btn-danger btn-lg fas" id="btn_nifservice" style = " margin-top: 5px; border-color: black; border-width: 2px;" disabled>NIF Info</button>
					<button type="button" class="btn btn-primary btn-lg fas" id="btn_dup" style = " margin-top: 5px; border-color: black; border-width: 2px;" disabled>Duplicado?</button>
				</div>
			</div>
		</div>

		<br><br>

		<button type="button" class="btn btn-warning btn-lg fas btn-block" id="btn_geo" style = " margin-top: 5px; border-color: black; border-width: 2px;" data-toggle="modal" data-target="#modalOptions"><i class="fa fa-cog"></i> Configurações</button>

    </div> 

	</div>

    <!-- 
    ************************************************
    ************************************************
    MODALS
    ************************************************
    ************************************************
    -->

	<!-- HELP MODAL -->
    <div class="modal" id="modal-help">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
        
          <div class="modal-header">
            <h4 class="modal-title">Ajuda</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          
          <div class="modal-body modal-body-help text-justify">
          		
				<p>
                      Esta página serve para testar vários módulos, cujo objectivo primário é a obtenção automática de diversa informação relacionada com uma "entidade nova introduzida": <i>geocodificação</i>, <i>google place</i> e <i>scraping</i>.

                      Além destes, também é possível verificar se a entidade introduzida, já se encontra na base de dados, demonstrando deste modo, a simplicidade do processo em evitar a introdução de entidades já existentes.
                  </p>
                  <p>
                      Decidiu-se pela seleção manual dos diferentes módulos, para que o utilizador possa vê-los em acção de modo independente.

                      Num sistema real, estes serão aplicados automaticamente, por exemplo, após a introdução do nome, o sistema iria tentar obter o máximo de informação da internet sobre essa entidade, tal como, a morada, coordenadas, horário de funcionamento, nif, contactos entre outros.
                  </p>
                  <p>
                      Os diferentes sistemas estarão disponíveis assim que o minimo de campos estiver preenchido.

                      Por exemplo, assim que os campos do <i>nome</i> e do <i>NIF</i> tiverem preenchidos, o botão <i><mark style="background-color: #FF0000;border-radius: 5px; padding: 2px;color:black;">Scraping</mark></i> será activado, possibilitando o utilizador a sua activação.
                  </p>


                <h3>Geocodificação (Google) + Google Place</h3>
                <p>
                    Após a introdução do <i>nome</i>, o sistema tentará encontrar as coordenadas e a morada completa desse local através do serviço de geocodificação da <i>Google</i>. Também tentará encontrar mais informações, tal como contactos, website e horário da funcionamento, através do serviço <i>Google Place</i>.
                </p>

                <h3>Geocodificação (Vários serviços)</h3>
                <p>
                    Após a introdução de uma nova <i>morada</i> e <i>localidade</i>, o sistema tentará encontrar as coordenadas desse local através de vários serviços de geocodificação, tal como, <i>TomTom</i>, <i>Bing</i> entre outros.
                </p>

                <h3>Scraping</h3>
                <p>
                    Após a introdução do <i>nome</i> e do <i>NIF</i>, o sistema tentará encontrar o máximo de informações relacionadas com essa entidade, tais como, morada, contactos, CAE, estado da empresa, entre outros.
                </p>

                <h3>NIF Info</h3>
                <p>
                    Após a introdução do <i>NIF</i>, o sistema tentará encontrar o máximo de informações relacionadas com esse NIF, tais como, morada, contactos, CAE, estado da empresa, entre outros, disponiveis em <a target="_blank" href="https://www.nif.pt">www.nif.pt</a>.
                    Note, que existe um limite finito de chamadas a este serviço: 1000 / mês, 100 / dia, 10 / hora, 1 / minuto.
                </p>

                <h3>Duplicação</h3>
                <p>
                    O sistema permite ao utilizador verificar se já existe entidade semelhante à introduzida. Para tal, basta clicar no botão <i>Duplicado?</i>, que será ativado, assim que o mínimo de campos exigidos se encontrem preenchidos, nomeadamente o <i>nome</i> e/ou <i>NIF</i> e/ou <i>coordenadas (lat, lon)</i>. Outros campos tais como <i>sede/estabelecimento</i> e <i>morada</i>, embora não requeridos, aumentam a precisão do sistema.<br>

                    No caso de existirem entidades semelhantes às introduzida, será apresentada uma lista com os seus nomes, NIFs e IDs.
                </p>
                <p>
                	Se o <i>Auto-check NIF</i> for selecionado, então cada vez que um NIF for introduzido/alterado, o sistema irá verificar se já existe alguma entidade com esse NIF, apresentando uma lista com todas as entidades com esse NIF.
                </p>
                <p>
                    Pode personalizar a detecção através das opções disponibilizadas nas <i><mark style="background-color: #FFCC66;border-radius: 5px; padding: 2px;color:black;">Configurações</mark></i>.
                </p>
                <p>
                	Por exemplo: verificar se uma Worten especifica já se encontra na base de dados.
                	Processo:
                </p>
                <ul>
                	<li>Escrever o nome e/ou Nif;</li>
                	<li>Se verificar agora, irá aparecer uma lista de wortens, o que não é muito util, necessitando uma maior filtragem;</li>
                	<li>Inserir morada seguida de geocodificação ou inserir as coordenadas ou clicar sobre o mapa a localização;</li>
                	<li>Se verificar agora, a lista anterior deverá estar reduzida (implicando que já existe na base de dados) e/ou não existente (entidade não existente na base de dados).</li>
                </ul>


          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
          </div>
          
        </div>
      </div>
    </div>
    <!-- END OF HELP MODAL -->



	<!-- OPTIONS MODAL -->
   	<div class="modal" id="modalOptions">
    	<div class="modal-dialog modal-lg">
      		<div class="modal-content">
      
		        <!-- Modal Header -->
		        <div class="modal-header">
		          <h4 class="modal-title">Opções</h4>
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		        </div>
        
		        <!-- Modal body -->
		        <div class="modal-body modal-body-modalFilter">
		          
					<p class="h3">Detecção de Duplicado</small></p>
		        	<br>
					<form id="input_form_entidades">						
						<div class="form-check form-check-inline">
							<label class="form-check-label" for="dup_ratio">Racio de similaridade:
								<select class="form-control" id="dup_ratio">
									<option value="100">100</option>
								    <option value="95">95</option>
								    <option value="90">90</option>
								    <option value="85" selected>85</option>
								    <option value="80">80</option>
								    <option value="75">75</option>							   
								 </select>
							 </label>						
						</div>
						<div class="form-check form-check-inline">
							<label class="form-check-label" for="dup_radius">Raio (m):
								<select class="form-control" id="dup_radius">
									<option value="10">10</option>
								    <option value="50">50</option>
								    <option value="100">100</option>
								    <option value="250" selected>250</option>
								    <option value="500">500</option>
								    <option value="1000">1000</option>
								    <option value="5000">5000</option>
								 </select>
							 </label>						
						</div>
						<div class="form-check form-check-inline">
							<label class="form-check-label" for="dup_chars">Nº caracters a usar na procura:
								<select class="form-control" id="dup_chars">
									<option value="0">0</option>
								    <option value="1">1</option>
								    <option value="2">2</option>
								    <option value="3">3</option>
								    <option value="4">4</option>
								    <option value="5">5</option>
								    <option value="6">6</option>
								    <option value="7">7</option>
								    <option value="8">8</option>
								    <option value="9">9</option>
								    <option value="10">10</option>
								    <option value="11">11</option>
								    <option value="12">12</option>
								    <option value="13">13</option>
								    <option value="14">14</option>
								    <option value="15" selected>15</option>
								 </select>
							 </label>						
						</div>
						<div class="form-check form-check-inline">
							<label class="form-check-label" for="dup_max_results">Nº máximo de resultados:
								<select class="form-control" id="dup_max_results">
									<option value="0">Todos</option>
									<option value="1">1</option>
								    <option value="5" selected>5</option>
								    <option value="10">10</option>
								    <option value="20">20</option>
								 </select>
							 </label>						
						</div>
						<br><br>

						<div class="form-check form-check-inline">
							<input type="checkbox" name="dup_auto" class="form-check-input" id="dup_auto"  disabled>
							<label class="form-check-label" for="dup_auto"> Auto-check</label>
						</div>

						<div class="form-check form-check-inline">
							<input type="checkbox" name="dup_nif_auto" class="form-check-input" id="dup_nif_auto">
							<label class="form-check-label" for="dup_nif_auto"> Auto-check NIF</label>
						</div>
				
					</form>

					<br>
					<hr>

		        	<p class="h3">Geocodificação</small></p>
		        	<br>
					<form id="input_form_entidades">
						<div class="form-check form-check-inline">
							<input type="checkbox" name="geocod_google" class="form-check-input" id="geocod_google" checked="checked" disabled>
							<label class="form-check-label" for="geocod_google"> Google</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" name="geocod_tomtom" class="form-check-input" id="geocod_tomtom" checked="checked" disabled>
							<label class="form-check-label" for="geocod_tomtom"> TomTom</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" name="geocod_bing" class="form-check-input" id="geocod_bing" checked="checked" disabled>
							<label class="form-check-label" for="geocod_bing"> Bing</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" name="geocod_here" class="form-check-input" id="geocod_here" checked="checked" disabled>
							<label class="form-check-label" for="geocod_here"> Here</label>
						</div>
						<br><br>
						<div class="form-check form-check-inline">
							<input type="checkbox" name="geocod_auto" class="form-check-input" id="geocod_auto" disabled>
							<label class="form-check-label" for="geocod_auto"> Auto-check</label>
						</div>
				
					</form>

					<br>
					<hr>		

		        	<p class="h3">Scraping</small></p>
		        	<br>
					<form id="input_form_entidades">
						<div class="form-check form-check-inline">
							<input type="checkbox" name="scraping_racius" class="form-check-input" id="scraping_racius" checked="checked" disabled>
							<label class="form-check-label" for="scraping_racius"> racius</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" name="scraping_codigopostal" class="form-check-input" id="scraping_codigopostal" checked="checked" disabled>
							<label class="form-check-label" for="scraping_codigopostal"> codigopostal</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" name="scraping_portugalio" class="form-check-input" id="scraping_portugalio" checked="checked" disabled>
							<label class="form-check-label" for="scraping_portugalio"> portugalio</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" name="scraping_gescontact" class="form-check-input" id="scraping_gescontact" checked="checked" disabled>
							<label class="form-check-label" for="scraping_gescontact"> gescontact</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" name="scraping_einforma" class="form-check-input" id="scraping_einforma" checked="checked" disabled>
							<label class="form-check-label" for="scraping_einforma"> einforma</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" name="scraping_guiaempresas" class="form-check-input" id="scraping_guiaempresas" checked="checked" disabled>
							<label class="form-check-label" for="scraping_guiaempresas"> guiaempresas</label>
						</div>
						<br><br>
						<div class="form-check form-check-inline">
							<input type="checkbox" name="scraping_auto" class="form-check-input" id="scraping_auto" disabled>
							<label class="form-check-label" for="scraping_auto"> Auto-check</label>
						</div>
				
					</form>
					
					<br>
					<hr>

					<p class="h3">Google Place</small></p>
		        	<br>
					<form id="input_form_entidades">						
						<div class="form-check form-check-inline">
							<input type="checkbox" name="google_auto" class="form-check-input" id="google_auto" disabled>
							<label class="form-check-label" for="google_auto"> Auto-check</label>
						</div>
				
					</form>


					<br>
					<hr>

		        </div>
        
		        <!-- Modal footer -->
		        <div class="modal-footer">
		          <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
		        </div>
        
      		</div>
    	</div>
  	</div>
  	<!-- END OF OPTIONS MODAL -->



	<!-- DUPLICATION MODAL -->
    <div class="modal" id="modal-dups">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
        
          <div class="modal-header">
            <h4 class="modal-title modal-dups-title">Entidade já existente?</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          
          <div class="modal-body modal-body-dup text-justify">
          		

          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
          </div>
          
        </div>
      </div>
    </div>
    <!-- END OF DUPLICATION MODAL -->


 <!-- TEMPORARY MESSAGE MODAL -->
   <div class="modal" id="modal_temporary">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title modal-title-message">Mensagem</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-content-message">
               <div class="modal-body modal-body-message text-justify">
                  <h4>Atenção! 
                  	De momento os seguintes serviços encontram-se inacessiveis: google, scraping, geocodificação e NIF data.
                  </h4>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
            </div>
         </div>
      </div>
   </div>
   <!-- TEMPORARY MESSAGE MODAL -->




</div>

<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
   integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
   crossorigin=""></script>

<script>

	// ************************************************
	// GLOBAL VARS AND CONST
	// ************************************************
	var nat_juridica = <?php echo $nat_juridica ?>;
	var distritos = <?php echo $distritos ?>;
	var warnings_errors = [];

	// when get data contains d/c/f then set these -> call get
	// concelho -> set concelho -> get freg -> set freg
	var concelho = null;
	var freguesia = null;

	// ************************************************
	// MAP
	// ************************************************
	var mymap = L.map('mapid').setView([39.5, -8], 6);
	var marker = null;
	//var markers = L.layerGroup([marker1, marker2]).addTo(map);

	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		maxZoom: 21,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox.streets'
	}).addTo(mymap);

	mymap.on('click', onMapClick);

	// ************************************************
	// PAGE STUFF
	// ************************************************
	$(document).ready(function() {
      // populate the sugestion lists
       for (var key in nat_juridica) {
           $("#juridica").append('<option value="' + nat_juridica[key]['designacao'].toLowerCase() + '">' + nat_juridica[key]['designacao'] + '</option>');
       }

       for (var key in distritos) {
           $("#distrito").append('<option value="' + distritos[key]['distrito'].toLowerCase() + '">' + distritos[key]['distrito'] + '</option>');
       }

       $('[data-toggle="tooltip"]').tooltip(); 

   });


	// ************************************************
	// ACTIVATE / DEACTIVATE BUTTONS ACCORDING TO WHICH FIELDS
	// ARE NOT EMTPY -- this check is made as the user types
	// ************************************************

	$('#name').on('focusout', function (e) {
		if ($("#name").val() != '') {
			$("#btn_google").prop("disabled", false);
			$("#btn_dup").prop("disabled", false);
			$("#btn_scraping").prop("disabled", false);
		} else {
			$("#btn_google").prop("disabled", true);
			$("#btn_dup").prop("disabled", true);
			$("#btn_scraping").prop("disabled", true);
		}
		checkState();
   	});

   	$('#name').on("keydown input", function(){
         if ($("#name").val() != '') {
			$("#btn_google").prop("disabled", false);
			$("#btn_dup").prop("disabled", false);
			$("#btn_scraping").prop("disabled", false);
		} else {
			$("#btn_google").prop("disabled", true);
			$("#btn_dup").prop("disabled", true);
			$("#btn_scraping").prop("disabled", true);
		}
		checkState();
   	});

   	$('#nif').on('focusout', function (e) {
		if ($("#nif").val() != '') {
			$("#btn_dup").prop("disabled", false);
			$("#btn_nifservice").prop("disabled", false);

			if ($("#dup_nif_auto").is(':checked')) {
				checkNIF();	
			}			
		} else {
			$("#btn_dup").prop("disabled", true);
			$("#btn_nifservice").prop("disabled", true);
		}
		checkState();		
   	});

   	$('#nif').on("keydown input", function(){
         if ($("#nif").val() != '') {
			$("#btn_dup").prop("disabled", false);
			$("#btn_nifservice").prop("disabled", false);
		} else {
			$("#btn_dup").prop("disabled", true);
			$("#btn_nifservice").prop("disabled", true);
		}
		checkState();
   	});

   	$('#address,#city').on('focusout', function (e) {
		if ($("#address").val() != '' && $("#city").val() != '') {
			$("#btn_geocode").prop("disabled", false);
		} else {
			$("#btn_geocode").prop("disabled", true);
		}
		checkState();
   	});

   	$('#address,#city').on("keydown input", function(){
		if ($("#address").val() != '' && $("#city").val() != '') {
			$("#btn_geocode").prop("disabled", false);
		} else {
			$("#btn_geocode").prop("disabled", true);
		}
		checkState();
   });
	

 	$('#cae').on('focusout', function (e) {
 		var cae_cod = $("#cae").val();
        if (cae_cod.length == 0) return;
	 	
    	getCAEDesignacao(cae_cod);
   	});


 	$('#latitude,#longitude').on('keydown input', function (e) {
 		if ($("#latitude").val() != '' && $("#longitude").val() != '') {
			$("#btn_dup").prop("disabled", false);
		}
		checkState();
   	});

 	$('#latitude,#longitude').on('focusout', function (e) {
 		if ($("#latitude").val() != '' && $("#longitude").val() != '') {
			$("#btn_dup").prop("disabled", false);
		}

        updateMap(false);
        checkState();
   	});


   	// ************************************************
	// ON BUTTONS CLICK
	// ************************************************

   	$("#btn_scraping").click( function() {
   		getScraping();
	});

   	$("#btn_google").click( function() {
   		getGeoGoogle();
	});

	$("#btn_geocode").click( function() {
   		getGeocode();
	});

	$("#btn_dup").click( function() {
   		getIsDuplicado();
	});

	$("#btn_nifservice").click( function() {
   		getNifInfo();
	});

	// ************************************************
	// PAGE UPDATES
	// ************************************************

	// SELECT DISTRITO X => CREATE LIST OF CONCELHOS OF X
	$('#distrito').on('change', function() {
  		if ($('#distrito').val() != '-')
  			getConcelhos();
	});

	// SELECT CONCELHO X => CREATE LIST OF FREGUESIAS OF X
	$('#concelho').on('change', function() {
  		if ($('#concelho').val() != '-')
  			getFreguesias();
	});


	// after an action, for exemple, scraping, some textfields
	// could have being changed, from empty to something =>
	// check the content of the relevant fields and activate the 
	// buttons accordingly
	function checkState() {
		if ($("#name").val() != '') {
			$("#btn_google").prop("disabled", false);
			$("#btn_dup").prop("disabled", false);
			$("#btn_scraping").prop("disabled", false);
		} else {
			$("#btn_google").prop("disabled", true);
		}
		if ($("#address").val() != '' && $("#city").val() != '') {
			$("#btn_geocode").prop("disabled", false);
		} else {
			$("#btn_geocode").prop("disabled", true);
		}
		if ($("#latitude").val() != '' && $("#longitude").val() != '') {
			$("#btn_dup").prop("disabled", false);
		}
		if ($("#nif").val() != '') {
			$("#btn_dup").prop("disabled", false);
			$("#btn_nifservice").prop("disabled", false);
		}
	}



  // ************************************************
  // EXECUTE AND FETCH DATA OF EACH MODULE
  // ************************************************

	function getGeocode(addr, city, country = "Portugal") {
		$(".loading").show();
		var addr = $("#address").val();
    	var city = $("#city").val();    	
    	var country = "Portugal";
    	if ($("#country").val() != '') {
    		country = $("#country").val();
    	}

      	$.ajax({
			    url: "<?php echo PYTHON_SRV_DOOR; ?>geocode",
			   contentType: 'application/json;charset=UTF-8',
			   data: JSON.stringify({'addr':addr, 'city': city, "country":country }, null, '\t'),
			   type: 'POST',
			   success: function(data){
        			setGeocodeData(data);
			   },
			   error: function(data){
			   		errorsCommonPython(data);
			   },
			   timeout: 20000 //in milliseconds
		});

    }

	function getScraping() {
		$(".loading").show();

      	var name = $("#name").val();
      	var nif = $("#nif").val().toString();
      	name += ' Lda';
      	$.ajax({
			    url: "<?php echo PYTHON_SRV_DOOR; ?>scraping",
			   contentType: 'application/json;charset=UTF-8',
			   data: JSON.stringify({'name':name, 'nif': nif }, null, '\t'),
			   type: 'POST',
			   success: function(data){
			    //    console.log('getScraping');
        			setScrapingData(data);
			   },
			   error: function(data){
			   		errorsCommonPython(data);
			   },
			   timeout: 20000 //in milliseconds
		});
    }

    function getGeoGoogle() {
    	$(".loading").show();
    	var addr_name = $("#name").val();
    	if ($("#name").val() == '' && $("#address").val() != '') {
    		addr_name = $("#address").val();
    	} else if ($("#name").val() != '' && $("#address").val() != '') {
    		addr_name += ", " + $("#address").val();
    	}
    	var city = $("#city").val();    	
    	var country = "Portugal";
    	if ($("#country").val() != '') {
    		country = $("#country").val();
    	}

      	$.ajax({
			   url: "<?php echo PYTHON_SRV_DOOR; ?>geogoogle",
			   contentType: 'application/json;charset=UTF-8',
			   data: JSON.stringify({'addr_name' : addr_name, 'city':city, 'country':country}, null, '\t'),
			   type: 'POST',
			   success: function(data){
			      	//console.log('getGeoGoogle');
			      	//console.log(data);
        			setGeoData(data);
			   },
			   error: function(data){
			   		errorsCommonPython(data);
			   },
			   timeout: 20000 //in milliseconds
		});
    }

    function getCAEDesignacao(cae_cod) {
    	$(".loading").show();

 		$.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/getcaedesignacao.php",  
                    method:"POST",
                    data:{'cae_cod' : cae_cod},
                    cache: false,
                    dataType:"json", 
                   success:function(response) {
                        setCAEDesignacao(response);
                   },
                  error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);                    
                    }
        });
    }

 	function getIsDuplicado() {
 		//console.log("is dup");
    	$(".loading").show();
    	var addr = $("#address").val();
    	var name = $("#name").val();
    	var nif = $("#nif").val();    	
    	var lat = $("#latitude").val();
    	var lon = $("#longitude").val();
    	var is_pai = $("#is_pai").val();
    	var data = {
    		'nome': name, 
    		'morada': addr, 
    		'nif': nif,
    		'latitude': lat,
    		'longitude': lon,
    		'is_pai': is_pai,
			'dup_ratio': $('#dup_ratio').val(),
			'dup_radius': $('#dup_radius').val(),			
    		'n_char': $('#dup_chars').val(),
    		'dup_max_results': $('#dup_max_results').val()    		
    	}

    	$(".loading").hide();
    	
      	$.ajax({
			   url: "<?php echo PYTHON_SRV_DOOR; ?>duplicated",
			   contentType: 'application/json;charset=UTF-8',
			   data: JSON.stringify(data, null, '\t'),
			   type: 'POST',
			   success: function(data){
        			console.log(data);
        			setDupData(data);
			   },
			   error: function(data){
			   		errorsCommonPython(data);
			   },
			   timeout: 30000 //in milliseconds
		});
		
    }



 	function getNifInfo() {
    	$(".loading").show();
    	var nif = $("#nif").val();    	
    	var data = {
    		'nif': nif		
    	}
    	
      	$.ajax({
			   url: "<?php echo PYTHON_SRV_DOOR; ?>nifservice",
			   contentType: 'application/json;charset=UTF-8',
			   data: JSON.stringify(data, null, '\t'),
			   type: 'POST',
			   success: function(data){
        			//console.log(data);
        			setNifData(data);
			   },
			   error: function(data){
			   		errorsCommonPython(data);
			   },
			   timeout: 30000 //in milliseconds
		});
		
    }


  // ************************************************
  // FETCH UPDATING DATA
  // ************************************************

    function getConcelhos() {
    	$(".loading").show();

 		$.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/getconcelhos.php",  
                    method:"POST",
                    data:{'distrito' : $("#distrito").val()},
                    cache: false,
                    dataType:"json", 
                   success:function(response) {
                        setConcelhos(response);
                        if (concelho) {
                        	setValue('concelho',concelho.toLowerCase(), false, "Poderá o concelho ser ");
                        	$('#concelho').trigger('change');
                        }
                   },
                  error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);                    
                    }
        });
    }

    function getFreguesias() {
    	$(".loading").show();

 		$.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/getfreguesias.php",  
                    method:"POST",
                    data:{
                    	'distrito' : $("#distrito").val(), 
                    	'concelho' : $("#concelho").val()
                    },
                    cache: false,
                    dataType:"json", 
                   success:function(response) {
                        setFreguesias(response);
                        if (freguesia) {
                        	setValue('freguesia',freguesia.toLowerCase(), false, "Poderá a freguesia ser ");
                        }
                   },
                  error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);                    
                    }
        });
    }



  // ************************************************
  // OTHERS
  // ************************************************

    // check if a particular NIF already exists in the database
    function checkNIF() {
    	$(".loading").show();

 		$.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/getentnifs.php",  
                    method:"POST",
                    data:{
                    	'nif' : $("#nif").val()
                    },
                    cache: false,
                    dataType:"json", 
                   success:function(response) {
                        setDupData(response, false, true);
                   },
                  error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);                    
                    }
        });
    }

 

   	// ************************************************
	// AJAX RESPONSES - fill or correct data
	// ************************************************

	function setDupData(data, parse = true, onlynif = false) {
		if (parse)
			data = JSON.parse(data);

		if (data['status'] == 'ERROR' || data['status'] == 'UNABLE') {
			$(".loading").hide();
    		var msg = "<b>Não foi possivel verificar esta entidade!</b><br><br>";
   			msg += "Possiveis problemas: desconhecido"
    		showMessage(msg);
    		return;
    	}
 		if (data.length == 0) { 
 			$(".loading").hide();
 			if (!onlynif) {
            	var msg = "<b>Não existe nenhuma entidade semelhante à que está a ser introduzida.</b>";
           		showMessage(msg);
           	}
            return;
        }

        var msg = `
        <table class="table" style="font-size:12px !important;">
              <thead class="text-left">
                <tr>
                  <th>      ID    </th>
                  <th>      NOME    </th>
                  <th>      MORADA    </th>
                  <th>      NIF    </th>
                  <th>      IS_PAI    </th>
                  <th>      TIPO    </th>
                </tr>
              </thead>
              <tbody>
        `;
        for(var i=0;i<data.length;i++) {
        	msg += '<tr>';

        	msg += '<td>';
        	msg += data[i]['id'];
        	msg += '</td>';
        	msg += '<td>';
        	msg += data[i]['nome'];
        	msg += '</td>';
        	msg += '<td>';
        	msg += (data[i]['morada'])?data[i]['morada']:'-';
        	msg += '</td>';
        	msg += '<td>';
        	msg += (data[i]['nif'])?data[i]['nif']:'-';
        	msg += '</td>';
        	msg += '<td>';
        	msg += (data[i]['is_pai'])?data[i]['is_pai']:'-';
        	msg += '</td>';
        	msg += '<td>';
        	msg += (data[i]['type'])?data[i]['type']:'-';
        	msg += '</td>';

        	msg += '</tr>';
        }
        msg += '</tbody></table>';

        if (onlynif) {
			$('.modal-dups-title').html('NIF já existente!');	
        } else {
        	$('.modal-dups-title').html('Entidade já existente?');	
        }        
        $('.modal-body-dup').html('');
        $('.modal-body-dup').append(msg);
        $('#modal-dups').modal('show');
        $(".loading").hide();
	}

	function setGeocodeData(data) {
    	var data = JSON.parse(data);
    	if (data['status'] == 'ERROR' || data['status'] == 'UNABLE') {
    		$(".loading").hide();
    		var msg = "<b>Não foi possivel Geocodificar a morada indicada!</b><br>";
    		if (data['error_code'] == 4) {
    			msg += "Não existem serviços disponiveis.<br><br>";
    			msg += "Possiveis problemas:";
    			msg += "<ul>";
    			msg += "<li>credenciais google (API KEY) incorrecta</li>";
    			msg += "<li>limites do serviço atingido</li>";
    			msg += "<ul>";
    		} else
    			msg += "Possiveis problemas: desconhecido"
    		showMessage(msg);
    		return;
    	}
    	//console.log(data);
    	setValue('address',data['formatted_address'], true, "Poderá a morada ser ");
		setValue('latitude',data['latitude'], true, "Poderá a latitude ser ");
		setValue('longitude',data['longitude'], true, "Poderá a longitude ser ");
		setValue('cp',data['postcode'], true, "Poderá o codigo postal ser ");	
		setValue('city',data['localidade'], true, "Poderá a localidade ser ");

		updateMap();
		showWarnings(true);
		$(".loading").hide();
		checkState();
    }

    function setGeoData(data) {
    	var data = JSON.parse(data);
    	if (data['status'] == 'ERROR' || data['status'] == 'UNABLE') {
    		$(".loading").hide();
    		var msg = "<b>Ocorreu um problema durante a operação!</b><br><br>";
    		msg += "Possiveis problemas:";
    		msg += "<ul><li>Nome inválido (entidade não identificada pela Google)</li>";
    		msg += "<li>credenciais google (API KEY) incorrecta</li>";
    		msg += "<li>limites do serviço atingido</li>";
    		msg += "<li>outro erro</li><ul>";
    		showMessage(msg);
    		return;
    	}
    	if (data['periods']) {
    		setValue('schedule',formatSchedule(data['periods']));
    	}
    	setValue('telephone',data['international_phone_number'], true, "Poderá o telefone ser ");
    	setValue('address',data['formatted_address'], true, "Poderá a morada ser ");
		setValue('latitude',data['latitude'], true, "Poderá a latitude ser ");
		setValue('longitude',data['longitude'], true, "Poderá a longitude ser ");
		setValue('site',data['website'], true, "Poderá o website ser ");
		setValue('cp',data['postcode'], true, "Poderá o codigo postal ser ");	
		setValue('city',data['localidade'], true, "Poderá a localidade ser ");
	

		if (data['permanently_closed']) {
			if (data['permanently_closed'] && data['permanently_closed'].toUpperCase() == 'TRUE') {
				setValue('state','ENCERRADA', true, "Poderá o estado actual da entidade ser ");
			} else {
				setValue('state','EM ACTIVIDADE', true, "Poderá o estado actual da entidade ser ");
			}
		}

		updateMap();
		showWarnings(true);
		$(".loading").hide();
		checkState();
    }

    function setScrapingData(data) {
		var data = JSON.parse(data);
		if (data['status'] == 'ERROR' || data['status'] == 'NOT FOUNDED') {
    		$(".loading").hide();
    		var msg = "<b>Ocorreu um problema durante a operação de craping!</b><br><br>";
    		msg += "Possiveis problemas: ";
    		msg += "<ul><li>Nome/NIF inválido</li>";
    		msg += "<li>Nada encontrado</li>";
    		msg += "<li>Indisponibilidade de todos os serviços (possível suspensão)</li>";
    		msg += "<li>Outro erro</li></ul>";
    		showMessage(msg);
    		return;
    	}

    	concelho = null;
		freguesia = null;

		setValue('telephone',data['telefone'], true, "Poderá o telefone ser ");
		setValue('address',data['morada'], true, "Poderá a morada ser ");
		setValue('latitude',data['latitude'], true, "Poderá a latitude ser ");
		setValue('longitude',data['longitude'], true, "Poderá a longitude ser ");
		setValue('site',data['site'], true, "Poderá o website ser ");	
		setValue('cp',data['codigo_postal'], true, "Poderá o codigo postal ser ");	
		setValue('city',data['localidade'], true, "Poderá a localidade ser ");
		setValue('nif',data['nif'], true, "Poderá o nif ser ");

		setValue('mobile',data['telemovel'], true, "Poderá o telemovel ser ");
		setValue('fax',data['fax'], true, "Poderá o fax ser ");
		setValue('email',data['email'], true, "Poderá o email ser ");			
		setValue('cae',data['cae'], true, "Poderá o cae ser ");

		if (data['distrito'])
			setValue('distrito',data['distrito'].toLowerCase(), true, "Poderá o distrito ser ");
		concelho = data['concelho'];
		freguesia = data['freguesia'];
		$('#distrito').trigger('change');	

		if (data['data_de_inicio'])
			setValue('init_act', formatDate(data['data_de_inicio']), true, "Poderá a data de inicio de actividade ser ");
		setValue('juridica', data['forma_juridica'], true, "Poderá a forma juridica ser ");
		if (data['estado'])
			setValue('state', data['estado'].toUpperCase(), true, "Poderá o estado actual da entidade ser ");


		var cae_code = $("#cae").val();
		if (cae_code != '') getCAEDesignacao(cae_code);

		updateMap();
		showWarnings(true);
		$(".loading").hide();
		checkState();
    }


    function setNifData(data) {
		var data = JSON.parse(data);
		if (data['status'] == 'ERROR' || data['status'] == 'NOT FOUNDED') {
    		$(".loading").hide();
    		var msg = "<b>Ocorreu um problema durante a operação!</b><br><br>";
    		msg += "Possiveis problemas: ";
    		msg += "<ul><li>NIF inválido/não existente</li>";
    		msg += "<li>Chave incorrecta</li>";
    		msg += "<li>Uso excessivo (ver Ajuda)</li></ul>";
    		showMessage(msg);
    		return;
    	}

    	concelho = null;
		freguesia = null;

    	setValue('name',data['nome'], true, "Poderá o nome ser ");

		if (data['estado']) {
			switch (data['estado']) {
				case 'active':
					setValue('state', "actividade", false, "Poderá o estado actual da entidade ser ");
					break;
				case 'inactive':
					setValue('state', "encerrada", false, "Poderá o estado actual da entidade ser ");
					break;
				case 'insolvency':
					setValue('state', "liquidacao", false, "Poderá o estado actual da entidade ser ");
			}
		}

		setValue('address',data['morada'], true, "Poderá a morada ser ");

		if (data['cp']) {
			var cp = data['cp'];
			if (data['cp_ext'])
				cp += '-' + data['cp_ext'];
			setValue('cp',cp, true, "Poderá o codigo postal ser ");		
		}
		
		setValue('city',data['cidade'], true, "Poderá a localidade ser ");
		setValue('telephone',data['phone'], true, "Poderá o telefone ser ");
		setValue('fax',data['fax'], true, "Poderá o fax ser ");

		if (data['distrito'])
			setValue('distrito',data['distrito'].toLowerCase(), true, "Poderá o distrito ser ");
		concelho = data['concelho'];
		freguesia = data['freguesia'];
		$('#distrito').trigger('change');

		setValue('site',data['website'], true, "Poderá o website ser ");
		setValue('cae',data['cae'], true, "Poderá o cae ser ");
		setValue('init_act',data['data_inicio'], true, "Poderá a data de inicio de actividade ser ");
		setValue('email',data['email'], true, "Poderá o email ser ");			

		setValue('activity',data['actividade'], true, "Poderá a actividade ser ");


		var cae_code = $("#cae").val();
		if (cae_code != '') getCAEDesignacao(cae_code);

		//updateMap();
		showWarnings(true);
		$(".loading").hide();
		checkState();
    }


    function setCAEDesignacao(data) {
    	$("#cae_desc").val(data['cae_designacao']);
    	$(".loading").hide();
    }

   	function setConcelhos(data) {
   		$("#concelho").html('');
   		for (var key in data) {
           $("#concelho").append('<option value="' + data[key]['concelho'].toLowerCase() + '">' + data[key]['concelho'] + '</option>');
       	}
    	$(".loading").hide();
    }

   	function setFreguesias(data) {
   		$("#freguesia").html('');
   		for (var key in data) {
           $("#freguesia").append('<option value="' + data[key]['freguesia'].toLowerCase() + '">' + data[key]['freguesia'] + '</option>');
       	}
    	$(".loading").hide();
    }

   	// ************************************************
	// MAP STUFF
	// ************************************************

    function updateMap(inv_size = true) {
    	if (marker) {
    		mymap.removeLayer(marker);
    		marker = null;
    	}
    	
    	var lat = $("#latitude").val();
    	var lon = $("#longitude").val();
    	if (lat != "" && lon != "") {
    		marker = L.marker([lat, lon]);
    		mymap.setView(new L.LatLng(lat, lon), 17);
    		if (!inv_size) {
    			marker.addTo(mymap);
    			return;
    		}    		
    	}
    	
    	// required if the tab of the map is not on screen 
    	setTimeout(function()
    		{ 
    			mymap.invalidateSize(); 
    			if (marker)
    				marker.addTo(mymap);
    		}, 
    	400);
    	
	}

	function onMapClick(e) {
		if (marker) {
    		mymap.removeLayer(marker);
    		marker = null;
    	}
    	marker = new L.marker(e.latlng).addTo(mymap);
    	$("#latitude").val(e.latlng.lat);
    	$("#longitude").val(e.latlng.lng);
	}

   	// ************************************************
	// DIVERSOS
	// ************************************************

    function setValue(id, value, check = false, msg = "") {
    	if (value == "None" || value == null || value == '') return;
    	$("#" + id).css("background-color", "#ffffff");
    	if (!check) {
    		$("#" + id).val(value);
    	} else {
    		var temp1 = $("#" + id).val();
    		if ( temp1 && temp1 != '' && temp1.toString().toLowerCase() != value.toString().toLowerCase() && temp1 !='-') {
    			//warnings_errors.push(msg + value + "?");
    			warnings_errors.push(msg + temp1 + "?");
    			$("#" + id).val(value);
    			$("#" + id).css("background-color", "#fdffb7");
    		} else {
    			$("#" + id).val(value);
    		}
    	}
    }

    // input: YYYY-MM-DD
    // returns: DD-MM-YYYY
    function formatDate(date) {
    	if (!date) return;
    	var parts = date.split("-");
    	if (parts[0].length == 4) return date;
    	if (parts[0].length == 1) parts[0] = '0' + parts[0];
    	if (parts[1].length == 1) parts[1] = '0' + parts[1];
    	return (parts[2] + "-" + parts[1] + "-" + parts[0]);
    }


    function formatSchedule(date) {
    	var schedule = '';
    	var WEEK_DAYS = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
    	for (var i=0;i<date.length;i++) {
    		schedule += "[" + WEEK_DAYS[i] + "]";
    		schedule += " Abertura: " + date[i]['open']['time'].replace(/(\d{2})(\d{2})/, "$1:$2");
    		schedule += " | Fecho: " + date[i]['close']['time'].replace(/(\d{2})(\d{2})/, "$1:$2");
    		if (i < date.length - 1) schedule += "\n";
    	}
    	return schedule;
    }


    function showWarnings(clear = true) {
   		if (warnings_errors.length == 0) return;
   		if (clear) $('.warnings-list').text('');

   		for (var i=0; i<warnings_errors.length; i++)
   			$('.warnings-list').append("<li>" + warnings_errors[i] + "</li>");
   		$('#warnings').show();
   		warnings_errors = [];
   	}

   	function showError(msg) {
   		$('.modal-body-modalDBError').html(msg);
        $('#modalDBError').modal('show');  
   	}

   	function showMessage(msg) {
   		showError(msg);
   	}

    // this prevent the #crap of being added to the url
    $('.nav-tabs').click(function(event){
        event.preventDefault();
    });

</script>


<?php include('../footer.php'); ?>