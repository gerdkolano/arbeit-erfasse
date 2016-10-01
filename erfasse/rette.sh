#!/bin/bash

DATUM=`date '+%Y_%m_%d_%H_%M_%S'`
DUMPDATEI="arbeit-$DATUM.sql"
TABLE_NAME="zeiten"
DATABASE_NAME="arbeit"
parole=`/usr/local/bin/koerperteil mysql`

echo "CREATE TABLE IF NOT EXISTS ${TABLE_NAME}_$DATUM LIKE $TABLE_NAME;
INSERT ${TABLE_NAME}_$DATUM SELECT * FROM $TABLE_NAME;
" | mysql -h fadi -u hanno -p$parole $DATABASE_NAME

mysqldump  -hzoe.xeo -uhanno -p$parole $DATABASE_NAME > /daten/srv/www/htdocs/arbeit/archiv/$DUMPDATEI
echo  'create database if not exists $DATABASE_NAME ;' | mysql -h fadi -u hanno -p$parole
mysql -h fadi -u hanno -p$parole $DATABASE_NAME < /daten/srv/www/htdocs/arbeit/archiv/$DUMPDATEI

echo "rsync -n -auv --delete --exclude '*~' --exclude '.??*' /daten/srv/www/htdocs/arbeit/ /fadi/daten/srv/www/htdocs/arbeit/"
echo 'for i in `find /daten/srv/www/htdocs/arbeit/ -name logging` ; do :> $i; done'
echo 'for i in `find /daten/srv/www/htdocs/arbeit/ -name logging` ; do ls -l $i; done'

cp -auv /daten/srv/www/htdocs/arbeit/brief-din-5008/  /daten/srv/www/htdocs/arbeit/din-brief-archiv/din-brief-v011

find /daten/srv/www/htdocs/arbeit/din-brief-archiv/ -name '*.swp' -exec rm -v {} \;

rsync -auv --exclude '*~' --exclude '.??*' /daten/srv/www/htdocs/arbeit/ /fadi/daten/srv/www/htdocs/arbeit/

exit 0

rsync -n -auv --delete-excluded --exclude '*~' --exclude '.??*' /daten/srv/www/htdocs/arbeit/ /fadi/daten/srv/www/htdocs/arbeit/

exit 0


/daten/srv/www/htdocs

hanno@zoe:~$ echo  'create database $DATABASE_NAME;' | mysql -h fadi -u hanno -p$parole  

hanno@zoe:~$ mysqldump  -hzoe.xeo -uhanno -p$parole $DATABASE_NAME > /tmp/$DATABASE_NAME.sql
mysql -h fadi -u hanno -p$parole  
mysql> create database if not exists $DATABASE_NAME;
hanno@zoe:~$ mysql -h fadi -u hanno -p$parole $DATABASE_NAME < /tmp/$DATABASE_NAME.sql


CREATE TABLE IF NOT EXISTS $TABLE_NAME_backup_2016_03_19 LIKE $TABLE_NAME;
INSERT $TABLE_NAME_backup_2016_03_19 SELECT * FROM $TABLE_NAME;


exit 0

for i in `find /daten/srv/www/htdocs/arbeit/ -name logging` ; do :> $i; done
for i in `find /daten/srv/www/htdocs/arbeit/ -name logging` ; do ls -l $i; done
for i in `find /daten/srv/www/htdocs/arbeit/ -name logging` ; do chown -R www-data: $i; done

cp -auv /daten/srv/www/htdocs/arbeit/erfasse ../archiv/erfasse-v002

exit 0

franzimint erfasse # mysql -h localhost -u hanno -p$parole arbeit < /var/www/html/arbeit/archiv/arbeit-2016_04_18_10_38_04.sql
franzimint erfasse # rsync -n -auv --exclude '.??*' --delete-excluded /zoe/daten/srv/www/htdocs/arbeit/ /var/www/html/arbeit/

W Austrii wygrywa ktoś, kim straszą prawe dzieci,  żeby cukru  nie jadły.
