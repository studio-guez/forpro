# Technical Documentation

## Infos

```md
├── CMS / API
│   !! KIRBY CMS - 4.0
│   !! PHP >=8.1
│   ├── plugins
│   │   ├── kirby-calendars
│   │   │   └──  data -> critical
│   │   ├── kirby-foodlab
│   │   │   ├──  data -> critical
│   │   │   !!  CHROMIUM HEADLESS -> dependency
│   │   │   !!  PUPPETEER -> dependency
│   │   ├── kirby-forpro
│   │   └── kirby-king-dedede
├── RESTAURANT -> FRONT
│   !!  Sveltekit 2.0
├── WEBSITE -> FRONT
│   !!  Sveltekit 2.0
```

### CMS
- Kirby CMS 4.0
- PHP 8.1

### Docker Dev
To be able to access services using the compose.dev.yml add this to your
/etc/hosts file
```127.0.0.1 restaurant.localhost website.localhost cms.localhost```

### Server Dependencies
#### Foodlab Plugin
To generate PDFs, the server needs to have the following dependencies installed:
- Chromium Headless
- puppeteer

#### Development Environment
php -S localhost:1234 kirby/router.php

### Webiste
- SvelteKit 2.0

#### Development Environment
pnpm run dev

## production

### Backend
config php de base, sur /var/www/cms

### Front
/var/www/website

lister process pm2
```bash
pm2 start build/index.js
```

Il devrait y avoir un seul process, celui qui sert le front du site.

relancer le proces
```bash
npm run build
pm2 restart 0
```

Si il faut démarrer le process
```bash
npm run build
pm2 start build/index.js
```
