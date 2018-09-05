FROM registry.cn-huhehaote.aliyuncs.com/ruoyubuy/laravel-docker

ADD config/home /home/worker
ADD config/maxmind/GeoIP.conf /usr/local/etc/GeoIP.conf

RUN chmod a+x /home/worker/bin/*
