# htmltopdf

## Description
This app can convert URL to PDF for only whitelisted IP Addresses.

## Installation
1. Download
```bash
git clone git@github.com:mrofi/htmltopdf.git
```
2. Install Dependencies
On MacOS you can install Puppeteer in your project via NPM:
```bash
npm install puppeteer
```
Or you could opt to just install it globally
```bash
npm install puppeteer --global
```
On a Forge provisioned Ubuntu 20.04 server you can install the latest stable version of Chrome like this:
```bash
curl -sL https://deb.nodesource.com/setup_14.x | sudo -E bash -
sudo apt-get install -y nodejs gconf-service libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgbm1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation libappindicator1 libnss3 lsb-release xdg-utils wget libgbm-dev libxshmfence-dev
sudo npm install --global --unsafe-perm puppeteer
sudo chmod -R o+rx /usr/lib/node_modules/puppeteer/.local-chromium
```
3. Composer Install
```bash
cd htmltopdf
composer install
```

4. Setup Environment
```bash
cp .env.example .env
```
5. Edit file .env, using vim or nano
```bash
// vim .env
nano .env
```
6. Define Whitelist IPs :
```bash
ALLOWED_IPS="your_allowed_ip,another_ip,etc"
// Example : ALLOWED_IPS="127.0.0.1"
```

## Go RUN
You can use your web server like NGINX or Apache.
Mounting to path /public inside the app folder

Or you can use PHP Server to self
```bash
php -S localhost:8000 -t public/
```

## Generate PDF
After this app running on server,
access it via curl or web browser to this app URL.

Let's try convert Google.com into PDF,
We assume use localhost at port 8000 :

Go to : [http://localhost:8000/index.php?url=https%3A%2F%2Fwww.google.com](http://localhost:8000/index.php?url=https%3A%2F%2Fwww.google.com)

