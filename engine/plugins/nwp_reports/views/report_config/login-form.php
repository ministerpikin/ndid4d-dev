<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php 
		require_once 'params.php';
	?>
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100" style="<?php $paramkey = 'BACKGROUND_STYLE'; echo isset( $params["constants"][ $paramkey ] )?( 'background: ' . $params["constants"][ $paramkey ] . ' !important;' ):''; ?>">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
						<?php 
							$card1 = '';
							$card2 = 'card';
							$show_info = 0;
						?>
                        <div class="<?php echo $card1; ?> overflow-hidden">
                            <div class="row g-0">
								<div class="col-lg-3"></div>
                                <div class="col-lg-6 <?php echo $card2; ?>">
									<div class="p-lg-5 p-4">
										<div style="<?php $paramkey = 'SIGNIN_TITLE_STYLE'; echo isset( $params["constants"][ $paramkey ] )?$params["constants"][ $paramkey ]:''; ?>">
											<?php $paramkey = 'SIGNIN_LOGO'; if( isset( $params["constants"][ $paramkey ] ) && $params["constants"][ $paramkey ] ){ ?>
												<img src="<?php echo $params["constants"][ $paramkey ]; ?>" alt="<?php $paramkey = 'LOGO_ALT'; echo isset( $params["constants"][ $paramkey ] )?$params["constants"][ $paramkey ]:''; ?>" <?php $paramkey = 'LOGO_WIDTH'; echo isset( $params["constants"][ $paramkey ] )?( 'width="' . $params["constants"][ $paramkey ] . '"' ):''; ?>>
											<?php } ?>
											<h4 class="text-primary"><?php $paramkey = 'SIGNIN_TITLE'; echo isset( $params["constants"][ $paramkey ] )?$params["constants"][ $paramkey ]:''; ?></h4>
											<p class="text-muted"><?php $paramkey = 'SIGNIN_SUBTITLE'; echo isset( $params["constants"][ $paramkey ] )?$params["constants"][ $paramkey ]:''; ?></p>
										</div>

										<div class="mt-4" id="sign-in-form-container">
											<?php
										    	$loginFormStyle = '';
											?>									    
											<form class="login-form activate-ajax login100-form validate-form" id="usersLoginForm" action="?action=nwp_reports&todo=execute&nwp_action=report_config&nwp_todo=save_login_report&default=default&html_replacement_selector=<?php echo (isset($data['html_replacement']) && $data['html_replacement'] ? $data['html_replacement'] : ''); ?>" method="post" style="<?php echo $loginFormStyle; ?>">

												<textarea style="display: none;" class="form-control" name="formParams" ><?php echo json_encode( $data ); ?></textarea>

												<div class="mb-3">
													<label for="username" class="form-label"><?php $paramkey = 'SIGNIN_USERNAME'; echo isset( $params["constants"][ $paramkey ] )?$params["constants"][ $paramkey ]:'Username'; ?> <sup class="r">*</sup></label>
													<input type="<?php $paramkey = 'SIGNIN_USERNAME_TYPE'; echo isset( $params["constants"][ $paramkey ] )?$params["constants"][ $paramkey ]:'text'; ?>" name="email" class="form-control" required id="username" placeholder="Enter <?php $paramkey = 'SIGNIN_USERNAME'; echo isset( $params["constants"][ $paramkey ] )?$params["constants"][ $paramkey ]:'username'; ?>">
												</div>

												<div class="mb-3">													
													<label class="form-label" for="password-input">Password <sup class="r">*</sup></label>
													<div class="position-relative auth-pass-inputgroup mb-3">
														<input type="password" class="form-control pe-5 password-input" name="password" required placeholder="Enter password" id="password-input">
														<button class="btn btn-link position-absolute end-0 top-0 text-decoration-none shadow-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
													</div>
												</div>
																								
												<div class="mt-4">
													<button class="btn btn-success w-100" type="submit">Sign In</button>
												</div>
											</form>
											
										</div>
										
										<?php 
											$paramkey = 'SIGNUP_POWERED_BY'; 
											if( isset( $params["constants"][ $paramkey ] ) && $params["constants"][ $paramkey ] ){
												$smsg = $params["constants"][ $paramkey ]; 
										?>
										<div class="mt-5 text-center">
											<?php 
												$paramkey = 'SIGNUP_POWERED_TITLE'; 
												if( isset( $params["constants"][ $paramkey ] ) && $params["constants"][ $paramkey ] ){
											?>
											<p class="mb-0"><?php echo $params["constants"][ $paramkey ]; ?></p>
											<?php 
												}
											?>
											
											<?php 
												if( ! empty( $smsg ) && is_array( $smsg ) ){
													foreach( $smsg as $sv ){
														if( ! isset( $sv["logo"] ) ){
															continue;
														}
														?>
														<img src="<?php echo $sv["logo"]; ?>" alt="<?php echo isset( $sv["title"] )?$sv["title"]:''; ?>" style="margin-right:10px; width:60px;">
														<?php
													}
												}
											?>
										</div>
										<?php 
											}
										?>
									</div>
								</div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

    </div>
</div>