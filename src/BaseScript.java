import org.openapitools.client.api.UsersApi;
import ProcessMaker_Client.ApiClient;
import com.google.gson.Gson;
import java.io.*;
import java.lang.reflect.Type;
import com.google.gson.reflect.TypeToken;
import java.util.Map;
import java.util.HashMap;

public interface BaseScript {

    public void execute(Map<String, Object> data, Map<String, Object> config, Map<String, Object> output);
}
