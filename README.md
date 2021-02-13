# Laravel Page Title best practices 

When you're working with Laravel Application, ever wonder how to use a best practice to set the page title?

```html
<title>{$pageTitle}</title>
```

Page Title is a must in order to SEO your pages. Also, with a full page title, users can know which tabs are what.

In real life, I've seen many lazy-application without a proper Page Title (only the brand name). 

We're using PHP, a super dynamic language, and we got many ways to set the page title. But which one we should follow?

## Bad practices
These practices below consider a bad practice to set a page title IMO.

### Using view's variables
Probably many of us ever used (using) this way:

Controller:
```php
<?php

class ArticleController extends Controller {
    public function viewArticleDetail(Article $article) {
        $pageTitle = $article->title . " - " . env('APP_NAME');
        return view('article.detail', compact('article', 'pageTitle'));
    }
}
```

View (Master Layout):
```html
<title>{{$pageTitle}}</title>
```

It can be improved like this:

Controller:
```php
$pageTitle = $article->title;
```

View (Master Layout):
```html
<title>{{isset($pageTitle) ? $pageTitle . ' - ' . env('APP_NAME') : env('APP_NAME')}}</title>
```

Problems:   
- We shouldn't access the `env` in the Controllers either Views
- Everytime we need the specific page title, we need to define the variable and pass it to the view.

### Using @yield in Views
Many people suggested using this way also:

Master View:
```html
<!--- This --->
<head>
    @yield('title')
</head>

<!--- or this --->
<title>@yield('title')</title>
```

So in your page's view, you can set the page title like this:
```html
<!--- First way --->
@section('title')
    <title>{{__('...') . " - " . env('APP_NAME')}}</title>
@endsection

<!--- Second way --->
@section('title', __('...') . " - " . env('APP_NAME'))
```

Problems:   
- We shouldn't access the `env` in the Controllers either Views
- Set page title for every pages is a pain in the ass
- IMO: Hard to maintain, we have to go through all the views to set the page title

## Best/Better Practice

I prepared for my application a simple class called [PageTitle](./PageTitle.php), stored it in `app/Libraries`

Basically, it just normal class with some static methods.

### First, setting up the class
We only need to set up this method:

```php
<?php
class PageTitle {
    /**
     * Get the postfix of the Page Title
     * @example <your title> - <post fix name here>
     * @example News - Google
     * @return string
     */
    protected static function getPostfixPageTitle() : string {
        // could be from config or env, your choice
        return __('Super Application');
    }

    //...
}
```

So in this `getPostfixPageTitle`, we can set up our postfix page title - mostly we're using our Brand Name:

```
Article A - My Brand Name
Index - My Branch Name
...
```

So within the method, we can use `config` or `env` or whatever you like, even retrieve the site name from DB.

We can set the page title in the Controller like this:

### Use the PageTitle class

```php
<?php
use App\Libraries\PageTitle;

class ArticleController extends Controller {
    public function viewArticleDetail(Article $article) {
        PageTitle::setPageTitle($article->title);
        return view('article.detail', compact('article'));
    }
}
```

Then in the Master View, simply declare `title` like this:

```html
    <!-- Title -->
    <title>{{\App\Libraries\PageTitle::getTitle()}}</title>
```

So:   
- If there's page title set in the application => it will render `Your Title - Your Postfix Name`
- If there's no page title => `Your postfix name` only

### Why it's a good/best practices
- Within a class, we can simply extend it in the future
    - `O` of SOLID
- Got a better way to compose the Postfix Name (like I said above, we can get from `config` or `env` or DB,...)
- Easy to use, no need to pass any variables
- Maintain should be easier because:
    - Everytime we use the class, we will use the `use` keyword and make the Controllers/Services depend on that
        - We can search where `PageTitle` being used really fast
    - 1 way only to set the page title across the Application
- We can write tests for the `PageTitle` class

## Conclusion

That's my way how I'm doing it for my Laravel application. How about yours?

## Contribute
Fork it and send me a PR if you want to improve the content

## Copyright
2021 by Seth Phat