#!/bin/bash
javac -cp sdk-java/target/lib/*:sdk-java/target/openapi-java-client-1.0.0.jar -Xlint:unchecked Script.java ProcessMaker.java Main.java BaseScript.java
java -cp sdk-java/target/lib/*:sdk-java/target/openapi-java-client-1.0.0.jar:. Main
