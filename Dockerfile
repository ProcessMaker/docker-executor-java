FROM guligo/jdk-maven-ant
MAINTAINER ProcessMaker Inc.

# https://superuser.com/questions/1423486
RUN printf "deb http://archive.debian.org/debian/ jessie main\ndeb-src http://archive.debian.org/debian/ jessie main\ndeb http://security.debian.org jessie/updates main\ndeb-src http://security.debian.org jessie/updates main" > /etc/apt/sources.list
RUN apt-key adv --keyserver keyserver.ubuntu.com --recv-keys AA8E81B4331F7F50
RUN apt-get update && apt-get install -y git-core

COPY /src /opt/executor
WORKDIR /opt/executor
