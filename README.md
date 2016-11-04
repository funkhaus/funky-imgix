## What is it?

[Imgix](http://imgix.com/) is a service that lets you modify and deliver images.

funky-imgix is a Wordpress plugin that lets you grab your Imgix images quickly and easily.

## How
1. **Imgix**
    1. Set up your account at [Imgix](http://imgix.com/).
    1. Create a [source](https://webapp.imgix.com/sources) for your images.
    1. Navigate to your source details and note the `Domains` and `Secure URL Token` fields.
1. **Wordpress**
    1. Drop the contents of this repo in `wp-content/plugins/funky-imgix` on your server. Activate the plugin through Wordpress.
    1. Navigate to Settings->Imgix Settings and enter the `Imgix Domain` (the value of the 'Domains' field from earlier) and the `Secure URL Token`.

That's it! You can now use the new funky-imgix functions.

## Functions

```php
get_the_imgix_image( $parameters = '', $image_id = false, $image_size = 'thumbnail' )
```

Returns the URL for an image modified on Imgix.

* `$parameters` *(string or array, optional)* - The [modification parameters](https://docs.imgix.com/apis/url) for your image. Either a query string (`'?blur=90&bri=-25'`) or an array:

```php 
array(
    'blur'  => 90,
    'bri'   => -25
    // etc ...
)
```

* `$image_id` *(int, optional)* - The attachment ID or post ID. Passing a post ID will grab the featured image of that post. Default `false`, which grabs the featured image of the current post.
* `$image_size` *(string, optional)* - The size of the source image on Wordpress. Default `thumbnail`.

```php
the_imgix_image( $parameters = '', $image_id = false, $image_size = 'thumbnail' )
```

Shortcut to echo the results of `get_the_imgix_image`. Same parameters and defaults.

-------

Version 1.0

http://funkhaus.us