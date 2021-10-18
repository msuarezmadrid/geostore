<?php

namespace App;

use DB;
use App\Enum\TransactDetails;
use Illuminate\Database\Eloquent\Model;

class SaleBoxDetail extends Model
{
      protected $fillable = ['seller', 
                             'sale_box_id', 
                             'transact_id',
                             'type', 
                             'amount', 
                             'doc_id', 
                             'observations'];

      public function getPaginated(array $filters = [], int $start = 0, int $limit = 10, string $orderField = 'id', string $orderDirection = 'DESC') : array
            {
                  $query = DB::table('sale_box_details AS o')
                               ->select('o.*');
                  if (array_key_exists('transact_id', $filters))
                  {
                        $query = $query->where('o.transact_id', '=', $filters['transact_id']);
                  }
                  $count = $query->count();
                  $query = $query->orderBy($orderField, $orderDirection)
                                                ->offset($start)
                                                ->limit($limit);
                  return [
                        'filtered' => $count ,
                        'rows' => $query->get(),
                        'total' => DB::table('sale_box_details')->count(),
                  ];
            }     
      public function getResume($transactionId) {
            $resumes = [
                  'total_box'    => 0,
                  'total_cash'   => 0,
                  'total_card'   => 0,
                  'total_cheque' => 0
            ];
            $resumes['total_box'] = DB::table('sale_box_details')
                                       ->where('transact_id', $transactionId)
                                       ->whereIn('type', [
                                          TransactDetails::BALANCE_ADD,
                                          TransactDetails::SALE_BOX_TICKET_CASH,
                                          TransactDetails::SALE_BOX_INVOICE_CASH,
                                          TransactDetails::SALE_BOX_CREDIT_NOTE,
                                          TransactDetails::SALE_BOX_DIFF_TICKET,
                                          TransactDetails::SALE_BOX_DIFF_INVOICE
                                       ])
                                       ->sum('sale_box_details.amount');
            $resumes['total_cash'] = DB::table('sale_box_details')
                                       ->where('transact_id', $transactionId)
                                       ->whereIn('type', [
                                          TransactDetails::SALE_BOX_TICKET_CASH,
                                          TransactDetails::SALE_BOX_INVOICE_CASH
                                       ])
                                       ->sum('sale_box_details.amount');
            $resumes['total_card'] = DB::table('sale_box_details')
                                       ->where('transact_id', $transactionId)
                                       ->whereIn('type', [
                                          TransactDetails::SALE_BOX_TICKET_CARD,
                                          TransactDetails::SALE_BOX_INVOICE_CARD
                                       ])
                                       ->sum('sale_box_details.amount');
            $resumes['total_cheque'] = DB::table('sale_box_details')
                                       ->where('transact_id', $transactionId)
                                       ->whereIn('type', [
                                          TransactDetails::SALE_BOX_TICKET_CHEQUE,
                                          TransactDetails::SALE_BOX_INVOICE_CHEQUE
                                       ])
                                       ->sum('sale_box_details.amount');
            $resumes['total_rounding'] = DB::table('sale_box_details')
                                       ->where('transact_id', $transactionId)
                                       ->whereIn('type', [
                                          TransactDetails::SALE_BOX_DIFF_TICKET,
                                          TransactDetails::SALE_BOX_DIFF_INVOICE
                                       ])
                                       ->sum('sale_box_details.amount');

            $resumes['total_app'] = DB::table('sale_box_details')
                                       ->where('transact_id', $transactionId)
                                       ->whereIn('type', [
                                          TransactDetails::SALE_BOX_TICKET_APP,
                                          TransactDetails::SALE_BOX_INVOICE_APP
                                       ])
                                       ->sum('sale_box_details.amount');
                                       
            $resumes['total_transfer'] = DB::table('sale_box_details')
                                       ->where('transact_id', $transactionId)
                                       ->whereIn('type', [
                                          TransactDetails::SALE_BOX_TICKET_TRANSFER,
                                          TransactDetails::SALE_BOX_INVOICE_TRANSFER
                                       ])
                                       ->sum('sale_box_details.amount');

            $resumes['total_credit_note'] = DB::table('sale_box_details')
                                       ->where('transact_id', $transactionId)
                                       ->whereIn('type', [
                                          TransactDetails::SALE_BOX_CREDIT_NOTE
                                       ])
                                       ->sum('sale_box_details.amount');

            //Ingresos en efectivo, si es necesario que sean ingresos totales descomentar cheques, y tarjeta.
            $resumes['total_income'] = DB::table('sale_box_details')
                                       ->where('transact_id', $transactionId)
                                       ->where('amount', '>', 0)
                                       ->whereIn('type', [
                                          TransactDetails::SALE_BOX_TICKET_CASH,
                                          // TransactDetails::SALE_BOX_TICKET_CARD,
                                          TransactDetails::SALE_BOX_INVOICE_CASH,
                                          // TransactDetails::SALE_BOX_INVOICE_CARD,
                                          TransactDetails::SALE_BOX_DIFF_TICKET,
                                          TransactDetails::SALE_BOX_DIFF_INVOICE,
                                          // TransactDetails::SALE_BOX_TICKET_CHEQUE,
                                          // TransactDetails::SALE_BOX_INVOICE_CHEQUE
                                       ])
                                       ->sum('sale_box_details.amount');

            $balanceIncome = DB::table('sale_box_details')
                                       ->where('transact_id', $transactionId)
                                       ->where('amount', '>', 0)
                                       ->whereIn('type', [
                                          TransactDetails::BALANCE_ADD
                                       ])
                                       ->where('observations', '<>', '')
                                       ->sum('sale_box_details.amount');

            $resumes['total_income'] += $balanceIncome;

            $resumes['total_expenses'] = DB::table('sale_box_details')
                                       ->where('transact_id', $transactionId)
                                       ->where('amount', '<', 0)
                                       ->whereIn('type', [
                                          TransactDetails::BALANCE_ADD,
                                          TransactDetails::SALE_BOX_TICKET_CASH,
                                          TransactDetails::SALE_BOX_INVOICE_CASH,
                                          TransactDetails::SALE_BOX_DIFF_TICKET,
                                          TransactDetails::SALE_BOX_DIFF_INVOICE,
                                       ])
                                       ->sum('sale_box_details.amount');
            
                                       
            $resumes['total_intern'] = DB::table('sale_box_details')
                                       ->where('transact_id', $transactionId)
                                       ->whereIn('type', [
                                          TransactDetails::SALE_BOX_TICKET_INTERN,
                                          TransactDetails::SALE_BOX_INVOICE_INTERN
                                       ])
                                       ->sum('sale_box_details.amount');

            $resumes['total_ticket'] = DB::table('sale_box_details')
            ->where('transact_id', $transactionId)
            ->whereIn('type', [
               TransactDetails::SALE_BOX_TICKET_INTERN,
               TransactDetails::SALE_BOX_TICKET_CARD,
               TransactDetails::SALE_BOX_TICKET_CASH,
               TransactDetails::SALE_BOX_TICKET_CHEQUE,
               TransactDetails::SALE_BOX_TICKET_APP,
               TransactDetails::SALE_BOX_TICKET_TRANSFER
            ])
            ->sum('sale_box_details.amount');
            
            $resumes['total_invoice'] = DB::table('sale_box_details')
            ->where('transact_id', $transactionId)
            ->whereIn('type', [
               TransactDetails::SALE_BOX_INVOICE_INTERN,
               TransactDetails::SALE_BOX_INVOICE_CARD,
               TransactDetails::SALE_BOX_INVOICE_CASH,
               TransactDetails::SALE_BOX_INVOICE_CHEQUE,
               TransactDetails::SALE_BOX_INVOICE_APP,
               TransactDetails::SALE_BOX_INVOICE_TRANSFER
            ])
            ->sum('sale_box_details.amount');
            //Egresos en efectivo, si es necesario que sean egresos totales descomentar suma de nota de credito.
            //$resumes['total_expenses'] += $resumes['total_credit_note'];
            $resumes['total_calculated'] = $resumes['total_income'] + $resumes['total_expenses'];
            

            //Caja chica por movimientos de balance de caja
            //Total de caja chica
            $resumes['smallbox'] = DB::table('sale_box_details')
                                       ->where('transact_id', $transactionId)
                                       ->whereIn('type', [
                                          TransactDetails::BALANCE_ADD
                                       ])
                                       ->sum('sale_box_details.amount');

            $resumes['sales_total'] = $resumes['total_cash'] 
            + $resumes['total_card']
            + $resumes['total_cheque']
            + $resumes['total_rounding']
            + $resumes['total_app']
            + $resumes['total_transfer']
            + $resumes['total_credit_note'];
            return $resumes;                               
      }                  
}
