<?php
//POR André Gasparin (pode tirar se quiser, não ligo mesmo kkkk)
//Quer fuçar no código? sem problema, qualquer modificação por menor que seja pode ajudar o projeto crescer, se não sabe trabalhar com o GIT, me chame que eu lanço a commit!
//Não fique com vergonha do seu código, ajude com o que sabe, afinal esse mesmo tem diversas funções que possivelmente poderiam ser substituidas por funções mais específicas que não conheço por estar iniciando no FW
//Não precisa necessariamente ser uma função nova, se você ver algo que fique melhor no código, POR FAVOR, MODIFIQUE e contribua 
class dbVersion extends TPage
{
    private $db;
    static  $dbver = 'dbversion';
    private $action = array();

    public function __construct()
    {
        parent::__construct();
        try
        {
            TTransaction::open(self::$dbver);
            TTransaction::close();
        }
        catch (Exception $e)
        {
           new TMessage('error', 'Banco dbversion não foi encontrado na aplicação, crie o banco de acordo com a instalação do readme');
        }
    }

    public function setDB($db)
    {
        
        TTransaction::open(self::$dbver);
        $vers = dbConnection::where('name', '=', $db)->count();
        //Caso a conexão não possua uma versão, setamos ela como 0 e criamos ela no banco de dados do dbVersion
        if($vers == 0)
        {
            dbConnection::create( [ 'name' =>  $db, 'version' => '0' ]);
        }
        TTransaction::close();
            
        $this->db = $db;
    }
    //Verifica a versão atual da conexão ativa 
    public function verifyVersion()
    {
        
        TTransaction::open(self::$dbver); // open transaction
           
            // query criteria
            $criteria = new TCriteria; 
            $criteria->add(new TFilter('name', '=',  $this->db)); 
            // load using repository
            $repository = new TRepository('dbConnection'); 
            $version = $repository->load($criteria); 
            //retorna a versão atual da conexão ativa
            return  $version[0]->version;
            
        TTransaction::close();
    }
    //retorna a última versão existente
    public function verifyNewestVersion()
    {
        TTransaction::open(self::$dbver); // open transaction
            // query criteria, ordena por versão e não por "id" do envio
            $criteria = new TCriteria; 
            $criteria->setProperty('order', 'version');
            $criteria->setProperty('direction','desc');            
            $criteria->setProperty('limit', 1);

            // load using repository
            $repository = new TRepository('dbVer'); 
            $version = $repository->load($criteria);
            //retorna a última versão existente 
            return  $version[0]->version;
        TTransaction::close();
    }
    //verifica se existe update
    public function verifyUpdate()
    {
        $return = ($this->verifyVersion() == $this->verifyNewestVersion()) ? false : true;
        //retorna true caso a versão do bd ativo for diferente da versão mais recente
        return $return;
    }
    //Verifica se a conexão está na lista das conexões que irão fazer a atualização, a permissão é passa pelo update version, essa função apenas faz o modelo da verificação e retorna o resultado
    public function verifyPermission($permission)
    {
        //caso não tenha verificação, retorna negativo, para não fazer a atualização
        $ok = false;
        $perm = explode(',', $permission);
        //verifica se tem a opção todas, liberando qualquer requisição
        if (in_array('*', $perm)) 
        {
            $ok = true;
        }
        //verifica se a conexão está em uma lista restrita de permissão
        if (in_array($this->db, $perm)) 
        {
            $ok = true;
        }
        //remove a permissão caso essa conexão não seja autorizada a fazer a atualização
        if (in_array('-'.$this->db, $perm)) 
        {
            $ok = false;
        }
        if($ok == true)
            echo '<br>Banco está habilitada para update';
        else
            echo '<br>Não fazer update nesse banco';

        return $ok;
    }

