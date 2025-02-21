
# 📌 Projeto Laravel - Grupo Econômico

Este projeto foi desenvolvido com as tecnologias:

- **Laravel**
- **MySQL**
- **Bootstrap**
- **jQuery**

## ⚠️ Avisos Importantes

Antes de prosseguir, certifique-se de que você possui os seguintes requisitos instalados:

### ✅ Requisitos

- **PHP 8 ou mais recente**
- **Extensões PHP ativadas no `php.ini`**:
  ```ini
  extension=gd
  extension=zlib
  extension=curl
  extension=fileinfo    
  extension=mbstring    
  extension=openssl     
  extension=pdo_mysql   
  extension=pdo_sqlite  
  extension=tokenizer   
  extension=xml         
  extension=intl        
  extension=bcmath      
  ```

## 🚀 Passos para Configuração

### 📂 Passo 1: Organização do Projeto

1. Baixe o projeto e **mova-o para uma pasta segura**, onde não seja necessária permissão de administrador para modificações.
2. Instale as dependências do Laravel (**Vendor**):
   ```sh
   composer install
   ```
   > 💡 Importante: Caso esteja clonando o projeto "Grupo Econômico" dentro de um Laravel em branco, utilize um **Vendor compatível com Laravel 11**.

### 🛠️ Passo 2: Configuração do Banco de Dados

1. Edite o arquivo `.env` e configure a conexão com o banco de dados.

#### 🔹 Usando Docker
Se estiver utilizando Docker para o banco de dados, configure o `.env` com:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=seu_banco
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha
   ```

#### 🔹 Usando XAMPP
Caso esteja utilizando XAMPP, configure o `.env` assim:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_DATABASE=seu_banco
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 📦 Passo 3: Migração do Banco de Dados

1. Abra um terminal e navegue até a pasta do projeto:
   ```sh
   cd /caminho/do/seu/projeto
   ```

2. Execute as migrações para criar as tabelas:
   ```sh
   php artisan migrate
   ```

3. Popule o banco de dados com os dados iniciais:
   ```sh
   php artisan db:seed --class=TypeUserSeeder
   ```

### 🌍 Passo 4: Executar o Servidor

1. Para iniciar o projeto, use:
   ```sh
   php artisan serve
   ```
2. O terminal exibirá um link semelhante a este:
   ```
   http://127.0.0.1:8000
   ```
   Copie e cole no seu navegador para acessar a aplicação.

#### 🔹 Usando XAMPP
Se estiver rodando o Laravel no XAMPP:
1. Configure o `httpd.conf` para apontar para a pasta `public/` como raiz do projeto.
2. Inicie o Apache e o MySQL no XAMPP.
3. Acesse o projeto pelo navegador:
   ```
   http://localhost:8080
   ```

## 📧 Envio de E-mails
Este projeto utiliza:
- **Mailtrap** para recuperação de senha.
- **FormSubmit** para envio de mensagens na página inicial.

## 📜 Licença
Este projeto é protegido pela **Licença MIT**.

---

> 💙 Desenvolvido com carinho por **Ighor Drummond** 🚀

> 💻 [Ighor Drummond](https://github.com/ighordrummond)