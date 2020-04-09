<?php include('header.php'); ?>
<?php
    // get all jpg filenames in /images/index/ directory
    $files = array();
    foreach (array_filter(glob('./images/index/*.jpg'), 'is_file') as $file)
    {
        array_push($files, $file);
    }
    // use ping to check if server can connect to the internet
    function isConnected() {
        $is_connected = 0;
        try {
                $ip = 'www.google.com';
                exec("ping -n 1 $ip 2>&1", $output, $retval);
                if ($retval == 0)  {
                  $is_connected = 1;
                }
        } catch(Exception $e) {
        }
        return  $is_connected;
    }
?>

<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/carousel.css"/>
<style>
  .message {
    color:black; 
    background-color: #CCFFFF;
    font-weight: normal; 
    border: 1px solid #003399;
    border-radius: 5px;
    text-align: center;
}
</style>



<div class="container-fluid">
    <div class="col-sm-12">
        <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="1500">
            <!-- Indicators -->
            <ul class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
            </ul>
            <!-- The slideshow -->
            <div class="carousel-inner" role="listbox">
                <div class="carousel-item active">
                    <img src="" alt="no image" id="img1">
                </div>
                <div class="carousel-item">
                    <img src="" alt="no image" id="img2">
                </div>
                <div class="carousel-item">
                    <img src="" alt="no image" id="img3">
                </div>
            </div>
            <!-- Left and right controls -->
            <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#myCarousel" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
    </div>

    <br>

    <div class="col-sm-12">
        <h1 class="text-center">Homepage</h1>
    </div>

    <br>

    <div class="col-sm-12">
        <div class="message">
            <div class="table-message">
                <h5>Utilize o navegador <b>Chrome</b> para uma melhor experência, particularmente nas <i>fiscalizações</i> e <a href="./entidades/novaentidade.php"><i>Nova Entidade</i></a>.</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-12">

        <br> Esta aplicação foi criada com o intuito de atingir dois objectivos:

        <ul>
            <li>
                Exploração da informação disponível;
            </li>
            <li>
                Como uma base de teste, experimentação e visualização dos diferentes módulos: classificação, geocodificação, duplicação, rotas de fiscalização, ...
            </li>
        </ul>
        <p>
            <mark><b>
                Não deve de forma alguma ser utilizada de forma oficial, inclusive para a consulta de informações.
            </b></mark>
        </p>

        <p>
            A maioria de cada página possuiu
            <i>Ajuda</i>, a qual é acessível diretamente na barra de menu.

        </p>
    </div>

    <!-- HELP MODAL -->
    <div class="modal" id="modal-help">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajuda</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body modal-body-help">
                    <br> Esta aplicação foi criada com o intuito de atingir dois objectivos:

                    <ul>
                        <li>
                            Exploração da informação disponível;
                        </li>
                        <li>
                            Como uma base de teste, experimentação e visualização dos diferentes módulos: classificação, geocodificação, duplicação, rotas de fiscalização, ...
                        </li>
                    </ul>
                    <p>
                        <mark><b>
                            Não deve de forma alguma ser utilizada de forma oficial, inclusive para a consulta de informações.
                        </b></mark>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END OF HELP MODAL -->

    <!-- NO INTERNET CONNECTION MODAL -->
    <div class="modal" id="modalNoNet">
        <div class="modal-dialog" style=" display: table; overflow-x: auto;width: auto;max-width: 800px;">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Atenção!</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body modal-body-modalNoNet text-justify">
                    <h3 class="text-danger text-center">
                        De momento existe alguns problema de ligação!
                    </h3>
                    <br>
                    <p>
                        Devido a este problema, diversos serviços encontram-se inoperacionais ou com funcionalidade limitada, entre eles:
                    </p>
                    <ul>
                        <li>Fiscalização</li>
                        <li>Scraping</li>
                        <li>Serviços Google</li>
                        <li>Classificadores</li>
                        <li>Geocodificação</li>
                    </ul>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END OF NO INTERNET CONNECTION MODAL -->

</div>

<script src="<?php echo DOMAIN_URL; ?>js/utils.js"></script>



<script>

    function shuffle(array) {
        var i = array.length,
            j = 0,
            temp;

        while (i--) {

            j = Math.floor(Math.random() * (i+1));

            temp = array[i];
            array[i] = array[j];
            array[j] = temp;

        }

        return array;
    }



     $( document ).ready(function() {
        var ImageArray = <?php echo json_encode($files) ?>;

        // create and shuffle the array
        var arr = [];
        for (var i = 0; i < ImageArray.length; i++)
            arr.push(i);

        var ranNums = shuffle(arr);

        
        // get the first 3 of the shuffled array
        $("#img1").attr("src", ImageArray[ranNums[0]]);
        $("#img2").attr("src", ImageArray[ranNums[1]]);
        $("#img3").attr("src", ImageArray[ranNums[2]]);

        

        // show or not the not connected modal indicating if the server
        // has some connection problem
        // uses 2 x 10min cookies for this display operation
        // if there is some conn problem, it can take a few seconds
        // to load the entire page
        var is_connected = getCookie("is_connected");
        var is_connected_showed = getCookie("is_connected_showed");
        
        var connected = <?php echo isConnected(); ?>;


        if (connected == 0) {
            if ((!getCookie("is_connected") || getCookie("is_connected") == '') && 
                  (!getCookie("is_connected_showed") ||getCookie("is_connected_showed") == '')) {
                setCookie('is_connected_showed','false',10);
                setCookie('is_connected','false',10);
            }

            if (getCookie("is_connected") != null && getCookie("is_connected") =='false') {
                if (getCookie("is_connected_showed") != null && getCookie("is_connected_showed") == "false") {
                  setCookie('is_connected_showed','true',10);
                  $('#modalNoNet').modal('show');
                }
            }
        } else {
          if (getCookie("is_connected") != null)
              deleteCookie('is_connected');
          if (getCookie("is_connected_showed") != null)
              deleteCookie('is_connected_showed');
        }
        
         
     });

</script>



<?php include('footer.php'); ?>