@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
<link rel="stylesheet" href="https://unpkg.com/vue-form-wizard/dist/vue-form-wizard.min.css">
<link rel="stylesheet" href="https://rawgit.com/lykmapipo/themify-icons/master/css/themify-icons.css">

<style type="text/css">
  div.scroll
{
height:300PX;
FLOAT: left;
padding: 1%;
overflow:scroll;
}
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 50%;
  margin: 0px auto;
  height: auto;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
.card {
  /* Add shadows to create the "card" effect */
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
}

/* On mouse-over, add a deeper shadow */
.card:hover {
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}

/* Add some padding inside the card container */
.container {
 
}
[v-cloak] {
    display: none;
  }

.centrado {
  text-align: center;
  align-content: center;
}

.ticket {
  width: 155px;
  max-width: 155px;
}

img {
  max-width: inherit;
  width: inherit;
}

@media print{
  .oculto-impresion, .oculto-impresion *{
    display: none !important;
  }
}
</style>
@endsection


@section('content')

<div id="main_vue">
  <!--TICKET-->
  <div id ="ticket" class="ticket" hidden>
    <svg id="barcode" style=" text-align: center;
  align-content: center;" class="img centrado"></svg>
    <p class="centrado">TICKET DE CAMBIO
      <br>Osorno
      <br>19/04/2019 12:22 p.m.</p>
    <p class="centrado">¡CONSERVE ESTE TICKET!
      <br>www.holitademar.ck</p>
  </div>
  <!-- END TICKET-->
  <!-- use the modal component, pass in the prop -->
  <modal v-if="showModalClient" @close="showModalClient = false">
  </modal>
 <section class="content-header">
      <h1>
        <i class="fa ti-shopping-cart" style="padding-right: 5px;"></i> Entrada
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Punto de Venta</li>
      </ol>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-7">
                

              <div class="box box-primary flat box-solid">


                <div class="box-header">
                 <multiselect v-model="multiselectValue" :options="items"  ref="search" @remove ="dispatchAction" @select="dispatchAction" placeholder="CÓDIGO/PRODUCTO" label="sku_name" track-by="name" :searchable="true" :show-labels="false" :loading="isLoading" :internal-search="false" v-on:keyup.native.enter="onEnterClick" :options-limit="300" :limit="3"  :max-height="600" :show-no-results="false"  @search-change="asyncFind"

                 >
                 </multiselect>

               </div>
               <!-- /.box-header -->
               <div class="box-body">
                <div class="btn-group">

                </div>
                <transaction :items="lineItems" :edit="toggleEdit" :remove="removeItem" :add="onItemClick" :discounts="discounts"></transaction>
                <div class="row">
                <div class="col-xs-12 col-md-6">
                 <div  v-cloak><p v-if="clientSelect">Cliente asociado : @{{ clientSelect }}</p></div>
                 <div class="col-xs-12 col-md-6"> <v-select :options="discounts" label="percent"  style = "font-size: 150%" placeholder="%">
                  <template slot="options" slot-scope="options">
                      (@{{ option.percent }}%) @{{ option.name }}
                  </template>
                </v-select></div>
                </div>
                <div class="col-xs-12 col-md-6">
                <final-transaction :items="lineItems" :edit="toggleEdit" :remove="removeItem" :add="onItemClick" ></final-transaction>
               </div>
              </div>
              </div>


            </div>
            <!-- /.box -->
            </div>
            <div class="col-xs-12 col-md-5">
             <div class="box box-primary flat box-solid">

                <div class="box-header">
                    <i class="fa ti-shopping-cart"></i><h3 class="box-title">Detalles de venta</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body"  v-cloak>
                    <form-wizard @on-complete="onComplete"  next-button-text="Siguiente" back-button-text="Volver" finish-button-text="Confirmar Pago" step-size ="xs" color="#3c8dbc"  error-color="#ff4949" title="" subtitle="" 
                    >
                      <tab-content icon="ti-shopping-cart-full" :before-change="validateFirstStep" >
                        <row >
                        <div ><label for="location_id">Tipo de Documento</label>
                        <v-select :options="paymentTypes" v-model="documentType" placeholder="Selecciona Documento"></v-select></div>
                        <div  v-if="documentType"><label for="location_id">Cliente Asociado</label>
                       <v-select :options="clients" v-model="clientSelect" placeholder="Seleccionar Cliente"></v-select><i class="fa fa-user-plus fa-2x" style="color:#3c8dbc" id="show-modal"  @click="showModalClient = true"></i></div>
                       </row>
                       <br>
                     </tab-content>
                     <tab-content title="Pagar" icon="ti-check" :before-change="validatePay" >
                       <row>
                       <div>
                       <label for="location_id">Selecciona Vendedor</label>
                       <v-select :options="sellers" label="name" v-model="seller" placeholder="Selecciona Vendedor" ></v-select></div>
                       <div v-if="seller"><label for="location_id">Forma de Pago</label>
                       <v-select :options="waytoPay"  v-model="paymentType" placeholder="Medio de Pago"></v-select></div>
                      <div v-if="seller&&paymentType"><label for="location_id">Monto de Forma de Pago</label>
                      <input class="form-control" v-model.number="paidTotal"  type="number" placeholder="cantidad pagada" ></div>
                     <div><final-transaction-user :items="lineItems" :input_total_paid ="paidTotal"  :edit="toggleEdit" :remove="removeItem" :add="onItemClick"  ></final-transaction-user></div>
                     <row>
                    </tab-content>
                  </form-wizard>
                </div>
            </div>
            <!-- /.box -->

            </div>
        </div>
    </section>
  </div>
