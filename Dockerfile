FROM guligo/jdk-maven-ant

MAINTAINER ProcessMaker Inc.

COPY /src /opt/executor

WORKDIR /opt/executor

# https://superuser.com/questions/1423486
RUN printf "deb http://archive.debian.org/debian/ jessie main\ndeb-src http://archive.debian.org/debian/ jessie main\ndeb http://security.debian.org jessie/updates main\ndeb-src http://security.debian.org jessie/updates main" > /etc/apt/sources.list
RUN apt-key adv --keyserver keyserver.ubuntu.com --recv-keys AA8E81B4331F7F50
RUN apt-get update && apt-get install -y git-core
RUN if [ ! -d "sdk-java" ]; then git clone --depth 1 https://github.com/ProcessMaker/sdk-java.git; fi

WORKDIR /opt/executor/sdk-java

RUN mvn clean install

WORKDIR /opt/executor
