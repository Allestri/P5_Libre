<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;

class Controller
{
    
    protected $container;
    
    // Passes the DIC to get the model.
    function __construct($container)
    {
        $this->container = $container;
    }
    
    public function render(ResponseInterface $response, $file, $params = []){
        
        $this->container->view->render($response, $file, $params);
    }
    
    public function redirect($response, $name){
        return $response->withStatus(302)->withHeader('Location', $this->container->router->pathFor($name));
    }
    
    public function flash($message, $type ='success')
    {
        if(!isset($_SESSION['flash'])){
            $_SESSION['flash'] = [];
        }
        return $_SESSION['flash'][$type] = $message;
    }
    
    public function flashAjax($message, $type ='success', $input = null)
    {
        $response = [
            "message" => $message,
            "type" => $type,
            "input" => $input,
        ];
        
        echo json_encode($response);
    }
    
    public function relativeDate($time)
    {
        $timeAgo = '';
        $now = time();

        $time2 = intval($time);
        $diff = $now - $time2;
                
        // Relative time / date
                
        if($diff < 60){ // x seconds ago
            return 'a l\'instant';
        } 
        elseif($diff < 3600){ // x minutes ago
            $nbr = round($diff / 60);
            return $nbr == 1 ? ('il y a ' . $nbr . ' minute') : ('il y a ' . $nbr . ' minutes');
        }
        elseif($diff < 3600 * 24){ // x hours ago
            $nbr = round($diff / 3600);
            return $nbr == 1 ? ('il y a ' . $nbr . ' heure') : ('il y a ' . $nbr . ' heures');
        }
        elseif($diff < 3600 * 24 * 2){ // yesterday
            return 'hier';
        }
        else {
            return 'il y a plusieurs jours';
        }
    }
    
    public function pagination($request, $contentTotal, $limit)
    {
        $query = $request->getQueryParams();

        // Checks if param exists, is a number, assigns default page(1) if not.
        if(isset($query['page']) && is_numeric($query['page'])) {
            $currentPage = $query['page'];
        } else {
            $currentPage = 1;
        }

        $totalPages = ceil($contentTotal / $limit);
        
        // If current page is higher than total pages.
        // Set current page to last page.
        if(isset($query['page']) && ($query['page'] > $totalPages)){
            $currentPage = $totalPages;
        }
        // If current page is lower than 1.
        // Sets to page 1.
        if(isset($query['page']) && ($query['page'] < 1)){
            $currentPage = 1;
        }
        
        // Sets offset based on current page(inclusive)
        $args['offset'] = ($currentPage - 1) * $limit;
        
        $args['currentPage'] = $currentPage;
        $args['totalPages'] = $totalPages;
        $args['nextPage'] = $currentPage + 1;
        $args['previousPage'] = $currentPage - 1;
        
        return $args;
    }    
    

    
    
}