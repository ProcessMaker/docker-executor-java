#!/bin/bash
set -e
set -x

BRANCH=${BRANCH:=master}
TAG=${TAG:=dev-${BRANCH//[\/]/-}}

pushd src
    if [[ ! -d "spark-sdk-java" ]]; then
        git clone --branch $BRANCH --depth 1 https://github.com/ProcessMaker/spark-sdk-java.git
    fi
popd

docker build -t processmaker/spark-docker-executor-java:${TAG} .
