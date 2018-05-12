<?php 

namespace App\Services;

use App\Entity\BackUpFile;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FileManagerService{
    
    private $container;
    private $validator;
    private $em;
    
    public function __construct(ContainerInterface $container){
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
        $this->validator = $this->container->get('app.file.validator.service');
    }
    
    public function uploadFile($request,$ud){
        
        $uploadInfo = $this->getUploadInfo($request,$ud);//array donde guardo todas las variables que me interesan del request
        
        try{
            $this->validator->makeValidations($uploadInfo);
            //$this->validator->checkSize(sizeof($file));
        }catch(\Exception $e){
            throw $e;
        }
        
        
        //creo el directorio donde voy a guardar los temporales de esta subida
        if(!file_exists($uploadInfo["tempPath"])) {
            mkdir($uploadInfo["tempPath"], 0777, true);
        }
        
        $uploadInfo["file"]->move($uploadInfo["tempPath"],$uploadInfo["chunkEnd"]); //muevo el archivo al directorio temporal y le pongo el nombre de la posicion final del chunk
        
        $noChunkFile = false;
        if(!isset($uploadInfo["chunkEnd"])||$uploadInfo["chunkEnd"]==""){
            $noChunkFile = true;
        }
        
        //si es el ultimo chunk, o la transferencia no es por chunks, creo el archivo permanente y borro lo temporal
        if(($uploadInfo['chunkEnd']==$uploadInfo["totalFileSize"]-1)||$noChunkFile == true){
            
            $dateTime = new \DateTime();
            
            //busco la carpeta de archivos temporales
            $files = array_diff(scandir($uploadInfo["tempPath"]), array('..', '.'));
            
            //busco la carpeta donde se guardan los back up del usuario, si no existe el directorio lo creo
            if(!file_exists($uploadInfo["permanentPath"])) {
                mkdir($uploadInfo["permanentPath"], 0777, true);
            }
            
            //ordeno por orden alfabetico los archivos en el array en memoria
            sort($files);
            
            //los grabo en orden con el titulo original del archivo y con la fecha    
            $fp = fopen($uploadInfo["permanentPath"].$dateTime->format("d-m-Y-H-i-s")."-".$uploadInfo["fileName"], 'ab');     //apendeo en el archivo permanente y borro el temporal
                
            foreach($files as $file){
                $fc = file_get_contents($uploadInfo["tempPath"].'/'.$file);
                fwrite($fp, $fc);
                unlink($uploadInfo["tempPath"].'/'.$file);
            }
            
            fclose($fp);
            
            rmdir($uploadInfo["tempPath"]);//borro el directorio temporal
            
            $backUpFile = new BackUpFile;
            $backUpFile->setName($uploadInfo["fileName"]);
            $backUpFile->setUploadDate($dateTime);
            $backUpFile->setLocation($uploadInfo["permanentPath"]);
            $backUpFile->setSize(/*$uploadInfo["totalFileSize"]*/"20");
            $backUpFile->setIdUser($ud["id"]);
            
            $this->em->persist($backUpFile);
            $this->em->flush();
        
            return "fileFinished";
        }
        
        return "chunkFinished";
    }
    
    public function downloadFile($request,$ud){
        
        $idBackUpFile = $request->get('id');
        $idUser = $ud["id"];
        $backUpFile = $this->em->getRepository('App:BackUpFile')->find($idBackUpFile);
        
        if(!$backUpFile){
            throw new Exception("Back up no encontrado en nuestra base de datos");
        }
        
        if($backUpFile->getIdUser()!=$idUser){
            throw new Exception("Usted no está autorizado para realizar esta acción");
        }
        
        //creo el download ticket
        
        //$url = $backUpFile->getLocation().$backUpFile->getUploadDate()->format("d-m-Y-H-i-s")."-".$backUpFile->getName();
        //$url = $this->container->get('router')->getContext()->getHost().'/LightDBS/uploads/'.$idUser.'/'.$backUpFile->getUploadDate()->format("d-m-Y-H-i-s")."-".$backUpFile->getName();
        $url = $request->getSchemeAndHttpHost()  . '/lightdbs_/uploads/'.$idUser.'/'.$backUpFile->getUploadDate()->format("d-m-Y-H-i-s")."-".$backUpFile->getName();
        return $url;
    }
    
    private function getUploadDirectory(){
        return $this->container->getParameter('uploads_directory');
    }
    
    private function getTempDirectory(){
        return $this->container->getParameter('temp_directory');
    }
    
    private function getUploadInfo($request,$ud){
        
        $files = $request->files;
        $headers = $request->headers;
        
        $content_range_header = $headers->get('content-range');
        $content_range = $content_range_header ?
            preg_split('/[^0-9]+/', $content_range_header) : null;
        
        $uploadInfo["chunkStart"] = $content_range[1];
        $uploadInfo["chunkEnd"] = $content_range[2];
        $uploadInfo["chunkSize"] = $files->get('files')[0]->getSize();
        $uploadInfo["totalFileSize"] = $content_range[3];
        $uploadInfo["fileName"] = $files->get('files')[0]->getClientOriginalName();
        $uploadInfo["fileExt"] =  $files->get('files')[0]->guessExtension();
        $uploadInfo["id"] = uniqid();
        $uploadInfo["file"] = $files->get('files')[0];
        $uploadInfo["tempPath"] = $this->getTempDirectory().$uploadInfo["fileName"];
        $uploadInfo["permanentPath"] = $this->getUploadDirectory().$ud["id"].'/';

        return $uploadInfo;
    }
}