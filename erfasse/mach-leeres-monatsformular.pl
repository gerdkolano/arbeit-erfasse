#!/usr/bin/perl

# http://zoe.xeo/arbeit/erfasse/zeitkonto.php?verbose=monate&wann=2016-04-15
# Klick rechts
# Seite Speichern unter /daten/srv/www/htdocs/arbeit/erfasse/Zeitkonto.html
# /daten/srv/www/htdocs/arbeit/erfasse/mach-leeres-monatsformular.pl /daten/srv/www/htdocs/arbeit/erfasse/Zeitkonto.html > /daten/srv/www/htdocs/arbeit/erfasse/form-monat-leer.html
#

use warnings;
use strict;

my $linie = "............";
$linie = ".&nbsp.&nbsp.&nbsp.&nbsp.&nbsp.&nbsp.&nbsp.&nbsp.";

while (<>) {
  s#März 2016#$linie#g; 
  s#April 2016#$linie#g; 
  s#Mai 2016#$linie#g; 
  s#September 2016#$linie#g;
  s#[^>A-Za-z]*</td>#</td>#g; 
  s#[^A-Za-z]*Arbeitsstunden# $linie Arbeitsstunden#g;
  s#Prämie[^A-Za-z]*#Prämie $linie #g;
  s#Verfallszeit[^A-Za-z]*#Verfallszeit $linie #g;
  s#Analysealgorithmus.*##g;
  s#gemacht am.*#gemacht am $linie#g;
  print;
}
