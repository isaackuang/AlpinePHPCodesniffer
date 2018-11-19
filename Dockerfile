FROM isaackuang/alpine-base:3.8.0

RUN apk --no-cache --progress add \
    php-cli php-phar php-tokenizer php-simplexml && \
    cd /usr/bin && \
    wget https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar && \
    wget https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar && \
    mv phpcs.phar phpcs && mv phpcbf.phar phpcbf && \
    chmod +x php*

ADD Standard /var/www/Standard

WORKDIR /var/www/html
ENTRYPOINT ["phpcs"]
CMD ["--help"]
# RUN phpcs --config-set default_standard PSR12
