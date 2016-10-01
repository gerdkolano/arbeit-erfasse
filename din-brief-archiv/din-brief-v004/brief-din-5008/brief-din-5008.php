<?php
class html_seite {

  function html_head( $stylesheet) {
    header('Content-Type: text/html; charset=utf-8');
    $zuletzt_aktualisiert = "Brief zuletzt aktualisiert: Di 2016-05-17 18:53:38";
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

  function __construct( anschrift $anschrift, informationsobjekt $infoblock) {
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

  function informationsblock() {
    $erg = sprintf( ""
      . "<tr><td class='infolabel'>Ihr Zeichen:          <td class='infoinhalt'>%s</tr>\n" 
      . "<tr><td class='infolabel'>Ihre Nachricht vom:   <td class='infoinhalt'>%s</tr>\n" 
      . "<tr><td class='infolabel'>Unser Zeichen:        <td class='infoinhalt'>%s</tr>\n" 
      . "<tr><td class='infolabel'>Unsere Nachricht vom: <td class='infoinhalt'>%s</tr>\n" 
      . "<tr><td class='infolabel'>Name:                 <td class='infoinhalt'>%s</tr>\n" 
      . "<tr><td class='infolabel'>Telefon:              <td class='infoinhalt'>%s</tr>\n" 
      . "<tr><td class='infolabel'>E-Mail:               <td class='infoinhalt'>%s</tr>\n" 
      . "<tr><td class='infolabel'>                      <td class='infoinhalt'>%s</tr>\n" 
      . "<tr><td class='infolabel'>Datum:                <td class='infoinhalt'>%s</tr>\n"
      . "\n",
      $this->infoblock->Ihr_Zeichen           ,
      $this->infoblock->Ihre_Nachricht_vom    ,
      $this->infoblock->Unser_Zeichen         ,
      $this->infoblock->Unsere_Nachricht_vom  ,
      $this->infoblock->Name                  ,
      $this->infoblock->Telefon               ,
      $this->infoblock->E_Mail                ,
      $this->infoblock->qqq                   ,
      $this->infoblock->Datum                  
    );
    return sprintf( "<div class='informationsblock'>\n<table style='border-spacing:0;padding:0'>\n%s</table></div>\n", $erg); 
  }

  function informationsblock_00( $arg) {
    return sprintf( "<div class='informationsblock'>\n" 
      . "<span style='font-size:6pt'>Ihr Zeichen:          </span>%s<br />\n" 
      . "<span style='font-size:6pt'>Ihre Nachricht vom:   </span>%s<br />\n" 
      . "<span style='font-size:6pt'>Unser Zeichen:        </span>%s<br />\n" 
      . "<span style='font-size:6pt'>Unsere Nachricht vom: </span>%s<br />\n" 
      . "<span style='font-size:6pt'>Name:                 </span>%s<br />\n" 
      . "<span style='font-size:6pt'>Telefon:              </span>%s<br />\n" 
      . "<span style='font-size:6pt'>E-Mail:               </span>%s<br />\n" 
      . "<span style='font-size:6pt'>                      </span>%s<br />\n" 
      . "<span style='font-size:6pt'>Datum:                </span>%s<br />\n"
      . "</div>\n",
      "" ,
      "" ,
      "" ,
      "" ,
      "Sabine Schallehn" ,
      "030 744 09 05" ,
      "" ,
      "" ,
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
    $erg .= $this->anschriftenfeld();
    $erg .= $this->informationsblock();
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

class iinformationsobjekt {
    public $Ihr_Zeichen            ;          
    public $Ihre_Nachricht_vom     ;   
    public $Unser_Zeichen          ;        
    public $Unsere_Nachricht_vom   ; 
    public $Name                   ;                 
    public $Telefon                ;              
    public $E_Mail                 ;               
    public $qqq                    ;                  
    public $Datum                  ;                

  function __construct(
    $Ihr_Zeichen            ,          
    $Ihre_Nachricht_vom     ,   
    $Unser_Zeichen          ,        
    $Unsere_Nachricht_vom   , 
    $Name                   ,                 
    $Telefon                ,              
    $E_Mail                 ,               
    $qqq                    ,                  
    $Datum                                   
  ) {
    $this->Ihr_Zeichen          = $Ihr_Zeichen          ;
    $this->Ihre_Nachricht_vom   = $Ihre_Nachricht_vom   ;
    $this->Unser_Zeichen        = $Unser_Zeichen        ;
    $this->Unsere_Nachricht_vom = $Unsere_Nachricht_vom ;
    $this->Name                 = $Name                 ;
    $this->Telefon              = $Telefon              ;
    $this->E_Mail               = $E_Mail               ;
    $this->qqq                  = $qqq                  ;    
    $this->Datum                = $Datum                ;
  }

}

class informationsobjekt {
    public $Ihr_Zeichen            ;          
    public $Ihre_Nachricht_vom     ;   
    public $Unser_Zeichen          ;        
    public $Unsere_Nachricht_vom   ; 
    public $Name                   ;                 
    public $Telefon                ;              
    public $E_Mail                 ;               
    public $qqq                    ;                  
    public $Datum                  ;                

  function __construct() {
    $ii = 0;
    foreach ( get_object_vars( $this) as $key=>$val) {
      $this->$key = func_get_args()[$ii++];
    }
  }

}

$ein_infoblock = new informationsobjekt (
"Ihr Zeichen"           , # Ihr_Zeichen            ,          
"Ihre Nachricht vom"    , # Ihre_Nachricht_vom     ,   
"Unser Zeichen"         , # Unser_Zeichen          ,        
"Unsere Nachricht vom"  , # Unsere_Nachricht_vom   , 
"Name"                  , # Name                   ,                 
"Telefon"               , # Telefon                ,              
"E-Mail"                , # E_Mail                 ,               
"qqq"                   , # qqq                    ,                  
"Datum"                   # Datum                                   
);

$eine_seite = new html_seite();
echo $eine_seite->html_head( "css-din-5008.css");

$ein_brief = new din_brief( new anschrift( "sabine"), $ein_infoblock);
  echo $ein_brief->alles();

$ein_brief = new din_brief( new anschrift( "aldi-großbeeren"), $ein_infoblock);
  echo $ein_brief->alles();

$ein_brief = new din_brief( new anschrift( "fred"), $ein_infoblock);
  echo $ein_brief->alles();

echo $eine_seite->html_fusz();

?>
