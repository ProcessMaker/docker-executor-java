FROM maven:3.9-eclipse-temurin-8
MAINTAINER ProcessMaker Inc.

COPY /src /opt/executor
WORKDIR /opt/executor
