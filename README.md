
---

# 📚 Documentação Técnica - TJ-JUD Sistema Bibliográfico

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![CI/CD Pipeline](https://img.shields.io/badge/CI%2FCD-GitHub_Actions-blue?logo=githubactions&logoColor=white)](https://github.com/tech-dev-coffee-tdah/sistema-bibliografico/actions)
[![Docker](https://img.shields.io/badge/Docker-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Redis](https://img.shields.io/badge/Redis-DC382D?logo=redis&logoColor=white)](https://redis.io/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?logo=bootstrap&logoColor=white)](https://getbootstrap.com/)

**Repositório Oficial:** [https://github.com/tech-dev-coffee-tdah/sistema-bibliografico](https://github.com/tech-dev-coffee-tdah/sistema-bibliografico)

**Sistema de Gestão Bibliográfica** desenvolvido em **Laravel 13** para a avaliação técnica do **Spassu**, com foco em **Arquitetura em Camadas**, **Design Patterns (DTO & Service)**, **Desempenho**, **Segurança**, **Testes Automatizados (Unit & Feature)** e **Processamento Assíncrono com Redis**.

---

## 🎯 Objetivos do Projeto

O objetivo deste sistema é gerenciar o acervo bibliográfico do Tribunal (Livros, Autores e Assuntos) atendendo a todos os requisitos técnicos obrigatórios e boas práticas de desenvolvimento de nível Sênior:

* **Gerenciamento Completo do Acervo (CRUD):** Livros, Autores e Assuntos com validações estritas de entrada.
* **Relacionamentos N:N Complexos:** Associação dinâmica e integridade referencial entre Livros, múltiplos Autores e Assuntos.
* **Chaves Primárias Customizadas:** Mapeamento explícito das chaves `$primaryKey` (`Codl`, `CodAu`, `CodAs`) em conformidade com a especificação do Tribunal.
* **Soft Deletes:** Exclusão lógica aplicada a todas as entidades principais para preservação do histórico de dados e auditoria.
* **VIEW SQL Relatorial (`vw_relatorio_livros`):** Agregação otimizada no banco via `GROUP_CONCAT` para geração de relatórios síncronos e assíncronos em PDF via DomPDF.
* **Arquitetura em Camadas (Layered Architecture):** Desacoplamento estrito entre Controllers, Form Requests, DTOs e Services.
* **Alta Performance com Redis:** Utilização do Redis para **Cache de Consultas**, **Processamento Assíncrono de Filas (Queues)** e **Gestão Centralizada de Sessões**.
* **Resiliência e Log de Exceções:** Interceptação direcionada de `QueryException` e `ModelNotFoundException` no Handler Global sem exposição de detalhes internos do banco de dados.
* **Pirâmide de Testes:** Cobertura de testes unitários de serviço (`LivroServiceTest`) e testes de integração de feature (`Feature`) utilizando banco SQLite em memória (`:memory:`).
* **DevOps & Integração Contínua (CI/CD):** Pipeline automatizado no GitHub Actions validando testes, lint e padrões em cada `push` e `Pull Request`.


---

## 🏗️ Arquitetura e Design Patterns

A aplicação segue uma **Arquitetura em Camadas** desacoplada:

```text
[HTTP Request] 
      │
      ▼
[FormRequest] (Validação e Sanitização dos Dados)
      │
      ▼
[Controller] (Orquestração de Fluxo HTTP)
      │
      ▼
[DTO (Data Transfer Object)] (Encapsulamento Tipado dos Dados)
      │
      ▼
[Service Layer] (Regras de Negócio e Transações DB)
      │
      ▼
[Model / Eloquent DB] (Persistência no MySQL / SQLite)
```

### Principais Padrões Utilizados:
1. **Service Layer (`LivroService`):** Centraliza a lógica de negócios e o controle de transações (`DB::transaction`), garantindo que alterações em Livros e suas tabelas pivô ocorram de forma atômica.
2. **Data Transfer Object (`LivroDTO`):** Garante a imutabilidade e a tipagem forte dos dados trafegados entre Controllers e Services.
3. **Form Requests (`LivroRequest`, `AutorRequest`, `AssuntoRequest`):** Isola a lógica de validação de entrada, impedindo que dados inconsistentes cheguem aos controllers.
4. **Chaves Primárias Compostas em Tabelas Pivô:** As tabelas `livro_autor` e `livro_assunto` utilizam `$table->primary([...])`, garantindo integridade e impedindo associações duplicadas no nível do banco.

---

## 🗄️ Estrutura da Base de Dados

### Tabelas Principais e Chaves Customizadas

| Tabela | Chave Primária | Descrição |
| :--- | :--- | :--- |
| **`livros`** | `Codl` (Auto-increment) | Cadastro dos livros (`titulo`, `editora`, `edicao`, `ano_publicacao`, `valor`) |
| **`autors`** | `CodAu` (Auto-increment) | Cadastro dos autores (`nome`) |
| **`assuntos`** | `CodAs` (Auto-increment) | Cadastro dos assuntos/categorias (`descricao`) |

### Tabelas Pivô (N:N)
* **`livro_autor`**: `livro_codl` (FK) + `autor_codau` (FK) com **PK Composta**.
* **`livro_assunto`**: `livro_codl` (FK) + `assunto_codas` (FK) com **PK Composta**.

### VIEW de Relatório (`vw_relatorio_livros`)
Agrupa o acervo com seus respectivos autores e assuntos formatados em texto:

```sql
CREATE OR REPLACE VIEW vw_relatorio_livros AS
SELECT 
    l.Codl AS livro_id,
    l.titulo,
    l.editora,
    l.edicao,
    l.ano_publicacao,
    l.valor,
    COALESCE(GROUP_CONCAT(DISTINCT a.nome ORDER BY a.nome ASC SEPARATOR ', '), 'Nenhum Autor') AS autores,
    COALESCE(GROUP_CONCAT(DISTINCT s.descricao ORDER BY s.descricao ASC SEPARATOR ', '), 'Nenhum Assunto') AS assuntos
FROM livros l
LEFT JOIN livro_autor la ON l.Codl = la.livro_codl
LEFT JOIN autors a ON la.autor_codau = a.CodAu
LEFT JOIN livro_assunto ls ON l.Codl = ls.livro_codl
LEFT JOIN assuntos s ON ls.assunto_codas = s.CodAs
WHERE l.deleted_at IS NULL
GROUP BY l.Codl, l.titulo, l.editora, l.edicao, l.ano_publicacao, l.valor
ORDER BY l.titulo ASC;
```

---

## 🐳 Ambientação com Docker

O projeto utiliza **Docker Compose** multinível composto por 5 containers interligados em rede isolada (`tjjud_network`):

| Container | Serviço | Imagem / Base | Porta Host |
| :--- | :--- | :--- | :--- |
| `tjjud_app` | PHP 8.2-FPM | `docker/php/Dockerfile` | Interna |
| `tjjud_nginx` | Web Server | `nginx:alpine` | `8080:80` |
| `tjjud_db` | Banco MySQL 8.0 | `mysql:8.0` | `3306:3306` |
| `tjjud_redis` | Cache & Queues | `redis:alpine` | `6379:6379` |
| `tjjud_queue_worker` | Queue Worker | `docker/php/Dockerfile` | Background (`queue:work`) |

---

## 🚀 Guia de Instalação e Execução

### Pré-requisitos
* **Docker** e **Docker Compose** instalados.
* **Git** para clonagem do repositório.

### Passo a Passo

1. **Clonar o Repositório:**
   ```bash
   git clone https://github.com/tech-dev-coffee-tdah/sistema-bibliografico.git
   cd sistema-bibliografico
   ```

2. **Configurar o Arquivo de Ambiente:**
   ```bash
   cp .env.example .env
   ```

3. **Subir os Containers Docker:**
   ```bash
   docker compose up -d --build
   ```

4. **Instalar Dependências do Composer:**
   ```bash
   docker compose exec app composer install
   ```

5. **Gerar a Chave da Aplicação:**
   ```bash
   docker compose exec app php artisan key:generate
   ```

6. **Executar Migrations e Seeders (População Inicial):**
   ```bash
   docker compose exec app php artisan migrate:fresh --seed
   ```

7. **Acessar a Aplicação:**
   Navegue para **`http://localhost:8080`** no seu navegador.

---

## ⚡ Otimizações de Desempenho & Redis

### 1. Redis Cache, Queue e Session
No arquivo `.env`, o sistema está totalmente integrado ao Redis:
* **`CACHE_STORE=redis`**: Armazena consultas frequentes em memória RAM.
* **`QUEUE_CONNECTION=redis`**: Processa tarefas assíncronas (como geração de relatórios) em segundo plano através do container `tjjud_queue_worker`.
* **`SESSION_DRIVER=redis`**: Sessões centralizadas de ultra-baixa latência.

### 2. Índices de Banco de Dados
Índices estrategicamente definidos no schema para eliminar *Full Table Scans*:
* `deleted_at`: Otimiza todas as queries automáticas do `SoftDeletes`.
* `titulo`: Acelera a ordenação e filtros de busca por título.
* `['livro_codl', 'autor_codau']` / `['livro_codl', 'assunto_codas']`: Otimização máxima para `JOINs` N:N.

---

## 🧪 Suíte de Testes Automatizados

A aplicação conta com testes automatizados executados sob banco **SQLite em memória** (`:memory:`), garantindo velocidade e isolamento.

### Executar Todos os Testes:
```bash
docker compose exec app php artisan test
```

### Executar Apenas Testes Unitários de Serviço:
```bash
docker compose exec app php artisan test --filter=LivroServiceTest
```

### Executar Testes de Feature (Integração HTTP/Controllers):
```bash
docker compose exec app php artisan test --testsuite=Feature
```

---

## 🛡️ Tratamento de Erros e Segurança

* **Ocultação de Erros de Infraestrutura:** Exceções do tipo `QueryException` são interceptadas no `bootstrap/app.php`, registradas em log seguro (`storage/logs/laravel.log`) e apresentadas ao usuário em formato de mensagens de alerta amigáveis.
* **Prevenção contra Double Submit:** Scripts JavaScript globais desabilitam botões de envio e exibem spinners de carregamento instantaneamente ao submeter formulários.
* **Modal Global de Exclusão:** Substituição do `confirm()` nativo por Modais do Bootstrap 5.3 com indicação explícita do título e ID do registro afetado.

---

## 🔄 DevOps & Esteira de CI/CD (GitHub Actions)

A esteira de integração contínua é configurada no arquivo `.github/workflows/ci.yml`:

* **Gatilhos:** Disparado em cada `push` e `Pull Request` nas branches `main` e `master`.
* **Ambiente de CI:** Runner Ubuntu com PHP 8.2 e container auxiliar de Redis Alpine.
* **Validações:** Validação de sintaxe do Composer, instalação de dependências, geração de chave de ambiente e execução da suíte completa de testes.
* **Branch Protection Rules:** Regra configurada no repositório impedindo o merge na branch `main` caso a esteira de testes falhe.

---

