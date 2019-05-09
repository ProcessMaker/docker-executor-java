import org.openapitools.client.api.UsersApi;
import ProcessMaker_Client.ApiClient;
import com.google.gson.Gson;
import java.io.*;
import java.lang.reflect.Type;
import com.google.gson.reflect.TypeToken;
import java.util.Map;
import java.util.HashMap;

public class Spark {

    public static ApiClient api() {
        String baseApiUrl = System.getenv("API_HOST");
        String v3Token = System.getenv("API_TOKEN");
        int connectTimeout = 60000;
        int readTimeout = 120000;

        ApiClient client = new ApiClient();

        client.setBasePath(baseApiUrl);
        if(v3Token != null && !v3Token.isEmpty()) {
            client.setApiKey("Bearer " + v3Token);
        }
        client.setConnectTimeout(connectTimeout);
        client.setDebugging(false);
        return client;
    }

    public static Map<String, Object> data() throws FileNotFoundException {
        Type typeOfHashMap = new TypeToken<Map<String, Object>>() { }.getType();
        BufferedReader bufferedReader = new BufferedReader(new FileReader("data.json"));
        Gson gson = new Gson();
        return gson.fromJson(bufferedReader, typeOfHashMap);
    }

    public static Map<String, Object> config() throws FileNotFoundException {
        Type typeOfHashMap = new TypeToken<Map<String, Object>>() { }.getType();
        BufferedReader bufferedReader = new BufferedReader(new FileReader("config.json"));
        Gson gson = new Gson();
        return gson.fromJson(bufferedReader, typeOfHashMap);
    }

    public static void printOutput(Map<String, Object> data) {
        String jsonString = new Gson().toJson(data);
        System.out.println(jsonString);
    }
}
