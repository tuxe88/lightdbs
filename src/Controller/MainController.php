<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\BackUpFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Microblet\CoreBundle\Controller\MicrobletPrivateController;

class MainController extends MicrobletPrivateController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request){
        //die(get_class($this));
        $ud = $this->getCurrentUser($request);
        return new Response($this->renderView('main/home.html.twig'),200);
    }
    
    /**
     * @Route("/upload/backup", name="upload")
     */
    public function uploadBackUp(Request $request)
    {
    
        if($request->getMethod()=='POST'){
            try{
                $fms = $this->get('app.file.manager.service');
                return new JsonResponse(array("success"=>$fms->uploadFile($request,$this->getCurrentUser($request))));    
            }catch(Exception $e){
                return new JsonResponse(array("error"=>"Ocurrio un error: ".$e->getMessage(),
                                                "code"=>500)); 
            }
        }
     
        return new Response($this->renderView('main/upload.html.twig'),200);
    }
    
    /**
     * @Route("/download/backup", name="download")
     */
    public function downloadBackUp(Request $request)
    {
    
        if($request->getMethod()=='POST'){
            try{
                $fms = $this->get('app.file.manager.service');
                return new JsonResponse(array("success"=>$fms->downloadFile($request,$this->getCurrentUser($request))));    
            }catch(Exception $e){
                return new JsonResponse(array("error"=>"Ocurrio un error: ".$e->getMessage(),
                                                "code"=>500)); 
            }
        }
     
        return new Response($this->renderView('main/upload.html.twig'),200);
    }
    
    /**
     * @Route("/list/backup", name="list_back_up")
     */
    public function listBackUp(Request $request){
        $ud = $this->getCurrentUser($request);
        
        $backups = $this->getDoctrine()->getManager()->getRepository('App:BackUpFile')->findBy(array("idUser"=>$ud["id"]));
        
        return new Response($this->renderView('main/listBackUp.html.twig',array("backups"=>$backups)),200);
    }
}
