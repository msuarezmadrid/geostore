@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-editable-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}">
@endsection

@section('content')
    <section class="content-header">
      <h1>
      <i class="glyphicon glyphicon-piggy-bank" style="padding-right: 5px;"></i> Conciliación
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Conciliación</li>
      </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">RUT : </label>
                                    <div class="col-sm-9">
                                        <select id="select_rut" class="form-control simple-select1-sm">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Cliente : </label>
                                    <div class="col-sm-9">
                                        <select id="select_client" class="form-control simple-select2-sm">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Tipo : </label>
                                    <div class="col-sm-9">
                                        <select id="doc_type" class="form-control">
                                            <option value="1">Boleta</option>
                                            <option value="2">Factura</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn pull-right btn-search btn-back">Volver</button>
                            <button type="button" style="margin-right:10px" class="btn pull-right btn-primary btn-search">Buscar</button>
                            <button type="button" style="margin-right:10px" class="btn pull-right  btn-clean">Limpiar</button>
                            <button type="button" style="margin-right:10px" class="btn pull-right btn-danger btn-conciliate">Conciliar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-primary">
                    <table class="table">
                        <theader>
                            <th>Tipo Documento</th>
                            <th>N.Doc</th>
                            <th>Fecha Emisión</th>
                            <th>Vencimiento</th>
                            <th>Monto Doc</th>
                            <th>Saldo Doc</th>
                            <th>Monto a Conciliar</th>
                        </theader>
                        <tbody class="table-content">
                        </tbody>
                    </table>
                    <div class="box-footer">
                        <table style="width:200px" class="table pull-right">
                            <tr>
                                <td> TOTAL :</td>
                                <td class="total_price"> $0</td>
                            </tr>
                            <tr>
                                <td> TOTAL SALDO :</td>
                                <td class="total_conciliation"> $0</td>
                            </tr>
                            <tr>
                                <td> TOTAL CONC :</td>
                                <td class="total_amount"> $0</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="box box-primary">
                    <table class="table">
                        <theader>
                            <th>Fecha</th>
                            <th>Método de Pago</th>
                            <th>Monto</th>
                            <th>Descripción</th>
                            <th>Acción</th>
                        </theader>
                        <tbody class="table-content-docs">
                        </tbody>
                    </table>
                    <div class="box-footer">
                        <table style="width:200px" class="table pull-right">
                            <tr>
                                <td> TOTAL CONC :</td>
                                <td class="total_cprice"> $0</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/awsidebar.js') }}"></script>
    <script src="{{ asset('js/jquery-editable-select.min.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script>
        var documents = [];
        var con_docs  = [];
        $(document).ready(function() {
            $( ".btn-conciliate" ).prop( "disabled", true );
            $('.simple-select1-sm').selectpicker({
                liveSearch:true,
                noneSelectedText: 'Seleccione un rut',
                noneResultsText: ''
            });
            $('.simple-select2-sm').selectpicker({
                liveSearch:true,
                noneSelectedText: 'Seleccione un cliente',
                noneResultsText: ''
            });
            $('.simple-select1-sm').find('.bs-searchbox').find('input').on('keyup', function(event) {
                if(event.keyCode != 38 && event.keyCode != 40) {
                    $('.simple-select1-sm').find('option').remove();
                    $('.simple-select1-sm').selectpicker('refresh');
                    AWApi.get('{{ url('/api/clients/search/') }}/rut/'+$(this).val(), function(response) {
                        if(response.code == 200) {
                            src1 = [{
                                id: null, txt:""
                            }];
                            src2 = [];
                            response.data.rut.map( (client) => {
                                src1.push({
                                    id:client.id,
                                    txt: (client.rut + '-' + client.rut_dv)
                                });
                            });

                            response.data.codigo_interno.map( (client) => {
                                src2.push({
                                    id:client.id,
                                    txt: client.internal_code
                                });
                            });
                            var options = [];
                            var option = '<optgroup label="Rut">';
                            src1.forEach(function (item) {
                                
                                option += "<option value="+item.id+" >" + item.txt + "</option>"
                                
                            });
                            option += '</optgroup>';

                            option += '<optgroup label="Rut chico">';
                            src2.forEach(function (item) {
                                
                                option += "<option value="+item.id+" >" + item.txt + "</option>"
                                
                            });
                            option += '</optgroup>';


                            options.push(option);
                            $('#select_rut').html(options);
                            $('.simple-select1-sm').selectpicker('refresh');
                        }
                    });
                }
            });
            $('.simple-select2-sm').find('.bs-searchbox').find('input').on('keyup', function(event) {
                if(event.keyCode != 38 && event.keyCode != 40) {
                    $('.simple-select2-sm').find('option').remove();
                    $('.simple-select2-sm').selectpicker('refresh');
                    AWApi.get('{{ url('/api/clients/search/') }}/name/'+$(this).val(), function(response) {
                        if(response.code == 200) {
                            src = [{
                                id: null, txt:""
                            }];
                            response.data.map( (client) => {
                                src.push({
                                    id:client.id,
                                    txt: client.name
                                });
                            });
                            var options = [];
                            src.forEach(function (item) {
                                var option = "<option value="+item.id+" >" + item.txt + "</option>"
                                options.push(option);
                            });
                            $('#select_client').html(options);
                            $('.simple-select2-sm').selectpicker('refresh');
                        }
                    });
                }
            });
            $('#select_rut').on('change', function() {
                if($(this).val() != '' && $(this).val() != 'null') {
                    AWApi.get('{{ url('/api/clients/') }}/'+$(this).val(), function(response) {
                        if(response.code == 200) {
                            $('.simple-select2-sm').find('option').remove();
                            src = [
                                { id: null, txt:"" }, 
                                { id: response.data.id, 
                                txt: response.data.name}];
                            var options = [];
                            src.forEach(function (item) {
                                var option = "<option value="+item.id+" >" + item.txt + "</option>"
                                options.push(option);
                            });
                            $('#select_client').html(options);
                            $('.simple-select2-sm').selectpicker('refresh');
                            $('.simple-select2-sm').selectpicker('val', response.data.id);
                            
                        }
                    });
                } else {
                    $('.simple-select2-sm').selectpicker('val', '');
                }
            });
            $("#select_client").on('change', function() {
                if($(this).val() != '' && $(this).val() != 'null') {
                    AWApi.get('{{ url('/api/clients/') }}/'+$(this).val(), function(response) {
                        if(response.code == 200) {
                            $('.simple-select1-sm').find('option').remove();
                            src = [
                                { id: null, txt:"" }, 
                                { id: response.data.id, 
                                txt: (response.data.rut + '-' + response.data.rut_dv)}];
                            var options = [];
                            src.forEach(function (item) {
                                var option = "<option value="+item.id+" >" + item.txt + "</option>"
                                options.push(option);
                            });
                            $('#select_rut').html(options);
                            $('.simple-select1-sm').selectpicker('refresh');
                            $('.simple-select1-sm').selectpicker('val', response.data.id);    
                        
                        }
                    });
                } else {
                    $('.simple-select1-sm').selectpicker('val', '');
                }
            });
            $('.btn-search').click(function() {
                   let client   = $('#select_client').val();
                   let docType  = $('#doc_type').val();
                   AWApi.get('{{ url('/api/sales/conciliated') }}/0/'+client+'/'+docType+'/0', function(response) {
                        if(response.code == 200) {
                            $('#select_client').prop( "disabled", true );
                            $( "#select_rut" ).prop( "disabled", true );
                            $( "#doc_type" ).prop( "disabled", true );
                            $( ".btn-conciliate" ).prop( "disabled", false );
                            $('.table-content').empty();
                            let rows = '';
                            documents = [];
                            response.data.docs.map(function(doc) {
                                let desc = doc.type == 1 ? 'Boleta Electrónica' : 'Factura Electrónica';
                                documents.push({
                                    id:             doc.id,
                                    total:          doc.total,
                                    conciliated:    doc.conciliated,
                                    amount:         0
                                });
                                rows += `
                                    <tr>
                                        <td>${desc}</td>
                                        <td>${doc.folio}</td>
                                        <td>${revertDate(doc.date)}</td>
                                        <td>${revertDate(doc.date)}</td>
                                        <td align="right">$${formatMoney(doc.total, 'CL')}</td>
                                        <td align="right">$${formatMoney((doc.total-doc.conciliated), 'CL')}</td>
                                        <td align="right"><input style="width:100px" onKeyUp="updatePrices(this, ${doc.id})" type="number" class="form-control form-control-sm" /></td>
                                    </tr>
                                `;
                            });
                            $('.table-content').append(rows);
                            $('.table-content-docs').empty();
                            con_docs = [];
                            rows = '';
                            response.data.con.map(function(doc) {
                                con_docs.push({
                                    id       : doc.id,
                                    amount   : doc.amount,
                                    selected : false 
                                });
                                let pay_meth ='';

                                switch (doc.payment_method){
                                    case 'cash': pay_meth = 'Efectivo'; break;
                                    case 'transfer': pay_meth = 'Transferencia'; break;
                                    case 'cheque': pay_meth = 'Cheque'; break;
                                    case 'card': pay_meth = 'Tarjeta'; break;
                                    default: pay_meth = 'Metodo de pago no registrado';
                                }
                                rows += `
                                    <tr>
                                        <td>${utcToLocal(doc.created_at)}</td>
                                        <td>${pay_meth}</td>
                                        <td align="right">$${formatMoney(doc.amount, 'CL')}</td>
                                        <td>${doc.description === null ? '-------' : doc.description}</td>
                                        <td align="center"><input onChange="updateCDoc(this, ${doc.id})" type="checkbox" /></td>
                                    </tr>
                                `;
                            });
                            $('.table-content-docs').append(rows);
                            renderPrices();
                        }
                   });
            });
            $('.btn-conciliate').click(function(){
                swal({
                title: "Conciliar Documentos",
                text: "¿Está seguro de realizar esta operación?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        if(documents.length == 0 || con_docs.length == 0) {
                            swal('Error!','Deben existir documentos a conciliar','error');
                            return;
                        }
                        let cdocs = [];
                        con_docs.map(function(doc) {
                            if (doc.selected == 1) {
                                cdocs.push(doc.id);
                            }
                        });
                        if (cdocs.length == 0) {
                            swal('Error!','Debe seleccionar uno o más documentos a conciliar','error');
                            return;
                        }
                        let docs = [];
                        documents.map(function(doc) {
                            if(doc.amount > 0) {
                                docs.push({
                                    id     : doc.id,
                                    amount : doc.amount
                                });
                            }
                        });
                        if (docs.length == 0) {
                            swal('Error!','Monto a conciliar debe ser mayor a 0','error');
                            return;
                        }
                        let formCon = new FormData();
                        formCon.append('client_id', $('#select_client').val());
                        formCon.append('conciliation_docs', JSON.stringify(cdocs));
                        formCon.append('documents', JSON.stringify(docs));
                        AWApi.post('{{ url('/api/conciliations') }}', formCon, function(response){
                            switch(response.code) {
                                case 200:
                                    swal('Correcto', "Conciliación creada de forma exitosa", "success").then( function(e) {
                                        if (e == true) window.location.href = "/concilliation";
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
                }
                });

            });
            $('.btn-back').click(function() {
                window.location.href = "/concilliation";
            });

            $('.btn-clean').click(function() {
                
                $('#doc_type').val('1');
                $('#select_client').prop( "disabled", false );
                $( "#select_rut" ).prop( "disabled", false );
                $( "#doc_type" ).prop( "disabled", false );
                $( ".btn-conciliate" ).prop( "disabled", true );
                $('.simple-select2-sm').selectpicker('refresh');
                $('.simple-select1-sm').selectpicker('refresh');
                $('.simple-select2-sm').selectpicker('val', '');
                $('.simple-select1-sm').selectpicker('val', '');
                con_docs  = [];
                documents = [];
                $('.table-content').empty();
                $('.table-content-docs').empty();
                renderCPrices();
                renderPrices();
            });
        });

        function updateCDoc(ths, docId) {
            for (x in con_docs) {
                if(con_docs[x].id == docId) {
                    con_docs[x].selected = ths.checked ? 1 : 0;
                    renderCPrices();
                }
            }
        }

        function updatePrices(ths, docId) {
            if(this.event.key == 'Enter') {
                for (x in documents) {
                    if(documents[x].id == docId) {
                        if($(ths).val() > (documents[x].total - documents[x].conciliated)) {
                            $(ths).val(0);
                            swal('Error!','Monto a conciliar no puede ser superior al saldo','error');
                            documents[x].amount = 0;
                            renderPrices();
                        } else {
                            documents[x].amount = $(ths).val();
                            renderPrices();
                        }
                    }
                }  
            }
        }

        function renderPrices() {
            let total  = 0;
            let conc   = 0;
            let amount = 0;
            documents.map(function(doc) {
                total  += doc.total;
                conc   += doc.conciliated;
                amount += parseInt(doc.amount);
            });
            $('.total_price').text('$'+formatMoney(total, 'CL'));
            $('.total_conciliation').text('$'+formatMoney((total-conc), 'CL'));
            $('.total_amount').text('$'+formatMoney(amount, 'CL'));
        }

        function renderCPrices() {
            let total = 0;
            con_docs.map(function(doc){
                if (doc.selected == 1) {
                    total += doc.amount;
                }
            });
            $('.total_cprice').text('$'+formatMoney(total, 'CL'));
        }

    </script>
@endsection