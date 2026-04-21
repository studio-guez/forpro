FROM node:lts-alpine AS builder

WORKDIR /app

COPY package.json ./

RUN npm install

COPY . .

RUN npm run build

FROM node:lts-alpine

USER node:node

WORKDIR /app

COPY --from=builder --chown=node:node /app/build ./build

COPY --from=builder --chown=node:node /app/node_modules ./node_modules

COPY --chown=node:node package.json .

CMD ["node","build"]
