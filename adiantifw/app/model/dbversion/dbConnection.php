<?php
//POR André Gasparin (pode tirar se quiser, não ligo mesmo kkkk)
class dbConnection extends TRecord
{
    const TABLENAME  = 'connection';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial'; // {max, serial}

    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('version');
        parent::addAttribute('name');
    }
}
