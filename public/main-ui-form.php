<?php require_once('../private/init.php'); ?>

<?php

$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());
$deletable_image_ids = "";

if(!empty($admin)){
    $all_categories = new Category();
    $all_categories = $all_categories->where(["status"=>1])->all();

    $slider_images = new Slider_Image();
    $slider_images = $slider_images->where(["admin_id"=>$admin->id])->all();

    $home_banners = new Home_Banner();
    $home_banners = $home_banners->where(["admin_id"=>$admin->id])->all();

}else Helper::redirect_to("login.php");

?>


<?php require("common/php/php-head.php"); ?>

<body>

<?php require("common/php/header.php"); ?>

<div class="main-container main-ui">
    <?php require("common/php/sidebar.php"); ?>

    <div class="main-content">

        <div class="oflow-hidden">
            <div class="float-l pb-15">
                <?php if($message) echo $message->format(); ?>
            </div>
            <h6 class="float-r">
                <a class="c-btn mb-30 mr-10" data-popup="#slider-image" href="#">+ Add Slider Image</a>
                <a class="c-btn mb-30" data-popup="#banner" href="#">+ Add Banner</a>
            </h6>
        </div>

        <div class="item-wrapper three">

            <?php $i = 1;
                foreach ($slider_images as $si) { ?>
                <div class="item">

                    <form data-validation="true" action="../private/controllers/slider_image.php" method="post" enctype="multipart/form-data">
                        <div class="item-inner">

                            <div class="item-header">
                                <h5 class="dplay-inl-b">Slider <?php echo $i; $i++; ?></h5>
                                <a data-confirm = "Are you sure?" href="<?php echo "../private/controllers/slider_image.php?id=" . $si->id . "&&admin_id=" . $si->admin_id; ?>" class="link float-r oflow-hidden"><b>Remove</b></a>
                            </div><!--item-header-->

                            <div class="item-content">
                                <div class="image-upload">

                                    <input type="hidden" name="id" value="<?php echo $si->id; ?>"/>

                                    <div class="dplay-tbl">
                                        <div class="dplay-tbl-cell">
                                            <img src="<?php if(!empty($si->image_name))
                                                echo UPLOADED_FOLDER . DIRECTORY_SEPARATOR . $si->image_name; ?>" alt="" class="uploaded-image"/>
                                        </div><!--dplay-tbl-cell-->
                                    </div><!--dplay-tbl-->

                                    <input data-required="image" type="file" name="image_name" class="image-input"
                                           value="<?php echo $si->image_name; ?>"/>
                                </div>

                                <label>Tag</label>
                                <input type="text" data-required="true" name="tag" placeholder="Tag Name" value="<?php echo $si->tag; ?>"/>

                                <div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Save</b></button></div>

                                <?php if(Session::get_session_by_key(SLIDER_IMAGE_ID) == $si->id){
                                    Session::unset_session_by_key(SLIDER_IMAGE_ID);
                                    if($errors){
                                        echo $errors->format();
                                        $errors = null;
                                    }
                                }?>

                            </div><!--item-content-->
                        </div><!--item-inner-->

                    </form>
                </div><!--item-->

            <?php } ?>
        </div><!-- item-wrapper -->

        
        <div class="item-wrapper one popup-area" id="slider-image">
            <div class="dplay-tbl">
                <div class="dplay-tbl-cell">

                    <div class="item">

                        <form data-validation="true" action="../private/controllers/slider_image.php" method="post" enctype="multipart/form-data">
                            <div class="item-inner">

                                <div class="item-header">
                                    <h5 class="dplay-inl-b">Slider Image</h5>
                                </div><!--item-header-->

                                <div class="item-content">
                                    <div class="image-upload">

                                        <img src="" alt="" class="uploaded-image"/>

                                        <div class="h-100" class="upload-content">
                                            <div class="dplay-tbl">
                                                <div class="dplay-tbl-cell">
                                                    <i class="ion-ios-cloud-upload"></i>
                                                    <h5><b>Choose Your Image to Upload</b></h5>
                                                    <h6 class="mt-10 mb-70">Or Drop Your Image Here</h6>
                                                </div>
                                            </div>
                                        </div><!--upload-content-->

                                        <input data-required="image" type="file" name="image_name" class="image-input"
                                               data-traget-resolution="image_resolution" value=""/>

                                    </div>

                                    <input type="text" data-required="true" name="tag" value="" placeholder="Tag Name"/>

                                    <div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Save</b></button></div>

                                    <?php if($errors) echo $errors->format(); ?>

                                </div><!--item-content-->
                            </div><!--item-inner-->

                        </form>
                    </div><!--item-->

                </div><!--dplay-tbl-cell-->
            </div><!--dplay-tbl-->
        </div><!-- item-wrapper -->


        <div class="item-wrapper one popup-area" id="banner">
            <div class="dplay-tbl">
                <div class="dplay-tbl-cell">

                    <div class="item">

                        <form data-validation="true" action="../private/controllers/home_banner.php" method="post" enctype="multipart/form-data">
                            <div class="item-inner">

                                <div class="item-header">
                                    <h5 class="dplay-inl-b">Banner</h5>
                                </div><!--item-header-->

                                <div class="item-content">
                                    <div class="image-upload">

                                        <img src="" alt="" class="uploaded-image"/>

                                        <div class="h-100" class="upload-content">
                                            <div class="dplay-tbl">
                                                <div class="dplay-tbl-cell">
                                                    <i class="ion-ios-cloud-upload"></i>
                                                    <h5><b>Choose Your Image to Upload</b></h5>
                                                    <h6 class="mt-10 mb-70">Or Drop Your Image Here</h6>
                                                </div>
                                            </div>
                                        </div><!--upload-content-->
                                        
                                        <input data-required="image" type="file" name="image_name" class="image-input"
                                               data-traget-resolution="image_resolution" value=""/>
                                    </div>


                                    <input type="text" data-required="true" name="tag" value="" placeholder="Tag Name"/>

                                    <div class="oflow-hidden w-100">
                                        <div class="input-6 pr-7-5">
                                            <?php if($all_categories > 0){ ?>
                                                <select name="category_1" data-required="dropdown">
                                                    <option selected="true" disabled="disabled">Please select a category</option>
                                                    <?php foreach($all_categories as $item){ ?>
                                                        <option value="<?php echo $item->id; ?>"><?php echo $item->title; ?></option>
                                                    <?php }?>
                                                </select>
                                            <?php  } ?>
                                        </div>

                                        <div class="input-6 pl-7-5">
                                            <?php if($all_categories > 0){ ?>
                                                <select name="category_2" data-required="dropdown">
                                                    <option selected="true" disabled="disabled">Please select a category</option>
                                                    <?php foreach($all_categories as $item){ ?>
                                                        <option value="<?php echo $item->id; ?>"><?php echo $item->title; ?></option>
                                                    <?php }?>
                                                </select>
                                            <?php  } ?>
                                        </div>
                                    </div><!--oflow-hidden-->

                                    <div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Save</b></button></div>

                                    <?php if($errors) echo $errors->format(); ?>

                                </div><!--item-content-->
                            </div><!--item-inner-->

                        </form>
                    </div><!--item-->

                </div><!--dplay-tbl-cell-->
            </div><!--dplay-tbl-->
        </div><!-- item-wrapper -->


        <div class="item-wrapper one ">
            <?php $i = 1;
            foreach ($home_banners as $si) { ?>

                <div class="item ">

                    <form data-validation="true" action="../private/controllers/home_banner.php" method="post" enctype="multipart/form-data">
                        <div class="item-inner">

                            <div class="item-header">
                                <h5 class="dplay-inl-b">Banner <?php echo $i; $i++; ?></h5>
                                <a data-confirm = "Are you sure?" href="<?php echo "../private/controllers/home_banner.php?id=" . $si->id . "&&admin_id=" . $si->admin_id; ?>" class="link float-r oflow-hidden"><b>Remove</b></a>
                            </div><!--item-header-->

                            <div class="item-content">
                                <div class="image-upload">

                                    <input type="hidden" name="id" value="<?php echo $si->id; ?>"/>

                                    <div class="dplay-tbl">
                                        <div class="dplay-tbl-cell">
                                            <img src="<?php if(!empty($si->image_name))
                                                echo UPLOADED_FOLDER . DIRECTORY_SEPARATOR . $si->image_name; ?>" alt="" class="uploaded-image"/>
                                        </div><!--dplay-tbl-cell-->
                                    </div><!--dplay-tbl-->

                                    <input data-required="image" type="file" name="image_name" class="image-input"
                                           value="<?php echo $si->image_name; ?>"/>
                                </div>

                                <label>Tag</label>
                                <input type="text" data-required="true" name="tag" placeholder="Tag Name" value="<?php echo $si->tag; ?>"/>

                                <div class="oflow-hidden w-100">
                                    <div class="input-6 pr-7-5">
                                        <?php if($all_categories > 0){ ?>
                                            <select name="category_1" data-required="dropdown">
                                                <option selected="true" disabled="disabled">Please select a category</option>
                                                <?php foreach($all_categories as $item){ ?>
                                                    <?php if($si->category_1 == $item->id) $selected_cat = "selected";
                                                    else $selected_cat = ""; ?>

                                                    <option value="<?php echo $item->id; ?>"  <?php echo $selected_cat; ?>><?php echo $item->title; ?></option>
                                                <?php }?>
                                            </select>
                                        <?php  } ?>
                                    </div>

                                    <div class="input-6 pl-7-5">
                                        <?php if($all_categories > 0){ ?>
                                            <select name="category_2" data-required="dropdown">
                                                <option selected="true" disabled="disabled">Please select a category</option>
                                                <?php foreach($all_categories as $item){ ?>
                                                    <?php if($si->category_2 == $item->id) $selected_cat = "selected";
                                                    else $selected_cat = ""; ?>

                                                    <option value="<?php echo $item->id; ?>"  <?php echo $selected_cat; ?>><?php echo $item->title; ?></option>
                                                <?php }?>
                                            </select>
                                        <?php  } ?>
                                    </div>
                                </div><!--oflow-hidden-->


                                <div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Save</b></button></div>

                                <?php if(Session::get_session_by_key(BANNER_IMAGE_ID) == $si->id){
                                    Session::unset_session_by_key(BANNER_IMAGE_ID);
                                    if($errors){
                                        echo $errors->format();
                                        $errors = null;
                                    }
                                }?>

                            </div><!--item-content-->
                        </div><!--item-inner-->

                    </form>
                </div><!--item-->

            <?php } ?>
        </div><!-- item-wrapper -->


    </div><!--main-content-->


