# 🎮 GameTracker

**GameTracker** je webová miniaplikácia postavená na PHP, ktorá slúži na správu a zobrazovanie herných štatistík. Ponúka moderné, "taktické" používateľské rozhranie inšpirované herným HUD a systém na správu hráčov.

## 🚀 Hlavné funkcie

*   **Autentifikácia**: Registrácia (`register.php`) a prihlásenie (`index.php`) s bezpečným hašovaním hesiel[cite: 1, 3].
*   **Osobný Dashboard**: Zobrazovanie individuálnych štatistík ako K/D pomer, počet výhier a percento headshotov (`home.php`)[cite: 4].
*   **Globálny Leaderboard**: Tabuľka 5 najlepších hráčov zoradená podľa počtu zabití[cite: 4].
*   **Admin Panel**: Rozhranie pre administrátorov na manuálnu úpravu štatistík všetkých hráčov (`admin.php`)[cite: 7].
*   **Responzívny dizajn**: Tmavý "Military-style" vizuál postavený na Bootstrap 5 a vlastnom štýle v `index_V1.css`[cite: 2, 4].

## 🛠 Použité technológie

*   **Backend**: PHP[cite: 5]
*   **Databáza**: MySQL (štruktúra v `gametracker_V1.sql`)[cite: 5]
*   **Frontend**: HTML5, CSS3, Bootstrap 5[cite: 1, 2, 4]

## 🔧 Inštalácia

1.  Naklonuj tento repozitár do svojho lokálneho servera (napr. XAMPP, WAMP).
2.  Vytvor v MySQL databázu s názvom `gametracker`[cite: 6].
3.  Importuj súbor `gametracker_V1.sql` do vytvorenej databázy[cite: 5].
4.  V súbore `db.php` uprav prihlasovacie údaje k databáze (host, username, password)[cite: 6].
5.  Spusti aplikáciu cez prehliadač otvorením súboru `index.php`.

## 📂 Štruktúra súborov

*   `index.php` - Prihlasovacia stránka a vstupný bod[cite: 3].
*   `register.php` - Registračný formulár pre nových používateľov[cite: 1].
*   `home.php` - Používateľský dashboard so štatistikami a rebríčkom[cite: 4].
*   `admin.php` - Administrátorské rozhranie (prístupné len pre používateľov s `role = 1`)[cite: 7].
*   `db.php` - Konfiguračný súbor na pripojenie k databáze[cite: 6].
*   `index_V1.css` - Definícia dizajnu a vizuálnych efektov[cite: 2].
*   `gametracker_V1.sql` - Export databázovej štruktúry[cite: 5].
