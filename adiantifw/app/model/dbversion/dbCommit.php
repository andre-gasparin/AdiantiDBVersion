<?php
//POR André Gasparin (pode tirar se quiser, não ligo mesmo kkkk)
class dbCommit extends TRecord
{
    const TABLENAME  = 'version_commits';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('id_version');
        parent::addAttribute('action');
        parent::addAttribute('target_type');
        parent::addAttribute('column');
        parent::addAttribute('tab');
        parent::addAttribute('code');
        parent::addAttribute('prefix');
        parent::addAttribute('sufix');
    }
}