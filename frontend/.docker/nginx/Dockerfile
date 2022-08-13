FROM nginx:alpine

# Copy custom nginx config
COPY ./nginx.conf /etc/nginx/nginx.conf

COPY ../../dist/kkayn-frontend /usr/share/nginx/html

EXPOSE 81 443

ENTRYPOINT ["nginx", "-g", "daemon off;"]