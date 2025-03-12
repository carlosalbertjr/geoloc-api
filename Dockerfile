# Escolhe a imagem base com PHP e Apache
FROM php:7.4-apache

# Instalar as dependências para Redis
RUN apt-get update && apt-get install -y libssl-dev && \
    pecl install redis && \
    docker-php-ext-enable redis

RUN mkdir -p /var/www/html/logs
RUN chmod 777 /var/www/html/logs
    
# Instala o cURL e outras dependências necessárias para o Nominatim
RUN apt-get update && apt-get install -y \
    curl \
    git \
    libcurl4-openssl-dev \
    && docker-php-ext-install curl

# Ativa o mod_rewrite do Apache
RUN a2enmod rewrite

# Copia o código da aplicação para dentro do container
COPY ./ /var/www/html/
