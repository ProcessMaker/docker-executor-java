FROM guligo/jdk-maven-ant
MAINTAINER ProcessMaker Inc.

COPY /src /opt/executor
WORKDIR /opt/executor
