# JAGARAPERDA KALSEL — Public Aspiration Portal

A web portal where the public can submit and track their aspirations on Draft Regional Regulations (*Rancangan Peraturan Daerah / Raperda*) of South Kalimantan, with an admin panel to process them.

## Features

**Public**
- Browse Raperda (draft regulations) and their details
- Submit an aspiration on a Raperda, with file attachments
- Track the status of a submitted aspiration
- See aspirations that have been followed up
- News / publications and photo gallery
- `sitemap.xml` for search engines

**Admin**
- Dashboard
- Aspiration queue — review incoming aspirations and give responses
- Manage Raperda, news, and gallery (CRUD)

## Tech

Laravel 12, Livewire 3, Laravel Breeze, Alpine.js, Bootstrap 5, MySQL.

## Installation

```bash
git clone https://github.com/HanifAuliya/jagaraperda.git
cd jagaraperda
composer install
npm install
cp .env.example .env
php artisan key:generate
# set DB_* in .env
php artisan migrate --seed
php artisan storage:link
npm run dev
php artisan serve
```
