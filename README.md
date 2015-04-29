# PiTemp
System for temperature and humidity measurement. 
Based on Raspberry Pi and DHT11 sensor.
Originally made in 2014.

### Raspberry Pi GPIO 
Use only marked pins.
![Raspberry Pi GPIO](http://imagizer.imageshack.us/a/img907/7799/yduTNz.jpg)

### Scheme
![](http://imageshack.com/a/img901/363/XR8oEv.png)
### Requirements
System was designed to work on the Raspberry Pi version B.
Based on Raspbian with apache server, php5 and mysql installed.
Also php ssh2 extension is needed
http://php.net/manual/en/book.ssh2.php
Install :
```
sudo apt-get install libssh2-1-dev libssh2-php
```
Don't forget to restart Apache 
```
service apache2 restart
```

### Install
Program is based on WiringPI so you need to install it first
```
sudo apt-get install git-core build-essential
git clone git://git.drogon.net/wiringPi
cd wiringPi
./build
```
To get data from sensor you need to compile source from /src/ named DHT11.c.
Copy that source to /home/pi and run command: 
```
gcc -o dht11 dht11.c -L/usr/local/lib -lwiringPi -lpthread
```
If program was compiled properly you are able to use command:
```
./dht11
```
It should return 2 variables (humidity and temperature) like:
```
33.0,27.0
```

### Usage
Upload project files to /temp/ folder on your Raspberry Pi and go to
```
http://raspberry_pi_address/temp/install.php
```
After that you can go to /temp/ folder
```
http://raspberry_pi_address/temp/
```
![](http://imageshack.com/a/img912/3888/54Ny9N.png)

### Cron

You can also run a cron work for automatically measurement.
```
crontab -e
0 22 0 0 0 wget ‘http://localhost/temp/add.php’ 
update-rc.d cron defaults
```
Sometimes mysql service can freeze. To resolve this problem use:
```
0 01 * * * service mysql restart
```
Or use SQLite :)