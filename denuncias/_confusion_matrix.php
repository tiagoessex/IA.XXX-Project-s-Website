<!--
    INCLUDED IN:
        /denuncias/denunciasclassificadas.php
        /denuncias/denunciasanalisarclass.php
-->
<br>
<h3 class="text-center">Matrix de Confusão das Actividades
 <a href="#" title="Clique para obter ajuda sobre matrizes de confusão." data-toggle="modal" data-target="#modal-matrix" style="font-size: 70%;"><sup><i class='fas fa-exclamation-circle'></i></sup></a>
 </h3>

  <table class="table table-bordered text-center">
    <thead>
        <tr>
            <td></td>
            <td></td>
            <td colspan="11" class="bg-info text-white" style="font-size: 150%;">Previsto</td>
        </tr>
      <tr>
		<th></th>
        <th></th>  
        <th class="bg-primary text-white">I</th>
        <th class="bg-primary text-white">II</th>
        <th class="bg-primary text-white">III</th>
        <th class="bg-primary text-white">IV</th>
        <th class="bg-primary text-white">V</th>
        <th class="bg-primary text-white">VI</th>
        <th class="bg-primary text-white">VII</th>
        <th class="bg-primary text-white">VIII</th>
        <th class="bg-primary text-white">XI</th>
        <th class="bg-primary text-white">X</th>
        <th class="bg-primary text-white">Z</th>
      </tr>
    </thead>
    <tbody>
        <tr>
        <td rowspan="12" class="bg-info text-white" style="font-size: 150%;vertical-align:middle">Real</td>
        </tr>
      <tr>
        <th class="bg-primary text-white">I</th>
        <td class="RI PI bg-success text-white">-</td>
        <td class="RI PII">-</td>
        <td class="RI PIII">-</td>
        <td class="RI PIV">-</td>
        <td class="RI PV">-</td>
        <td class="RI PVI">-</td>
        <td class="RI PVII">-</td>
        <td class="RI PVIII">-</td>
        <td class="RI PIX">-</td>
        <td class="RI PX">-</td>
        <td class="RI PZ">-</td>
      </tr>
      <tr>
        <th class="bg-primary text-white">II</th>
        <td class="RII PI">-</td>
        <td class="RII PII bg-success text-white">-</td>
        <td class="RII PIII">-</td>
        <td class="RII PIV">-</td>
        <td class="RII PV">-</td>
        <td class="RII PVI">-</td>
        <td class="RII PVII">-</td>
        <td class="RII PVIII">-</td>
        <td class="RII PIX">-</td>
        <td class="RII PX">-</td>
        <td class="RII PZ">-</td>
      </tr>
      <tr>
        <th class="bg-primary text-white">III</th>
        <td class="RIII PI">-</td>
        <td class="RIII PII">-</td>
        <td class="RIII PIII bg-success text-white">-</td>
        <td class="RIII PIV">-</td>
        <td class="RIII PV">-</td>
        <td class="RIII PVI">-</td>
        <td class="RIII PVII">-</td>
        <td class="RIII PVIII">-</td>
        <td class="RIII PIX">-</td>
        <td class="RIII PX">-</td>
        <td class="RIII PZ">-</td>
      </tr>
      <tr>
        <th class="bg-primary text-white">IV</th>
        <td class="RIV PI">-</td>
        <td class="RIV PII">-</td>
        <td class="RIV PIII">-</td>
        <td class="RIV PIV bg-success text-white">-</td>
        <td class="RIV PV">-</td>
        <td class="RIV PVI">-</td>
        <td class="RIV PVII">-</td>
        <td class="RIV PVIII">-</td>
        <td class="RIV PIX">-</td>
        <td class="RIV PX">-</td>
        <td class="RIV PZ">-</td>
      </tr>
        <tr>
        <th class="bg-primary text-white">V</th>
        <td class="RV PI">-</td>
        <td class="RV PII">-</td>
        <td class="RV PIII">-</td>
        <td class="RV PIV">-</td>
        <td class="RV PV bg-success text-white">-</td>
        <td class="RV PVI">-</td>
        <td class="RV PVII">-</td>
        <td class="RV PVIII">-</td>
        <td class="RV PIX">-</td>
        <td class="RV PX">-</td>
        <td class="RV PZ">-</td>
      </tr>
            <tr>
        <th class="bg-primary text-white">VI</th>
        <td class="RVI PI">-</td>
        <td class="RVI PII">-</td>
        <td class="RVI PIII">-</td>
        <td class="RVI PIV">-</td>
        <td class="RVI PV">-</td>
        <td class="RVI PVI bg-success text-white">-</td>
        <td class="RVI PVII">-</td>
        <td class="RVI PVIII">-</td>
        <td class="RVI PIX">-</td>
        <td class="RVI PX">-</td>
        <td class="RVI PZ">-</td>
      </tr>
            <tr>
        <th class="bg-primary text-white">VII</th>
        <td class="RVII PI">-</td>
        <td class="RVII PII">-</td>
        <td class="RVII PIII">-</td>
        <td class="RVII PIV">-</td>
        <td class="RVII PV">-</td>
        <td class="RVII PVI">-</td>
        <td class="RVII PVII bg-success text-white">-</td>
        <td class="RVII PVIII">-</td>
        <td class="RVII PIX">-</td>
        <td class="RVII PX">-</td>
        <td class="RVII PZ">-</td>
      </tr>
            <tr>
        <th class="bg-primary text-white">VIII</th>
        <td class="RVIII PI">-</td>
        <td class="RVIII PII">-</td>
        <td class="RVIII PIII">-</td>
        <td class="RVIII PIV">-</td>
        <td class="RVIII PV">-</td>
        <td class="RVIII PVI">-</td>
        <td class="RVIII PVII">-</td>
        <td class="RVIII PVIII bg-success text-white">-</td>
        <td class="RVIII PIX">-</td>
        <td class="RVIII PX">-</td>
        <td class="RVIII PZ">-</td>
      </tr>
            <tr>
        <th class="bg-primary text-white">XI</th>
        <td class="RIX PI">-</td>
        <td class="RIX PII">-</td>
        <td class="RIX PIII">-</td>
        <td class="RIX PIV">-</td>
        <td class="RIX PV">-</td>
        <td class="RIX PVI">-</td>
        <td class="RIX PVII">-</td>
        <td class="RIX PVIII">-</td>
        <td class="RIX PIX bg-success text-white">-</td>
        <td class="RIX PX">-</td>
        <td class="RIX PZ">-</td>
      </tr>
            <tr>
        <th class="bg-primary text-white">X</th>
        <td class="RX PI">-</td>
        <td class="RX PII">-</td>
        <td class="RX PIII">-</td>
        <td class="RX PIV">-</td>
        <td class="RX PV">-</td>
        <td class="RX PVI">-</td>
        <td class="RX PVII">-</td>
        <td class="RX PVIII">-</td>
        <td class="RX PIX">-</td>
        <td class="RX PX bg-success text-white">-</td>
        <td class="RX PZ">-</td>
      </tr>
            <tr>
        <th class="bg-primary text-white">Z</th>
        <td class="RZ PI">-</td>
        <td class="RZ PII">-</td>
        <td class="RZ PIII">-</td>
        <td class="RZ PIV">-</td>
        <td class="RZ PV">-</td>
        <td class="RZ PVI">-</td>
        <td class="RZ PVII">-</td>
        <td class="RZ PVIII">-</td>
        <td class="RZ PIX">-</td>
        <td class="RZ PX">-</td>
        <td class="RZ PZ bg-success text-white">-</td>
      </tr>
    </tbody>
  </table>
