# Swifki PHP - The Swift PHP Wiki



## Intro

I've used [dokuwiki](https://www.dokuwiki.org/dokuwiki) as my personal wiki but I had an issue when upgrading and I didn't really like this.
I needed a simple wiki system, preferably with 0 effort maintenance and custom to my needs.
This is my second (improved) version which uses mainly Markdown markup but can also use html and, most important, it can be easily extended to others.


## Requirements

Version: 0.1

* Geshi - for syntax highliting
* jsTree - for sidebar tree menu
* PHP Markdown - main markup language
* PHP 5.3.x
* *nix environment


## Install

* Check requirements.php in your browser.
* Download requirements in their respective folders.
* Make sure you have write access to `cache/` folder.


## Directory structure

* ./cache/ - compiled templates
* ./libs/  - php libraries like Swifki, GeShi and PHP Markdown
* ./pages/ - you template pages (php, md, html etc.)
* ./www/   - www root

## How to use

Just create a file inside `pages` directory :)
It works with php, html, md and common images.


## Specs

* Dynamically generated tree menu from pages directory
* Search in files
* MD, HTML, PHP and images as templates
* Dynamically generated table-of-contents
* Breadcrumbs


## Extend

### Styles

Use `./www/assets/user.css` for changing styles - this way you can upgrade to a new version once/if I release a new one :)
Currently the wiki has NO theme. If you can help me by creating one and for this I thank you :)

### Compiler

By default PHP, MD, HTML and common images are compiled into `cache/` folder.
If you need further requirements check `./libs/Swifki.php` class and look over
methods prefixed with `compile` to have an overview how things are processed.
Once you understand this, you MUST update `./libs/UserSwifki.php` class and NOT `./libs/Swifki.php`.


## Links

* [Swifki](http://wiki.iuliann.ro/) - My wiki
* [Inspiration](http://wikitten.vizuina.com/) - From where i had some inspiration


## Known issues

* Tree menu after search remains expanded


## Roadmap

* Toggle source
* Edit source via browser (not sure if it's a good idea though)