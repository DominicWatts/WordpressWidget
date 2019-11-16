# Lightweight Wordpress Widget

Lightweight widget which you can use to load wordpress blog posts on your magento site with minimal setup by providing link to json feed and a few other parameters

No further integration steps required. Ideal for sites which have minimal interaction with WordPress install.

# Install instructions #

`composer require dominicwatts/wordpresswidget`

`php bin/magento setup:upgrade`

# Usage instructions

    Content > Widgets > Add Widget
    
Choose `Type: WorpPress Post List` and choose your theme.

There are a number of additional options that need configuring.

### Under `Storefront Properties` 

Give your widget a name, assign to store and give a sort.

### Under `Layout Properties`

`Sidebar Additional` / `Sidebar Main` Layouts include:

  - WordPress Images and Titles Template

![WordPress Images and Titles Template](https://snipboard.io/e3CUzF.jpg)

  - WordPress Titles Only Template

![WordPress Titles Only Template](https://i.snipboard.io/y4xrm1.jpg)  
  
  - WordPress Featured Image Only Template
  
![WordPress Featured Image Only Template](https://i.snipboard.io/uVnret.jpg)  

`Main Content Area` layouts include:

  - Wordpress Grid Template

![Wordpress Grid Template](https://i.snipboard.io/MaUfSj.jpg) 

  - Wordpress List Template

![Wordpress List Template](https://i.snipboard.io/2Rwzv0.jpg) 

### Under `Widget Options`
  
Configure URL to JSON feed of Wordpress site

Typically the URL is structured like this

https://domain.com/wp-json/wp/v2/posts?per_page=10&_embed

The `per_page` parameter can limit post number.  The `_embed` option is to pull through featured image.

You can also configure widget post limit and cache option here.