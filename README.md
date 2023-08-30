# Course Learning docker / docker-compose

### (Nginx, MySQL, React, Symfony)

Configure docker to up container.


## Getting Started

These instructions will cover usage information and for the docker container

## Prerequisities

In order to run this container you'll need docker installed.

* [Linux](https://docs.docker.com/linux/started/)



## Usage (Scripts)

Stop, delete all containers and recreate this in `local` version

```shell
./local_recreate_docker.sh
```


## Usage (Detailed)

Build container in `local` version in the background

```shell
docker-compose -f docker-compose.yaml -f docker-compose.local.yaml up
```



## Configure file `hosts` to start container in local domain

```text
# local domains for production and development
127.0.0.1 courselab.com
# etc...
```

#### Symfony .env.local configuration

Database configuration

```text
DATABASE_URL=mysql://courselab-user:123456@172.22.75.8:3306/courselab?serverVersion=5.7
```

#### Show logs

For FRONTEND container use:
```text
docker-compose logs -f --tail 50 frontend
```

For API container use:
```text
docker-compose logs -f --tail 50 api
```

For NGINX container use:
```text
docker-compose logs -f --tail 50 nginx
```

#### Execute containers

For FRONTEND container use:
```text
docker-compose exec frontend sh
```

For API container use:
```text
docker-compose exec api sh
```

For NGINX container use:
```text
docker-compose exec nginx sh
```


#### In browser

PhpMyAdmin & MySQL database only work in `local` version. Don't forget to start container.

* [Main page (ReactJS)](https://courselab.com)
* [Main page (Symfony)](https://courselab.com/api)
* [PhpMyAdmin](https://courselab.com:8443)
