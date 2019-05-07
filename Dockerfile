FROM guligo/jdk-maven-ant

MAINTAINER ProcessMaker Inc.

COPY /src /opt/executor

WORKDIR /opt/executor/spark-sdk-java
RUN ls
RUN mvn package mvn -DmyProperty=3.9.1
RUN cp target/openapi-java-client-1.0.0.jar ../openapi-java-client-1.0.0.jar
