package pl.chomik.app;

import javafx.application.Application;
import javafx.application.Platform;
import javafx.event.EventHandler;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.image.Image;
import javafx.stage.Stage;
import javafx.stage.WindowEvent;

public class Main extends Application {
    @Override
    public void start(Stage mainStage) throws Exception{
        if(AppProperties.LoadProperties()){
            System.out.println("[ APP OK ] Pobrano ustawienia z pliku");

            System.out.println("[ APP OK ] START > URUCHAMIANIE APLIKACJI");
            FXMLLoader fxmlloader = new FXMLLoader(getClass().getResource("/pl/chomik/frames/MainGuiFXML.fxml"));
            Parent rootGui = fxmlloader.load();

            mainStage.setMinHeight(600);
            mainStage.setMinWidth(1020);
            mainStage.setHeight(600);
            mainStage.setWidth(1020);
            mainStage.setTitle("Chomik [otodom]");
            mainStage.getIcons().add(new Image(Main.class.getResourceAsStream("/pl/chomik/img/icon.png")));
            mainStage.setScene(new Scene(rootGui, 1020,600));
            rootGui.getStylesheets().add("pl/chomik/css/MainCSS.css");

            if(AppProperties.getDebugState()) System.out.println("[ APP OK ] START > TWORZENIE GUI");
            mainStage.show();

            mainStage.setOnCloseRequest(new EventHandler<WindowEvent>() {
                @Override
                public void handle(WindowEvent event) {
                    if(AppProperties.getDebugState()) System.out.println("[ APP OK ] Zamykanie aplikacji");
                    Platform.exit();
                }
            });
        }
        else {
            System.out.println("[ APP FAILED ] Nie można uruchomić aplikacji. Błąd podczas ładowania pliku ustawień.");
        }
    }

    public static void main(String[] args){
        launch(args);
    }
}
