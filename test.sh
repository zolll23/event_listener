#!/bin/sh

docker run -v $(pwd)/code:/var/event_sourcing vpa/event_sourcing:1.0 php vendor/bin/psalm
docker run -v $(pwd)/code:/var/event_sourcing vpa/event_sourcing:1.0 php vendor/bin/phpunit