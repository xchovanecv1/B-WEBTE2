# Zadanie č.6 - web služby
## Pre-requirements
For this to work, we need .htaccess allowed on our host machine, so:
```
vim /etc/apache2/sites-available/000-default.conf
```
```
<Directory /home/user_name/public_html>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
                # Uncomment this directive is you want to see apache2's
                # default start page (in /apache2-default) when you go to /
                #RedirectMatch ^/$ /apache2-default/
</Directory>
```
## Úlohy:
- Vytvorte webovú službu (klientsku a aj serverovú stranu), ktorá bude poskytovať informácie o meninách osôb na základe priloženého xml dokumentu. Jednotlivé metódy API nech umožňujú:
  - na základe zadaného dátumu získať informáciu, kto má v daný deň meniny na Slovensku, resp. v niektorom inom uvedenom štáte
  - na základe uvedeného mena a štátu získať informáciu, kedy má osoba s týmto menom meniny v danom štáte
  - získať zoznam všetkých sviatkov na Slovensku (element <SKsviatky>) spolu s dňom, na ktorý tieto sviatky pripadajú
  - získať zoznam všetkých sviatkov v Čechách (element <CZsviatky>) spolu s dňom, na ktorý tieto sviatky pripadajú
  - získať zoznam všetkých pamätných dní na Slovensku (element <SKdni>) spolu s dňom, na ktorý tieto dni pripadajú
  - vložiť nové meno do kalendára (element <SKd>) k určitému dňu
- Webovú službu vytvorte pomocou jednej z nasledujúcich alternatív: XML-RPC, JSON-RPC, SOAP alebo REST. Pri zadaní sa bude kontrolovať, či funkcionalita stránky je robená naozaj pomocou zvolenej webovej služby. Pri REST službe si dajte záležať na tom, aby boli skutočne dodržané zásady RESTu. 
- Klientska strana aplikácie by mala umožňovať zadávať vstupné údaje vo forme formulára. Na web stránke nezabudnite popísať API vytvorenej služby. V prípade, že vytvoríte WSDL dokument pre SOAP, tak stačí, keď namiesto ručného popisu API iba vizualizujete jednotlivé metódy služby pomocou nejakého voľne dostupného wsdl viewera. Kto však má záujem, môže ručne popísať API aj v tomto prípade.
