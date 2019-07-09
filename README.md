# docker-executor-java
Script Task Executor Engine with Java Runtime

This docker image provides a sandboxed protected environment to run custom Java scripts that are written in ProcessMaker 4.

## How to use
The execution requires a data.json, config.json and an output.json file be present on the host system. The data.json represents the 
Request instance data.  The config.json represents configuration specific for this Script Task. And the output.json should be a blank 
file that will be populated by the successful output of the script task. The script task is represented by a script.Java file.
It is the responsibility of the caller to have these files prepared before executing the engine via command line (or docker API).


### Install

```
git clone https://github.com/ProcessMaker/docker-executor-java.git
cd docker-executor-java
docker build -t processmaker4/docker-executor-java .
```

### Example data.json
```json
{
  "x": 100,
  "y": 200
}
```

### Example Script Task
```Java
import ProcessMaker_Client.ApiClient;
import java.io.*;
import java.util.Map;

public class Script implements BaseScript {

    /**
    * This code receives a data.x and data.y and returns data.z = data.x + data.y
    */
    public void execute(Map<String, Object> data, Map<String, Object> config, Map<String, Object> output, ApiClient api) {
        Double x = (Double) data.get("x");
        Double y = (Double) data.get("y");
        Double z = x + y;
        output.put("z", z);
    }
}
```

### Example output.json
```json
{"z":300.0}
```

### Example using the ProcessMaker 4 SDK
Get all users' email addresses
```java
import ProcessMaker_Client.ApiClient;
import java.io.*;
import java.util.*;

import ProcessMaker_Client.ApiClient;
import ProcessMaker_Client.ApiException;
import ProcessMaker_Client.Configuration;
import ProcessMaker_Client.auth.*;
import ProcessMaker_Model.*;
import ProcessMaker_Api.UsersApi;

public class Script implements BaseScript {
    public void execute(Map<String, Object> data, Map<String, Object> config, Map<String, Object> output, ApiClient api) {
        UsersApi apiInstance = new UsersApi(api);
        String filter = "";
        String orderBy = "id";
        String orderDirection = "asc";
        Integer perPage = 100;
        String include = "";

        try {
            InlineResponse20019 result = apiInstance.getUsers(filter, orderBy, orderDirection, perPage, include);

            List<String> emails = new ArrayList<>();
            for (Users u : result.getData()) {
                emails.add(u.getEmail());
            }
            output.put("emails", emails);

        } catch (ApiException e) {
            System.err.println("Exception when calling UsersApi");
            System.err.println("Status code: " + e.getCode());
            System.err.println("Reason: " + e.getResponseBody());
            e.printStackTrace();
        }
    }
}
```

## Command Line Usage
```bash
$ docker run -v <path to local data.json>:/opt/executor/data.json \
  -v <path to local config.json>:/opt/executor/config.json \
  -v <path to local Script.java>:/opt/executor/Script.java \
  -v <path to local output.json>:/opt/executor/output.json \
  processmaker4/docker-executor-java \
  /opt/executor/run.sh
```
