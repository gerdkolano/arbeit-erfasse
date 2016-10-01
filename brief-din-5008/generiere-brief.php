<?php
require_once( "../include/datum.php");

function brief( $quelle) {
  // A4 210 by 297 millimetres
  // --user-style-sheet
  $konverter = "/usr/bin/wkhtmltopdf";
  $ziel   = "/daten/srv/www/htdocs/arbeit/pdf/brief.pdf";
  $param  = "--margin-top  0 --margin-right 0 --margin-bottom 0 --margin-left 0 --orientation Portrait";
  $param .= " --user-style-sheet http://zoe.xeo/arbeit/brief-din-5008/css-din-5008-bunt.css"; // wirkungslos
  $kommando = "$konverter --margin-left 20 --margin-top 20 --page-size A4 --orientation Landscape \"$quelle\" $ziel";
  $kommando = "$konverter --margin-top  0 --margin-right 0 --margin-bottom 0 --margin-left 0 --page-height 600mm --page-width 400mm --orientation Portrait  \"$quelle\" $ziel";
  $kommando = "$konverter $param --page-size A4 --orientation Portrait  \"$quelle\" $ziel";
  $kommando = "$konverter $param --page-width  70mm --page-height  99mm \"$quelle\" $ziel";
  $kommando = "$konverter $param --page-width 140mm --page-height 198mm \"$quelle\" $ziel";
  $kommando = "$konverter $param --page-width 170mm --page-height 297mm \"$quelle\" $ziel";
  $kommando = "$konverter $param --page-width 210mm --page-height 297mm \"$quelle\" $ziel";
  $kommando = "$konverter $param --page-width 170mm --page-height 252mm \"$quelle\" $ziel";
  echo "$kommando\n";
  system( $kommando);
}

$url =

