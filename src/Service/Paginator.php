<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Paginator 
{
    private $twig;
    private $router;
    private $entities;
    private $links;
    
    public function __construct(Environment $twig, UrlGeneratorInterface $router) 
    {
        $this->twig=$twig;
        $this->router = $router;
    }
    
    public function buildPaginer($query, $numberItems) :self
    {
        $request = Request::createFromGlobals();
        $path    = $request->getPathInfo();
        $routeParams = $this->router->match($path);
        $routeName   = $routeParams['_route'];
        unset($routeParams['_route']);
        
        $page=$request->query->get('page',1);
        $totalItems=count($query->getResult());
        $numberPages=$totalItems/$numberItems;
        $page= intval($page);
        if($page<1) $page=1;
        $offset=($page-1)*$numberItems;
        $query->setFirstResult($offset);
        $query->setMaxResults($numberItems);
        $this->entities=$query->getResult();
        $link=[];
        if($numberPages>1)
        {
            if($page>1)
            {
                $routeParams['page']=$page-1;
                $link[]=[
                    'path'=>$this->router->generate($routeName,$routeParams),
                    'text'=>'předchozí',
                    'class'=>'prev',
                ];
            };
            if($page<$numberPages)
            {
                $routeParams['page']=$page+1;
                $link[]=[
                    'path'=>$this->router->generate($routeName,$routeParams),
                    'text'=>'následující',
                    'class'=>'next',
                ];
            };
        }
        $this->links=$link;
        
        return $this;
    }
    
    public function entities() 
    {
        return $this->entities;
    }
    
    public function links() 
    {
        return $this->links;
    }
}
