<?php namespace App\Enum;

/**
 * Interface to define Quote detail types
 */
interface TransactDetails
{
    const SALE_BOX_OPEN           = 1;
    const BALANCE_ADD             = 2;
    const SALE_BOX_CLOSE          = 3;
    const SALE_BOX_TICKET_CASH    = 4;
    const SALE_BOX_TICKET_CARD    = 5;
    const SALE_BOX_TICKET_CHEQUE  = 6;
    const SALE_BOX_INVOICE_CASH   = 7;
    const SALE_BOX_INVOICE_CARD   = 8;
    const SALE_BOX_INVOICE_CHEQUE = 9;
    const SALE_BOX_TICKET_INTERN  = 10;
    const SALE_BOX_INVOICE_INTERN = 11;
    const SALE_BOX_DIFF_TICKET    = 12;
    const SALE_BOX_DIFF_INVOICE   = 13;
    const SALE_BOX_CREDIT_NOTE    = 14;
    const SALE_BOX_TICKET_APP = 15;
    const SALE_BOX_INVOICE_APP = 16;
    const SALE_BOX_TICKET_TRANSFER = 17;
    const SALE_BOX_INVOICE_TRANSFER = 18;
}