an7metaVid
================

A plugin for [Phile](https://github.com/PhileCMS/Phile) that takes meta tags with Vimeo IDs and returns new meta tags with embed code and thumbnail images.

### 1.1 Installation (composer)
```
php composer.phar require an7/meta-vid:*
```

### 1.2 Installation (Download)

* Install the latest version of [Phile](https://github.com/PhileCMS/Phile)
* Clone this repo into `plugins/an7/metaVid`

### 2. Activation

After you have installed the plugin. You need to add the following line to your `config.php` file:

```
$config['plugins']['an7\\metaVid'] = array('active' => true);
```

### Usage

Some example markdown input:

```markdown
Title: Page Title
Preview: 146707828
Template: post
```

With the following option set in your config:

```markdown
  'meta_tags' => 'preview'
```

This is what will be output:

```html
{{ page.meta.preview-img }} = https://i.vimeocdn.com/video/545172616-959ac693b466b3ea0621d98330adb2d507a40fe08baef7e6927c26fea83801a2-d_640.jpg
{{ page.meta.preview-vid }} = <iframe class="animate" src="https://player.vimeo.com/video/146707828" allowfullscreen onload="{{ page.title }}_demo=new Vimeo.Player(this)"></iframe>
```

### Config

This is the default `config.php` file. It explains what each key => value does.

```php
  'meta_tags' => 'tag1,tag2', // list of the meta tags that should be converted from comma separated lists into html lists
  'meta_suffix' => '_code', // meta tag suffix for the newly created meta tag after processing
  'open_class' => 'thumbnail', // class applied to the anchor element that opens the embed overlay (includes javascript triggers)
  'close_class' => 'close', // class applied to the anchor element that closes the embed overlay (includes javascript triggers)
  'overlay_class' => 'lightbox animate', // class applied to the overlay element
  'iframe_class' => 'animate', // class applied to the iFrame element
```
