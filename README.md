# Metodika

Кастомная тема WordPress для сайта миграционных услуг. На главной — шапка и hero по макету: меню из админки WordPress, тексты, ссылки и картинка hero правятся без правки кода (Carbon Fields).

В репозитории только тема (`wp-content/themes/metodika/`). Ядро WordPress, `wp-config.php` и загрузки в git не входят.

## Требования

- WordPress 6.7+ (уже установлен в целевом каталоге)
- PHP 8.1+
- Git
- [Composer](https://getcomposer.org/) — Carbon Fields ставится в тему, не отдельным плагином

Сборки фронтенда нет: CSS и JS подключаются как есть.

## Деплой

Корень сайта — непустой каталог WordPress, в него нельзя сделать `git clone`. Репозиторий инициализируют на месте и подтягивают remote.

В корне WordPress:

```bash
git init
git remote add origin https://github.com/pererushev/metodika.git
git fetch origin
git checkout feature/header-hero
```

`.gitignore` из репозитория скрывает ядро WP, конфиг и загрузки: в индекс попадёт только тема.

Зависимости в git нет — после выкладки темы:

```bash
cd wp-content/themes/metodika
composer install --no-dev
```

В админке: **Внешний вид → Темы → Metodika → Активировать**.

Обновление: `git pull` в корне сайта. Если менялся `composer.lock` — снова `composer install --no-dev` в папке темы.

## Настройка

1. Логотип: **Внешний вид → Настроить → Свойства сайта → Логотип**.
2. Тексты, соцсети и картинка hero: пункт **Главная** в админке.
3. Меню шапки: **Внешний вид → Меню**.
   - локация **Шапка — меню** — пункты навигации;
   - локация **Шапка — контакты** — телефон в верхнем ряду.
   Класс пункта `is-button` — красная кнопка «Бесплатная консультация».
