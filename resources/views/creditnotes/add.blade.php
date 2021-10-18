@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-editable-select.min.css') }}">
    <!-- Bootstrap Select -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}">
    <style>
      .attribute-item{
        max-width: 400px;
        word-wrap: break-word;
      }
    </style>
@endsection

@section('content')
<section class="content-header">
    <h1>
      <i class="fa fa-cubes" style="padding-right: 5px;"></i> Crear Nota de Crédito
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ url('/creditnotes') }}"><i class="fa "></i> Notas de Crédito</a></li>
      <li class="active">Crear Nota de Crédito</li>
    </ol>
</section>
<section class="content">
      <div class="row">
          <div class="col-md-8">
              <div class="box box-primary">
                 <div class="box-header">
                    <h3 class="box-title">Nota de Crédito</h3>
                 </div>
                 <form class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label col-sm-2">Documento : </label>
                            <div class="col-sm-10">
                                <input disabled class="form-control show-document"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Cliente : </label>
                            <div class="col-sm-10">
                                <input disabled class="form-control show-client"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Tipo : </label>
                            <div class="col-sm-10">
                                <select class="form-control cn-type">
                                    <option value="0">Seleccione Tipo ...</option>
                                    <option value="1">Total</option>
                                    <option value="3">Parcial</option>
                                    <option value="2">Corrección de Texto</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display:none">
                            <label class="control-label col-sm-2">Caja asignada : </label>
                            <div class="col-sm-10">
                                <select class="form-control show-sale_boxes">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Razón : </label>
                            <div class="col-sm-10">
                                <input class="form-control cn-reason" disabled />
                            </div>
                        </div>
                        <div class="form-group enterprise-data" >
                            <label class="control-label col-sm-2">Datos empresa : </label>
                            <div class="col-sm-10">
                                <select disabled class="form-control cn-enterprise">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group client-data" hidden>
                            <label class="control-label col-sm-2">Rut Cliente : </label>
                            <div class="col-sm-10">
                                <input class="form-control cn-client_rut" />
                            </div>
                        </div>
                        <div class="form-group client-data" hidden>
                            <label class="control-label col-sm-2">Nombre Cliente : </label>
                            <div class="col-sm-10">
                                <input class="form-control cn-client_name" />
                            </div>
                        </div>
                        <div class="form-group client-data" hidden>
                            <label class="control-label col-sm-2">Giro Cliente : </label>
                            <div class="col-sm-10">
                                <input class="form-control cn-client_industries" />
                            </div>
                        </div>
                        <div class="form-group client-data" hidden>
                            <label class="control-label col-sm-2">Comuna cliente : </label>
                            <div class="col-sm-10">
                                <select id="s_comune" class="form-control cn-client_comune">
                                </select>
                            </div>
                        </div>
                        <div class="form-group client-data" hidden>
                            <label class="control-label col-sm-2">Dirección Cliente : </label>
                            <div class="col-sm-10">
                                <input class="form-control cn-client_address" />
                            </div>
                        </div>
                        <div class="form-group" style="display:none">
                            <label class="control-label col-sm-2">Donde dice : </label>
                            <div class="col-sm-10">
                                <input class="form-control cn-where-say" />
                            </div>
                        </div>
                        <div class="form-group" style="display:none">
                            <label class="control-label col-sm-2">Debe decir : </label>
                            <div class="col-sm-10">
                                <input class="form-control cn-should-say" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12" style="display:none">
                                <label class="control-label col-sm-2">Mostrar Lineas eliminadas : </label>
                                <div class="col-sm-10">
                                    <input id="chkEliminado" class="form-check-input" type="checkbox"/>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <theader>
                                        <tr>
                                            <th>Cantidad</th>
                                            <th>Item</th>
                                            <th style="text-align:right">Precio</th>
                                            <th style="text-align:right">% Descuento</th>
                                            <th style="text-align:right">Total</th>
                                        </tr>
                                    </theader>
                                    <tbody class="content-table">
                                    </tbody>
                                </table>
                                <table class="table pull-right" style="width:150px;text-align:right">
                                    <tr class="cn-net">
                                        <td>NETO : </td>
                                        <td class="td-net">0</td>
                                    </tr>
                                    <tr class="cn-tax">
                                        <td>IVA : </td>
                                        <td class="td-tax">0</td>
                                    </tr>
                                    <tr class="cn-total">
                                        <td>TOTAL : </td>
                                        <td class="td-total">0</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                 </form>
                 <div class="box-footer">
                     <button class="btn pull-right btn-primary btn-create-cn">Crear</button>
                 </div>
              </div>
          </div>
          <div class="col-md-4">
              <div class="box box-danger">
                 <div class="box-header">
                    <h3 class="box-title">Documentos</h3>
                 </div>
                 <form class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label col-sm-3">Tipo : </label>
                            <div class="col-sm-9">
                                <select class="form-control" id="selectType">
                                    <option value="0">Seleccione Tipo...</option>
                                    <option value="1">Boleta</option>
                                    <option value="2">Factura</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Folio : </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="selectFolio" />
                            </div>
                        </div>
                    </div>
                 </form>
                 <div class="box-footer">
                     <button class="btn btn-primary pull-right btn-search">Buscar</button>
                 </div>
              </div>
          </div>
      </div>
