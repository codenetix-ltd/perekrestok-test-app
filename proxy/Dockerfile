FROM nginx:alpine

COPY nginx.conf /etc/nginx/nginx.conf
RUN rm /etc/nginx/conf.d/default.conf /etc/nginx/nginx.conf.default

EXPOSE 80