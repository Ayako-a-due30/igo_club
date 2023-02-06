<footer>
        <a href="">
            囲碁部ノート all right reserved.
        </a>
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script>
    $(function(){
    var $ftr = $('#footer');
    if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
      $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
    }
  });

  var $dropArea = $('.area-drop');
  var $fileInput = $('.input-file');
  $dropArea.on('dragover',function(e){
    e.stopPropagation();
    e.preventDefault();
    $(this).css('border','none');
  });
  $fileInput.on('change',function(e){
    $dropArea.css('border','none');
    var file = this.files[0],
    $img = $(this).siblings('.prev-img'),
    fileReader= new fileReader();
    fileReader.onload = function(event){
      $img.attr('src',event.target.result).show();
    };
    fileReader.readAsDataURL(file);
  });
</script>
