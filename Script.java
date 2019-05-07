import org.openapitools.client.api.UsersApi;
import ProcessMaker_Client.ApiClient;

public class Script {
    private final UsersApi api = new UsersApi();

    public static void main(String args[]) {
        int x=1;
        int y=2;
        int z=x+y;

        String baseApiUrl = System.getenv("API_HOST");
        String v3Token = System.getenv("API_TOKEN");
        int connectTimeout = 60000;
        int readTimeout = 120000;

        // Create an UsersApi
        ApiClient client = new ApiClient();

        client.setBasePath(baseApiUrl);
        client.setApiKey("Bearer " + v3Token);
        client.setConnectTimeout(connectTimeout);
        client.setDebugging(false);
        
        System.out.println("Sum of x+y = " + z);
    }
}
