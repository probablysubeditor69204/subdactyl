# Build Stage
FROM node:20-alpine as build-stage
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Nginx Stage (to serve frontend) or just scratch to export?
# We will use this image to provide the volume to the main webserver
# or we can use a small alpine image to hold the files
FROM alpine:3.18
WORKDIR /app
COPY --from=build-stage /app/dist /app/dist
CMD ["sh", "-c", "while :; do sleep 2073600; done"]