"http://zoe.xeo/arbeit/brief-din-5008/brief-din-5008.php"
 . "?gesendet-von=brief-schreiben.php"
 . "&anschrift[]="
 . "&anschrift[]="
 . "&anschrift[]="
 . "&anschrift[]=Aldi+GmbH+%26+Co.+KG"
 . "&anschrift[]=Frau+Giebler"
 . "&anschrift[]=Osdorfer+Ring+21"
 . "&anschrift[]=14979+Gro%C3%9Fbeeren"
 . "&anschrift[]="
 . "&anschrift[]="
 . "&info[]="
 . "&info[]="
 . "&info[]="
 . "&info[]="
 . "&info[]=Sabine+Schallehn"
 . "&info[]=030+744+09+05"
 . "&info[]="
 . "&info[]="
 . "&info[]="
 . "&brieftext[betreff]=Verdienstabrechnungen+und+gfos+4.7plus+Zeitkonto"
 . "&brieftext[anrede]=Sehr+geehrte+Frau+Giebler%2C"
 . "&allseiten[]=am+26.+5.+2015+hat+Herr+Heyland+mir+mitgeteilt+%3A%0D%0A"
 . "%0D%0A"
 . "%22Gern+zahlen+wir+Sie+auch+jeden+Monat+auf+null+Mehrarbeitsstunden+aus.%0D%0A"
 . "Sie+m%C3%BCssen+es+uns+nur+wissen+lassen.%22%0D%0A"
 . "%0D%0A"
 . "Bitte+informieren+Sie+mich+dar%C3%BCber%2C%0D%0A"
 . "ob+und+wenn+ja%2C+wann+mir+schon+einmal+%0D%0A"
 . "%22auf+null+Mehrarbeitsstunden+aus[gezahlt]%22%0D%0A"
 . "wurden%2C%0D%0A"
 . "so+dass+ich+einzusch%C3%A4tzen+lerne%2C%0D%0A"
 . "wie+dies+dokumentiert+wird.%0D%0A"
 . "Ohne+Ihre+Informationen+sehe+ich+f%C3%BCr+mich+keine+M%C3%B6glichkeit%2C%0D%0A"
 . "zu+verstehen%2C%0D%0A"
 . "geschweige+denn+zu+%C3%BCberpr%C3%BCfen%2C%0D%0A"
 . "was+sie+berechnen.%0D%0A"
 . "%0D%0A"
 . "%3Cp%3E%0D%0A"
 . "%0D%0A"
 . "Am+%0D%0A"
 . "2.+Mai+2016+%0D%0A"
 . "habe+ich+Sie+mit+Hilfe+eines+%22Von-an-Zettel%22s+gebeten%2C%0D%0A"
 . "mich+%C3%BCber%0D%0A"
 . "die+Modalit%C3%A4ten+der+Abrechnung+meiner+Arbeitszeiten+und+meines+Vedienstes%0D%0A"
 . "zu+informieren.%0D%0A"
 . "%0D%0A"
 . "Vielleicht+ist+diese+Nachricht+verloren+gegangen%2C%0D%0A"
 . "jedenfalls+haben+Sie+mir+bis+heute+weder+m%C3%BCndlich+noch+schriftlich+eine+Antwort+zuteil+werden+lassen.%0D%0A"
 . "%0D%0A"
 . "Gern+wiederhole+ich+deshalb+noch+einmal+meine+Bitten+und+sehe+Ihrer+Antwort+in+der+n%C3%A4chsten+Woche+entgegen.%0D%0A"
 . "%0D%0A"
 . "%3Cp%3E%0D%0A"
 . "Sie+haben+mir+am+21.4.2015+mitgeteilt%2C%0D%0A"
 . "ich+solle%0D%0A"
 . "anhand+der+%22gfos+4.7plus+Zeitkonto%22-Ausdrucke%0D%0A"
 . "selbst+%C3%BCberpr%C3%BCfen%2C%0D%0A"
 . "auf+welchen+Zeitraum%0D%0A"
 . "sich+54.70+Stunden+aus+meiner+Verdienstabrechnung+M%C3%A4rz+2016%0D%0A"
 . "erstrecken.%0D%0A"
 . "%0D%0A"
 . "%3Cp%3E%0D%0A"
 . "Das+kann+ich+nicht.%0D%0A"
 . ""
 . "&allseiten[]=%3Cp%3E%0D%0A"
 . "In+meiner+Verdienstabrechnung+finde+ich+Zeitangaben+wie+54.70+Std%2C+22.38+Std+und+2.32+Std.%0D%0A"
 . "%3Cp%3E%0D%0A"
 . "Diese+Zeitangaben+scheinen+mit+Angaben+wie+10.94%2C+11.19+und+00.58+%0D%0A"
 . "in+der+Spalte+%22Ist%22+meines+%22gfos+4.7plus+Zeitkonto%22-Ausdrucks+zusammenzuh%C3%A4ngen%2C%0D%0A"
 . "die+in+der+Spalte+%22Bemerkungen%22+mit+%22Auszahlung%22+gekennzeichnet+sind.%0D%0A"
 . "%0D%0A"
 . "%3Cp%3E%0D%0A"
 . "Die+Materie+scheint+mir+f%C3%BCr+eine+m%C3%BCndliche+Er%C3%B6rterung+zu+komplex+zu+sein.%0D%0A"
 . "Bitte+informieren+Sie+mich+schriftlich+dar%C3%BCber%2C%0D%0A"
 . "in+welchem+Zeitraum+54.70+Std%2C+22%2C38+Std+und+2%2C32+Std+aufgelaufen+sind+und%0D%0A"
 . "wie+sich+die+Angaben+in+der+%22Ist%22-Spalte+auf+Kontost%C3%A4nde+meines%0D%0A"
 . "%22gfos+4.7plus+Zeitkonto%22s+auswirken.%0D%0A"
 . "%0D%0A"
 . "%3Cp%3E%0D%0A"
 . "Meiner+Verdienstabrechnung+und+einem+%22gfos+4.7plus+Zeitkonto%22-Ausdruck+f%C3%BCr+M%C3%A4rz+2016%0D%0A"
 . "%3Cp%3E%0D%0A"
 . "entnehme+ich%3A%0D%0A"
 . "%3Cpre%3E%0D%0A"
 . "Anscheinend+gehen+Sie+von+54.70+Std+Sp%C3%A4tzuschlagszeit+aus+%2C+weil+10.94+20%25+von+54.70+sind.%0D%0A"
 . "Anscheinend+gehen+Sie+von+22.38+Std+Nachtzuschlagszeit+aus%2C+weil+11.19+50%25+von+22.38+sind.%0D%0A"
 . "Anscheinend+gehen+Sie+von++2.32+Std+Mehrarbeitszeit+aus+++%2C+weil+00.58+25%25+von++2.32+sind.%0D%0A"
 . "Mehrarbeitszeit+%3A+Nicht+durch+h%C3%A4lftige+Anrechnung+der+Verkaufsstellenpr%C3%A4mie+abgegoltene+Arbeitszeit.%0D%0A"
 . "%3C%2Fpre%3E%0D%0A"
 . "Treffen+meine+Vermutungen+zu%3F%0D%0A"
 . ""
 . "&allseiten[]=%3Cp%3E%0D%0A"
 . "%C3%84hnliche+Zeitangaben+finde+ich+auch+in+meiner+Verdienstabrechnung+Juli+2015%2C%0D%0A"
 . "denen+aber+nichts+in+meinen+%22gfos+4.7plus+Zeitkonto%22-Ausdrucken%0D%0A"
 . "entspricht.%0D%0A"
 . "%0D%0A"
 . "%3Cp%3E%0D%0A"
 . "In+den+mir+vorliegenden+%22gfos+4.7plus+Zeitkonto%22-Ausdrucken+%0D%0A"
 . "sind+dar%C3%BCber+hinaus+etliche+Arbeits-+und+Urlaubszeiten+falsch+angegeben.%0D%0A"
 . "Auch+sind+viele+%C3%9Cbertr%C3%A4ge+zwischen+den+Monaten+inkonsistent.%0D%0A"
 . "%3Cp%3E%0D%0A"
 . "Bitte+geben+Sie+mir+aktuelle+%22gfos+4.7plus+Zeitkonto%22-Ausdrucke%0D%0A"
 . "einschlie%C3%9Flich+Monatskonten-Salden%C3%BCbersichten%0D%0A"
 . "f%C3%BCr+die+Zeit+von+April+2015+bis+heute%2C+Ende+April+2016.%0D%0A"
 . "%0D%0A"
 . "%3Cp%3E%0D%0A"
 . ""
 . "&brieftext[grusz]=Mit+freundlichen+Gr%C3%BC%C3%9Fen"
 . "&brieftext[unterschrift]=Sabine+Schallehn"
  ;

if (true) brief( $url);

?>