    //função principal, que faz o update
    public function updateVersion()
    {
        //verifica se existe update para ser feito
        if($this->verifyUpdate())
        {
            TTransaction::open(self::$dbver); // open transaction
                //Pega os updates na ordem
                $criteria = new TCriteria; 
                $criteria->add(new TFilter('version', '>', $this->verifyVersion())); 
                $criteria->setProperty('order', 'version');
                $criteria->setProperty('direction','asc');            

                // load using repository
                $repository = new TRepository('dbVer'); 
                $result = $repository->load($criteria);
            
                foreach($result as $row)
                {
                    if($this->verifyPermission($row->permission))
                    {
                        echo '<br><b>--  Verificar e fazer commit: v'.$row->version.'</b>';
                        //Faz a execução do código do campo CODE, verificando se teve sucesso no retorno ou não
                        $commit = $this->commitVersion($row->code);
                        if($commit)
                        {
                            //Faz a mudança de versão da conexão no banco do dbVersion
                            $this->setVersion($row->version);
                        }
                        else
                        {
                            //caso não faça o commit ele para tudo! (você pode remover essa parte e criar um log ou algo do tipo) para não bloquear o acesso no caso de colocar a chamada no login
                            exit();
                        }
                    }
                }  
            TTransaction::close();
        }
        else
        {
            //caso não exista nenhum update
            return 'Nenhuma atualização disponível.';
        }
    }

    //Faz a execução do código, pode ser implementado uma função de debug, assim como aqui no <pre> nas outras funções que retornam "echo"
    public function commitVersion($code)
    {
        TTransaction::open($this->db);
            $conn = TTransaction::get();
            // if($this->debug) //Precisa implementar
            echo '<pre>'.$code.'</pre>';      
                // else
            $executarSql = $conn->query($code);
            //retorna o status da query
            if($executarSql)
                return true;
            else
                return false;
        TTransaction::close();
        //atualizar base de dados do comit na função de updateVersion, caso o commit retornar true
    }
    //Muda a versão da conexão atual no banco de dados do dbVersion
    public function setVersion($versionset)
    {    
        TTransaction::open(self::$dbver); // open transaction
            //procura a conexão pelo nome (que é unico)
            $vers = dbConnection::where('name', '=', $this->db)->load();
            foreach ($vers as $veral) 
            {
                //seta a versão no banco 
                $veral->version = $versionset;
                $veral->store(); 
            }
        TTransaction::close();
       
    }
     /* Versão 2.0
    
    
    public function commitVersion($version){
        TTransaction::open(self::$dbver); // open transaction
        
        
        $criteria = new TCriteria; 
        $criteria->add(new TFilter('id_version', '=', $version)); 
        $criteria->setProperty('order', 'id');
        $criteria->setProperty('direction','asc');            

        // load using repository
        $repository = new TRepository('dbCommit'); 
        $resultc = $repository->load($criteria);
        foreach($resultc as $rowc)
        {
          
            
            //cria a array para separar por tabela deixando as ações das colunas cada um em uma posição da array
            //ou seja cada coluna pode ter mais de 1 ação, ex. criar e colocar pk
           $action[$rowc->tab][] =
           [
                'column'        => $rowc->column,
                'target_type'   => $rowc->target_type,
                'action'        => $rowc->action,
                'target_type'   => $rowc->target_type,
                'code'          => $rowc->code,
                'prefix'        => $rowc->prefix,
                'sufix'         => $rowc->sufix
            ];
        }     
        //separa tabela por tabela, para montar o sql por tabela
        $sql = array();
        foreach($action as $tab => $prop){
            $dbquery = new dbVersionQuery();
            $dbquery->setTab($tab);
            $dbquery->setDb($this->db);
            $dbquery->setDebug(1);
            //ENVIA coluna por coluna para dividir quais vai inserir, quais vai modificar e quais vai modificar PK
            foreach($prop as $key => $value){
                $dbquery->setColumn($value);
            }
           $dbquery->run();
        }
        TTransaction::close();
    }
    */
}