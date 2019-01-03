package pl.chomik.app;

import java.io.IOException;
import java.io.InputStream;
import java.util.Properties;

public class AppProperties {

    public static boolean LoadProperties(){
        System.out.println(System.getProperty("user.dir"));
        Properties prop = new Properties();
        InputStream input = null;

        try{
            input = AppProperties.class.getResourceAsStream("/pl/chomik/config/hamster.properties");
            prop.load(input);

            ADDRESS =  prop.getProperty("ip");
            DEBUGMODE =  prop.getProperty("debugmode");
        }
        catch (IOException e){
            System.out.println("[ DBH FAILED ] IOException");
            e.printStackTrace();
            return false;
        }
        finally {
            if (input != null){
                try{
                    input.close();
                    return true;
                }
                catch (IOException e){
                    System.out.println("[ APP FAILED ] IOException");
                    return false;
                }
            }
        }
        return false;
    }

    private static String ADDRESS;
    private static String DEBUGMODE;

    public static String getStoredAddress(){
        return ADDRESS;
    }
    public static Boolean getDebugState(){
        if (DEBUGMODE.equals("true")) return true;
        else return false;
    }
}
