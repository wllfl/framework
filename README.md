Framework WLL
=========

[![FrameworWLL](http://www.wllsistemas/image/logoFramework.png)]

Framework em PHP para facilitar o desenvolvimento e trazer simplicidade para operações de CRUD. Ele não é um framework que atende aos 3 pilares MVC, somente abstrai a camada de conexão e manipulação de dados. 

Esse framework suporta 3 SGBDs:
- MySQL
- PostgreSQL
- SQL Server

É importante que as extensões PDO para cada banco de dados estejam habilitadas no PHP, existe uma diferença em relação
á extensão PDO para SQL Server em servidores Linux, nesse caso temos que habilitar a extensão pdo_dblib. Para servidores Windows
é indicado a extensão pdo_sqlsrv.