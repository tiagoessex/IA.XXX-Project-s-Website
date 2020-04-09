<?php require_once 'settings/config.php'; ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
// $isLogIn = false;
// If session variables are not set 
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
  $isLogIn = 0;
  include('login.php');
} else {
  $isLogIn = 1;
}
?>

<!DOCTYPE html>
<html>

<head>

  <title>rev_2.12</title>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- ROUTES_FLASK_API and ROUTES_OSRM_API is available in every js file -->
  <script>
    ROUTES_FLASK_API = '<?php echo ROUTES_FLASK_API; ?>'
    ROUTES_OSRM_API = '<?php echo ROUTES_OSRM_API; ?>'
  </script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <!-- DATETIME PICKER -->
  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
  <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

  <!-- BOOTSTRAP SLIDER -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/bootstrap-slider.min.js" integrity="sha256-oj52qvIP5c7N6lZZoh9z3OYacAIOjsROAcZBHUaJMyw=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/css/bootstrap-slider.min.css" integrity="sha256-G3IAYJYIQvZgPksNQDbjvxd/Ca1SfCDFwu2s2lt0oGo=" crossorigin="anonymous" />

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">

  <link rel='stylesheet' href="<?php echo DOMAIN_URL; ?>css/header.css">
  <link rel='stylesheet' href="<?php echo DOMAIN_URL; ?>css/footer.css">


  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.8/angular.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-simple-logger/0.1.7/angular-simple-logger.min.js" integrity="sha256-pCSPFdd2xTyAjqQUAaN4amj+x4uAeTpn3Qly6nfXrxk=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ui-leaflet/2.0.0/ui-leaflet.min.js" integrity="sha256-5LHZeeI8VUdPcORIXLzmLXGRc9h9oKUWe+VEkqGsJXU=" crossorigin="anonymous"></script>

  <!-- Leaflet 1.5.1 -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js" integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og==" crossorigin=""></script>

  <!-- Leaflet Routing Machine -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
  <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

  <!-- Leaflet Control Geocoder -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

  <script>
    if (<?php echo $isLogIn; ?> == 1) {
      localStorage.setItem('access-token', '<?php echo $isLogIn ? $_SESSION['jwt'] : ''; ?>');
      localStorage.setItem('user', JSON.stringify(<?php echo $isLogIn ? json_encode($_SESSION['user']) : ''; ?>));
      console.log(<?php echo $isLogIn ? json_encode($_SESSION['user']) : ''; ?>)
    } else {
      localStorage.removeItem('access-token');
      localStorage.removeItem('user');
    }

    // handles the logout event
    function LogOut() {
      $.ajax({
        url: '<?php echo DOMAIN_URL; ?>logout.php',
        complete: function(response) {
          window.location.replace(window.location.href);
        },
        error: function() {}
      });
      return false;
    }
  </script>
</head>

