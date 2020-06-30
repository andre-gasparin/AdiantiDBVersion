<?php
//POR André Gasparin (pode tirar se quiser, não ligo mesmo kkkk)
class dbVersionQuery extends TPage
{
    private $db;
    private $debug;
    private $tab;
    private $code;
    static  $dbver = 'dbversion';
    private $createcolumn = array();
    private $editcolumn = array();
    private $pk = array();
    
 

    public function __construct()
    {
        parent::__construct();
    }
    public function setTab($tab){
        $this->tab = $tab;
    }
    public function setDb($db){
        $this->db = $db;
    }
    public function setDebug($debug){
        $this->debug = $debug;
    }
    public function setCode($code){
        $this->code = $code;
    }

 
    //Versão 2.0, implementação de insert com rollback, o código sql será gerado pela classe, possibilitando criar o código inverso ex: insert / delete


    public function setColumn($prop){
       
       if($prop['action'] == 'create'){
            $this->createColumn($prop['column'],$prop['code'],$prop['prefix'],$prop['sufix']);
       }
       if($prop['action'] == 'pk'){
            $this->pkAction($prop['column'],$prop['code']);
        }
        if($prop['action'] == 'edit'){
            $this->editColumn($prop['column'],$prop['code'],$prop['prefix'],$prop['sufix'],$prop['code']);
        }
        // if($prop['action'] == 'delete'){
        //     $this->createPk($prop['column'],$prop['code']);
        // }
    } 
    
    public function pkAction($column, $type){
        $this->pk[] = array($column, $type);
    }

    public function createColumn($column, $type, $prefix, $sufix){
      
        $this->createcolumn[] = array('column'=>$column, 'type'=>$type, 'prefix'=>$prefix, 'sufix'=>$sufix);
    }
    public function editColumn($column, $type, $prefix, $sufix, $code){
        $this->editcolumn[] = array('column'=>$column, 'type'=>$type, 'prefix'=>$prefix, 'sufix'=>$sufix, 'code'=>$code);
    }
    public function run(){
        TTransaction::open($this->db);
        $conn = TTransaction::get();
        // print_r($this->pk);
        // print_r($this->createcolumn);
        // print_r($this->editcolumn);
        
        if(count($this->createcolumn)>0)
        {
            //criar tabela se não existir
            $sql = 'CREATE TABLE IF NOT EXISTS '.$this->tab.' ();';         
            if($this->debug)
                echo '<br><pre>'.$sql;
            else
                $executarSql = $conn->query($sql);
         
            foreach($this->createcolumn as $key => $col){
            
                $sql = '<br>    ALTER TABLE '.$this->tab.' ADD '.$col['prefix'].' '.$col['column'].' '.$col['type'].' '.$col['sufix'].';';
            
                if($this->debug)
                    echo ''.$sql;
                else
                    $executarSql = $conn->query($sql);
            }
            if($this->debug)
            echo '</pre>';
        }


        if(count($this->editColumn)>0)
        {

        }
        TTransaction::close();
    }
   
}

