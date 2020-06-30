
# Gerenciador de versões simples de banco de dados  PHP / Adianti 
> Um gerenciador de banco opensource criado por <a href="https://sistemas.gasparimsat.com.br" target="_blank" rel="noopener">sistemas.gasparimsat.com.br</a> com e para Adianti Framework.

<p align="center">
<img src="https://img.shields.io/badge/VERSÃO-1.0.0-green">
<img src="https://img.shields.io/badge/Licença-GNU 3.0-success">
<img src="https://img.shields.io/badge/PHP-GasparimSat-blue">
<img src="https://img.shields.io/badge/PHP-Adianti-blue">
<img src="https://img.shields.io/badge/PHP->7.2-blueviolet">
</p>

Agora você não precisa mais atualizar base por base do seu cliente (multibancos, multischemas), coloque no login ou na página inicial conforme a instalação que assim que logado o sistema irá fazer o update do banco de dados de acordo com as conexões que você permitiu no formulário.
Ele irá fazer as atualizações na sequencia, ou seja, caso crie uma conexão do 0, ele vai passar por todas as versões e criar um banco do 0 que passará por todas atualizações até a versão atual da produção.

## Colaboração

Fred Azevedo


## Instalação

- Utilize o arquivo SQL.txt e crie as tabelas que a classe utiliza (são 3 e bem pequenas), sendo que a version_commits já é uma preparação para a versão 2.0, então não necessita na versão atual
- Coloque os arquivos no seu projeto
- Coloque o código:
<pre>
$dbversion = new dbVersion();
$dbversion->setDB('VARIAVEL QUE FALA QUAL CONEXAO ATUAL'); // Variavel com o nome do "conexao.ini", você pode fazer dinamicamente para atualizar de acordo com o usuário que logar
$dbversion->updateVersion();
</pre>
- De a permissão nas classes (a permissão da mesma forma que você dá quando cria um form por exemplo):
dbVersion 	- que faz todo o controle
VersionFormList - que cria o form para atualizações

- Você pode utilizar os seguintes comandos em qualquer lugar do seu FW:
<pre>
 	$dbversion = new dbVersion();
        $dbversion->setDB('VARIAVEL QUE FALA QUAL CONEXAO ATUAL');
        <b>Verificar versão atual:</b>
        echo $dbversion->verifyVersion();
        <b>Verificar versão mais recente:</b>
        echo $dbversion->verifyNewestVersion();
        <b>Verificar se existe update:</b>';
        echo $dbversion->verifyUpdate();
        OBS: Não precisa fazer a verificação antes do update, a própria classe faz a verificação internamente antes de fazer o update.

        <b>Fazer update:</b>
        echo $dbversion->updateVersion();
</pre>
OBS: Caso queira, modifique a linha 124 do "dbVersion", o padrão é para que caso uma atualização falhar ele pare toda a aplicação, você pode por exemplo criar um log ou algo do tipo.
Caso ela falhe, não vai setar no banco que ele está na versão falhada e sim na anterior.

## Utilização

Acesse o "VersionFormList" e:

<b>Campo Version:</b>  utilize para citar a versão da sua modificação. (obs, utilize números e pontos somente) se você inserir 1.0 e posteriormente 0.9, ele executará na ordem da versão e não da inserção, tome cuidado!

<b>Campo Code:</b> Insira o SQL de criação de tabelas, exclusão, modificação e tudo mais.

<b>Campo Permission:</b> 

-Caso queira atualizar todas as conexões, utilize *

-Caso queira atualizar apenas uma conexão ou mais utilize nomedaconexao, nomedaconexao2

-Caso queira atualizar todas menos uma ou mais conexões utilize:  *, -conexao, -conexao2


OBS: Não precisa fazer a verificação antes do update, a própria classe faz a verificação internamente antes de fazer o update.

## ALERTA
Caso seu banco de dados seja muito grande com muitas modificações, teste primeiro no DEV, não temos um limite definido de interações que ele conseguirá fazer, o que também dependerá do seu servido.

## Configuração para Desenvolvimento

Caso queira implementar algo no sistema, utilize os padrões do Adianti Framework, ficaremos felizes com sua participação!

## Precisa de melhoria ou ajuda com algum BUG?

<a href="https://github.com/andre-gasparin/AdiantiDBVersion/issues">https://github.com/andre-gasparin/controle-consultorio-gs/issues</a>


## Histórico (ChangeLog)

* 1.0.0
    * Projeto criado

* 1.0.1
    * Alteração em nome de variáveis
    * Alteração em nome de classes
    * Alteração em nome de arquivos
    * Revisão do código


## Meta

André Gasparin – [@andre-gasparin] – andre@gasparimsat.com.br / andre.gasparin@hotmail.com

Distribuído sob a Licença Pública Geral GNU (GPLv3) 


## Contributing

1. Faça o _fork_ do projeto (<https://github.com/andre-gasparin/AdiantiDBVersion/fork>)
2. Crie uma _branch_ para sua modificação (`git checkout -b feature/fooBar`)
3. Faça o _commit_ (`git commit -am 'Add some fooBar'`)
4. _Push_ (`git push origin feature/fooBar`)
5. Crie um novo _Pull Request_
