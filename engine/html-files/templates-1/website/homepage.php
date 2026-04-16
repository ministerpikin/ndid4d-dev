	<?php $display_pagepointer .= "engine/";?>
	<!-- BEGIN PAGE CONTAINER -->  
    <div class="page-container" >
         <!-- BEGIN REVOLUTION SLIDER -->    
        <div class="fullwidthbanner-container slider-main" >
            <div class="fullwidthabnner">
                <ul id="revolutionul">

                        <!-- THE SECOND SLIDE -->
                        <li data-transition="fade" data-slotamount="7" data-masterspeed="300" data-delay="9400" data-thumb="<?php echo $display_pagepointer; ?>frontend-assets/img/sliders/revolution/thumbs/thumb2.jpg">                        
                            <img src="<?php echo $display_pagepointer; ?>frontend-assets/img/sliders/revolution/bg9 - Copy.jpg" alt="">
                            
							<div class="caption lfl slide_title slide_item_left"
                                 data-x="0"
                                 data-y="72"
                                 data-speed="400"
                                 data-start="3500"
                                 data-easing="easeOutExpo">
                                 <span style="font-weight:bold; color:#fff;">Royal Events<br />Centre</span>
                            </div>   
                            <div class="caption lfl slide_subtitle slide_item_left"
                                 data-x="0"
                                 data-y="210"
                                 data-speed="400"
                                 data-start="4000"
                                 data-easing="easeOutExpo">
                                 <span style="text-transform:none;">
								 We are a special organization created to give<br />succor and hope to our clients, we go beyond<br />catering services to events management.  
								 </span>
                            </div>                         
                            <div class="caption lfr slide_item_right" 
                                 data-x="675" 
                                 data-y="10" 
                                 data-speed="1200" 
                                 data-start="1500" 
                                 data-easing="easeOutBack">
                                <div style="box-shadow:1px 1px 10px 1px #B18B36; background: #f9f9f9; margin-top:5px;">
									<div style="background:#4a5866; color:#fff; padding:10px;">
										<span style="font-size:1.5em;"><i style="font-size:21px;" class="icon-calendar blue"></i> Book Now</span>
										<p style="margin-top: 4px;font-size: 0.9em;font-style: italic;color: #eee;margin-bottom: 0;">Select a date for your event by clicking on the calendar below</p>
									</div>
									<div style="padding: 15px 10px 5px 10px;">
										<div id="dash-board-main-content-area">
											<?php if( isset( $data['visit_schedule_form'] ) )echo $data['visit_schedule_form']; ?>
										</div>
									</div>
								</div>
                            </div>
                        </li>
                    
				</ul>
                <div class="tp-bannertimer tp-bottom"></div>
            </div>
        </div>
        <!-- END REVOLUTION SLIDER -->
		
        <!-- BEGIN CONTAINER -->   
        <div class="container" >
            <!-- BEGIN SERVICE BOX -->   
            <div class="row service-box" >
				<div class="col-md-6 col-sm-6 front-carousel">
					<div id="myCarousel" class="carousel slide">
						<!-- Carousel items -->
						<div class="carousel-inner">
							<div class="active item">
								<img src="<?php echo $display_pagepointer; ?>frontend-assets/img/sliders/hall-0.jpg" />
								<div class="carousel-caption">
									<p>Excepturi sint occaecati cupiditate non provident</p>
								</div>
							</div>
							<div class="item">
								<img src="<?php echo $display_pagepointer; ?>frontend-assets/img/sliders/hall-1.jpg" alt="">
								<div class="carousel-caption">
									<p>Ducimus qui blanditiis praesentium voluptatum</p>
								</div>
							</div>
							<div class="item">
								<img src="<?php echo $display_pagepointer; ?>frontend-assets/img/sliders/hall-2.jpg" alt="">
								<div class="carousel-caption">
									<p>Ut non libero consectetur adipiscing elit magna</p>
								</div>
							</div>
						</div>
						<!-- Carousel nav -->
						<a class="carousel-control left" href="#myCarousel" data-slide="prev">
							<i class="icon-angle-left"></i>
						</a>
						<a class="carousel-control right" href="#myCarousel" data-slide="next">
							<i class="icon-angle-right"></i>
						</a>
					</div>                
				</div>
                <div class="col-md-6 col-sm-6 well">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="service-box-heading">
								<em><i class="icon-resize-small green"></i></em>
								<span>Welcome</span>
							</div>
							<p>We are a special organization created to give succor and hope to our client. Though registered with Corporate Affairs Commission as TRUDON FOODS LIMITED, we go beyond catering services to events management.</p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="service-box-heading">
								<em><i class="icon-location-arrow blue"></i></em>
								<span>Vision</span>
							</div>
							<p>We exist as one group recognized by all as a one-stop-solution on all matters relating to events management.</p>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="service-box-heading">
								<em><i class="icon-ok red"></i></em>
								<span>Mission</span>
							</div>
							<p>We help our clients to plan and execute their events efficiently, profitably and without stress by putting in our professional touch.</p>
						</div>
					</div>
                </div>
            </div>
            <!-- END SERVICE BOX -->  
		
		            <!-- BEGIN BLOCKQUOTE BLOCK -->   
            <div class="row quote-v1">
                <div class="col-md-9 quote-v1-inner">
                    <span>Rental of Events Hall & Other Items like Public Address System, Chairs / Tables</span>
                </div>
                <div class="col-md-3 quote-v1-inner text-right">
                    <a class="btn-transparent" href="?page=book-event" ><i class="icon-rocket margin-right-10"></i>BOOK NOW</a>
                </div>
            </div>
            <!-- END BLOCKQUOTE BLOCK -->
        <?php if( 1 == 2 ){ ?>
			<!-- BEGIN SERVICE BOX -->   
            <div class="row service-box">
                <div class="col-md-4 col-sm-4">
                    <div class="service-box-heading">
                        <em><i class="icon-location-arrow blue"></i></em>
                        <span>Mission</span>
                    </div>
                    <p>Lorem ipsum dolor sit amet, dolore eiusmod quis tempor incididunt ut et dolore Ut veniam unde nostrudlaboris. Sed unde omnis iste natus error sit voluptatem.</p>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="service-box-heading">
                        <em><i class="icon-ok red"></i></em>
                        <span>Vision</span>
                    </div>
                    <p>Lorem ipsum dolor sit amet, dolore eiusmod quis tempor incididunt ut et dolore Ut veniam unde nostrudlaboris. Sed unde omnis iste natus error sit voluptatem.</p>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="service-box-heading">
                        <em><i class="icon-resize-small green"></i></em>
                        <span>Mandate</span>
                    </div>
                    <p>Lorem ipsum dolor sit amet, dolore eiusmod quis tempor incididunt ut et dolore Ut veniam unde nostrudlaboris. Sed unde omnis iste natus error sit voluptatem.</p>
                </div>
            </div>
            <!-- END SERVICE BOX -->  



            <div class="clearfix"></div>

            <!-- BEGIN TABS AND TESTIMONIALS -->
            <div class="row mix-block">
                <!-- TABS -->
                <div class="col-md-7 tab-style-1 margin-bottom-20">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-1" data-toggle="tab">Multipurpose</a></li>
                        <li><a href="#tab-2" data-toggle="tab">Documented</a></li>
                        <li><a href="#tab-3" data-toggle="tab">Responsive</a></li>
                        <li><a href="#tab-4" data-toggle="tab">Clean & Fresh</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane row fade in active" id="tab-1">
                            <div class="col-md-3">
                                <a href="<?php echo $display_pagepointer; ?>frontend-assets/img/photos/img7.jpg" class="fancybox-button" title="Image Title" data-rel="fancybox-button">
                                    <img class="img-responsive" src="<?php echo $display_pagepointer; ?>frontend-assets/img/photos/img7.jpg" alt="" />
                                </a>
                            </div>
                            <div class="col-md-9">
                                <p class="margin-bottom-10">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Cosby sweater eu banh mi, qui irure terry richardson ex squid Aliquip placeat salvia cillum iphone.</p>
                                <p><a class="more" href="#">Read more <i class="icon-angle-right"></i></a></p>
                            </div>
                        </div>
                        <div class="tab-pane row fade" id="tab-2">
                            <div class="col-md-9">
                                <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia..</p>
                            </div>
                            <div class="col-md-3">
                                <a href="<?php echo $display_pagepointer; ?>frontend-assets/img/photos/img10.jpg" class="fancybox-button" title="Image Title" data-rel="fancybox-button">
                                    <img class="img-responsive" src="<?php echo $display_pagepointer; ?>frontend-assets/img/photos/img10.jpg" alt="" />
                                </a>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-3">
                            <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>
                        </div>
                        <div class="tab-pane fade" id="tab-4">
                            <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.</p>
                        </div>
                    </div>
                </div>
                <!-- END TABS -->
        
                <!-- TESTIMONIALS -->
                <div class="col-md-5 testimonials-v1">
                    <div id="myCarousel" class="carousel slide">
                        <!-- Carousel items -->
                        <div class="carousel-inner">
                            <div class="active item">
                                <span class="testimonials-slide">Denim you probably haven't heard of. Lorem ipsum dolor met consectetur adipisicing sit amet, consectetur adipisicing elit, of them jean shorts sed magna aliqua. Lorem ipsum dolor met consectetur adipisicing sit amet do eiusmod dolore.</span>
                                <div class="carousel-info">
                                    <img class="pull-left" src="<?php echo $display_pagepointer; ?>frontend-assets/img/people/img1-small.jpg" alt="" />
                                    <div class="pull-left">
                                        <span class="testimonials-name">Lina Mars</span>
                                        <span class="testimonials-post">Commercial Director</span>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <span class="testimonials-slide">Raw denim you Mustache cliche tempor, williamsburg carles vegan helvetica probably haven't heard of them jean shorts austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica.</span>
                                <div class="carousel-info">
                                    <img class="pull-left" src="<?php echo $display_pagepointer; ?>frontend-assets/img/people/img5-small.jpg" alt="" />
                                    <div class="pull-left">
                                        <span class="testimonials-name">Kate Ford</span>
                                        <span class="testimonials-post">Commercial Director</span>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <span class="testimonials-slide">Reprehenderit butcher stache cliche tempor, williamsburg carles vegan helvetica.retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid Aliquip placeat salvia cillum iphone.</span>
                                <div class="carousel-info">
                                    <img class="pull-left" src="<?php echo $display_pagepointer; ?>frontend-assets/img/people/img2-small.jpg" alt="" />
                                    <div class="pull-left">
                                        <span class="testimonials-name">Jake Witson</span>
                                        <span class="testimonials-post">Commercial Director</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carousel nav -->
                        <a class="left-btn" href="#myCarousel" data-slide="prev"></a>
                        <a class="right-btn" href="#myCarousel" data-slide="next"></a>
                    </div>
                </div>
                <!-- END TESTIMONIALS -->
            </div>                
            <!-- END TABS AND TESTIMONIALS -->
			<?php } ?>
			
            <!-- BEGIN STEPS -->
            <div class="row no-space-steps margin-bottom-40">
                <div class="col-md-4 col-sm-4">
                    <div class="front-steps front-step-one">
                        <h2>Special Events</h2>
                        <p>We plan and organize your wedding from invitation to execution & reception</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="front-steps front-step-two">
                        <h2>Trainings</h2>
                        <p>We provide accommodation, classrooms and training manuals if required</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="front-steps front-step-three">
                        <h2>Meetings</h2>
                        <p>Annual Company Meetings, Town / Village Meetings. We also camp groups for social / religious activities</p>
                    </div>
                </div>
            </div>
            <!-- END STEPS -->

    </div>
    <!-- END PAGE CONTAINER -->
