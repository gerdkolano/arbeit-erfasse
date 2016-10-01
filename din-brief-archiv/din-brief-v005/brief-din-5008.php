<?php
require_once( "../include/datum.php"              );
require_once( "../include/informationsobjekt.php" );
require_once( "../include/anschriftobjekt.php" );

class html_seite {

  function html_head( $stylesheet) {
    header('Content-Type: text/html; charset=utf-8');
    $zuletzt_aktualisiert = "Brief zuletzt aktualisiert: Mi 2016-05-18 12:44:39";
    $erg = "";
    $erg .= "<!DOCTYPE html>\n";
    $erg .= "<html>\n";
    $erg .= "<head>\n";
    $erg .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
    $erg .= "<link rel=\"stylesheet\" href=\"$stylesheet\" type=\"text/css\">\n";
    $erg .= "<title>Din-5008-Brief</title>\n";
    $erg .= "</head>\n";
    $erg .= "<body>\n";
    $erg .= sprintf( "<!-- %s -->\n", $zuletzt_aktualisiert);
    return $erg;
  }
  
  function html_fusz( ) {
    $erg = "";
    $erg .= "</body>";
    $erg .= "</html>";
    $erg .= "\n";
    return $erg;
  }

}

class kasten {
  public $erg;

  function __construct( $f, $tief) {
    $this->erg = "";
    $this->kiste( $f, $tief);
  }
  
  function zeichnung() { return $this->erg; }
  
  function kiste( $f, $tief) {
    $d = 8; $e = $f - $d;
    $d = 4; $e = $f - $d;
    $d = 2; $e = $f - $d; $k = 0;
    $this->erg .= sprintf( "<div style='border:solid black %dpx; width:%dpx; height:%dpx; padding:%dpx 0px 0px %dpx; margin:0px 0px 0px 0px'>\n",
      $d,                              // 1 
    ( $tief * $f - $d) * 2 + $k,        // 2 5.5
    ( $tief * $f - $d) * 2 + $k,        // 4 4
      $e,
      $e 
    );
    if ($tief>1) {
      $this->kiste( $f, $tief-1);
    } else {
    }
    $this->erg .= "</div>\n";
  }

}

class din_brief {

  private $anschrift;
  private $infoblock;

  function __construct( anschriftobjekt $anschrift, informationsobjekt $infoblock) {
    $this->anschrift = $anschrift;
    $this->infoblock = $infoblock;
  }

  function briefkopf( $arg) {
    $kasten = (new kasten( 6,  5))->zeichnung();
    $abstand = ""; for ($i=0;$i<50;$i++) $abstand .= "&nbsp;"; $abstand .= "\n";
    $arg = sprintf( "<div style='display: inline-block;'>%s</div>", $arg);    
    $erg = sprintf( "<div style='display: inline-block;'>\n%s</div>", $kasten);    
    return sprintf( "<div class='briefkopf'>\n%s%s %s\n</div>\n",
      $arg,
      $abstand,
      $erg);
  }

  function absenderzeile( $arg) {
    return sprintf( "<div class='absenderzeile'>\n%s\n</div>\n", $arg);
  }

  function anschriftenfeld() {
    return sprintf( "<div class='anschriftenfeld'>\n%s<br />\n%s<br />\n%s<br />\n%s<br />\n%s<br />\n%s<br />\n%s<br />\n%s<br />\n%s<br />\n</div>\n",
      "" ,
      "" ,
      "" ,
      $this->anschrift->name      ,
      $this->anschrift->straße    ,
      $this->anschrift->stadt     ,
      "" ,
      "z.Hd.von" ,
      ""  
    );
  }

  function betreff( $arg) {
    return sprintf( "<div class='betreff'>\n%s\n</div>\n", $arg);
  }

  function textfeld( $arg) {
    return sprintf( "<div class='textfeld'>\n%s\n</div>\n", $arg);
  }

  function brieffusz( $arg) {
    return sprintf( "<div class='brieffusz'>\n%s\n</div>\n", $arg);
  }

