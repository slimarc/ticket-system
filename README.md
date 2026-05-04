# 🎫 Ticket System API

Sistema de gerenciamento de chamados com controle de status, prioridade, setores e rastreamento de execução.

Projeto desenvolvido com foco em praticar arquitetura de API, regras de negócio e fluxo de estados de chamados.

---

## 🚀 Tecnologias

- PHP 8+
- Laravel
- PostgreSQL
- Docker
- HTML + Vanilla JS 

---

## 📌 Funcionalidades

### 🎟️ Tickets
- Criar chamados (title, description, requester, sector e priority)
- Listagem de tickets com paginação
- Filtro por:
  - setor
  - prioridade
  - status
  - tempo estimado (min/max horas)

### 🔄 Fluxo de status
- OPEN → chamado aberto
- IN_PROGRESS → chamado em atendimento (check-in)
- CLOSED → chamado finalizado (checkout com solução)
- CANCELLED → chamado cancelado

---

### ⏱️ Controle de tempo 
- Cálculo de tempo decorrido do ticket
- Comparação com tempo estimado da prioridade
- Destaque de chamados em atraso

---

### 🧭 Check-in / Check-out
- Check-in inicia atendimento e registra `started_at`
- Check-out finaliza chamado e registra:
  - `finished_at`
  - `solution`

---

### 🏢 Setores
- Cadastro de setores
- Associação de tickets a setores

---

### ⚡ Prioridades
- Cadastro de prioridades
- Definição de tempo estimado (SLA em horas)
- Cor associada à prioridade

---

### 📊 Tracking 
- Registro de eventos do ticket:
  - STARTED (check-in)
  - FINISHED (check-out)
- Base para auditoria e rastreabilidade

---

## 🌐 Endpoints principais

Todos endpoints no arquivo postman em 

```
docs/postman 
```

---

## 🧪 Frontend 

Inclui uma interface simples em HTML para:

- Criar tickets
- Executar check-in / check-out
- Visualizar lista de tickets
- Destacar tickets em atraso 

---

## 🐳 Executando com Docker

```bash
docker-compose up -d --build
```

A API ficará disponível em:

```bash
http://localhost:8000
```

Frontend disponível em:

```bash
http://localhost:8000/index.html
```

---

## 📌 Observações

- O sistema utiliza enums para controle de status dos tickets
- Regras de negócio estão centralizadas em Services
- O tracking serve para auditoria de ações no ticket
- O cálculo de SLA é baseado em diferença entre started_at e finished_at
- Existem seeders que populam o banco de dados
