# Technical Documentation

## Infos

├── CMS -> API
│   !! KIRBY CMS - 4.0
│   !! PHP >=8.1
│   ├── plugins
│   │   ├── kirby-calendars
│   │   │   ├── data -> critical
│   │   ├── kirby-foodlab
│   │   │   ├── data -> critical
│   │   │   !! CHROMIUM HEADLESS -> dependency
│   │   │   !! PUPPETEER -> dependency
│   │   ├── kirby-forpro
│   │   ├── kirby-king-dedede
├── RESTAURANT -> FRONT
│   ├── Sveltekit 2.0
├── WEBSITE -> FRONT
│   ├── Sveltekit 2.0

### CMS
- Kirby CMS 4.0
- PHP 8.1

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
