#!/usr/bin/env bash
# Derived from http://www.bluepiccadilly.com/2011/12/creating-mysql-database-and-user-command-line-and-bash-script-automate-process

FILE='./.env'
if [[ ! -f ${FILE} ]]; then
    echo "No environment file configured. Cancelling database setup."
    exit 0
fi

source ${FILE}

MYSQL=`which mysql`

Q1="CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;"
Q2="GRANT USAGE ON *.* TO '${DB_USER}'@'${DB_HOST}' IDENTIFIED BY '${DB_PASSWORD}';"
Q3="GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'${DB_HOST}';"
Q4="FLUSH PRIVILEGES;"
SQL="${Q1}${Q2}${Q3}${Q4}"

$MYSQL -u${DB_USER} -p${DB_PASSWORD} -e "$SQL"
