#Инструкция по установке модуля в CMS [Opencart V2](http://www.opencart.com/?route=download/download)

0.  [Скачайте модуль](https://github.com/yandex-money/yandex-money-cms-opencart2/raw/master/pack_yandex_oc.zip)

1. 	**Сделайте бекап!**

2. 	Содержимое скачанного архива поместите в корень вашего магазина.

3. 	Если вы используете [ЧПУ](https://clck.ru/9PcGw) то впишите в ваш ```.htaccess``` строку ```RewriteRule ^yandexbuy/([^?]*)$ index.php?route=yandexbuy/$1 [L,QSA]``` Вписать нужно после ```RewriteBase /``` и перед ```RewriteRule ^sitemap.xml$ index.php?route=feed/google_sitemap [L]```

  **Должно выйти так:**
  ```
  RewriteBase /
  RewriteRule ^yandexbuy/([^?]*)$ index.php?route=yandexbuy/$1 [L,QSA]
  RewriteRule ^sitemap.xml$ index.php?route=feed/google_sitemap [L]
  RewriteRule ^googlebase.xml$ index.php?route=feed/google_base [L]
  RewriteRule ^download/(.*) /index.php?route=error/not_found [L]
  ```
[Скачать пример .htaccess](https://github.com/yandex-money/yandex-money-cms-opencart2/blob/master/.htaccess-example)

4.	Заходим в админку в Extension --> feed. Там устанавливаем наш модуль и настраиваем.

5.	Для работы Яндекс Касса необходим работающий https.


**Внимание!**

Если вы хотите использовать p2p,то вам сначала необходимо [зарегистрировать своё приложение](https://tech.yandex.ru/money/doc/dg/tasks/register-client-docpage/).

Для сервисов Метрика и Маркет необходимо [зарегистрировать приложение](https://tech.yandex.ru/oauth/doc/dg/tasks/register-client-docpage/) на OAuth-сервере.

Все необходимые ссылки вы найдёте в настройках модуля!

Также для работы Метрики необходим vqmod

[видео по установке](https://code.google.com/p/vqmod/wiki/Install_OpenCart)

[Скачать vqmod](https://code.google.com/p/vqmod/downloads/list)

-------------

**Нашли ошибку или у вас есть предложение по улучшению модуля?**

Пишите нам cms@yamoney.ru
