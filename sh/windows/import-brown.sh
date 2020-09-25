## INFO ##
username="root";
password="";
database="brown";

## LOGIN SSH ##
rm database/digimahouse_latest_backup.sql.gz
rm database/digimahouse_latest_backup.sql

plink -pw "digima2018" digima@brown.com.ph "~/export.sh"

## DATABASE ##
mysql -u$username -e "DROP DATABASE $database"
mysql -u$username -e "CREATE DATABASE $database"

curl --insecure "https://brown.com.ph/digimahouse_latest_backup.sql.gz" -o database/digimahouse_latest_backup.sql.gz
gunzip database/digimahouse_latest_backup.sql.gz
mysql -u $username $database < database/digimahouse_latest_backup.sql

rm database/digimahouse_latest_backup.sql.gz
rm database/digimahouse_latest_backup.sql

echo "Imported Successfully"
start message.bat