<?php

namespace App\Libraries;

use Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Enum\TransactDetails;

class Utils {
    const TIME_ZONE = 'America/Santiago'; 

    public function UTCToLocal($date, $format = 'd-m-Y', $type = 'Y-m-d', $timezone = 'America/Santiago') {
        try{
			$date = Carbon::createFromFormat($type, $date, 'UTC')
			->setTimezone($timezone)
			->format($format);
			return $date;
        }
        catch(\Exception $e) {
			Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
			throw $e;
        }

    }

    public static function utcToLocalString($time,$timezone = '') {
		if ($timezone == '') {
			$timezone = self::TIME_ZONE;
		}
		return Carbon::parse($time)->setTimezone($timezone)->format('d-m-Y H:i:s');
	}


    public static function getTransactString(int $type) {
		switch ($type) {
			case TransactDetails::SALE_BOX_OPEN  : return 'Apertura de Caja'; break;
			case TransactDetails::BALANCE_ADD    : return 'Agregar saldo';   break;
			case TransactDetails::SALE_BOX_CLOSE : return 'Cierre de Caja'; break;
			case TransactDetails::SALE_BOX_TICKET_CASH : return 'Venta boleta con efectivo'; break;
			case TransactDetails::SALE_BOX_TICKET_CARD : return 'Venta boleta con tarjeta'; break;
			case TransactDetails::SALE_BOX_TICKET_CHEQUE : return 'Venta boleta con cheque'; break;
			case TransactDetails::SALE_BOX_INVOICE_CASH : return 'Venta factura con efectivo'; break;
			case TransactDetails::SALE_BOX_INVOICE_CARD : return 'Venta factura con tarjeta'; break;
			case TransactDetails::SALE_BOX_INVOICE_CHEQUE : return 'Venta factura con cheque'; break;
			case TransactDetails::SALE_BOX_TICKET_INTERN  : return 'Venta boleta con crédito interno'; break;
			case TransactDetails::SALE_BOX_INVOICE_INTERN : return 'Venta factura con crédito interno'; break;
			case TransactDetails::SALE_BOX_DIFF_TICKET    : return 'Venta boleta con pago mixto'; break;
			case TransactDetails::SALE_BOX_DIFF_INVOICE   : return 'Venta factura con crédito mixto'; break;
			case TransactDetails::SALE_BOX_TICKET_APP : return 'Venta boleta con pago por app'; break;
			case TransactDetails::SALE_BOX_INVOICE_APP : return 'Venta factura con pago por app'; break;
			case TransactDetails::SALE_BOX_TICKET_TRANSFER : return 'Venta boleta con pago por transferencia'; break;
			case TransactDetails::SALE_BOX_INVOICE_TRANSFER : return 'Venta factura con pago por transferencia'; break;
		}
	}

	public static function cleanString($str) {
		$str = str_replace(
			array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
			array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
			$str
		);
	 
			//Reemplazamos la E y e
			$str = str_replace(
			array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
			array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
			$str 
		);
	 
			//Reemplazamos la I y i
			$str = str_replace(
			array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
			array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
			$str 
		);
	 
			//Reemplazamos la O y o
			$str = str_replace(
			array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
			array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
			$str
		 );
	 
			//Reemplazamos la U y u
			$str = str_replace(
			array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
			array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
			$str 
		);
	 
			//Reemplazamos la N, n, C y c
			$str = str_replace(
			array('Ñ', 'ñ', 'Ç', 'ç'),
			array('N', 'n', 'C', 'c'),
			$str
		);
		Log::info($str);
		$str = preg_replace('([^A-Za-z0-9 ])', '', $str);
		Log::info($str);
		return $str;
	}


}