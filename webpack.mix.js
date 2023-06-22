require('laravel-mix-mjml');

const mix = require('laravel-mix');

mix.mjml('resources/emails', 'resources/views/emails');
