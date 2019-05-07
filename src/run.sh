#!/bin/bash
javac -cp spark-sdk-java/target/lib/*:spark-sdk-java/target/openapi-java-client-1.0.0.jar Script.java
java -cp spark-sdk-java/target/lib/*:spark-sdk-java/target/openapi-java-client-1.0.0.jar:. Script
