# Sistema de Gerenciamento de Produtos e Pedidos de Compra

Este sistema gerencia produtos, pedidos de compra e registros de logs de alterações. Ele foi desenvolvido em **PHP 8.2.12** utilizando o framework **CodeIgniter 3.1.11**.

## Funcionalidades Principais

1. **Sistema de Login Seguro:**
   - Bloqueio de IP após 3 tentativas incorretas.

2. **CRUD de Produtos:**
   - Gerenciamento de produtos com campos como código, nome e valor unitário.
   - Produtos podem ser inativados e reativados.

3. **CRUD de Pedidos de Compra:**
   - Gerenciamento de pedidos com fornecedores ativos.
   - Status: **Ativo** (permitido editar/excluir) ou **Finalizado** (não pode ser alterado).

4. **Listagem de Logs:**
   - Registro das alterações no sistema.

## Instalação

1. Clone o repositório:

   ```bash
   git clone https://github.com/seu-usuario/seu-repositorio.git
   
2. Configure o banco de dados no arquivo application/config/database.php. O nome do banco deve ser crudci_products_db.
   
3. Você deve executar manualmente o script crudci_products_db.sql para gerar o banco de dados.

4. Por fim, altere as configurações dos arquivos de configuração, com base no seu ambiente, nos seguintes arquivos:

- **application/config/config.php:** Altere a configuração da variável `$config['base_url']` para a URL em que seu projeto está sendo executado.

- **application/config/database.php:** Altere as configurações de `hostname`, `username`, `password` e `database` com base nas credenciais do seu banco de dados.
   
5. Inicie o servidor:
   ```bash
   php -S localhost:8000
6. Acesse via http://localhost:8000.
## Autenticação da API
Para acessar o Web Service de pedidos de compra, é necessário adicionar um cabeçalho de autorização do tipo ApiKey. O token é obtido após realizar o login com sucesso na tela inicial.

Exemplo de Cabeçalho de Autorização:
`Authorization: ApiKey `
`API-TOKEN: <seu_token_aqui>`
- API-TOKEN: O token que será gerado após o login.
  
Esse token será necessário para autenticar todas as chamadas subsequentes ao Web Service.

Endpoint para obtenção dos pedidos finalizados WS:

   ```bash
   GET /OrdersService/getAllFinalized
```

## Requisitos
- PHP 8.2.12
- CodeIgniter 3.1.11
- SQL

## Credenciais

# Login de Acesso 

`Email: teste@manyminds.com.br`
`Senha: senha123`