</div><!--main-container-->


 <?php echo "<script>maxUploadedFile = '" . MAX_IMAGE_SIZE  . "'</script>"; ?>
 <?php echo "<script>maxUploadedFileCount = '" . MAX_FILE_COUNT  . "'</script>"; ?>
 <?php echo "<script>adminId = '" . $admin->id  . "'</script>"; ?>

 <?php require("common/php/php-footer.php"); ?>

<script> attrQty = [];</script>
<script> attrFromDB = [];</script>
<script> attrIdsFromDB = [];</script>

<?php if(!empty($inventories)){
    foreach($inventories as $inv_item){
        $a_id = str_replace(",", "_", $inv_item->attributes);
        echo "<script>attrIdsFromDB.push('" . $inv_item->id . "'); </script>";
        echo "<script>attrFromDB.push('" . $a_id . "'); </script>";
        echo "<script>attrQty['" . $a_id . "'] = '" . $inv_item->quantity  . "'</script>";
    }
}?>

 <script>

     var uploadedFolder = '<?php echo UPLOADED_FOLDER; ?>';
     uploadedFolder = uploadedFolder + '/';

     var uploadAjaxCall,
         refreshDisable = false,
         submitDisable = false;

     var uploadStatus = $('#upload-status'),
         uploadProgress = $('#upload-progress'),
         multipleImages = $('#multiple-images'),
         sliderInputId = 0;

     function checkFileType(fileType, acceptedFiles){

         var validFile = false;
         $(acceptedFiles).each(function(key, value){
             if(fileType == value) {
                 validFile = true;
                 return;
             }
         });
         return validFile;
     }

     function checkUpload(input, file){
         var $this = $(input),
             file_data = file,
             noError = true,
             acceptedFiles = ['image/png', 'image/jpeg', 'image/jpg'];

         if(file_data != null){
             if(checkFileType(file_data.type, acceptedFiles)){
                 if(file_data.size / (1024 * 1024) > maxUploadedFile){
                     noError  = false;
                     alert("Too Large file (Max file size : " + maxUploadedFile + "MB)");
                 }
             }else {
                 alert(file_data.name + " is Invalid File Type(Accepted File : png, jpg, jpeg)");
                 noError = false;
             }
         }else noError = false;
         return noError;
     }


     function showUploadedImage(file){
         var _URL = window.URL || window.webkitURL,
             img = new Image(),
             imgElement = $('<img />'),
             imageWrapper = $("<div></div>",{ class: "img-wrapper" });

         img.src = _URL.createObjectURL(file);
         img.onload = function() {

             $(imgElement).attr('src', img.src).appendTo(imageWrapper);
             imageWrapper.appendTo('#multiple-images');
         };
         return imageWrapper;
     }


     function upload(input, form_data ){
         var $this = $(input),
             fileCount = $this.get(0).files.length,
             url = $this.data('url'),
             removeUrl = $this.data('remove-url');

         $(uploadProgress).css('width', 0);

         if(maxUploadedFileCount < fileCount) alert('You can upload maximum ' + maxUploadedFileCount + ' files per upload.');
         else{
             for (var i = 0; i < fileCount; ++i) {

                 var _URL = window.URL || window.webkitURL,
                     file = $this[0].files[i],
                     form_data = new FormData();

                 form_data.append('image_name', file);

                 if(file){
                     if(checkUpload(input, file)){
                         var fileType = file["type"],
                             fileName = file["name"],
                             fileSize = file["size"] / (1024 *1024);

                         uploadAjaxCall = $.ajax({
                             url: url,
                             dataType: 'json',
                             cache: false,
                             contentType: false,
                             processData: false,
                             data: form_data,
                             type: 'POST',

                             success: function (res) {
                                 var uploadedObj = JSON.parse(JSON.stringify(res));

                                 if(uploadedObj.status_code == 200){

                                     var imgElement = $('<img />'),
                                         imageWrapper = $("<div></div>",{ class: "img-wrapper" }),
                                         removeImage = $("<a></a>",{ class: "remove-image", href: removeUrl, 'data-file-name': uploadedObj.data.file_name}),
                                         removeIcon = $('<i></i>', {class: 'ion-close-round'});

                                     $(imgElement).attr('src', uploadedFolder + uploadedObj.data.file_name).appendTo(imageWrapper);

                                     $(removeIcon).appendTo(removeImage);
                                     $(removeImage).appendTo(imageWrapper);
                                     $(imageWrapper).appendTo(multipleImages);

                                     var imageNames = $('[name="uploaded-image-names"]').val();
                                     imageNames += uploadedObj.data.file_name + ',';
                                     $('[name="uploaded-image-names"]').val(imageNames)

                                     $(uploadStatus).text('Done');

                                     var tagNameInput = $("<input>", { name: uploadedObj.data.file_name, placeholder:'Tag', class: 'mt-15',
                                        'data-required': 'true' });
                                     $(imageWrapper).append(tagNameInput);

                                     sliderInputId ++;
                                 }else $(uploadStatus).text(uploadedVideoObj.message);

                             },

                             xhr: function(){
                                 var xhr = $.ajaxSettings.xhr();
                                 if (xhr.upload) {
                                     xhr.upload.addEventListener('progress', function(event) {
                                         var percent = 0;
                                         var position = event.loaded || event.position;
                                         var total = event.total;
                                         if (event.lengthComputable) {

                                             percent = Math.ceil(position / total * 100);
                                             $(uploadProgress).css('width', percent + '%');
                                             $(uploadStatus).text('Uploading... ' + percent + ' %');
                                         }
                                     }, true);
                                 }
                                 return xhr;
                             },

                             mimeType:"multipart/form-data"
                         });
                     }
                 }
             }
         }
     }

     function removeFile(removeBtn){
         var fileName = $(removeBtn).attr('data-file-name'),
             url = $(removeBtn).attr('href');

         var values = {
             'image_name': fileName,
             'admin_id': '<?php echo $admin->id; ?>'
         };

         var a = $.ajax({
             url: url,
             dataType: 'json',
             cache: false,
             data: values,
             type: 'POST',
             success: function(res) {
                 var uploadedObj = JSON.parse(JSON.stringify(res));

                 if(uploadedObj.status_code == 201) $(uploadStatus).text(uploadedObj.message);

                 $(removeBtn).closest('.img-wrapper').remove();
                 $(uploadStatus).text('Successfully Deleted');

                 var imageNames = $('[name="uploaded-image-names"]').val();
                 var updatedNames = '';
                 var nameArray = imageNames.split(',');
                 if(nameArray[nameArray.length-1].trim() == '') nameArray.splice(nameArray.length-1, 1);

                 $(nameArray).each(function(key, value){
                     if(fileName == value)  nameArray.splice(key, 1);
                     else updatedNames += value + ','

                 });

                 $('[name="uploaded-image-names"]').val(updatedNames);

                 $(uploadProgress).css('width', '0px');
             }
         });
     }


     function removeExistingFile(removeBtn){
         var fileName = $(removeBtn).attr('data-file-name'),
             url = $(removeBtn).attr('href');

         var values = {
             'image_name': fileName,
             'admin_id': '<?php echo $admin->id; ?>'
         };

         var a = $.ajax({
             url: url,
             dataType: 'json',
             cache: false,
             data: values,
             type: 'POST',
             success: function(res) {
                 var uploadedObj = JSON.parse(JSON.stringify(res));

                 if(uploadedObj.status_code == 201) $(uploadStatus).text(uploadedObj.message);

                 $(removeBtn).closest('.img-wrapper').remove();
                 $(uploadStatus).text('Successfully Deleted');

                 var imageNames = $('[name="removed-image-ids"]').val();
                 imageNames += $(removeBtn).data('image-id') + ',';

                 $('[name="removed-image-ids"]').val(imageNames);

                 $(uploadProgress).css('width', '0px');
             }
         });
     }



     /*MAIN SCRIPTS*/
     (function ($) {
         "use strict";

         $('[data-popup]').on('click', function(e){
             e.stopPropagation();
             e.preventDefault();

             var $this = $(this),
                 targetDiv = $this.data('popup');



             $(targetDiv).addClass('active');

         });


         $(document).on('click touch', function(e) {
             $('.popup-area').removeClass('active');
         });

         $('.popup-area .item').on('click touch', function(e) {
             e.stopPropagation();
         });



         window.onbeforeunload = function() {
             if(refreshDisable){
                 return "Are you sure you want to leave?";
             }
         }


         $("#file-upload").closest('form').on('submit', function(){
             if(submitDisable) {
                 alert("Upload is in Progress");
                 return false;
             }
         });

         $("#file-upload").change(function (){
             upload($(this));
         });


         $(document).on('click', '.remove-image', function(e) {
             e.preventDefault();
             e.stopPropagation();
             if(confirm("Are You Sure?")){
                 var $this = $(this);
                 removeFile($this)
             }
             return false;
         });

     })(jQuery);
 </script>

</body>