#!/usr/bin/perl
#./anal.pl salden.txt | less

use warnings;
use strict;

# salden.txt
my $jahr = 1999;

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
    printf "%04d-%02d-%02d %6d %2d.%02d\n", $jahr, $2, $1, , $zeit, $3, $4;
  } 
  if(m#(^\d{4})#g) {
    print "# Jahr $1\n";
    $jahr = $1;
  } else {
    if(m/(^#.*)/g) {
      print "# Kommentar  $1\n";
      $jahr = $1;
    }
  } 
}

