# Chomik - wyszukiwarka mieszkań

<strong><ol>
  <li>Informacje o autorach</li>
  <li>Opis działania</li>
  <li>Wymagania dla aplikacji Chomik</li>
  <li>Uruchamianie aplikacji</li>
  <li>Konfiguracja</li>
  <li>Sposób użytkowania aplikacji klienckiej Chomik</li>
  <li>Podstawowe błędy mogące wystąpić podczas działąnia aplikacji klienckiej Chomik</li>
  <li>Parser</li>
  </ol></strong>


1. Informacje o autorach<br>
  Nad projektem pracują: Borek Kamil i Kozubek Dariusz.
  
2. Opis działania
  Projekt działa na zasadzie klient-serwer.
  Klientem jest aplikacja napisana w języku Java zawarta w katalogu GUI.
  Skrypt parsujący uruchomiany jest na serwerze. Aktualizuje dane co godzinę.
  Przeciętny czas aktualizacji danych wynosi ok. 5-10min.
  Klient łączy się za pomocą sieci Internet z serwerem pobierając dane o ogłoszeniach
  uwzględniając kryteria podane przez użytkownika. Po pobraniu informacji analizuje je
  i wyświetla umożliwiając użytkownikowi szybki przegląd ofert z danego serwisu.
  
3. Wymagania dla aplikacji Chomik
  <ul>
    <li>Środowisko uruchomieniowe Java Runtime w wersji 8</li>
    <li>Połączenie internetowe</li>
  </ul>
  
4. Uruchamianie aplikacji
  <ul>
    Po pobraniu aplikacji (plik Chomik.jar zawarty w katalogu GUI/Chomik/out/artifacts/Chomik_jar) uruchamiamy
    go za pomocą Java Runtime Environment (jest to domyślny program do obsługi plików *.jar więc w większosci przypadków
    wystarczy podwójne klikniecie na ikonę aplikacji).
    <li>
      Można również uruchomić aplikację z poziomu wiersza poleceń, aby otrzymać dodatkowe informacje, które
      aplikacja wysyła na konsolę. W tym celu należy:
      <ol>
        <li> uruchomić: <i>Wiersz polecenia</i> w systemie Windows lub dowolny terminal/konsolę
        w systemach Linux/macOS.</li>
        <li>Przejść do katalogu, w którym znajduje się plik <i>Chomik.jar</i></li>
        <li>Wpisać w konsoli:
        <pre>$ java -jar Chomik.jar</pre>
        i uruchomić wyżej wymienione polecenie. Opcja ta jest przydatna szczególnie, gdy używamy trybu debugowania.</li>
      </ol>
     </li> 
  </ul>
  
