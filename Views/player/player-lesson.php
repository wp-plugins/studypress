<?php



$type = "lesson";

require_once __ROOT_PLUGIN__ .'Views/player/includeCSSPlayer.php';


global $tr;

?>


<div id="fullscreen-sp-player" class="sp-player">
    <span class="sp-powered">Powered by StudyPress</span>
	<div class="sp-player-left">
		<div class="sp-player-profil">
            <a href="" class="sp-img-profil"></a>
            <a href="" class="sp-name-profil"></a>
            <div class="hr"></div>
            <h4 class="sp-title-lesson"></h4>
        </div>
         <div class="sp-player-tabs">
            <div class="tabs tabs-style-topline">
            <nav>
                <ul>
                    <li><a href="#section-topline-1" class="icon"><span>Menu</span></a></li>
                    <li><a href="#section-topline-2" class="icon"><span>Notes</span></a></li>
                    <li><a href="#section-topline-3" class="icon"><span><?php $tr->_e("Glossaries"); ?></span></a></li>

                </ul>
            </nav>
            <div class="content-wrap">
                <section id="section-topline-1">
                    <ul>


                    </ul>
                </section>
                <section id="section-topline-2">
                    <ul>
                    <?php
                    foreach ($lesson->getNote() as $note) {
                        echo "<li>".$note ."</li>";
                    }


                    ?>
                    </ul>
                </section>
                <section id="section-topline-3">
                    <ul>
                    <?php
                    $g = $lesson->getGlossary();
                    for ($i=0;$i<count($g->name);$i++) {
                        echo "<li><b>".$g->name[$i] ."</b> : ".$g->desc[$i]."</li>";
                    }


                    ?>
                    </ul>
                </section>

            </div>
        </div>
        </div>

	</div>


	<div class="sp-player-right">
		<div class="sp-player-right-top">
			<div id="carousel" class="slide">


      		</div>
		</div>
		<div class="sp-player-right-bottom">
            <div class="buttons-control">
				<button class="btn-next">Next</button>
				<button class="btn-prev">Prev</button>
            </div>
            <button class="full-screen" title="Plein écran">fullScreen</button>
		</div>
	</div>

    <div class="sp-rater">
        <div class="sp-btn-rater"></div>
            <div class="sp-content-rater hide">
                <div class="sp-rater-quality">
                    <h2><?php echo  $tr->__("Quality") ?></h2>
                    <?php
                    $managerRate = new RateQualityManager();
                    $user =  new StudyPressUserWP();

                    ?>
                    <div class="sp-rate-quality" data-id="<?php echo  $id ?>"  data-user="<?php echo  $user->id() ?>" ></div>
                    <?php
                    echo $tr->__("Number of raters") . ": " . $managerRate->countRate($id) ."<br/>";
                    echo $tr->__("Average") . ": " . round((float) $managerRate->AVG($id),2) ."<br/>";
                    ?>
                    <div class="serverResponse"><p>&nbsp;</p></div>
                </div>
                <div class="sp-rater-domain">
                <?php
                $managerDomain = new DomainManager();
                $managerRateDomain = new RateDomainManager();
                $domains = $managerDomain->getAll();

                if($domains) echo "<h2>".$tr->__("Utility") ."</h2>";

                foreach ($domains as $domain) :

                ?>

                <div class="div-rate-domain">

                    <?php echo  $domain->getName() ?>
                    <div class="sp-rate-domain" data-average="<?php echo  ($user->isLoggedIn())?(($managerRateDomain->voteExist($id,$user->id(),$domain->getId()))?$managerRateDomain->voteExist($id,$user->id(),$domain->getId())->getValue():"0"):"0" ?>" data-id="<?php echo  $id ?>" data-domain="<?php echo  $domain->getId() ?>" data-user="<?php echo  $user->id() ?>"></div>
                    <?php
                    echo $tr->__("Number of raters") . ": " . $managerRateDomain->countRate($id,$domain->getId()) ."<br/>";
                    echo $tr->__("Average") . ": " . round((float) $managerRateDomain->AVG($id,$domain->getId()),2) ."<br/>";
                    ?>
                    <div class="serverResponse"><p>&nbsp;</p></div>

                </div>
                <?php
                endforeach;
                ?>

                </div>

            </div>
    </div>

</div>





<script src="<?php echo  __ROOT_PLUGIN__2 . "js/jquery.js" ?>"></script>
<script src="<?php echo  __ROOT_PLUGIN__2 . "js/owl.carousel.min.js" ?>"></script>
<script src="<?php echo  __ROOT_PLUGIN__2 . "js/jquery.rateyo.js" ?>"></script>
<script src="<?php echo  __ROOT_PLUGIN__2 . "js/rating-function.js" ?>"></script>

<script src="<?php echo  __ROOT_PLUGIN__2 . "js/cbpFWTabs.js" ?>"></script>


