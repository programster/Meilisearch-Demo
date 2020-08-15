# Please do not manually call this file!
# This script is run by the docker container when it is "run"


# Ensure our web storage area is set up the way we need.
mkdir -p /storage
chmod 770 /storage
chown www-data:www-data /storage


# Ensure the database is fully up before migrations attempt to run.
sleep 10


# Run migrations
/usr/bin/php /var/www/my-site/scripts/migrate.php


# Run the apache process in the background
/usr/sbin/apache2 -D APACHE_PROCESS &


# Run the cron service in the foreground to tie up the docker container
cron -f