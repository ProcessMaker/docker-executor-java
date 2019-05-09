import org.openapitools.client.api.UsersApi;
import ProcessMaker_Client.ApiClient;
import com.google.gson.Gson;
import java.io.*;
import java.lang.reflect.Type;
import com.google.gson.reflect.TypeToken;
import java.util.Map;
import java.util.HashMap;

public class Main {

    public static void main(String args[]) throws FileNotFoundException {
        Script script = new Script();
        Map<String, Object> data = Spark.data();
        Map<String, Object> config = Spark.config();
        Map<String, Object> output = new HashMap<String, Object>();
        ApiClient api = Spark.api();
        script.execute(data, config, output, api);
        Spark.printOutput(output);
    }
}
