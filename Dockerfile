FROM phpdockerio/php:8.3-cli AS php83

# 扩展依赖
RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        php8.3-common \
        php8.3-dev \
        make \
        rustc cargo \
        git gcc make re2c libpcre3-dev build-essential
RUN apt-get clean 
RUN apt-get autoremove   
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR /workspace

COPY sqlparser-rs /workspace/sqlparser-rs
COPY sqlparser /workspace/sqlparser
COPY php-zephir-parser /workerspace/php-zephir-parser

RUN cd /workspace/sqlparser-rs && cargo clean && cargo build
RUN cd /workerspace/php-zephir-parser && phpize && ./configure && make && make install

RUN cd /workspace/sqlparser && php -dextension=zephir_parser zephir.phar fullclean && php -dextension=zephir_parser zephir.phar generate
#RUN cd /workspace/sqlparser/ext && phpize --clean && phpize && ./configure && make && make install

#docker build -t sqlparser:0.1 .
