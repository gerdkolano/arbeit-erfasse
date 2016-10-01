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
rsync -auv --exclude '*~' --exclude '.??*' /daten/srv/www/htdocs/arbeit/ /fadi/daten/srv/www/htdocs/arbeit/

exit 0

rsync  --delete-excluded -auv --exclude '*~' --exclude '.??*' /daten/srv/www/htdocs/arbeit/ /fadi/daten/srv/www/htdocs/arbeit/

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

