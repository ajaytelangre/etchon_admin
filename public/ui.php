<?php require_once('../private/init.php'); ?>

<?php
$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

if(!empty($admin)){

    $setting = new Setting();
    $setting = $setting->where(["admin_id"=>$admin->id])->one();

}else Helper::redirect_to("login.php");

?>


<?php require("common/php/php-head.php"); ?>

    <body>

<?php require("common/php/header.php"); ?>

    <div class="main-container">

        <?php require("common/php/sidebar.php"); ?>

        <div class="main-content">
            <?php if($message) echo $message->format(); ?>

            <div class="item-wrapper three">
                <div class="item">
                    <div class="item-inner">

                        <div class="item-header">
                            <h5 class="dplay-inl-b">Home Screen</h5>
                        </div><!--item-header-->

                        <div class="item-content p-0">

                            <img id="slider-image" src="" alt=""/>
                            <div class="banner-area"></div>

                        </div><!--item-content-->

                    </div><!--item-inner-->

                    <div class="item-footer ui-footer">
                        <a href="<?php echo 'main-ui-form.php'; ?>"><i class="ion-compose"></i>Edit</a>
                    </div><!--item-footer-->

                </div><!--item-->
            </div><!--item-wrapper-->

        </div><!--main-content-->
    </div><!--main-container-->



<?php echo "<script>api_token = '" . $setting->api_token  . "'</script>"; ?>
<?php echo "<script>uploadFolder = '" . UPLOADED_FOLDER . '/' . "'</script>"; ?>


<?php require("common/php/php-footer.php"); ?>

    <script>
        $.ajax({
            url: 'api/product/home.php',
            dataType: 'json',
            cache: false,
            data: {api_token: api_token},
            type: 'POST',
            success: function(res) {
                var uploadedObj = JSON.parse(JSON.stringify(res));

                if(uploadedObj.status_code == 200) {

                    var bannerArea = $('.banner-area');

                    console.log(uploadedObj.data['home_banners']);

                    $('#slider-image').attr('src', uploadFolder + uploadedObj.data['slider_images'][0].image_name);

                    $(uploadedObj.data['home_banners']).each(function(e){

                        var bannerWrapper = $('<div>', { class: 'banner-wrapper' });
                        var bannerCatWrapper = $('<div>', { class: 'banner-cat-wrapper' });

                        $("<img>", { src: uploadFolder + this.image_name}).appendTo(bannerWrapper);

                        if(this.category_1.images.length == 1){

                            var category_1 = $("<div>", { class: 'cat-1 single'});

                            if(this.category_2.images.length == 4) var category_2 = $("<div>", { class: 'cat-2 quad'});
                            else var category_2 = $("<div>", { class: 'cat-2 single'});


                            $('<h5>', { text: this.category_1.title, class: 'mb-15' }).appendTo(category_1);
                            $('<img>', { src: uploadFolder + this.category_1.images[0].image_name }).appendTo(category_1);

                            $('<h5>', { text: this.category_2.title, class: 'mb-15' }).appendTo(category_2);

                            $(this.category_2.images).each(function (e) {
                                $('<img>', { src: uploadFolder + this.image_name }).appendTo(category_2);
                            });

                        }else if(this.category_2.images.length == 1){

                            var category_2 = $("<div>", { class: 'cat-2 single'});

                            if(this.category_1.images.length == 4) var category_1 = $("<div>", { class: 'cat-1 quad'});
                            else var category_1 = $("<div>", { class: 'cat-1 single'});

                            $('<h5>', { text: this.category_2.title, class: 'mb-15' }).appendTo(category_2);
                            $('<img>', { src: uploadFolder + this.category_2.images[0].image_name }).appendTo(category_2);

                            $('<h5>', { text: this.category_1.title, class: 'mb-15' }).appendTo(category_1);

                            $(this.category_1.images).each(function (e) {
                                $('<img>', { src: uploadFolder + this.image_name }).appendTo(category_1);
                            });

                        }

                        $(category_1).appendTo(bannerCatWrapper);
                        $(category_2).appendTo(bannerCatWrapper);

                        $(bannerCatWrapper).appendTo(bannerWrapper);
                        $(bannerWrapper).appendTo(bannerArea);

                    });


                }
            }
        });

    </script>
