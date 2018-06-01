# Zadanie č.4 - SSE vs. WebSockets
## WARNING
***This script contains posibility of XSS attack on chat component. If you are serious about using it, fix it please.***
## Pre-requirements
```
apt-get install php-ldap
apt-get install php7.0-curl
sudo apt-get install nodejs npm
npm install --prefix ./ websocket
```
## Running websocket server
```
node server.js
```
## Alternatíva 2 
- Vytvorte webovú aplikáciu, ktorá umožní na vyznačenú plochu súčasne kresliť viacerým užívateľom. Po načítaní stránky si užívateľ bude môcť zvoliť farbu, s ktorou bude kresliť a po jej zvolení bude môcť začať kresliť pomocou myši do canvas-u. V rovnakom čase budú môcť do toho istého obrázku kresliť aj ďalší užívatelia.
- Vykreslený obrázok umožnite uložiť do png súboru. Existuje na to viacero postupov, jeden z nich je zverejnený napr. tu: [canvas2png](http://infoheap.com/convert-html-canvas-to-png-image/)
