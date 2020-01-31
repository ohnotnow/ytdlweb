#!/bin/bash

# This assumes you have a DOCKER_ORG environment variable set and optionally a TAG_VERSION, eg
# $ export DOCKER_ORG=myamazingorg
# $ export TAG_VERSION=1.3

TAG_VERSION=${TAG_VERSION:-latest}

docker buildx build --push --platform linux/amd64,linux/arm/v7 -t "${DOCKER_ORG}"/ytdlweb:"${TAG_VERSION}" .
