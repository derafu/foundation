# Derafu: Foundation - Base for Derafu's Projects

[![CI Workflow](https://github.com/derafu/foundation/actions/workflows/ci.yml/badge.svg?branch=main&event=push)](https://github.com/derafu/foundation/actions/workflows/ci.yml?query=branch%3Amain)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://opensource.org/licenses/MIT)

## Quick Start

You need to have [PHP](https://www.php.net/manual/en/install.php) and [Composer](https://getcomposer.org/download/) installed, then run:

```shell
composer create-project derafu/project project_dir --stability=dev
```

If you need to use assets (CSS, JavaScript or images) add them to the `assets` directory and then run:

```shell
npm run build
```

To run the project, enter to the project directory and start the PHP server with:

```shell
php -S localhost:9000 public/index.php
```

Then go to http://localhost:9000

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

Happy coding! ✨
