FROM guligo/jdk-maven-ant

MAINTAINER ProcessMaker Inc.

COPY /src /opt/executor

WORKDIR /opt/executor/spark-sdk-java

RUN mvn package -DmyProperty=3.9.1

WORKDIR /opt/executor
