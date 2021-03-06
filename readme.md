# TorrentMonitor
Приложение мониторит изменения на популярных торрент-трекерах рунета и автоматизирует закачку обновлений (сериалы, раздачи которые ведутся *путем добавления новых серий/новых версий*, перезалитые торрент-файлы и т.д.)

###Список возможностей приложения:

* Слежение за темами
 * anidub.com
 * animelayer.ru
 * casstudio.tv
 * kinozal.tv
 * nnm-club.ru
 * pornolab.net
 * rustorka.com
 * rutracker.org
 * rutor.org
 * tfile.me
 * tracker.0day.kiev.ua
 * tv.mekc.info
* Слежение за релизерами
 * nnm-club.ru
 * pornolab.net
 * rutracker.org
 * tfile.me
* Поиск новых серий (SD/HD 720/HD 1080 версии на выбор)
 * baibako.tv 
 * hamsterstudio.org
 * lostfilm.tv
 * newstudio.tv
 * novafilm.tv
* Работа через proxy (SOCKS5/HTTP)
* Управление торрент-клиентами (добавление/удаление раздач и файлов)
 * Transmission (через XML-RPC)
 * Deluge (через deluge-console)
* Сервисы уведомлений:
 * E-mail
 * Prowl
 * Pushbullet
 * Pushover
* RSS-лента
* Выполенение собственных скриптов после обновления раздачи

###Скриншоты:
 ![Screenshot0](http://blog.korphome.ru/wp-content/uploads/2011/02/Мониторинг-torrent-трекеров-2014-01-27-14-53-42.jpg "Screenshot0")
 ![Screenshot1](http://blog.korphome.ru/wp-content/uploads/2011/02/Мониторинг-torrent-трекеров-2014-01-27-14-54-16.jpg "Screenshot1")
 ![Screenshot2](http://blog.korphome.ru/wp-content/uploads/2011/02/Мониторинг-torrent-трекеров-2014-01-27-14-54-38.jpg "Screenshot2")
 ![Screenshot3](http://blog.korphome.ru/wp-content/uploads/2011/02/Мониторинг-torrent-трекеров-2014-01-27-14-54-52.jpg "Screenshot3")
 ![Screenshot4](http://blog.korphome.ru/wp-content/uploads/2011/02/Мониторинг-torrent-трекеров-2014-01-27-14-55-28.jpg "Screenshot4")
 ![Screenshot5](http://blog.korphome.ru/wp-content/uploads/2011/02/Мониторинг-torrent-трекеров-2014-01-27-14-55-41.jpg "Screenshot5")
 ![Screenshot6](http://blog.korphome.ru/wp-content/uploads/2011/02/Мониторинг-torrent-трекеров-2014-01-27-14-56-36.jpg "Screenshot6")

###Требования для установки:

* Веб-сервер (Apache, nginx, lighttpd)
* PHP (5.2 или выше) с поддержкой cURL и PDO
* MySQL, PostgreSQL, SQLite

###Установка:

* Импортировать дамп базы из директории db_schema в зависимости от используемой БД - *.sql
* Перенести все файлы в папку на вашем сервере (например /path/to/folder/torrent_monitor/)
* Внести изменения в config.php и указать данные для доступа к БД

Для MySQL:
```
Config::write('db.host', 'localhost');
Config::write('db.type', 'mysql');
Config::write('db.charset', 'utf8');
Config::write('db.port', '3306');
Config::write('db.basename', 'torrentmonitor');
Config::write('db.user', 'torrentmonitor');
Config::write('db.password', 'torrentmonitor');
```
Для PostgreSQL:
```
Config::write('db.host', 'localhost');
Config::write('db.type', 'pgsql');
Config::write('db.port', '5432');
Config::write('db.basename', 'torrentmonitor');
Config::write('db.user', 'torrentmonitor');
Config::write('db.password', 'torrentmonitor');
```
Для SQLite:
```
Config::write('db.type', 'sqlite');
Config::write('db.basename', '/var/www/htdocs/TorrentMonitor/torrentmonitor.sqlite'); #Указывайте _абсолютный_ путь до файла с базой и не забудьте выставить на него верные права доступа.
```

* Добавить в cron engine.php ( *проверьте права на запись в каталог /path/to/log/* )

```
*/10 * * * * php -q /path/to/folder/torrent_monitor/engine.php >> /path/to/log/torrent_monitor_error.log 2>&1
```
* Зайти в веб-интерфейс ( **пароль по умолчанию — torrentmonitor, смените(!) его после первого входа** )
* Указать учётные данные от трекеров
* Настроить обязательные параметры: "Адрес TorrentMonitor" и "Директория для скачивания" (описание всех параметров настроек вы можете найти в разделе Помощь)
* Добавить торренты для мониторинга
* Перейти на вкладку «Тест» и проверить — всё ли верно работает

###Настройки:

Так же, в php.ini (для CLI) необходимо изменить следующие параметры:

```
; увеличить максимальное вермя выполнения скрипта
max_execution_time = 300

; указать date.timezone
date.timezone = Europe/Moscow

; эту опцию желательно включить в php.ini как для CLI, так и для веб-сервера
allow_url_fopen = on

; проверить - разрешена ли запись в сторонние каталоги. 
; Нужно разрешить запись в каталог с самим приложением TorrentMonitor 
; и каталог куда будут сохраняться *.torrent файлы для torrent клиента
open_basedir = /tmp/:/path/to/folder/torrent_monitor/:/path/to/folder/torrent_client_watch/
```

###Страница проекта:

http://blog.korphome.ru/torrentmonitor/

###Форум:

http://korphome.ru/TorrentMonitor/
