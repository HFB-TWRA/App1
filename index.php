<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';


 
require 'TWRAController.php';
 

 

    
    
\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim(
                      array(
                            'debug' => true
                            )
                      );
  
/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */

// GET route
    
    $app->get('/', function () {
              echo "Web Services";
              });
    
    
    $app->get('/locations/:type','getLocations');
    
    $app->get('/location/:type/:id','getLocationDetails');
    
    $app->get('/searchlocations/:type/:searchkeyword','getLocationsBasedOnSearch');

    $app->get('/recipes','getRecipes');
    
    $app->get('/fishguide','getFishGuide');
    
    $app->get('/sunrisesunset/:state/:place/:day/:month/:year','getSunriseSunsetTimings');

    $app->get('/sunrisesunsetNearest/:lat/:long/:year/:month/:day','sunrisesunsetNearest');
    
// USNavy - need to add
    
/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();

	function sunrisesunsetNearest($lat,$long,$year,$month,$day){
        global $app;        
        
		$controller= new TWRAController();
        $locationsArray=$controller->sunrisesunsetNearest($lat,$long,$year,$month,$day);
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($locationsArray);
		
    }

    function getLocations($type){
        global $app;

        $controller= new TWRAController();
        $locationsArray=$controller->getLocations($type);
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($locationsArray);
        
        
    }
    
    function getLocationDetails($type,$id){
        global $app;

        $controller= new TWRAController();
        $location= $controller->getLocationDetails($type,$id);
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($location);
        
    }
    
    function getLocationsBasedOnSearch($type,$searchkeyword){
        global $app;
        
        $controller= new TWRAController();
        $locationsArray=$controller->getLocationsBasedOnSearch($type,$searchkeyword);
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($locationsArray);
    }
    
    
    function getRecipes(){
        
        global $app;
        
        //$controller= new TWRAController();
        //$RecipesArray=$controller->getRecipes();
        $app->response->headers->set('Content-Type', 'application/json');
        $app->response->headers->set('charset', 'utf-8');
        
        $jsondata=file_get_contents('recipes.json');

        echo $jsondata;//json_encode($RecipesArray);
    
    }
    
    function getFishGuide(){
        global $app;

        
        $controller= new TWRAController();
        $RecipesArray=$controller->getFishGuide();
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($RecipesArray);
    }
    
    function getSunriseSunsetTimings($state,$place,$day,$month,$year){
        
        $controller= new TWRAController();
        $responceString=$controller->getSunriseSunsetTimings($state,$place,$day,$month,$year);
        echo $responceString;
    }
    
