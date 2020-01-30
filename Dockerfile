FROM node:12-buster-slim as JSLAND

# fix for laravel mix webpack paths
RUN ln -s /home/node/public /public

USER node
WORKDIR /home/node
# make sure the paths the build output expects are there
RUN mkdir -p /home/node/public/css /home/node/public/js
# copy in our js/css/html
COPY package*.json webpack.mix.js /home/node/
COPY resources/ /home/node/resources/
# install node stuff and build our assets
RUN npm ci && npm run prod

FROM uogsoe/soe-php-apache:7.4 as PHPLAND

ENV PYTHON_VERSION 3.7

WORKDIR /var/www/html/

# make sure background processes can write to our workdir as www-data
RUN chown www-data:www-data /var/www/html

# install the packages we need
RUN apt-get update && apt-get install -y --no-install-recommends gosu ffmpeg curl python${PYTHON_VERSION} && ln -s /usr/bin/python${PYTHON_VERSION} /usr/bin/python
RUN curl -L https://yt-dl.org/downloads/latest/youtube-dl -o /usr/local/bin/youtube-dl && chmod a+rx /usr/local/bin/youtube-dl
# make sure our db directory exists and will be writeable
RUN mkdir /tmp/sqlite && chown www-data:www-data /tmp/sqlite

# copy all our code in
COPY --chown=www-data:www-data . /var/www/html/
RUN chmod +x /var/www/html/app-start /var/www/html/app-healthcheck
COPY --from=JSLAND /home/node/public/js/ /var/www/html/public/js/
COPY --from=JSLAND /home/node/public/css/ /var/www/html/public/css/

# install php deps
RUN composer install --no-dev --no-suggest

RUN php artisan storage:link

# and off we go
HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=3 CMD [ "/var/www/html/app-healthcheck" ]
ENTRYPOINT [ "/var/www/html/app-start" ]

