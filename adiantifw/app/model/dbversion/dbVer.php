<?php
//POR André Gasparin (pode tirar se quiser, não ligo mesmo kkkk)
class dbVer extends TRecord
{
    const TABLENAME  = 'version';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('version');
        parent::addAttribute('code');
        parent::addAttribute('permission');
    }
}