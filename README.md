This is an improvement to SlimFramework that permits to declare routes with the style of Symfony2

If you want to see a real demo you can clone [this repository](https://www.github.com/jlaso/slim-routing-manager-sample) and launch composer install

or to visit [this post](http://www.jaitec.net/blog/improving-the-behaviour-of-slimframework-with-a-routing-manager)  to see a complete demo
in action

The idea is to have this structure in the front controller (normally index.php)

    new RoutingCacheManager(  
      array(  
        'cache'      => __DIR__ . '/cache/routing',  
        'controller' => __DIR__ . '/app/controller',  
      )  
    );  

The default values of cache and controller are exactly the shown, i.e. the previous sentence is equivalen to:


    new RoutingCacheManager();  


And the 'controller' key accept a path or an array of paths, for instance:  
 
    new RoutingCacheManager(
       array(
          'cache'      => __DIR__ . '/cache/routing',
          'controller' => array(
             __DIR__ . '/app/controller',
             __DIR__ . '/app/subcontroller',
            )
        )
    );

Obviouslly the 'cache' path must to be write rights for the http/apache daemon user.

The idea is that RoutingCacheManager process the xxxxController classes that exist in the path/paths and creates for each one a Slim loader version.

Let see an example.

This is the content of app/controller/FrontendController.php
 
```
    class FrontendController extends Controller
    {
      /**
       * @Route('/')
       * @Name('home.index')
       */
       public function sampleRouteAction()
       {
            /** @var Slim\Slim $slim */
            $slim = $this->getSlim();
            $slim->response()->body('This is the home route ' . $this->getName());
       }
    }
```

As you can see the annotations are very clear and reflect clearly the intention of this route endopoint

The problem is that Slim doesn't understand this structure and we need to pass to this format:


    $app->map("/", function(){ blah blah blah })->via("GET")->name("home.index");


Then our SlimRoutingManager process the FrontendController class and creates a little loader with the Slim flavour:

    $app->map("/", "FrontendController::___sampleRouteAction")->via("GET")->name("home.index");

The main Controller class does the magic changing the invocation of this pseudo static method __sampleRouteAction
to the correct method.

