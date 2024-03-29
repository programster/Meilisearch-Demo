FROM ubuntu:22.04


VOLUME /storage


# Fix a timezone issue
ENV TZ=Europe/London
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone


# Update and install ondrej PPA for PHP 8.2
RUN apt-get update && apt-get dist-upgrade -y \
    && apt-get install -y software-properties-common apt-transport-https \
    && add-apt-repository ppa:ondrej/php -y


# Install the relevant packages
RUN apt-get install -y \
    vim apache2 curl libapache2-mod-php8.2 composer \
    php8.2-cli php8.2-bcmath php8.2-gmp php8.2-uuid php8.2-mbstring php8.2-xml php8.2-mysql php8.2-zip php8.2-curl


# Install the cron service to tie up the container's foreground process
RUN apt-get install cron -y


# Enable the php mod we just installed
RUN a2enmod php8.2
RUN a2enmod rewrite


# Expose web ports
EXPOSE 80
#EXPOSE 443


# Manually set the apache environment variables in order to get apache to work immediately.
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_RUN_DIR=/var/run/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2/apache2.pid


# Turn on display errors. We will disable them based on environment
RUN sed -i 's;display_errors = .*;display_errors = On;' /etc/php/8.2/apache2/php.ini
RUN sed -i 's;post_max_size = .*;post_max_size = 1G;' /etc/php/8.2/apache2/php.ini
RUN sed -i 's;upload_max_filesize = .*;upload_max_filesize = 1G;' /etc/php/8.2/apache2/php.ini


# Add the startup script to the container
ADD docker/startup.sh /root/startup.sh


# Update our apache sites available with the config we created
ADD docker/apache-config.conf /etc/apache2/sites-enabled/000-default.conf


# Use the crontab file.
# The crontab file was already added when we added "project"
ADD docker/crons.conf /root/crons.conf
RUN crontab /root/crons.conf


# Add the site's code to the container.
# We could mount it with volume, but by having it in the container, deployment is easier.
COPY --chown=root:www-data app /var/www/my-site
COPY --chown=root:www-data .env /var/www/my-site/.env


# Set permissions
RUN chown root:www-data /var/www
RUN chmod 750 -R /var/www


# Execute the containers startup script which will start many processes/services
# The startup file was already added when we added "project"
CMD ["/bin/bash", "/root/startup.sh"]