5. Konfiguracja
  Aplikacja do normalnego działania nie wymaga żadnej konfiguracji.
  Gdyby jednak nastąpił problem z połączeniem lub wystąpił by błąd użytkownik może zmienić domyślną konfigurację
  otwierając plik Chomik.jar w dowolnym programie do obsługi plików *.zip, *.rar itp. W katalogu <i>'pl/chomik/config'</i>
  znajduje się plik <i>'hamster.properties'</i>. 
  
  ![Image description](http://i68.tinypic.com/2ius6qd.png)
  <br/>
  Jego zawartość jest ładowana do aplikacji jako konfiguracja.
  Otwierając go dowolnym edytorem tekstowym możemy zmienić następujące opcje:
  <ul>
    <li>Adres serwera parsującego projektu Chomik</li>
    <li>Stan trybu debugowania (zaleca się używanie go tylko w celu wykrycia błędu)</li>
  </ul>
  Prawidłowa zawartość pliku <i>'hamster.properties'</i>
  <pre><i>
    ip=&ltadres_serwera&gt;<br>
    debugmode=&lt;stan_trybu_debugowania&gt;
  </i></pre>
  Opis opcji:
  <ul>
    <li>ip -> adres serwera zewnętrznego na którym działa oprogramowanie parsera</li>
    <li>debugmode -> umożliwia włączenie/wyłączenie trybu debugowania, zalecany tylko do wykrywanie błędów, testowania
    oraz do uzyskania szczegółowych informacji na temat działań wykonywanych przez Chomika (możliwe wartości to:
      <ul>
        <li><i>true</i> -> włącz tryb debugowania</li>
        <li><i>false</i> -> wyłącz tryb debugowania</li>
      </ul>
    </li>
  </ul>
  
  6. Sposób użytkowania aplikacji klienckiej Chomik
  <ul>
    <li>Po uruchomieniu aplikacji wyświetlone zostanie okno jak na przykładzie poniżej</li>
    <li>U góry okna znajduje się menu, w którym znajdziemy opcje takie jak:
      <ul>
        <li><i>Rozpocznij nową sesję</i> - Odnawia połączenie, czyści krysteria</li>
        <li><i>Zamknij Chomika</i> - zamyka aplikację kliencką Chomik</li>
        <li><i>Wyczyść kryteria</i> - czyści kryteria wprowdzone przez użytkownika</li>
        <li><i>Odśwież połączenie z bazą dancyh</i> - łączy ponownie z serwerem danych</li>
        <li><i>O projekcie ...</i> - pokazuje krótką informację o Chomiku</li>
      </ul>
    </li>
    <li>Użytkownik może wypełnić następujące kryteria (w dowolnej konfiguracji i ilości):
      <ul>
        <li><i>Miasto</i> - miasto dla szukanego mieszkania</li>
        <li><i>Cena (min)[zł]</i> - cena minimalna w zł</li>
        <li><i>Cena (max)[zł]</i> - cena maksymalna w zł</li>
        <li><i>Powierzchnia (min)[m^2]</i> - najmniejsza powierzchnia szukanego mieszkania</li>
        <li><i>Powierzchnia (max)[m^2]</i> - maksymalna powierzchnia szukanego mieszkania</li>
        <li><i>Liczba pokoi</i></li>
      </ul>
    </li>
    <li>Po wpisaniu kryteriów, klikając w <i>'Wyszukaj'</i> po chwili otrzymamy listę ofert</li>
    <li>Po zaznaczeniu interesującej nas oferty możemy użyć przycisku <i>'Otwórz ofertę</i> lub dwukrotnie kliknąć
    na nią, aby otworzyć okno szczególów oferty.</li>
    <li>Znajdziemy tam podstawowe informacje oraz link do oferty, który możemy skopiować lub otworzyć bezpośrednio
    ofertę w domyślnej przeglądarce używając przycisku <i>'Otwórz w przeglądarce'</i>.</li>
  </ul>
  
  ![Image description](http://i65.tinypic.com/2rmbhy0.png)
  
  7. Podstawowe błędy mogące wystąpić podczas działąnia aplikacji klienckiej Chomik
  <ul>
    <li><i>'Musisz zaznaczyć ogłoszenie, aby je otworzyć'</i></li>
    <li><i>'Pole może przyjmować tylko litery'</i> - aktualnie wypełniane pole kryterium
    nie przyjmuje wprowadzonego znaku</li>
    <li><i>'Pole może przyjmować tylko wartości numeryczne'</i> - aktualnie wypełniane pole kryterium
    nie przyjmuje wprowadzonego znaku</li>
    <li><i>'Nie można sprawdzić daty ostatniej aktualizacji'</i> - nie można połączyć z bazą danych,
    sprawdź konfigurację</li>
  </ul>
  
  8. Parser<br>
  Obecna wersja parsera obsługuje jedynie bazy danych serwera PostgreSQL. Obsługa serwisów ogłoszeniowych została rozdzielona do osobnych plików.<br>
  Konfiguracja parsera.<br>
  <ul>
  <li>Konfiguracja parsera polega na edycji pliku "pgsqlbdconfig.php" gdzie w odpowiednich polach podajemy dane potrzebne do połączenia z serwerem sql i tabel oraz odpowiednie nazwy kolumn.</li>
  Tabela główna posiada kolumny które przechowują dane: 
  <ul>
    <li>#kolumna nie jest używana w konfiguracji#\t-\tid ogłoszenia w naszej bazie, jest to Primary_key, auto_increment</li>
    <li>tab1col1&#09-&#09znakowe id ogłoszenia w serwisie</li>
    <li>tab1col2\t-\tliczbowe id ogłoszenia w serwisie</li>
    <li>tab1col3\t-\tnazwa miasta</li>
    <li>tab1col4\t-\tcena dla danego ogłoszenia</li>
    <li>tab1col5\t-\twielkość oferowanej nieruchomości</li>
    <li>tab1col6\t-\tliczba pokoi</li>
    <li>tab1col7\t-\tadres ogłoszenia</li>
    <li>tab1col8\t-\twstawiana jest zawsze aktualna data i czas dodania ogłoszenia do naszej bazy</li>
  </ul>
  Tabela dla przechowywania daty ostatniej aktualizacji tabeli głównej
  <ul>
    <li>datatabcol1\t-\tid dla danego serwisu</li>
    <li>datatabcol2\t-\tnazwa serwisu aktualizowanego</li>
    <li>datatabcol3\t-\tczas ostatniej aktualizacji</li>
  </ul>
  </ul>
  Opis działania<br>
  Skrypt obsługujący serwis otodom pobiera dane takie m.in. id_ogłoszenia, miasto, cenę, ilość pokoi, a następnie zapisuje je do odpowiednich kolumn danej tabeli w naszej bazie.<br>
  
project-borek-kozubek created by GitHub Classroom
