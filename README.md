# Comissão de Vendedores

Sistema web completo para cadastro e gerenciamento de vendas, com controle de comissões por vendedor. Desenvolvido com Laravel (API) e Vue.js (frontend), rodando em containers Docker para facilitar o ambiente de desenvolvimento.

## Tecnologias utilizadas

- **Laravel (PHP)** 
- **Vue.js (TypeScript)** 
- **Docker**
- **MySQL**

## Estrutura do projeto

- `/backend` – Contém a API RESTful desenvolvida em Laravel
- `/frontend` – Contém a aplicação frontend em Vue.js

## URLs de acesso

- Aplicação Vue.js: [http://localhost:5173](http://localhost:5173)
- API Laravel: [http://comissao-vendedores.local](http://comissao-vendedores.local)

## Funcionalidades

- Cadastro de vendedores informando nome e email.
- Cadastro de vendas informando vendedor, valor e data da venda.
- Listagem de todos os vendedores.
- Listagem de todas as vendas.
- Listagem de vendas por vendedor
- Cálculo automático de comissão (8.5% para cada venda).
- Envia um e-mail para o vendedor ao final de cada dia com a quantidade de vendas
realizadas no dia, o valor total delas e o valor total das comissões.
- Envia um e-mail para o administrador do sistema contendo todas a soma de todas as
vendas efetuadas no dia;
- Permite que **o administrador** reenvie o e-mail de comissão a um determinado
vendedor.

## Pré-requisitos

- Docker e Docker Compose instalados
- Node.js

## Como rodar o projeto

Clone este repositório:

```bash
git clone https://github.com/arthurvpires/comissao-vendedores.git
cd comissao-vendedores
```
# Configurando o Backend (Laravel + Docker)

1. Inicie os containers da API Laravel:

```bash
cd backend
docker-compose up -d --build
```

2. Clone o .env.example:
```
cp .env.example .env
```

3. Instale as dependências e popule o banco de dados:
 
```bash
docker exec -it comissao-vendedores composer install
docker exec -it comissao-vendedores php artisan migrate
docker exec -it comissao-vendedores php artisan db:seed
docker exec -it comissao-vendedores php artisan key:generate
```
## Envio de emails (Opcional)
  - Usei o **Mailtrap** para fazer o teste de envio dos emails.
  - Crie uma conta em https://mailtrap.io e configure no **.env** para visualizar os emails recebidos. 
    
    ```
    MAIL_USERNAME=seu_username
    MAIL_PASSWORD=sua_senha
    ```
 - Os e-mails são enviados de forma assíncrona usando filas. Para que eles sejam processados corretamente, é necessário iniciar o worker com o comando abaixo:

   ```
   docker exec -it comissao-vendedores php artisan queue:work
   ````

## Configuração do Hosts:

  ### Windows
  1. Abra o Bloco de Notas como administrador
  2. Abra o arquivo: `C:\Windows\System32\drivers\etc\hosts`
  3. Adicione a seguinte linha:
  ```
  127.0.0.1 comissao-vendedores.local
  ```
  
  ### Linux/Mac
  1. Abra o terminal
  2. Execute o comando:
  ```bash
  sudo nano /etc/hosts
  ```
  3. Adicione a seguinte linha:
  ```bash
  127.0.0.1 comissao-vendedores.local
  ```

##  Testes

Para executar os testes criados:

- AuthControllerTest
- SellerControllerTest
- SaleControllerTest
- EmailControllerTest
  
```
php artisan test 
```

## Teste da API com Postman ou Insomnia

 - **[Baixar Collection](https://drive.google.com/file/d/1o6o4-WhPX1suYZOcqbKRCe7NDDG_ABOO/view?usp=sharing)**
   


# Agora configurando o frontend:

```bash
cd ..
cd frontend
```

```bash
npm install
npm run dev
```

## Usuário de testes para API:
    - Email: admin@admin.com
    - Senha: 12345678

## Rotas disponíveis no frontend:

- URL base: `http://localhost:5173`

  - `/login`
    -  Login do usuário
  - `/register`
    - Cadastro de novo usuário
  - `/dashboard`
     -  Tela principal com os endpoints da API
  

   
   
