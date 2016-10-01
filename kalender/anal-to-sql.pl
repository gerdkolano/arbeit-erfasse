#!/usr/bin/perl
# ./anal-to-sql.pl salden.txt | less
# ./anal-to-sql.pl < salden.txt | mysql -h zoe.xeo -u hanno -p"" arbeit
# select * from saaldo order by datum;

use warnings;
use strict;

# salden.txt
my $jahr = 1999;

my $table = "saldo";
print "CREATE TABLE IF NOT EXISTS $table (datum date, i_saldo_dauer decimal(5,2));\n";
while(<>) {
  if(m#(\d+)\.(\d+)\. ([+-]{0,1}\d+)\.(\d+)#g) {
    # print "$jahr-$2-$1 $3.$4\n";
    my $zeit;
    #$zeit = ($3 < 0) ? : - (-100 * $3 + $4) : (100 * $3 + $4);
    if ($3 < 0) {
	    $zeit =  - (-100 * $3 + $4);
    } else {
	    $zeit =   (100 * $3 + $4);
    }
    printf "INSERT $table (datum, i_saldo_dauer) VALUES ( '%04d-%02d-%02d', %2d.%02d);\n", $jahr, $2, $1, $3, $4;
    # INSERT saaldo (datum, i_saldo_dauer) VALUES ( '2016-03-04', '29.16');
  } 
  if(m#(^\d{4})#g) {
    print "-- Jahr $1\n";
    $jahr = $1;
  } else {
    if(m/(^#.*)/g) {
      print "-- Kommentar  $1\n";
      $jahr = $1;
    }
  } 
}

# 2015-04-22   3420 34.20
#
