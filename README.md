> [!IMPORTANT]
> This repo is still pretty rough around the edges.
> While the core functionality of click button -> Process video essentially works, I'm aware of at least the following issues:
>
> - The frontend will **always** show an error message, even if the background processing is successful
> - The icon itself isn't the best, and doesn't update based on success/failure

# FreshRSS TubeArchivist Button

A [FreshRSS](https://freshrss.org/) extension which adds a [TubeArchivist](https://readeck.org/en/) sharing integration.
Clicking the button will automatically add the selected video to your downloads list on a chosen TubeArchivist instance.
Suports config for including button in footer/header and supports setting a keyboard shortcut.

This repo is shamelessly forked from the excellent [FreshRSS Readeck Button](https://github.com/Joedmin/xExtension-readeck-button), and repurposed for my needs, all credit to the original extension.

## Download and setup

1. Download the [latest release](https://github.com/Pete-Hamlin/xExtension-tubearchivist-button/releases) or clone this repo
2. Extract and upload it to the `./extensions` folder of your FreshRSS installation (if you opt for the clone option, no extraction necessary)
3. Go to `<readeck_intance_url>/settings/application` -> **Integrations** -> API token
4. Enter your TubeArchivist instance url in the TubeArchivist Button extension settings
5. Enter your key in the extension settings
6. _Optional Set a custom keyboard shortcut_
7. _Optional Update the extension behavior_

> [!NOTE]
> Due to the nature of TA, the button will only appear on 'articles' from YouTUbe (i.e. have `youtube.com`)

### Readeck Button Compatability

Due to the nature of FreshRSS extensions, only one extension can override a `phtml` file.
This makes this extension and any other extension that modifies `entry_header`/`entry_bottom` incompatible.

As the main goal of this was to use it alongside the Readeck Button extension, I've added a compat layer within this extension, to allow the 2 to work alongside each other.
This does require that the TubeArchivist Button is loaded **AFTER** the Readeck button.

Your extension load order is determined in your `config.php` file, either globally in `/data/config.php` or user specific in `/data/users/$USER/config.php`.

Ensure the Readeck button extension appears **above** the TubeArchivist one:

```php
  'extensions_enabled' =>
  array (
    'Readeck Button' => true,
    'TubeArchivist Button' => true,
  ),
```

Configure/setup your Readeck button as usual, and the 2 should work in harmony.

## Contributing

### Translations

If you'd like to translate the extension to another language please file a pull request. I'd be happy to merge it!

### Development

For local development pull the repository. The prerequisite is [Docker](https://www.docker.com/) installed.

Go to the repository root folder and run `docker compose up` that will start a local [FreshRSS](https://www.freshrss.org/) instance running `http://localhost:8080/`.

Complete it's installation and navigate to Extensions, where you have to enable `Readeck Button`.

All changes in the PHP files are loaded with each page refresh.

## Credits

This extension is based [FreshRSS Readeck Button](https://github.com/Joedmin/xExtension-readeck-button), which in turn is based on [freshrss-pocket-button](https://github.com/christian-putzke/freshrss-pocket-button) and re-branded for Readeck.

Thank you very much [Christian Putzke](https://github.com/christian-putzke) for creating the original extension,
and thanks to [Joedmin](https://github.com/Joedmin) for the Readeck flavour (which I still use!).
