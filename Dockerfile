FROM webdevops/php-nginx:7.4

WORKDIR /app

RUN apt update
RUN apt install -y composer git

RUN git clone https://github.com/gruessung/php-gfont-proxy .

RUN compose install