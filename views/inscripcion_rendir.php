<?php include("../cabecera.php");?>

    <!--  Extension de TableBoostrap para EXPORTAR Datos (Excel y Pdf)  
          No me funciona poniendo estas propiedades en la tabla, por eso 
           personalise el js de la extension
             exportDataType="all"
             exportTypes=['json', 'pdf', 'txt', 'sql', 'excel']           
        <script src="../js/bootstrap-table-export.min.js"></script>
    --> 
    <script src="../js/ranbootstrap-table-export.js"></script>
    <script src="../js/tableExport.min.js"></script>
    <script type="text/javascript" src="../js/jsPDF/jspdf.min.js"></script>
    <script type="text/javascript" src="../js/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
    
    
<div class="container"> 
 <form class="form-inline" role="form" >
   <div class="col-sm-12">
         <!-- Panel Del Titulo y Filtros -->
         <div class="panel panel-info">         
            <div class="panel-heading">
              <h3 class="panel-title">Inscripcion a Rendir</h3>
            </div>
            <div class="panel-body">
              <div class="form-group">
	     		<label class="control-label">Id Carrera:</label>
        		<input type="text" class="form-control" id="idcarrera" value="20" />

	     		<label class="control-label">Id Alumno:</label>
        		<input type="text" class="form-control" id="idalumno" value="200" />
          <label class="control-label">A�o Lectivo:</label>
            <input type="text" class="form-control" id="anio" value="2017" />

              </div>  
              <div class="form-group">
       			<button type="button" onClick="consultar()" class="btn btn-primary pull-right">Consultar</button>             
              </div>  
		    </div>
         </div> <!-- Fin Panel Info -->

        <!-- Panel De la Tabla -->
        <div class="panel panel-success">         
   		  <table id="mitabla"
           data-toggle="table"
           data-search="true"
           data-show-export="true"
           data-cache = "false"
           data-pagination="true"
           data-page-list=""
           class="table table-bordered"
          >
          <thead>
          <tr>
            <th data-halign="center" data-sortable="true">Materia</th>
            <th data-sortable="true">A�o</th>
            <th data-halign="center" data-align="center" data-sortable="true">Duracion</th>
            <th data-halign="center" data-align="right" data-sortable="true">Estado</th>
            <th data-events="operateEvents" data-formatter="operateFormatter"></th>            
          </tr>
          </thead>
    	 </table>
	    </div> <!-- fin Panel Tabla -->
  </div> <!-- fin de col -->
 </form> 
</div> <!-- /container -->



<div class="modal fade" id="modalEdit">
 <div class="modal-dialog">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">�</a>
    <h3>Modificacion</h3>
  </div>
  <div class="modal-body">
    <p>Campos a modificar...........</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn">Cerrar</a>
    <a href="#" class="btn btn-primary">Salvar Cambios</a>
  </div>
 </div>
</div>

<?php  include("../pie.php");?>



   <script>

   $(function() {
     //Se pone para que en todos los llamados ajax se bloquee la pantalla mostrando el mensaje Procesando...
     $.blockUI.defaults.message = '<h3>Procesando...</h3>';
     $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
   });

     $(document).ready(function(){
   //    consultar();
     });      

  
   var $table = $('#mitabla');
  
   function consultar()  {
    // LLama a 2da pagina con la logica de la busqueda
    // ------------------------------------------------      
			  // Tomo los datos de entrada
		    $idcarrera = $("#idcarrera").val();
		    $idalumno = $("#idalumno").val();
        $anio = $("#anio").val();
        
        $nombreSP = "Materias_Alumno("+ $idcarrera + "," + $idalumno +"," + $anio + ")";
 
			  $.ajax({
						dataType: "json",
            data: { nombreSP: $nombreSP},
						url:   '../controllers/inscripcion_rendir2.php',
						type:  'post',
						beforeSend: function(){
							//Lo que se hace antes de enviar el formulario
							},
						success: function(respuesta){
							//lo que se si el destino devuelve algo
							// console.log(respuesta.html);
					        $nomfile = "../controllers/" + respuesta.html;
			  			    $table.bootstrapTable('refresh', { url: $nomfile ,silent: true} );
						},
						error:	function(xhr,err){ 
							// console.log("readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
						    // readyState values are 1:loading, 2:loaded, 3:interactive, 4:complete.
						    // status is the HTTP status number, i.e. 404: not found, 500: server error, 200: ok. 
					        $nomfile = "../controllers/json/error.json"; // Para que vacie la tabla
						    msgerror( xhr.responseText);
			  			    $table.bootstrapTable('refresh', { url: $nomfile ,silent: true} );
					    } // Fin si hay error
					    
					});
		} // Fin consultar()


        //  alert('Valor: ' + JSON.stringify(row.Cli_Id));

		    window.operateEvents = {
		            'click .editar': function (e, value, row) {
   	   	   				//'location.href = "#myModal.modal('toggle')" // "ABM_Clientes.php?id=" +  row.Cli_Id + "&accion=M";	
   	   	   			    $('#modalEdit').modal('show')  ; 	   						                
		            },
		            'click .remove': function (e, value, row) {
		                //alert('Click en Eliminar, row: ' + JSON.stringify(row));
		                $('#modalerror').modal('show');
		            }	
		        };
		    			
		    function operateFormatter(value, row, index) {
		        return [
		          '<div class="pull-left">',
		            value ,
		          '</div>',
		          '<div class="pull-right">',
		            '<a class="editar" href="javascript:void(0)" title="Modificar">',
		            '<i class="glyphicon glyphicon-saved"></i>',
		            '</a>  ',
		            '<a class="remove" href="javascript:void(0)" title="Eliminar">',
		            '<i class="glyphicon glyphicon-remove"></i>',
		            '</a>',
		          '</div>'
		        ].join('');
		    }
				  
     </script>
  </body>
</html>
