<?php 

namespace App\Services;

use App\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class FileValidatorService{
    
    private $container;
    
    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }
    
    public function makeValidations($uploadInfo){
        if($uploadInfo["fileExt"]!='sql' ){
           throw new \Exception("Extensión de archivo inválida");    
        }
    }
    
}