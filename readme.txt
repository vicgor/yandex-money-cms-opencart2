1. 	Сделайте бекап!
2. 	Содержимое папки поместить в корень вашего магазина.
3. 	Если вы используете чпу то впишите в ваш .htaccess строку
	RewriteRule ^yandexbuy/([^?]*)$ index.php?route=yandexbuy/$1 [L,QSA]
	Вписать нужно после RewriteBase / и перед RewriteRule ^sitemap.xml$ index.php?route=feed/google_sitemap [L]
	Должно выйти так:
	...........................................................................................................
	RewriteBase /
	RewriteRule ^yandexbuy/([^?]*)$ index.php?route=yandexbuy/$1 [L,QSA]
	RewriteRule ^sitemap.xml$ index.php?route=feed/google_sitemap [L]
	RewriteRule ^googlebase.xml$ index.php?route=feed/google_base [L]
	RewriteRule ^download/(.*) /index.php?route=error/not_found [L]
	...........................................................................................................

4.	Заходим в админку в Extension --> feed. Там устанавливаем наш модуль и настраиваем.
5.	Для работы Яндекс Касса необходим работающий https.

Пример .htaccess лежит рядом с readme.