<script>
    (function($) {
        $(document).ready(function () {


            var pathQuality = "<?php echo  __ROOT_PLUGIN__2 ?>controllers/ratingQuality.controller.php";

            var rateQuality = $(".sp-rate-quality").rateYo({
                starWidth: "40px",
                rating: "<?php echo  ($user->isLoggedIn())?(($managerRate->voteExist($id,$user->id()))?$managerRate->voteExist($id,$user->id())->getValue():"0"):"0" ?>",
                fullStar: true,
                onChange: function (rating, rateYoInstance) {

                }

            }).on("rateyo.set", function (e, data) {

                requestRate($(this), data.rating, pathQuality);
            });


            var pathDomain = "<?php echo  __ROOT_PLUGIN__2 ?>controllers/ratingDomain.controller.php";

            $(".sp-rate-domain").each(function () {
                var item = $(this);
                item.rateYo({
                    starWidth: "30px",
                    rating: $(this).data("average"),
                    fullStar: true
                }).on("rateyo.set", function (e, data) {
                    console.log($.data(this, "average"));
                    requestRate($(this), data.rating, pathDomain);
                });
            });

        });
    })(jQuery);
</script>
    <script>

        function supportFullScreen(){
            var doc = document.documentElement;
            return ('requestFullscreen' in doc) || ('mozRequestFullScreen' in doc && document.mozFullScreenEnabled) || ('webkitRequestFullScreen' in doc);
        }

        function toggleFullScreen(elem) {
          if (!document.fullscreenElement &&
              !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement ) {
            if (elem.requestFullscreen) {
              elem.requestFullscreen();
            } else if (elem.msRequestFullscreen) {
              elem.msRequestFullscreen();
            } else if (elem.mozRequestFullScreen) {
              elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
              elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            }
          } else {
            if (document.exitFullscreen) {
              document.exitFullscreen();
            } else if (document.msExitFullscreen) {
              document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
              document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
              document.webkitExitFullscreen();
            }
          }
}


   </script>
    <script>


        (function($) {
            $(document).ready(function () {


                var sp_owl = $(".sp-player #carousel");

                var optionsOwl = {
                    jsonPath: "<?php echo  __ROOT_PLUGIN__2 . "Public/Lesson/" . sha1($lesson->getId()).".json" ?>",
                    jsonSuccess: setSlides,
                    singleItem: true,
                    lazyLoad: true,
                    pagination: false,
                    addClassActive: true,
                    rewindNav: false,
                    afterMove: afterMoving

                };

                sp_owl.owlCarousel(optionsOwl);


                function setSlides(data) {
                    var htmlContent = "";
                    var htmlName = "";

                    $(".sp-title-lesson").html(data['title']);

                    $(".sp-img-profil").attr("href", data['authorLink']).html(data['authorImg']);


                    $(".sp-name-profil").attr("href", data['authorLink']).html(data['authorName']);


                    for (i = 0; i < data['items'].length; i++) {

                        var nameSlide = data["items"][i]['name'];
                        var contentSlide = data["items"][i]['content'];

                        var selected = (i == 0) ? " selected " : "";
                        if (nameSlide !== "") {
                            htmlName += "<li><a href='#' class='slide-name " + selected + "'>" + nameSlide + "</a></li>";
                        }

                        htmlContent += "<div>" + contentSlide + "</div>";


                    }

                    $('#section-topline-1 ul').html(htmlName);
                    $('#carousel').html(htmlContent);
                }



                function afterMoving() {
                    $('.sp-player .selected').removeClass('selected');
                   var owl = sp_owl.data('owlCarousel');
                    $(".sp-player #section-topline-1 ul li:eq(" + owl.currentItem + ")").find("a").addClass('selected');
                    if (owl.currentItem + 1 === owl.itemsAmount)
                        showRater();
                    else
                        hideRater();
                }


                $(".sp-player #section-topline-1").on("click", ".slide-name", function (e) {
                    e.preventDefault();
                    var pos = $(this).parent().index();
                    sp_owl.trigger("owl.goTo", pos);
                });

                $("body").keydown(function (e) {

                    if ((e.keyCode || e.which) == 37) {
                        sp_owl.trigger("owl.prev");

                    }

                    if ((e.keyCode || e.which) == 39) {
                        sp_owl.trigger("owl.next");

                    }
                });


                $(".btn-next").on("click", function () {
                    sp_owl.trigger("owl.next");
                });

                $(".btn-prev").on("click", function () {
                    sp_owl.trigger("owl.prev");
                });

                $(".sp-player .full-screen").on("click", function () {
                    if (supportFullScreen()) {
                        toggleFullScreen(document.getElementById("fullscreen-sp-player"));
                    }
                    else
                        alert("Votre navigateur ne supporte pas le Fullscreen !! veuillez le metre à jour");

                });


                $(".sp-btn-rater").click(toggleRater);


                function toggleRater() {
                    $(".sp-rater").animate({
                        height: ($(".sp-rater").height() == "0") ? "100%" : "0"
                    }, 500, function () {
                        $(".sp-btn-rater").attr('id', ($(".sp-btn-rater").attr('id') === 'sp-down' ? '' : 'sp-down'));
                        $(".sp-content-rater").toggleClass("hide");
                    });
                }

                function showRater() {
                    $(".sp-rater").animate({
                        height: "100%"
                    }, 500, function () {
                        $(".sp-btn-rater").attr('id', 'sp-down');
                        $(".sp-content-rater").removeClass("hide");
                    });
                }


                function hideRater() {
                    if ($(".sp-btn-rater").attr('id') !== '') {
                        $(".sp-rater").animate({
                            height: "0"
                        }, 500, function () {
                            $(".sp-btn-rater").attr('id', '');
                            $(".sp-content-rater").addClass("hide");
                        });
                    }
                }

                [].slice.call( document.querySelectorAll( '.sp-player .tabs' ) ).forEach( function( el ) {
                    new CBPFWTabs( el );
                });



            });
})(jQuery);


    </script>




