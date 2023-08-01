# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = '2'

@script = <<SCRIPT
# Install dependencies
apt-get -q=2 update --fix-missing

echo '==> Installing Linux tools'

cp /vagrant/config/bash_aliases /home/vagrant/.bash_aliases
chown vagrant:vagrant /home/vagrant/.bash_aliases
apt-get -q=2 install software-properties-common bash-completion curl tree zip unzip pv whois &>/dev/null

echo '==> Installing Git'

apt-get -q=2 install git &>/dev/null

echo '=>>>>>>>>>>> ADDING ADDITIONAL REPOSITORY'
apt install -y lsb-release gnupg2 ca-certificates apt-transport-https software-properties-common
LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
add-apt-repository ppa:ondrej/apache2
apt-get -q=2 update
echo '<<<<<<<<<<<<< END OF ADDING ADDITIONAL REPOSITORY'
echo '==> Installing Apache'
apt-get -q=2 install apache2 apache2-utils &>/dev/null
apt-get -q=2 update


# Configure Apache
echo '<VirtualHost *:80>
	DocumentRoot /var/www/public
	AllowEncodedSlashes On

	<Directory /var/www/public>
		Options +Indexes +FollowSymLinks
		DirectoryIndex index.php index.html
		Order allow,deny
		Allow from all
		AllowOverride All
	</Directory>

	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf
a2enmod rewrite
sudo service apache2 restart


apt-get -q=2 update

apt-get -q=2 install php8.2 libapache2-mod-php8.2 libphp8.2-embed php8.2-bcmath php8.2-bz2 php8.2-cli php8.2-curl php8.2-fpm php8.2-gd php8.2-imap php8.2-intl php8.2-mbstring php8.2-mysql php8.2-mysqlnd php8.2-opcache php8.2-pgsql php8.2-pspell php8.2-soap php8.2-sqlite3 php8.2-tidy php8.2-xdebug php8.2-xml php8.2-xmlrpc php8.2-yaml php8.2-zip
a2dismod mpm_event &>/dev/null
a2enmod mpm_prefork &>/dev/null
a2enmod php8.2 &>/dev/null
#cp /vagrant/config/php.ini.htaccess /var/www/.htaccess
PHP_ERROR_REPORTING_INT=$(php -r 'echo '"$PHP_ERROR_REPORTING"';')
#sed -i 's|PHP_ERROR_REPORTING_INT|'$PHP_ERROR_REPORTING_INT'|' /var/www/.htaccess


if [ -e /usr/local/bin/composer ]; then
    /usr/local/bin/composer self-update
else
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

# Reset home directory of vagrant user
if ! grep -q "cd /var/www" /home/vagrant/.profile; then
    echo "cd /var/www" >> /home/vagrant/.profile
fi

echo "** [Laminas] Run the following command to install dependencies, if you have not already:"
echo "    vagrant ssh -c 'composer install'"
echo "** [Laminas] Visit http://localhost:8080 in your browser for to view the application **"

DBHOST=localhost
DBNAME=default_project_db
DBUSER=project_user
DBPASSWD=qaz123wsx

apt-get update
apt-get install vim curl build-essential python-software-properties git

debconf-set-selections <<< "mysql-server mysql-server/root_password password $DBPASSWD"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $DBPASSWD"
debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $DBPASSWD"
debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $DBPASSWD"
debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $DBPASSWD"
debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect none"

# install mysql and admin interface

apt-get -y install mysql-server

mysql -uroot -p$DBPASSWD -e "CREATE DATABASE $DBNAME"
mysql -uroot -p$DBPASSWD -e "grant all privileges on $DBNAME.* to '$DBUSER'@'%' identified by '$DBPASSWD'"

cd /home/vagrant

# update mysql conf file to allow remote access to the db

sudo sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf

sudo service mysql restart
sudo apt-get -qq update


echo "***********************************"
echo "Install and re-link node and npm..."
echo "***********************************"
cd /home/vagrant
# Install NVM
  git clone https://github.com/creationix/nvm.git ~/.nvm && cd ~/.nvm && git checkout `git describe --abbrev=0 --tags`
  source ~/.nvm/nvm.sh
  echo "source ~/.nvm/nvm.sh" >> ~/.bashrc

  # Install Node
  echo "Installing Node.js (please be patient)"
  nvm install stable &> /dev/null
  nvm alias default stable
sudo service apache2 stop
sudo service apache2 start
SCRIPT

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = 'bento/ubuntu-22.04'
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "forwarded_port", guest: 3306, host: 3306
  config.vm.network "public_network", :bridge => "eth0", ip: "192.168.1.237"
  config.vm.synced_folder '.', '/var/www'
  config.vm.provision 'shell', inline: @script
  config.vm.provider "virtualbox" do |vb|
    vb.customize ["modifyvm", :id, "--memory", "1024"]
    vb.customize ["modifyvm", :id, "--name", "Laminas MVC Skeleton - Ubuntu 18.04"]
  end
end