<!--MODAL CLIENT-->
  <script type="text/x-template" id="modal-template">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="box box-primary flat box-solid">
                <div class="box-header">
                    <i class="fa fa-user"></i><h3 class="box-title">Nuevo Cliente</h3>
                </div>
             <!-- /.box-header -->
             <div class="box-body">
                  <div class="row">
                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="code">Rut:</label>
                          <input class="form-control" v-model="rut" placeholder="Ingrese Rut">
                      </div>
                  </div>

                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="date">Razon Social</label>
                           <input class="form-control" v-model="rut" placeholder="Ingrese Rut">
                      </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="location_id">Email</label>
                           <input class="form-control" v-model="rut" placeholder="Ingrese Rut">   
                      </div>
                  </div>
                   <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="client_id">Giro</label>
                           <input class="form-control" v-model="rut" placeholder="Ingrese Rut">  
                      </div>
                  </div>
                </div>
                 <div class="row">
                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="location_id">Dirección</label>
                           <input class="form-control" v-model="rut" placeholder="Ingrese Rut">   
                      </div>
                  </div>
                   <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="client_id">Ciudad</label>
                           <input class="form-control" v-model="rut" placeholder="Ingrese Rut">  
                      </div>
                  </div>
                </div>
                 <div class="row">
                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="location_id">Comuna</label>
                           <input class="form-control" v-model="rut" placeholder="Ingrese Rut">   
                      </div>
                  </div>
                   <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="client_id">Días de pago</label>
                           <input class="form-control" v-model="rut" placeholder="Ingrese Rut">  
                      </div>
                  </div>
                </div>
                 <div class="row">
                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="location_id">Días de vencimiento</label>
                           <input class="form-control" v-model="rut" placeholder="Ingrese Rut">   
                      </div>
                  </div>
                </div>
              <div >
              </div>
            </div>
            <div class="box-footer">
                  <button type="button" class="btn btn-primary pull-left" @click="$emit('close')" >Cancelar</button>
                  <button-v :sale ="onSale">Crear</button-v>
              </div>    

          </div>
        </div>
      </div>
    </transition>
  </script>
  <!--END MODAL CLIENT-->

  <!-- TABLE SALE-->
   <script type="text/x-template" id="tbl_sale">
    <div class="col-md-12 scroll">
      <table class="table">
        <tbody> 
          <tr v-for="item in items" :key="item.name" style="border-collapse:separate; 
            border-spacing:1px;" class="card">
            <div class="container-fluid ">
            <div class="row">
              <div class="col-md-12 ">
                <div class="row">
                  <div class="col-md-1">
                    <div class="row">
                      <div class="col-md-12">
                        <a class="fa fa-plus-circle fa-2x" @click="itemClicked(item.item)"/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <a class="fa fa-minus-circle fa-2x" @click="removeItem(item)"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <span  class ="card" style="font-size: 250%;" v-if="!item.editing" @dblclick="toggleEdit(item)">@{{ item.numberOfItems }}</span>
                    <input v-if="item.editing"  style="font-size: 250%;" @blur="toggleEdit(item)" type="number" v-model="item.numberOfItems">
                  </div>
                  <div class="col-md-2">
                   <v-select :options="discounts"  label="percent" placeholder="%" style = "font-size: 150%">
                     <template slot="option" slot-scope="option">
                      (@{{ option.percent }}%) @{{ option.name }}
                     </template>
                   </v-select>
                  </div>
                  <div class="col-md-7">
                    <div class="row">
                      <div class="col-md-12">
                      @{{ item.item.name }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                       $/unidad: $@{{item.item.item_prices[0].price}}
                      </div>
                      <div class="col-md-6">
                         totalxProducto: $@{{item.numberOfItems * item.item.item_prices[0].price }}
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </tr>
        </tbody>
      </table>
      <p v-if="!items.length">No hay productos ingresados.</p></div>
  </script>

 <!-- <script type="text/x-template" id="ticket"></script>-->
  <!--END TABLE SALE-->
</div>


@endsection

@section('js')

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="{{ asset('js/api.js') }}"></script>
<script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
<!-- include VueJS first -->
<script src="https://unpkg.com/vue@latest"></script>

<!-- use the latest release -->
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">

<link rel="stylesheet" href="https://unpkg.com/vue-form-wizard/dist/vue-form-wizard.min.css">

<script src="https://unpkg.com/vue-form-wizard/dist/vue-form-wizard.js"></script>
<script src="{{ asset('js/JsBarcode.all.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

<script type="text/javascript">

Vue.component('modal', {
  template: '#modal-template'
})


Vue.component('button-v', {
  template: '<button class="btn btn-primary pull-right" @click="callback($event)"><slot></slot></button>',
  props:["sale"],
  methods: {
    callback: function(e) {
      this.sale(e);
    }
  }
});



Vue.component('transaction', {
  props: ["items", "edit", "remove","add","discounts"],
  template: '#tbl_sale',
   computed: {
    subtotal: function() {
      var subtotal = 0;
      this.items.forEach(function(item) {
        var item_prices = item.item.item_prices[0].price;
        subtotal += item_prices * item.numberOfItems;
      });
      return subtotal;
    },
    tax: function() {
      return this.subtotal * 0.19;
    },
    total: function() {
      return this.subtotal + this.tax;
    }
  },components: {
    'v-select': VueSelect.VueSelect
  },
  methods: {
    toggleEdit: function(item) {
      this.edit(item);
    },
    removeItem: function(item) {
      this.remove(item);
    },itemClicked:function(item) {
      this.add(item);
    }

  }

});


 Vue.component('final-transaction', {
  props: ["items", "edit"],
  template: '<div class="col-md-12"><table class="table"><tbody><tr ><td>Neto:</td><td>$@{{ subtotal }}</td></tr><tr><td>Impuesto:</td><td>$@{{ tax }}</td></tr><tr class="card"><td>Total:</td><td>$@{{ total }}</td></tr></tbody></table></div>',
   computed: {
    subtotal: function() {
      var subtotal = 0;
      this.items.forEach(function(item) {

        var item_prices = item.item.item_prices[0].price;
        subtotal += item_prices  * item.numberOfItems;
      });
      return subtotal;
    },
    tax: function() {
      return this.subtotal * 0.19;
    },
    total: function() {
      return this.subtotal + this.tax;
    }
  },
  methods: {
    toggleEdit: function(item) {
      this.edit(item);
    },
    removeItem: function(item) {
      this.remove(item);
    },itemClicked:function(item) {
      this.add(item);
    }

  }

});

 Vue.component('final-transaction-user', {
  props: ["items", "edit","input_total_paid"],
  template: '<div class="col-md-12 "><div class="col-md-12" ><table class="table"><tbody><tr><td >Total a Pagar:</td><td >$@{{ total }}</td></tr><tr><td>Total Pagado:</td><td>$@{{ totalPaid }}</td></tr><tr v-if="input_total_paid > 0"><td>Vuelto:</td><td>$@{{ turned }}</td></tr></tbody></table></div></div>',
   computed: {
    subtotal: function() {
      var subtotal = 0;
      this.items.forEach(function(item) {
        var item_prices = item.item.item_prices[0].price;
        subtotal += item_prices * item.numberOfItems;
      });
      return subtotal;
    },
    tax: function() {
      return this.subtotal * 0.19;
    },
    total: function() {
      return this.subtotal + this.tax;
    },
    totalPaid: function() {
      var paid = 0;
      if(this.input_total_paid != 0 && this.input_total_paid != null){
           paid = this.input_total_paid;
      }

      return paid;
    },
    turned: function() {
      return this.input_total_paid-this.total;
    }
  },
  methods: {
    toggleEdit: function(item) {
      this.edit(item);
    },
    removeItem: function(item) {
      this.remove(item);
    },itemClicked:function(item) {
      this.add(item);
    }

  }

});


var app7 = new Vue({
  el: '#main_vue',
  data: {
    showModalClient: false,
    clientSelect:null,
    message:null,
    multiselectValue: null,
    items:[],
    lineItems:[],
    sellers:[],
    seller:null,
    paidTotal: null,
    paymentType:null,
    documentType:null,
    waytoPay:[
    'Efectivo'
    ],
    discounts:[],
    paymentTypes: [
      'Boleta',
      'Factura',
      'Factura Exenta',
      'Factura Afecta'
    ],clients: [
      'Entel pcs',
      'allware',
      'chileexpress',
      'correo de chile',
      'ripley',
      'la polar'
    ],
    isLoading: false,
  },components: {
    Multiselect: window.VueMultiselect.default,
    'v-select': VueSelect.VueSelect,
    'vue-form-wizard':Vue.use(VueFormWizard)
  },
  mounted () {
    this.setFocus();
    AWApi.get('{{ url('/api/discount') }}', this.loadDiscount);
    AWApi.get('{{ url('/api/users') }}', this.loadSellers);
   /* axios
      .get('http://192.241.171.29/index.php/requests/contributors/contributor/77407770-7')
      .then(response => (this.info = response));*/
    
   },
   methods: {
    onEnterClick: function() {
      this.$refs.search.$el.focus();
    },
    setFocus: function() {
      // Note, you need to add a ref="search" attribute to your input.
      //this.$refs.search.$el.focus();
      //
      this.$nextTick(() => {
      this.$refs.search.$el.focus();
      /*this.$data.multiselectValue= "";*/});
    },
    loadSellers:function(response){

       this.sellers  = response.data.users;
    },
    loadDiscount:function(response){
      this.discounts  = response.data.discounts;
    },
    loadItems:function(response){
      this.items = response.data.items;
      this.isLoading = false;
    },dispatchAction (actionName) {



      this.onItemClick(actionName);
    },asyncFind (query) {
      this.isLoading = true
      var data = new Object();
      var filters = new Object();
      filters.name = query;
      data.filters = filters;
      AWApi.get('{{ url('/api/postitems') }}?'+$.param(data), this.loadItems)
      
    },
    onItemClick: function(item) {
      console.log("in", item);
      var found = false;
      for (var i = 0; i < this.lineItems.length; i++) {
        if (this.lineItems[i].item.id === item.id) {
          this.lineItems[i].numberOfItems++;
          found = true;
          break;
        }
      }
      if (!found) {
        this.lineItems.push({ item: item, numberOfItems: 1, editing: false });
      }

       //this.setFocus();

    },
    toggleEdit: function(lineItem) {
      //lineItem.editing = !lineItem.editing;
    },
    removeItem: function(lineItem) {
      for (var i = 0; i < this.lineItems.length; i++) {
        if (this.lineItems[i] === lineItem) {
          if(this.lineItems[i].numberOfItems == 1){
            this.lineItems.splice(i, 1);
            break;
          }
          this.lineItems[i].numberOfItems--;
          break;
        }
      }
    },onSale: function(e){
      for (var i = 0; i < this.lineItems.length; i++) {
         //console.log("Producto:"+this.lineItems[i].item.name+" "+"Cantidad:"+this.lineItems[i].numberOfItems+" ");
      }
    },onComplete: function(){

      if (this.lineItems.length > 0) {
         subtotal = 0;
         tax = 0;
         total = 0;

         for (var i = 0; i < this.lineItems.length; i++) {
           var item_prices = this.lineItems[i].item.item_prices[0].price;
           subtotal += item_prices * this.lineItems[i].numberOfItems;
         }
         tax = subtotal * 0.19;
         total = tax + subtotal;

         var sale_detail = {document_type:this.documentType, client_id:1,seller_id:this.seller.id, payment_type:1,net:subtotal,tax:tax,total:total,date:moment().format("YYYY-MM-DD")};

         createPostSale(this.lineItems,sale_detail);
         //CLEAR DATA
         this.$data.paidTotal = "";
         this.$data.clientSelect = null;
         this.$data.lineItems = [];
         this.$data.seller = null;
         this.$data.paidTotal = null;
         this.$data.paymentType = null;
         this.$data.documentType = null;
         this.$data.multiselectValue = null;

       }else{
         alert("Ingrese productos a compra");
      }
       /* this.$nextTick(() => {
                 JsBarcode("#barcode", Math.floor(Math.random() * 10000));
                 createTicket();
             });*/
    },validateFirstStep() {
           return new Promise((resolve, reject) => {
               this.documentType?resolve(true):resolve(false);
               //this.clientSelect?resolve(true):resolve(false);
               ;

           })

         }
    ,validatePay() {
           return new Promise((resolve, reject) => {
              (this.lineItems.length > 0)?resolve(true):resolve(false);
               //this.clientSelect?resolve(true):resolve(false);
               ;

           })

         }

  }
});

 function createPostSale(items_sale,sale_detail){

   var data = new FormData();
   data.append('items_sale', JSON.stringify(items_sale));
   data.append('sale_detail',JSON.stringify(sale_detail));
   AWApi.post('{{ url('/api/pos') }}', data, function(response){
      if(response.code == 200){
        alert("Venta Realizada Exitosamente");
      }
      
    });
 }

function createTicket(){

          var printContent = document.getElementById("ticket");
          var WinPrint = window.open('', '', 'width=900,height=650');
          WinPrint.document.write('<html><head><title>Print Invoice</title>');
          // Make sure the relative URL to the stylesheet works:
          WinPrint.document.write('<base href="' + location.origin + location.pathname + '">');
           // Add the stylesheet link and inline styles to the new document:
          WinPrint.document.write('<link rel="stylesheet" href="css/invoice.css">');
          WinPrint.document.write('<style type="text/css">.centrado {text-align: center; align-content: center; font-size: 11px; font-family:"Courier New"}.ticket {width: 155px;max-width: 155px; font-family:"Courier New"}img {max-width: inherit;width: inherit; font-family:"Courier New"; font-size: 10px}</style>');
          WinPrint.document.write('</head><body >');
          WinPrint.document.write(printContent.innerHTML);
          WinPrint.document.write('</body></html>');
          WinPrint.document.close();
          WinPrint.document.close();
          setTimeout(function () {
              WinPrint.print();
          }, 500);
          return false;
}

</script>


@endsection