</section>   
@endsection
@section('js')
<script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/awsidebar.js') }}"></script>
    <script src="{{ asset('js/jquery-editable-select.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>

    <script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script>
        var item_details      = [];
        var doc_type          = null;
        var doc_id            = null;
        var IVA               = 0.19;
        var with_client_data  = 0;

        function changeQty(e, ths, id) {
            let thsVal = parseInt($(ths).val());
            let maxVal = parseInt($(ths).attr('id'));
            if(isNaN($(ths).val())) {
                thsVal = maxVal;
            }
            if (thsVal < 0 ){
                swal('Error!','Cantidad no puede ser menor a 0','error');
                $(ths).val('0');
            }else if (thsVal > maxVal){
                swal('Error!','Cantidad no puede ser mayor a '+maxVal ,'error');
                $(ths).val(maxVal);
            }else{
                item_details.map( function (detail) {
                    if(detail.id == id) {
                        detail.qtyf = thsVal
                    }
                });
                renderTable(true);
            }
        }

        function renderQty(detail, fixed) {
            if (fixed) {
                return '<input type="number" onChange="changeQty(event, this, '+detail.id+')" value="'+detail.qtyf+'" id= "'+ detail.qty +'" class="form-control" style="width:100px" />';
            } else {
                return detail.qty;
            }
        }

        function renderTable(fixed = false) {
            $('.content-table').empty();
            let rows  = '';
            let total =  0;
            item_details.map( function (detail) {
                console.log(detail);
                let price = 0;
                qty       = detail.qty;
                if (fixed) {
                    qty = detail.qtyf;
                }
                if (doc_type == 1) {
                    price = parseInt(detail.price);
                } else {
                    price = parseFloat(detail.price/(parseInt(1+IVA))).toFixed(2);
                }
                
                let ptotal      = 0;
                ptotal          += Math.round(price*qty);
                ptotal          -= Math.round(Math.round(parseFloat(price*qty))*parseFloat(detail.dsc/100));

                total           += ptotal;
                let style = ''
                if(detail.qtyf == 0 && $("#chkEliminado:checked").length <= 0) {
                    style = "style='display:none;'"
                }
                rows += `<tr id='row-`+ detail.id +`' + ` + style +`>
                            <td>${renderQty(detail, fixed)}</td>
                            <td>${detail.name}</td>
                            <td style="text-align:right">${formatMoney(price, 'CL')}</td>
                            <td style="text-align:right">${detail.dsc}</td>
                            <td style="text-align:right">${formatMoney(ptotal, 'CL')}</td>
                        </tr>`;
            });
            $('.content-table').append(rows);
            renderTotal(total);
        }

        function renderTotal(total) {
            if(doc_type == 1) {
                $('.cnet-net').hide();
                $('.cnet-tax').hide();
                $('.td-total').html(formatMoney(total, 'CL'));
            } else {
                $('.cnet-net').show();
                $('.cnet-tax').show();
                let tax = Math.round(total*IVA);
                $('.td-net').html(formatMoney(total, 'CL'));
                $('.td-tax').html(formatMoney(tax, 'CL'));
                $('.td-total').html(formatMoney((total+tax), 'CL'));
            }
        }

        function validaRut(valor){
            if ( with_client_data == 0 ) { return true; }
            if ( valor.length == 0 ){ return false; }
            if ( valor.length < 8 ){ return false; }

            valor = valor.replace('-','')
            valor = valor.replace(/\./g,'')

            var suma = 0;
            var caracteres = "1234567890kK";
            var contador = 0;    
            for (var i=0; i < valor.length; i++){
                u = valor.substring(i, i + 1);
                if (caracteres.indexOf(u) != -1)
                contador ++;
            }
            if ( contador==0 ) { return false }
            
            var rut = valor.substring(0,valor.length-1)
            var drut = valor.substring( valor.length-1 )
            var dvr = '0';
            var mul = 2;
            
            for (i= rut.length -1 ; i >= 0; i--) {
                suma = suma + rut.charAt(i) * mul
                        if (mul == 7) 	mul = 2
                        else	mul++
            }
            res = suma % 11
            if (res==1)		dvr = 'k'
                        else if (res==0) dvr = '0'
            else {
                dvi = 11-res
                dvr = dvi + ""
            }
            if ( dvr != drut.toLowerCase() ) { return false; }
            else { return true; }
        }

        $(document).ready(function() {

            
        $('.cn-client_comune').selectpicker({
            liveSearch:true,
            noneSelectedText: 'Seleccione una comuna',
            noneResultsText: ''
        });


        $('.cn-client_comune').find('.bs-searchbox').find('input').on('keyup', function(event) {
            //get code 
            if(event.keyCode != 38 && event.keyCode != 40) {
                $('.cn-client_comune').find('option').remove();
                $('.cn-client_comune').selectpicker('refresh');
                let data = new Object();
                data.comune_detail = $(this).val();
                AWApi.get('{{ url('/api/comunes') }}?' + $.param(data), function(response) {
                    if(response.code == 200) {
                        src1 = [{
                            id: null, txt:""
                        }];

                        response.data.rows.map( (comunes) => {
                            src1.push({
                                id:comunes.id,
                                txt:comunes.comune_detail
                            });
                        });

                        var options = [];
                        var option = '<optgroup label="Comuna">';
                        src1.forEach(function (item) {
                            
                            option += "<option value="+item.id+" >" + item.txt + "</option>"
                            
                        });
                        option += '</optgroup>';


                        options.push(option);
                        $('#s_comune').html(options);
                        $('.cn-client_comune').selectpicker('refresh');
                    }
                });
            }
        });

            AWApi.get('{{ url('/api/active_sale_boxes') }}',function (response) {
                $('.show-sale_boxes').empty();
                $('<option />', {value: '0', text:'Sin asignar' }).appendTo($(".show-sale_boxes"));
                for (var i = 0; i < response.data.length; i++) {
                    $('<option />', {value: response.data[i].id, text: response.data[i].name }).appendTo($(".show-sale_boxes")); 
                }
                $(".show-sale_boxes").val('0');
            });
            $('.btn-create-cn').hide();

            
            $('.btn-create-cn').click(function() {
                if (!validaRut($('.cn-client_rut').val())) {
                    swal({
                        title: "Crear Nota de Crédito",
                        text: "Rut cliente es erroneo",
                        icon: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "OK",
                        closeModal: true,
                        cancelButtonText: "NO",
                    })
                }else{
                    swal({
                        title: "Crear Nota de Crédito",
                        text: "¿Esta seguro de realizar esta acción?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "SI",
                        closeModal: true,
                        cancelButtonText: "NO",
                        buttons: [
                                                        'No',
                                                        'Si'
                                                    ]
                    }).then(
                    function (isConfirm) {
                        if(isConfirm){
                        $('.btn-create-cn').attr('disabled', true);
                        let formData = new FormData();
                        formData.append('sale_id', doc_id);
                        formData.append('type', $('.cn-type').val());
                        formData.append('reason', $('.cn-reason').val());
                        formData.append('where', $('.cn-where-say').val());
                        formData.append('should', $('.cn-should-say').val());
                        formData.append('enterprise', $('.cn-enterprise').val());
                        formData.append('client_rut', $('.cn-client_rut').val());
                        formData.append('client_name', $('.cn-client_name').val());
                        formData.append('client_industries', $('.cn-client_industries').val());
                        formData.append('client_comune', $('#s_comune').val());
                        formData.append('client_address', $('.cn-client_address').val());
                        formData.append('sale_box_movement', $('.show-sale_boxes').val());
                        formData.append('with_client_data', with_client_data);
                        formData.append('items', JSON.stringify(item_details));
                        console.log(formData);
                        AWApi.post('{{ url('/api/creditnotes') }}', formData, function(response){
                            $('.btn-create-cn').attr('disabled', false);
                            switch(response.code) {
                                case 200:
                                    swal('Correcto', "Nota creada de forma exitosa", "success").then( function(e) {
                                        if (e == true) window.location.href = "/creditnotes";
                                    });
                                break;
                                case 401:
                                    swal('Error del sistema' , response.data.msg , 'error');
                                break;
                                default:
                                    swal('Error del sistema', 'Error del sistema, contacte al administrador', 'error');
                                break;
                            }
                        });
                    };
                    });
                }
            });
            $('.cn-type').change(function() {
                let type = parseInt(this.value);
                switch(type) {
                    case 0 :
                        $('.cn-reason').val('');
                        $('.show-sale_boxes').val('0');
                        $('.show-sale_boxes').parent().parent().hide();
                        $('.cn-reason').attr('disabled', true);
                        $('.cn-where-say').val('');
                        $('.cn-where-say').parent().parent().hide();
                        $('.cn-should-say').val('');
                        $('.cn-should-say').parent().parent().hide();
                        $("#chkEliminado").parent().parent().hide();
                        renderTable();
                    break;
                    case 1 :
                        $('.cn-reason').val('');
                        $('.show-sale_boxes').val('0');
                        $('.cn-reason').attr('disabled', false);
                        $('.cn-where-say').val('');
                        $('.cn-where-say').parent().parent().hide();
                        $('.cn-should-say').val('');
                        $('.cn-should-say').parent().parent().hide();
                        $('.show-sale_boxes').parent().parent().show();
                        $("#chkEliminado").parent().parent().hide();
                        renderTable();
                    break;
                    case 3 :
                        $('.cn-reason').val('');
                        $('.show-sale_boxes').val('0');
                        $('.cn-reason').attr('disabled', false);
                        $('.cn-where-say').val('');
                        $('.cn-where-say').parent().parent().hide();
                        $('.cn-should-say').val('');
                        $('.cn-should-say').parent().parent().hide();
                        $('.show-sale_boxes').parent().parent().show();
                        $("#chkEliminado").parent().parent().show();
                        renderTable(true);
                    break;
                    case 2:
                        $('.cn-reason').val('');
                        $('.cn-reason').attr('disabled', false);
                        $('.cn-where-say').val('');
                        $('.cn-where-say').parent().parent().show();
                        $('.cn-should-say').val('');
                        $('.cn-should-say').parent().parent().show();
                        $('.show-sale_boxes').val('0');
                        $('.show-sale_boxes').parent().parent().hide();
                        $("#chkEliminado").parent().parent().hide();
                        renderTable();
                    break;
                }
            });
            $('.btn-search').click(function() {
                $(".client-data").hide();
                $(".enterprise-data").show();
                with_client_data = 0;
                let type  = $('#selectType').val();
                let folio = $('#selectFolio').val();
                AWApi.get('{{ url('/api/creditnote/search') }}/'+type+"/"+folio, function (response) {
                    if(response.code == 200) {
                        $('.show-client').val(response.data.client_name);
                        $('.cn-reason').val('');
                        $('.cn-reason').attr('disabled', true);
                        $('.cn-enterprise').attr('disabled', false);
                        $('.cn-where-say').val('');
                        $('.cn-where-say').parent().parent().hide();
                        $('.cn-should-say').val('');
                        $('.cn-should-say').parent().parent().hide();
                        $('.btn-create-cn').show();
                        $('.cn-type').val(0);
                        if (response.data.client_name == "GENERIC" && response.data.type == 1){
                            $(".enterprise-data").hide();
                            $(".client-data").show();
                            with_client_data = 1;
                        }
                        doc_type = response.data.type;
                        doc_id   = response.data.id;
                        $('.show-document').val((doc_type == 1 ? 'Boleta' : 'Factura')+' '+response.data.folio);
                        item_details = [];
                        response.data.details.map( function(detail) {
                            item_details.push({
                                id    : detail.id,
                                qty   : detail.fix_qty,
                                qtyf  : detail.fix_qty,
                                name  : detail.item_name,
                                dsc   : detail.discount_percent,
                                price : detail.price 
                            });
                        });
                        renderTable();
                    }
                    else if(response.code == 404) {
                        item_details = [];
                        $('.show-client').val('');
                        $('.cn-reason').val('');
                        $('.cn-enterprise').val('0');
                        $('.cn-reason').attr('disabled', true);
                        $('.cn-enterprise').attr('disabled', true);
                        $('.btn-create-cn').hide();
                        $('.cn-where-say').val('');
                        $('.cn-where-say').parent().parent().hide();
                        $('.cn-should-say').val('');
                        $('.cn-should-say').parent().parent().hide();
                        $('.cn-type').val(0);
                        doc_type = 2;
                        doc_id = null;
                        item_details = [];
                        $('.show-document').val('');
                        renderTable();
                        swal('Error', response.data.error, 'error');
                    } else if(response.code == 401) {
                        item_details = [];
                        $('.show-client').val('');
                        $('.cn-reason').val('');
                        $('.cn-enterprise').val('0');
                        $('.cn-reason').attr('disabled', true);
                        $('.cn-enterprise').attr('disabled', true);
                        $('.btn-create-cn').hide();
                        $('.cn-where-say').val('');
                        $('.cn-where-say').parent().parent().hide();
                        $('.cn-should-say').val('');
                        $('.cn-should-say').parent().parent().hide();
                        $('.cn-type').val(0);
                        doc_type = 2;
                        doc_id = null;
                        item_details = [];
                        $('.show-document').val('');
                        renderTable();
                        swal('Error', response.data.error, 'error');
                    } else {
                        doc_id = null;
                    }
                });
            });

            $("#chkEliminado").click(function() {
                renderTable(true);
            });
        });
    </script>
@endsection