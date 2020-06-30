<?php
/**
 * @author André Gasparin 
 * version 1.1
 * @revision Fred Azevedo
 * @update 2020-06-27
 * **/

class DbVer extends TRecord
{
    const TABLENAME  = 'up_version';
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