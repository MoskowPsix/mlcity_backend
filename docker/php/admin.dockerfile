FROM node:latest

WORKDIR /var/www/MLCity_backend


CMD npm install && npm run build

