FROM nginx:alpine

RUN apk add --update nodejs nodejs-npm

COPY . /var/www/html
COPY dockerfiles ./dockerfiles
WORKDIR /var/www/html

RUN cp ./dockerfiles/nginx.conf /etc/nginx/nginx.conf && \
    cp ./dockerfiles/site.conf /etc/nginx/conf.d/site.conf && \
    rm /etc/nginx/conf.d/default.conf && \
    rm -r ./dockerfiles/nginx.conf && \
    rm -r ./dockerfiles/site.conf

RUN adduser -D -u 1000 codenetix

RUN npm install && npm run build

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