  function alles() {
    $erg ="";
    $erg .= $this->briefkopf( "Sabine Schallehn<br />\nBerlin-Lichtenrade");
    $erg .= $this->absenderzeile( "Sabine Schallehn Löwenbrucher Weg 24c D-12307 Berlin");
    $erg .= $this->anschrift; // $this->anschriftenfeld();
    $erg .= $this->infoblock;
    $erg .= $this->betreff( "Verdienstabrechnungen und \"gfos 4.7plus\"-Zeitkontoauszüge");
    $erg .= $this->textfeld( "Sehr geehrte Frau Giebler,<p> meine Bitte um Информация, изложенная в этом блоге, предоставлена исключительно с целью ознакомления.  Используя эту информацию, вы действуете на свой страх и риск. <p> Автор не гарантирует результаты и не несет ответственность за последствия таких действий.  Перед выполнением действий, описанных в этом блоге, в информационных системах, убедитесь в том, что вы обладаете правом, навыками на выполнение таких действий и отдаете себе отчет о последствиях в случае не успешного результата.</p>  Перепечатки материалов этого блога, ссылки на источник и конструктивная критика приветствуются.  <p> ცნობილი ფაქტია, რომ გვერდის წაკითხვად შიგთავსს შეუძლია მკითხველის ყურადღება მიიზიდოს და დიზაინის აღქმაში ხელი შეუშალოს. Lorem Ipsum-ის გამოყენებით ვღებულობთ იმაზე მეტ-ნაკლებად სწორი გადანაწილების ტექსტს, ვიდრე ერთიდაიგივე გამეორებადი სიტყვებია ხოლმე.<p> შედეგად, ტექსტი ჩვეულებრივ ინგლისურს გავს, მისი წაითხვა კი შეუძლებელია. დღეს უამრავი პერსონალური საგამომცემლო პროგრამა და ვებგვერდი იყენებს Lorem Ipsum-ს, როგორც დროებით ტექსტს წყობის შესავსებად; Lorem Ipsum-ის მოძებნისას კი საძიებო სისტემები ბევრ დაუსრულებელ გვერდს გვიჩვენებენ. წლების მანძილზე ამ ტექსტის უამრავი ვერსია გამოჩნდა, ზოგი შემთხვევით დაშვებული შეცდომის გამო, ზოგი კი — განზრახ, ხუმრობით. "
 . " <p> meine Bitte um Информация, изложенная в этом блоге, предоставлена исключительно с целью ознакомления.  Используя эту информацию, вы действуете на свой страх и риск. <p> Автор не гарантирует результаты и не несет ответственность за последствия таких действий.  Перед выполнением действий, описанных в этом блоге, в информационных системах, убедитесь в том, что вы обладаете правом.  ");
    $erg .= $this->brieffusz( "IBAN DE49 10090000 205 306 4003 BIC BEVODDBE");
    $erg .= "<div class=\"page-break\"></div>\n";
    return $erg;
  }

}

class anschrift {
  public $name      ;
  public $straße    ;
  public $stadt     ;

  function __construct( $arg) {
    switch ($arg) {
    case "aldi-großbeeren" :
      $this->name      = "ALDI GmbH & Co. KG Großbeeren";
      $this->straße    = "Osdorfer Ring 21";
      $this->stadt     = "14979 Großbeeren";
      break;
    case "fred"            :
      $this->name      = "Manfred Birkhahn";
      $this->straße    = "Ostpreußendamm 24b";
      $this->stadt     = "12207 Berlin";
      break;
    case "sabine"          :
      $this->name      = "Sabine Schallehn";
      $this->straße    = "Löwenbrucher Weg 24c";
      $this->stadt     = "12307 Berlin";
      break;
    default                :
      break;
    }
  }

  function __toString() {
    return sprintf( "<div class='anschriftenfeld'>\n %s<br />\n %s<br />\n %s<br />\n </div>\n",
      $this->anschrift->name      ,
      $this->anschrift->straße    ,
      $this->anschrift->stadt
    );
  }
}

foreach( $_POST as $key => $val) {
  if (is_array( $val)) {
    foreach( $val as $akey=>$aval) {
      echo "akey $akey $aval §!<br>";
    }
  } else {
    echo "key $key $val !<br>";
  }
}

echo "<pre>POST"     ; print_r( $_POST)    ; echo "</pre>";

if (isset( $_POST["info"])) {
  $ein_infoblock = new informationsobjekt( $_POST["info"]);
} else {
  $ein_infoblock = new informationsobjekt();
}

if (isset( $_POST["anschrift"])) {
  $eine_anschrift = new anschriftobjekt( $_POST["anschrift"]);
} else {
  $eine_anschrift = new anschriftobjekt();
}

$eine_seite = new html_seite();
echo $eine_seite->html_head( "css-din-5008.css");

$ein_brief = new din_brief( $eine_anschrift, $ein_infoblock);
  echo $ein_brief->alles();

$ein_brief = new din_brief( $eine_anschrift, $ein_infoblock);
  echo $ein_brief->alles();

$ein_brief = new din_brief( $eine_anschrift, $ein_infoblock);
  echo $ein_brief->alles();

echo $eine_seite->html_fusz();

?>
