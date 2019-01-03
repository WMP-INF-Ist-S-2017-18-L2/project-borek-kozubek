package pl.chomik.app;

import java.sql.*;

public class DatabaseHandler {
    public static Connection connect;

    public static Connection Connector(){
        AppProperties appProperties = new AppProperties();
        String ipaddr = appProperties.getStoredAddress();

        try{
            Class.forName("org.postgresql.Driver");

            if(AppProperties.getDebugState()) System.out.println("[ DBH INIT ] Próba połączenia z serwerem . . . (" + ipaddr + ")");
            connect = DriverManager.getConnection("jdbc:postgresql://" + ipaddr + ":5432/Chomik", "postgres", "postgres");
            if(connect!=null){
                if(AppProperties.getDebugState()) System.out.println("[ DBH OK ] CONNECT > POŁĄCZONO Z BAZĄ DANYCH [connect:" + connect + "]");
                return connect;
            }
            else{
                if(AppProperties.getDebugState()) System.out.println("[ DBH FAILED ] CONNECT > NIEUDANA PRÓBA ŁĄCZENIA Z BAZĄ DANYCH");
                return null;
            }
        }
        catch(ClassNotFoundException e){
            if(AppProperties.getDebugState()) System.out.println("[ DBH FAILED ] CONNECT > NIE ZNALEZIONO STEROWNIKA BAZY DANYCH");
            return null;
        }
        catch(SQLException e){
            if(AppProperties.getDebugState()) System.out.println("[ DBH FAILED ] CONNECT > SQLException");
            return null;
        }
    }

    public static Boolean CloseConnection(){
        try{
            connect.close();
            if(AppProperties.getDebugState()) System.out.println("[ DBH OK ] CLOSE > POMYŚLNIE ROZŁĄCZONO Z BAZĄ DANYCH");
            return true;
        }
        catch(SQLException e){
            if(AppProperties.getDebugState()) System.out.println("[ DBH FALSE ] CLOSE > ROZŁĄCZANIE Z BAZĄ DANYCH NIE POWIODŁO SIĘ");
            return false;
        }
    }

    public static ResultSet Query(String sqlstring){
        try{
            Statement stmt = connect.createStatement();
            ResultSet rs = stmt.executeQuery(sqlstring);
            if(AppProperties.getDebugState()) System.out.println("[ DBH OK ] UPDATE > ODCZYTANO DANE Z BAZY");
            return rs;
        }
        catch(SQLException e){
            if(AppProperties.getDebugState()) System.out.println("[ DBH FAILED ] UPDATE > SQLException");
            return null;
        }
    }
}
