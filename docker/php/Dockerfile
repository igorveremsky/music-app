# PHP Docker image for Yii 2.0 Framework runtime
# ==============================================

FROM php:7.2-fpm

# add own php.ini configs
COPY configs/base.ini /usr/local/etc/php/conf.d

# command line goodies
RUN echo "export LS_OPTIONS='--color=auto'" >> /root/.bashrc
RUN echo "alias ls='ls \$LS_OPTIONS'" >> /root/.bashrc
RUN echo "alias ll='ls \$LS_OPTIONS -l'" >> /root/.bashrc
RUN echo "alias l='ls \$LS_OPTIONS -lA'" >> /root/.bashrc