<body ng-app="fiscalizacoesApp">
  <div class="container-fluid">

    <nav class="navbar fixed-top navbar-expand-lg navbar-light   nav-bk3">

      <!-- <a class="navbar-brand text-light" href="index.php" >XXX</a> -->
      <a class="navbar-brand" rel="home" href="<?php echo DOMAIN_URL; ?>index.php" title="EASA">
        <img style="max-width:36px; margin-top: 0px;" src="<?php echo DOMAIN_URL; ?>images/logo.png"></a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#demo-navbar" aria-controls="demo-navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>



      <div class="collapse navbar-collapse" id="demo-navbar">

        <ul class="navbar-nav mr-auto">

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-light <?php echo ($isLogIn ? '' : 'd-none'); ?>" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Entidades
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>entidades/singleentity.php">Procurar por Nome/ID</a><a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>entidades/activityentity.php">Procurar por Actividade</a>
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>entidades/multipleentities.php"> Procurar por Região (aleatório)</a>

              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>entidades/entitiesradius.php"> Procurar no Raio de ...</a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>entidades/novaentidade.php"> Nova Entidade</a>

              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>entidades/entitiesradiusgoogle.php"> Procurar no Raio de ... (Google)</a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>entidades/consultarduplicados.php">Ver Duplicados</a>
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>entidades/duplicadovalid.php">Validar Duplicados</a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>entidades/consultaranomalias.php">Ver Anomalias</a>
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>entidades/anormaisvalid.php">Validar Anomalias</a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>entidades/consultarnifinvalid.php">Entidades com NIFs Inválidos</a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>entidades/consultardatas.php">Entidades não Utilizadas</a>
            </div>
          </li>


          <li class="nav-item">
            <a class="nav-link text-light <?php echo ($isLogIn ? '' : 'd-none'); ?>" href="<?php echo DOMAIN_URL; ?>densidades/densidades.php">Densidades</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-light <?php echo ($isLogIn ? '' : 'd-none'); ?>" href="javascript:void(0)" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Fiscalizações
            </a>
            <div class="dropdown-menu <?php echo ($isLogIn ? '' : 'd-none'); ?>" aria-labelledby="navbarDropdown2">
              <a class="dropdown-item" data-toggle="modal" data-target="#newModal" href="javascript:void(0)">Nova fiscalização</a>
              <a class="dropdown-item" data-toggle="modal" data-target="#consultarFiscalizacaoModal" href="javascript:void(0)">Consultar fiscalização</a>
              <!-- <div class="dropdown-divider"></div>-->
            </div>
          </li>


          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-light <?php echo ($isLogIn ? '' : 'd-none'); ?>" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Denúncias </a>
            <div class="dropdown-menu <?php echo ($isLogIn ? '' : 'd-none'); ?>" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>denuncias/singledenuncia.php">Analisar uma Denúncia</a>
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>denuncias/denunciasanalisar.php">Analisar Denúncias</a>
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>denuncias/denunciaconsultaralterar.php">Consultar & Alterar</a>

              <div class="dropdown-divider"></div>
              <!--
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>denuncias/denunciasanalisarclass.php">Analisar Denúncias já Classificadas</a>

              <div class="dropdown-divider"></div>
              
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>denuncias/denunciasclassificadasact.php">Classificadas por Actividade (Consulta)</a>-->
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>denuncias/denunciasclassificadas.php">Real vs Previsto (Consulta)</a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>denuncias/denunciasexporttrain.php">Exportar e Treinar</a>


            </div>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-light <?php echo ($isLogIn ? '' : 'd-none'); ?>" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Dashboards
            </a>
            <div class="dropdown-menu <?php echo ($isLogIn ? '' : 'd-none'); ?>" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>dashboards/geraldashboard.php" target="_self">Geral 1</a>
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>dashboards/denunciasdashboard.php">Denúncias</a>
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>dashboards/entidadesdashboard.php">Entidades</a>
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>dashboards/fiscalizacoesdashboard.php">Fiscalizações</a>
              <a class="dropdown-item" href="<?php echo DOMAIN_URL; ?>dashboards/userdefineddashboard.php">User Defined</a>
            </div>
          </li>
          <!--
      <li class="nav-item"> 
        <a class="nav-link text-light <?php echo ($isLogIn ? '' : 'd-none'); ?>" href="javascript:void(0)" style="pointer-events: none;">Relatórios</a>
      </li> 
    -->

          <li class="nav-item">
            <a class="nav-link text-light <?php echo ($isLogIn ? '' : 'd-none'); ?>" href="<?php echo DOMAIN_URL; ?>videos/videoshelp.php">Videos</a>
          </li>

          <li class="nav-item">
            <a class="nav-link text-light" href="javascript:void(0)" data-toggle="modal" data-target="#modal-help">Ajuda</a>
          </li>


        </ul>


        <!-- LOGIN STUFF -->

        <ul class="nav navbar-nav flex-row justify-content-between ml-auto" id="login-ul">
          <li class="dropdown order-1 <?php echo ($isLogIn ? 'd-none' : ''); ?>">
            <button type="button" id="dropdownMenu1" data-toggle="dropdown" class="btn  btn-primary" style="border-color: white;"><span class="fas fa-sign-in-alt"></span> Login <span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-right mt-2" style="width: 300px !important; background-color: rgb(112,146,190);">
              <li class="px-3 py-2">
                <form method="post" class="form" role="form" action="/index.php">
                  <div class="form-group">
                    <input id="username" name="username" placeholder="Utilizador" class="form-control form-control-sm" type="text" required="">
                  </div>
                  <div class="form-group">
                    <input id="password" name="password" placeholder="Password" class="form-control form-control-sm" type="password" required="">
                  </div>
                  <div class="form-group">
                    <!--
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                  -->
                    <button type="submit" class="btn btn-primary btn-block" id="login-btn">Login</button>
                  </div>
                  <div class="form-group text-left">
                    <small><a href="javascript:void(0)" data-toggle="modal" data-target="#modalPassword" style="color:white;">Esqueceu-se da password?</a></small>
                  </div>
                </form>
              </li>
            </ul>
          </li>
          <li class="nav-item <?php echo ($isLogIn ? '' : 'd-none'); ?>">
            <a class="nav-link text-light" href="#">(<?php echo (($_SESSION['user'])['username']); ?>) <?php echo ($_SESSION['user']['nome']); ?> - Unidade <?php echo ($_SESSION['user']['unidade_id']); ?></a>
          </li>
          <li class="dropdown order-1 <?php echo ($isLogIn ? '' : 'd-none'); ?>" id="no_fisc_exit">
            <button type="button" id="logout-btn" class="btn btn-secondary" onclick="LogOut();"><span class="fas fa-sign-out-alt"></span> Logout</button>
          </li>

        </ul>
        <!-- END LOGIN STUFF -->



      </div>

    </nav>

  </div>


  <!-- MODAL RESET PASSWORD -->
  <div id="modalPassword" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3>Recuperação da password</h3>
          <button type="button" class="close font-weight-light" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
          <p>It sucks to be you.</p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
          <button class="btn btn-primary">Enviar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- END MODAL RESET PASSWORD -->


  <!-- ERROR MODAL -->
  <div class="modal" id="modalDBError">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title">ERRO</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body modal-body-modalDBError">
          error
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
        </div>

      </div>
    </div>
  </div>
  <!-- END OF ERROR MODAL -->

  <script>
    //stack modals
    $(document).on('show.bs.modal', '.modal', function() {
      var zIndex = 1040 + (10 * $('.modal:visible').length);
      $(this).css('z-index', zIndex);
      setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
      }, 0);
    });
    // required to recover modal's scrolling ability
    $(document).on("hidden.bs.modal", '.modal', function(e) {
      if ($('.modal:visible').length) {
        $('body').addClass('modal-open');
      }
    });
  </script>


  <?php include('fiscalizacoes/modals/new.php'); ?>
  <?php include('fiscalizacoes/modals/get.php'); ?>
