## INFO ##
username="root";
password="water123";
database="digimahouse";

## DEPENDENCIES ##
command -v gzip >/dev/null 2>&1 || { sudo apt-get update; sudo apt-get install gzip; }
command -v pv >/dev/null 2>&1 || { sudo apt-get update; sudo apt-get install pv; }
command -v sshpass >/dev/null 2>&1 || { sudo apt-get update; sudo apt-get install sshpass; }

## LOGIN SSH ##
sshpass -p "digima2018" ssh -o StrictHostKeyChecking=no digima@128.199.182.197 "~/export.sh"

## DATABASE ##
mysql -u$username -p$password -e "DROP DATABASE $database"
mysql -u$username -p$password -e "CREATE DATABASE $database"

## IMPORT ##
rm -rf ~/digimahouse_latest_backup.sql.gz
wget -P ~/ https://uwealth100.com/digimahouse_latest_backup.sql.gz
rm -rf ~/digimahouse_latest_backup.sql
gunzip ~/digimahouse_latest_backup.sql.gz
pv ~/digimahouse_latest_backup.sql | mysql -u$username -p$password $database

echo "Imported Successfully"