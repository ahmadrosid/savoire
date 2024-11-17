# Savoire

Transform your ideas into engaging LinkedIn content with AI assistance.

![demo-savoire.png](demo-savoire.png)

## Environment

This project requires an Anthropic AI API key. Please add your key to the .env file.

```bash
ANTHROPIC_API_KEY=
```

## Running Locally

First install php dependencies
```bash
composer install
```

Then install npm dependencies
```bash
npm install
```

Build the javascript bundle
```bash
npm run build
```

Run the server
```bash
php artisan serve
```

Open http://localhost:8000