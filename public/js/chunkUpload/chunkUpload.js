var chunkEnviados = 0;
var chunkSize = 0;
var seconds = 1;

$(document).ready(function(){
    init();
});

//cuando se sube un archivo completo exitosamente
function uploadSuccessfully(e,data){
    seconds = 1;
    //window.location.reload();
}

//se llama cada un segundo para mostrar estadisticas de transferencias
function updateProgress(e,data){
    
    var progress = parseInt(data.loaded / data.total * 100, 10);
    $('#progress').css('width',progress + '%');
    $('#val').html(progress+"%");
    
    //console.log(data);
    
    $('#lblSpeed').html(getFileSizeAndUnit(data.bitrate));
    seconds++;
    
}

//cuando se click en el boton
function submitUpload(e,data){
  
    data.context = $( "#btn-submit" ).click(function() {
        data.submit();
        $('#progress-container').toggleClass("hide");;
    });
   
}

//dado ciertos bytes devuelve tamanio
function getFileSizeAndUnit(bytes){
    
    var iSize = (bytes / 1024);
    
     if (iSize / 1024 > 1){
        if (((iSize / 1024) / 1024) > 1){
            iSize = (Math.round(((iSize / 1024) / 1024) * 100) / 100);
            return iSize + " Gb";
        }else{
            iSize = (Math.round((iSize / 1024) * 100) / 100)
            return iSize +" Mb";
        }
     }else{
        iSize = (Math.round(iSize * 100) / 100)
        return iSize + "kb";
     }
    
}


//define el tamanio del chunk en funcion al tamanio dado
function getCorrectChunkSize(bytes){
    
    if(bytes<1200000){
        return bytes;   
    }else{
        return Math.round(bytes/10);
    }
    
}

function init(){
    
    var options = {
            maxChunkSize: chunkSize,
            done: uploadSuccessfully,   //se ejecuta al terminar la subida
            progressall: updateProgress, //se usa para actualizar el progreso de subida
            autoUpload: false,
            add: submitUpload,           //para que el boton sea el que lanza el upload
            maxNumberOfFiles: 1,
            progressInterval:1000,
            progressInterval:1000,
        };
    
    $('#fileupload').fileupload(options)
    .on('fileuploadchunksend', function (e, data) {
    
    })
    .on('fileuploadchunkdone', function (e, data) {

    })
    .on('fileuploadchunkfail', function (e, data) {
        
    })
    .on('fileuploadchunkalways', function (e, data) {
        
    });

    
    $("#fileupload").change(function (e){
        
        $('#lblSize').html(getFileSizeAndUnit(this.files[0].size)); //actualizo el tamaño
        chunkSize = getCorrectChunkSize(this.files[0].size);
        //console.log("Correct chunk size: "+getCorrectChunkSize(this.files[0].size));
        //console.log("Tamanio de archivo: "+this.files[0].size);
        var file = this.file;
        //console.log(file);
        
        $('#fileupload').fileupload({
            maxChunkSize: chunkSize,
            done: uploadSuccessfully,   //se ejecuta al terminar la subida
            progressall: updateProgress, //se usa para actualizar el progreso de subida
            autoUpload: false,
            add: submitUpload,           //para que el boton sea el que lanza el upload
            maxNumberOfFiles: 1,
            progressInterval:1000,
            progressInterval:1000}
        ).on('fileuploadchunksend', function (e, data) {
        
        })
        .on('fileuploadchunkdone', function (e, data) {
            
            if(data.result.success==="fileFinished"){
                
                swal({
                  title: "Subida exitosa",
                  text: "!Su back up fue almacenado correctamente!",
                  type: "success"
                }, function() {
                        location.reload();
                });
                
            }
        })
        .on('fileuploadchunkfail', function (e, data) {
            
            console.log(data);    
            
            swal({
              title: "Ocurrió un error",
              text: "!Por favor intente nuevamente en unos instantes!",
              type: "error"
            }, function() {
                    //location.reload();
            });
            
        })
        .on('fileuploadchunkalways', function (e, data) {

        });
        
    });
    
    
    
}