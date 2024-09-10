FROM node:latest

WORKDIR /var/www/MLCity_backend


CMD npm install && export NODE_ENV=production && npm run build

