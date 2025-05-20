# GeoLoc API - Serviço de Geolocalização com Nominatim e Redis

Este projeto implementa uma API simples que permite pesquisar endereços ou coordenadas geográficas (latitude/longitude) usando o serviço Nominatim da OpenStreetMap. Ele utiliza Redis para gerenciar a fila de requisições, respeitando o limite de tempo entre cada consulta.

## Tecnologias Utilizadas

- **PHP 7.4**: A API é construída com PHP 7.4 e roda sobre o Apache.
- **Nominatim API**: Utiliza o Nominatim para realizar buscas por endereço ou coordenadas geográficas.
- **Redis**: Usado para gerenciar as requisições, garantindo que o limite de consultas seja respeitado.
- **Docker**: O aplicativo é containerizado utilizando Docker para garantir um ambiente de desenvolvimento e produção consistente.
- **cURL**: Usado para fazer requisições HTTP para o serviço Nominatim.

## Funcionalidades

- Pesquisa de endereços pelo nome.
- Pesquisa por coordenadas geográficas (latitude/longitude).
- Retorno da URL do Google Maps para o endereço encontrado.
- Filtragem das requisições com Redis para evitar múltiplas chamadas em um curto espaço de tempo.
- Logs das requisições realizadas para monitoramento.

## Pré-Requisitos

Antes de rodar o projeto, você precisará ter as seguintes ferramentas instaladas:

- **Docker**: O projeto usa Docker para criar containers.
- **Docker Compose**: Usado para orquestrar os containers.

## Como Rodar o Projeto

### 1. Clone o repositório

```bash
git clone https://github.com/carlosalbertjr/geo-loc-api.git
cd geo-loc-api
```

### 2. **Crie um arquivo .env**

CCrie um arquivo .env na raiz do projeto com as seguintes variáveis de ambiente::

```ini
NOMINATIM_API_URL=https://nominatim.openstreetmap.org
SERVER_PORT=8081
REDIS_HOST=redis
```

### 3. **Rodando com Docker**

#### 📌 **Rodar em Background (Modo Detach)**

```sh
docker-compose up -d
```

#### 📌 **Parar o Container**

```sh
docker-compose down
```

---

### 4. Como Usar a API

```sh
http://localhost:8081/
```

**Exemplo de Requisição por Endereço:**

```json
{
    "endereco": "Rua Lua Crescente 153 - Santana de Parnaíba, São Paulo"
}

```

**Exemplo de Requisição por Latitude e Longitude:**

```json
{
    "latitude": -23.40404883321355,
    "longitude": -46.88962380626277
}
```

## 📌 Estrutura dos Arquivos

```
.
├── docker-compose.yml
├── Dockerfile
├── .env
├── logs
│   └── requests.log
├── src
│   └── index.php
└── README.md

```

---

## 🏗 Contribuições

Sinta-se à vontade para enviar **pull requests** e reportar **issues**. 😊

---

## 📜 Licença

Este projeto está licenciado sob a **MIT License**.
