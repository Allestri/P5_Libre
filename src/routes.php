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
    
    $app->post('/map/like', \App\Controllers\ContentController:: class . ':likePost');
    $app->post('/map/unlike', \App\Controllers\ContentController:: class . ':unlikePost');
    $app->post('/map/report', \App\Controllers\ContentController:: class . ':reportPost');
    $app->post('/map/comment', \App\Controllers\ContentController:: class . ':commentPost');
    $app->get('/map/showcomment', \App\Controllers\ContentController:: class . ':getComments');
    $app->post('/map/deletecomment', \App\Controllers\ContentController:: class . ':deleteComment');
    
    $app->post('/map/getid', \App\Controllers\ContentController:: class . ':retrievePostId');
    $app->post('/getids', \App\Controllers\ContentController:: class . ':retrieveIds');
    $app->post('/getId', \App\Controllers\ImagesController:: class . ':retrieveImageId');
      
    $app->get('/map/api', \App\Controllers\ImagesController:: class . ':fetchMarkersRest');
    $app->get('/map/infos', \App\Controllers\ImagesController:: class . ':getInfos');
    $app->get('/map/getlikes', \App\Controllers\ContentController:: class . ':getLikes');
    
    // Inscription
    $app->get('/signup', \App\Controllers\MembersController::class . ':getFormInscripton')->setName('inscription');
    $app->post('/signup', \App\Controllers\MembersController:: class . ':postSignup')->setName('postInscription');
    
    //Login
    $app->post('/login', \App\Controllers\MembersController:: class . ':login')->setName('login');
    
    // Espace membre
    $app->get('/profile', \App\Controllers\MembersController:: class . ':displayProfile')->setName('profile');
    $app->get('/profile/myimgs', \App\Controllers\ImagesController:: class . ':getMyPhotos');
    $app->post('/profile/deleteimg', \App\Controllers\ContentController:: class . ':deletePost');
    
    $app->get('/newprofile', \App\Controllers\MembersController:: class . ':displayNewProfile')->setName('newprofile');
    $app->post('/newprofile/editpost', \App\Controllers\ContentController:: class . ':editPost');
    $app->post('/newprofile/getpost', \App\Controllers\ContentController:: class . ':getSelectedPost');
    
    // Profil Settings
    $app->post('/addavatar', \App\Controllers\MembersController:: class . ':addNewAvatar');
    $app->post('/switchavatar', \App\Controllers\MembersController:: class . ':switchAvatar');
    
    $app->post('/settings', \App\Controllers\MembersController:: class . ':changeSettings');
    $app->get('/delete', \App\Controllers\MembersController:: class . ':deleteAvatar');
    
    // Admin
    $app->get('/admin', \App\Controllers\AdminController:: class . ':adminPanel')->setName('admin');
    $app->post('/admin/delete', \App\Controllers\AdminController:: class . ':deletePost');
    $app->post('/admin/getreport', \App\Controllers\AdminController:: class . ':getSelectedPost');
    $app->post('/admin/edit', \App\Controllers\AdminController:: class . ':editPost');
    
    $app->get('/admin/debug', \App\Controllers\ContentController:: class . ':debug');
    $app->post('/admin/debug', \App\Controllers\ContentController:: class . ':debug');
    
    $app->get('/flash', \App\Controllers\ContentController:: class . ':testFlash');
    
    // Deconnexion
    $app->get('/deconnexion', \App\Controllers\MembersController:: class . ':logout')->setName('deconnexion');
    
    // Memberlist
    $app->get('/members', \App\Controllers\MembersController:: class . ':displayMembersList')->setName('memberList');
    
    $app->post('/addfriend', \App\Controllers\MembersController:: class . ':addFriendRequest')->setName('addFriend');
    $app->get('/addedfriend', \App\Controllers\MembersController:: class . ':acceptFriend');
    $app->get('/ignorefriend', \App\Controllers\MembersController:: class . ':ignoreFriendRequest');

    // Ajouter contenus ( test )
    $app->get('/add', \App\Controllers\ContentController ::class . ':getAddForm')->setName('ajouter');
    $app->post('/addcontent', \App\Controllers\ContentController ::class . ':addContent');
        
    // Upload de photos
    $app->get('/upload', \App\Controllers\ImagesController::class . ':getForm')->setName('upload');
    $app->post('/addexif', \App\Controllers\ImagesController:: class . ':manageExif');
    $app->get('/testupload', \App\Controllers\ImagesController:: class . ':getTestForm')->setName('testForm');
    $app->post('/testexif', \App\Controllers\ImagesController:: class . ':testExif');
    
};