</div>


  <!-- DENUNCIA/RECLAMACAO MESSAGE MODAL -->
   <div class="modal" id="modal-matrix">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title modal-title-message">Matriz de Confusão</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-content-message">
               <div class="modal-body modal-body-message text-justify">
                  <p>
                      Uma matriz de confusão é uma tabela que indica os erros e acertos do seu modelo, comparando com o resultado esperado (ou etiquetas/labels). A imagem abaixo demonstra um exemplo de uma matriz de confusão.
                  </p>

                   <img src="images/matriz.png" alt="matrix de confusao" style="width:50%;height:50%;" class="mx-auto d-block">
                   <br>
                   <br>
                  <ul>
                    <li>Verdadeiros Positivos: classificação correta da classe Positivo;</li>
                    <li>Falsos Negativos (Erro Tipo II): erro em que o modelo previu a classe Negativo quando o valor esperado era classe Positivo;</li>
                    <li>Falsos Positivos (Erro Tipo I): erro em que o modelo previu a classe Positivo quando o valor esperado era classe Negativo;</li>
                    <li>Verdadeiros Negativos: classificação correta da classe Negativo.</li>
                  </ul>

                  <p>
                      <i>Fonte (cópia integral): <a href="https://medium.com/@vitorborbarodrigues/m%C3%A9tricas-de-avalia%C3%A7%C3%A3o-acur%C3%A1cia-precis%C3%A3o-recall-quais-as-diferen%C3%A7as-c8f05e0a513c">https://medium.com/@vitorborbarodrigues/m%C3%A9tricas-de-avalia%C3%A7%C3%A3o-acur%C3%A1cia-precis%C3%A3o-recall-quais-as-diferen%C3%A7as-c8f05e0a513c</a></i>
                  </p>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
            </div>
         </div>
      </div>
   </div>
   <!-- END OF DENUNCIA/RECLAMACAO MESSAGE MODAL -->


<script>
/*
	setTableValue('III','III',666);
	setTableValue('I','III',111);
	setTableValue('II','III',999);
*/
function setTableValue(row, column, value) {
	var clss = '.R' + row;
	clss += '.P' + column;
	$(clss).text(value);
}

function getTableValue(row, column) {
    var clss = '.R' + row;
    clss += '.P' + column;
    return $(clss).text();
}


function resetTable(value) {
    $('.RI').text(value);
    $('.RII').text(value);
    $('.RIII').text(value);
    $('.RIV').text(value);
    $('.RV').text(value);
    $('.RVI').text(value);
    $('.RVII').text(value);
    $('.RVIII').text(value);
    $('.RXI').text(value);
    $('.RX').text(value);
    $('.RZ').text(value);
}


</script>


