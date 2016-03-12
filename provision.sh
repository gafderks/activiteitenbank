#!/usr/bin/env bash

apache_config_file="/etc/apache2/envvars"
apache_vhost_file="/etc/apache2/sites-available/vagrant_vhost.conf"
php_config_file="/etc/php5/apache2/php.ini"
xdebug_config_file="/etc/php5/mods-available/xdebug.ini"
mysql_config_file="/etc/mysql/my.cnf"
default_apache_index="/var/www/html/index.html"
project_web_root="public"

main() {
    apt-get update
    install_apache
    install_php
    apt-get -y autoremove
}

install_apache() {
    apt-get -y install apache2

    sed -i "s/^\(.*\)www-data/\1vagrant/g" ${apache_config_file}
    chown -R vagrant:vagrant /var/log/apache2

    if [ ! -f "${apache_vhost_file}" ]; then
        cat << EOF > ${apache_vhost_file}
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /vagrant/${project_web_root}
    LogLevel debug

    ErrorLog /var/log/apache2/error.log
    CustomLog /var/log/apache2/access.log combined

    <Directory /vagrant/${project_web_root}>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF
    fi

    a2dissite 000-default
    a2ensite vagrant_vhost

    a2enmod rewrite

    service apache2 reload
    update-rc.d apache2 enable
}

install_php() {
    apt-get -y install php7.0 php7.0-curl php7.0-mysql php7.0-sqlite php7.0-xdebug php7.0-pear

    sed -i "s/display_startup_errors = Off/display_startup_errors = On/g" ${php_config_file}
    sed -i "s/display_errors = Off/display_errors = On/g" ${php_config_file}

    if [ ! -f "{$xdebug_config_file}" ]; then
        cat << EOF > ${xdebug_config_file}
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.remote_port=9000
xdebug.remote_host=10.0.2.2
EOF
    fi

    service apache2 reload

    # Install latest version of Composer globally
    if [ ! -f "/usr/local/bin/composer" ]; then
        curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    fi

    # Install PHP Unit 4.8 globally
    if [ ! -f "/usr/local/bin/phpunit" ]; then
        curl -O -L https://phar.phpunit.de/phpunit-old.phar
        chmod +x phpunit-old.phar
        mv phpunit-old.phar /usr/local/bin/phpunit
    fi
}

main
exit 0