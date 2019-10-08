<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    
    $container = $app->getContainer();
    /*
    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    }); */
    
    
    $app->get('/', \App\Controllers\ContentController::class .':home')->setName('home');
        
    $app->get('/content', \App\Controllers\ContentController::class . ':getContent')->setName('content');
    
    // Google Map
    $app->get('/map', \App\Controllers\ImagesController:: class . ':displayMap')->setName('map');
    
    $app->post('/map', \App\Controllers\ImagesController:: class . ':likeImage');
    $app->post('/map/report', \App\Controllers\ImagesController:: class . ':reportImage');
    $app->post('/map/comment', \App\Controllers\ImagesController:: class . ':commentImage');
    $app->post('/map/getid', \App\Controllers\ImagesController:: class . ':retrieveImageId');
    
    $app->get('/map/showcomment', \App\Controllers\ImagesController:: class . ':getComments');
    
    $app->get('/map/api', \App\Controllers\ImagesController:: class . ':fetchMarkersRest');
    
    // Inscription
    $app->get('/signup', \App\Controllers\MembersController::class . ':getFormInscripton')->setName('inscription');
    $app->post('/signup', \App\Controllers\MembersController:: class . ':postSignup')->setName('postInscription');
    
    //Login
    $app->post('/login', \App\Controllers\MembersController:: class . ':login')->setName('login');
    
    // Espace membre
    $app->get('/profile', \App\Controllers\MembersController:: class . ':displayProfile')->setName('profile');
    $app->get('/profile/myimgs', \App\Controllers\MembersController:: class . ':getMyPhotos');
    
    // Admin
    $app->get('/admin', \App\Controllers\AdminController:: class . ':adminPanel')->setName('admin');
    
    // Deconnexion
    $app->get('/deconnexion', \App\Controllers\MembersController:: class . ':logout')->setName('deconnexion');
    
    // Memberlist
    $app->get('/members', \App\Controllers\MembersController:: class . ':displayMembersList')->setName('memberList');
    
    $app->post('/addfriend', \App\Controllers\MembersController:: class . ':addFriendRequest')->setName('addFriend');
    $app->get('/addedfriend', \App\Controllers\MembersController:: class . ':acceptFriend');
    $app->get('/ignorefriend', \App\Controllers\MembersController:: class . ':ignoreFriendRequest');
    $app->post('/addavatar', \App\Controllers\MembersController:: class . ':changeAvatar');
    
    // Ajouter contenus ( test )
    $app->get('/add', \App\Controllers\ContentController ::class . ':getAddForm')->setName('ajouter');
    $app->post('/addcontent', \App\Controllers\ContentController ::class . ':addContent');
        
    // Upload de photos
    $app->get('/upload', \App\Controllers\ImagesController::class . ':getForm')->setName('upload');
    $app->post('/addexif', \App\Controllers\ImagesController:: class . ':manageExif');
    
};
