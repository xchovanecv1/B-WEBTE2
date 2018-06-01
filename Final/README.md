# Pokyny k vypracovaniu projektu ku skúške LS2017/2018
## Technologies used in this project
- [AdminLTE](https://adminlte.io/themes/AdminLTE/index.html)
- [Google Maps Platform](https://cloud.google.com/maps-platform/)
- [Cron Jobs](https://cron-job.org/en/)
  - ***mailPHPmailer.php*** and ***geocode.php*** needs to be executed every few moments, you can use linux cron daemon if you choose to
- [Dom2Pdf](https://github.com/dompdf/dompdf)
- [PhpMailer](https://github.com/PHPMailer/PHPMailer)
## WARNING
***This is just proof of concept, storing data from Google's geocoder service is against their TOS, so don't do so. Get yourself sufficient api call limit, and call that coder as it should be!***
## Základné pokyny:
Projekty sa budú robit po piatich, pricom sa pocíta s tým, že úlohy si medzi sebou rozdelíte
rovnomerne. Zadefinovanie pätíc sa robí v Moodle prostredníctvom linky „Prihlásenie na projektové
zadanie“. Je treba sa len so svojimi kolegami dohodnút na císle svojho tímu. Uprednostnujú sa tímy
z jedného termínu cvicenia.
Zadanie je potrebné odovzdat spakované do prostredia Moodle najneskôr do 18.5.2018 (23:55)
jedným clenom tímu. Neskoršie odovzdanie projektu bude penalizované 1 bodom za každý den
omeškania na každého clena tímu.
Pri odovzdávaní nezabudnite pribalit aj sql súbor, ktorým vytvoríte a naplníte databázu. Všetky
nastavenia musia byt v konfiguracnom súbore. Pre úcely ukoncenia predmetu je potrebné mat celý
projekt umiestnený na školskom serveri. Do poznámky nezabudnite napísat adresu umiestnenia, aby
sme vedeli, pod koho menom ho máme hladat a login a heslo pre administrátorský prístup do
aplikácie.
Súcastou projektu bude podstránka s technickou dokumentáciou k projektu a rozdelením úloh medzi
jednotlivých clenov tímu.
## Zameranie projektu
Hlavnou úlohou projektu bude vytvorit stránku, kde sa bude dat sledovat na základe
odbehnutých/odjazdených kilometrov, ako sa približujeme k vytýcenému cielu.
- Ako prvé si bude treba vybrat využitím Google maps štát, trasu a ciel, ktorý chceme v rámci
tréningového procesu dosiahnut.
- Potom na základe údajov, ktoré zadá užívatel
  - pocet odbehnutých/odjazdených kilometrov (jediný povinný údaj),
  - den, kedy k tréningu prišlo,
  - presný cas, kedy tréning zacal a skoncil,
  - GPS súradnice zaciatku a konca tréningu,
  - subjektívne hodnotenie tréningu užívatelom pomocou 5 úrovnovej stupnice, napr. pomocou rôznych smajlíkov,
  - poznámka,
  - budeme postupne na vybranej trase zobrazovat „už prejdenú“ vzdialenost.
- Počíta sa s tým, že užívatel pocas nejakého obdobia prejde postupne celú vytýcenú trasu, takže formulár z bodu B bude používat opakovane.
## Úlohy:
- [x] Pri práci na projekte je potrebné používat verzionovací systém, napr. „github“, pricom
v technickej dokumentácii musí byt uvedená adresa, kde sa vaše úložisko nachádza.
- [x] Aplikáciu budú moct používat len zaregistrovaní užívatelia. Využite pritom vlastnú registráciu,
kde loginom bude emailová adresa, ktorú použijete neskôr aj na komunikáciu v rámci
aplikácie. Zaregistrovat sa bude dat dvojakým spôsobom:
  - každý jednotlivec vyplnením registracného formuláru, ked mu príde potvrdzovací
mail, na základe ktorého dokáže dokoncit registráciu.
  - Administrátor dokáže zaregistrovat viacerých ludí hromadne po importe csv súboru,
ktorého vzor nájdete v prílohe tohoto zadania. Takto importovaným užívatelom bude
prednastavené defaultné heslo, ktoré si budú musiet pri prvom prihlásení do
systému zmenit.
- [x] Aplikácia podporuje 3 roly – užívatel, administrátor a anonymný neprihlásený host stránky.
- [x] Aplikácia umožní definovanie a zapamätanie si trasy, ktorá bude naším cielom pri tréningu.
V prípade záujmu je možné spravit túto trasu neaktívnou a nastavit si novú trasu. Aj pre
aktívne a aj pre neaktívne trasy si bude môct užívatel pozriet už odbehnutý/odjazdený úsek
na tejto trase. V každom okamihu bude môct byt aktívna iba jedna trasa, avšak neskoršie
zaktívnovanie a dezaktivnovanie trasy je tiež možné. To, ktorá trasa je aktívna alebo
neaktívna, si definuje užívatel. Definovanie trasy robí užívatel alebo administrátor – vid další
bod zadania.
- [x] Pri definovaní každej trasy sa bude dat nastavit, v akom móde má tréning prebiehat:
  - privátny mód (trasu definuje užívatel) – trasu a prejdený úsek vidí iba užívatel
a administrátor,
  - verejný mód (trasu definuje administrátor) – trasu a prejdený úsek vidia všetci
zaregistrovaní užívatelia,
  - štafetový mód (trasu definuje administrátor) – ide o istý typ verejného módu, t.j.
trasu a prejdený úsek vidia všetci zaregistrovaní užívatelia.
- [x] Aby si bolo možné prezerat jednotlivé trasy vrátane na nich prejdených úsekov, tak na
vhodnom mieste aplikácie je potrebné mat uvedenú tabulku, so všetkými zadefinovanými
trasami. Z tabulky bude zrejmé, ci ide o aktívnu alebo neaktívnu trasu a v akom móde bola
trasa zadefinovaná. Užívatel vidí iba verejné trasy a administrátor aj privátne, kde je treba
uviest aj to, kto si ju definoval. Tabulka bude zotrieditelná podla všetkých stlpcov.
Administrátor si bude môct vyfiltrovat údaje iba pre urcitého užívatela.
- [x] Štafetový mód umožnuje, aby sa na prejdení jednotlivých úsekov trasy podielali viacerí
clenovia jedného družstva. Zaclenenie ludí do družstva robí administrátor pomocou vhodne
zvoleného GUI. Pocíta sa s tým, že v jednom družstve bude max. 6 clenov. Zloženie družstiev
sa bude dat v aplikácii pozriet a menit (vrátane vymazania celého družstva alebo iba
niektorých jeho clenov) na osobitnej stránke.
- [x] V prípade, že si budeme pozerat trasu s prejdenými úsekmi, ktorá bola zadefinovaná bud
vo verejnom alebo štafetovom móde, tak jednotliví zobrazení užívatelia, resp. jednotlivé
zobrazené družstvá budú graficky odlíšení a zo stránky sa bude dat zistit, ktorá ciara komu
prináleží.
- [x] Každý užívatel si v rámci aplikácie bude moct pozriet podrobnú tabulku svojich výkonov, t.j.
v ktorý den kolko odbehol/odjazdil, atd (všetky údaje z formulára z bodu B). Posledným
stlpcom tabulky bude priemerná rýchlost na tréningu.
Tabulka bude zoraditelná podla všetkých stlpcov.
Pod tabulkou sa zobrazí priemerná hodnota odbehnutých/odjazdených kilometrov na jeden
tréning.
- [x] Administrátor si bude môct pozriet podrobné tabulky všetkých užívatelov, t.j. pre neho treba
spravit najprv tabulku všetkých užívatelov a potom po kliknutí na užívatela, zobrazit to isté
ako v úlohe c.7.
- [x] Tabulku podla úlohy c.8 vygenerujte aj do pdf súboru. Pri generovaní pdf súboru rešpektujte
práve nastavené zotriedenie súboru.
- [x] Na titulnej stránke celej aplikácie (dostupnej bez prihlásenia) zobrazte mapu Slovenska, kde
vyznacíte podla toho, co si vyberie anonymný host stránky,
  - bud odkial pochádzajú jednotliví zaregistrovaní užívatelia
  - alebo kde navštevujú svoju školu. V prípade, že jednu školu navštevuje viacero užívatelov, musí to byt z mapy jednoznacne jasné. Tieto údaje dostanete do aplikácie na základe údajov z csv súborov importovaných na základe úlohy c.1.
- [ ] V prípade, že niekto prídá do aplikácie svoje údaje podla bodu B, tak je potrebné, aby sa
okamžite zobrazili všetkým užívatelom (s výnimkou privátneho módu), t.j. aktualizácia údajov
sa nebude robit až po refreshi stránky.
- [x] Vo vnútri aplikácie umožnite na vhodnom mieste zobrazovanie aktualít, ktoré bude do
aplikácie zadávat administrátor. V aplikácii sa bude možné prihlásit na odoberanie aktualít
cez Newsletter. Treba zabezpecit aj možnost zrušenia odoberania aktualít.
## Poznámky
- Na vypracovanie projektu je možné použit PHP framework (použitie CMS systému nie je povolené).
- Nezabudnite na to, že sa hodnotí aj grafický dizajn vytvorenej aplikácie, vhodne navrhnuté clenenie, lahkost orientácie v prostredí.
- Pamätat by ste mali aj na zabezpecenie celej aplikácie
