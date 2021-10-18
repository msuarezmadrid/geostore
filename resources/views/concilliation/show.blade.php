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
      <i class="glyphicon glyphicon-piggy-bank" style="padding-right: 5px;"></i> Conciliación : {{ $id }}
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Conciliación ID : {{ $id }}</li>
      </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Nombre cliente : </label>
                                    <div class="col-sm-9">
                                        <input class="form-control" disabled value="{{ $client->name }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">RUT : </label>
                                    <div class="col-sm-9">
                                    <input id="select_client" class="form-control" disabled value="{{ $client->rut.'-'.$client->rut_dv }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn pull-right btn-search btn-back">Volver</button>
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
                            <th>Conciliado</th>
                            <th>Saldo Actual</th>
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
            loadData();
            $('.btn-back').click(function() {
                window.location.href = "/concilliation";
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

        function loadData(){

                   let client   = '{{ $client->id }}'
                   let docType  = '{{ $documents[0]->type }}';
                   AWApi.get('{{ url('/api/sales/conciliated') }}/'+'{{ $id }}'+'/'+client+'/'+docType+'/1', function(response) {
                        if(response.code == 200) {
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
                                        <td align="right">$${formatMoney((doc.amount), 'CL')}</td>
                                        <td align="right">$${formatMoney((doc.total-doc.conciliated), 'CL')}</td>
                                    </tr>
                                `;
                            });
                            $('.table-content').append(rows);
                            $('.table-content-docs').empty();
                            con_docs = [];
                            rows = '';
                            response.data.con.map(function(doc) {
                                console.log(doc);
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
                                    </tr>
                                `;
                            });
                            $('.table-content-docs').append(rows);
                            renderPrices();
                            renderCPrices();
                        }
                   });
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
                    total += doc.amount;
            });
            $('.total_cprice').text('$'+formatMoney(total, 'CL'));
        }

    </script>
@endsection