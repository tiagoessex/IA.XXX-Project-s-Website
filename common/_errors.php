
<script type="text/javascript">
    // 90% right
  function errorsCommon(jqXHR, status) {
        var s = '';
        if (jqXHR.status == 500) {    
            s = '<h4>Problemas com base de dados</h4></br>';        
            s += '<b>Possiveis soluções:</b> <ul><li>Verifique as queries!</li></ul></br></br>';
        } else if (jqXHR.status == 200) { 
            s = '<h4>Problemas!</h4></br>';
            s += '<b>Possiveis soluções:</b> <ul><li>Verifique as credenciais de acesso</li><li>Verifique as queries e/ou aumente a sua performance!</li><ul></br></br>';
        } else if (jqXHR.status == 0) {
            s = '<h4>Timeout</h4></br>';
            s += '<b>Possiveis soluções:</b> <ul><li>Aumente a performance das queries!</li></ul></br></br>';
        } else {
            s = '<h4>Ocorreu um erro!</h4></br>';
        }
        s += '<b>ERRO: </b></br>';
        s += '<mark>' + jqXHR.responseText + '</mark>';
        $('.modal-body-modalDBError').html(s);
        $('#modalDBError').modal('show');
        $(".loading").hide();
    }

	
    // 99% right
    function errorsCommonPython(data) {
        $(".loading").hide();
        var s = '<b>Error com o servidor</b><br><br>';
        s += "Possiveis problemas:<br>";
        s += "<ul>";
        s += "<li>Timout demasiado curto</li>";
        s += "<li>Servidor incapaz de aceder ao serviço web</li>";
        s += "<li>Servidor Python não activo ou com erro</li>";
        s += "</ul>";
        $('.modal-body-modalDBError').html(s);
        $('#modalDBError').modal('show'); 
    }

</script>