# **Setup:**

## Install Docker according to your local OS
[Docker documentation](https://docs.docker.com/install/).

## Copy project
```bash
git clone git@github.com:NataMusic/devchallenge.it-qa-1.git
```

## Run docker
Open tests directory
```bash
docker-compose up
```

## Open console
```bash
docker-compose run php bash
```

## Run tests
```bash
vendor/bin/phpunit -c phpunit.xml
```