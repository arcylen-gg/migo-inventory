## INFO ##
username="root";
password="";
database="jcawellness";

## LOGIN SSH ##
rm database/digimahouse_latest_backup.sql.gz
rm database/digimahouse_latest_backup.sql

plink -pw "digima2018" digima@128.199.202.35 "~/export.sh"

## DATABASE ##
mysql -u$username -e "DROP DATABASE $database"
mysql -u$username -e "CREATE DATABASE $database"

curl --insecure "http://jcawellness.com/digimahouse_latest_backup.sql.gz" -o database/digimahouse_latest_backup.sql.gz
gunzip database/digimahouse_latest_backup.sql.gz
mysql -u $username $database < database/digimahouse_latest_backup.sql

rm database/digimahouse_latest_backup.sql.gz
rm database/digimahouse_latest_backup.sql

## IMPORT ##
# rm -rf ~/digimahouse_latest_backup.sql.gz
# wget -P ~/ http://digimahouse.com/digimahouse_latest_backup.sql.gz
# rm -rf ~/digimahouse_latest_backup.sql
# gunzip ~/digimahouse_latest_backup.sql.gz
# pv ~/digimahouse_latest_backup.sql | mysql -u$username -p$password $database

echo "Imported Successfully"
start message.bat