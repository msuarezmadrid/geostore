<?php namespace App\Enum;

/**
 * Interface to define Quote detail types
 */
interface CreditNoteTypes
{
    const TYPE_TOTAL   = 1;
    const TYPE_PARTIAL = 3;
    const TYPE_TEXT    = 2;
}