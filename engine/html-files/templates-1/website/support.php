<style type="text/css">
    #support_enquiry-form .control-group{
        clear:both;
    }
    #form-panel-wrapper .control-group {
        width: 60%;
    }
</style>
<div class="container">
<div class="row-fluid">
	<div class="span6">
        <fieldset class="special-format" style="min-height:270px;">
            <legend>Support Ticket</legend>
            <?php
                $key = 'html';
                if( isset($data[ $key ]) && $data[ $key ] ){
                    echo $data[ $key ];
                }
            ?>
        </fieldset>
	</div>
    <!-- ./span6 -->
	<div class="span6">
        <fieldset class="special-format" style="min-height:270px;">
            <legend>Contact Us</legend>
            
            <?php
                $key = 'appsettings';
                if( isset($data[ $key ]) && $data[ $key ] ){
                    /*
                   $k = 'contact_address';
                   if( isset($data[ $key ][ $k ]) && $data[ $key ][ $k ] ){
                        echo '<h4>' . GLOBALS_ADDRESS_LABEL . '</h4>';
                        echo '<p>' . $data[ $key ][$k] . '</p>';
                   }
                   */
                   $k = 'contact_phone_number';
                   if( isset($data[ $key ][ $k ]) && $data[ $key ][ $k ] ){
                        echo '<h4><strong>' . GLOBALS_TELEPHONE_LABEL . '</strong></h4>';
                        echo '<p>' . $data[ $key ][$k] . '</p><br />';
                   }
                   
                   $k = 'contact_email_address';
                   if( isset($data[ $key ][ $k ]) && $data[ $key ][ $k ] ){
                        echo '<h4><strong>' . GLOBALS_EMAIL_LABEL . '</strong></h4>';
                        echo '<p>' . $data[ $key ][$k] . '</p><br />';
                   }
                   
                   $k = 'support_line';
                   if( isset($data[ $key ][ $k ]) && $data[ $key ][ $k ] ){
                        echo '<h4><strong>' . GLOBALS_SUPPORT_LINE_LABEL . '</strong></h4>';
                        echo '<p>' . $data[ $key ][$k] . '</p>';
                   }
                   
                }
            ?>
        </fieldset>
	</div>
    <!-- ./span6 -->
</div>
<!-- ./row -->

<div class="row-fluid">
	<div class="span6">
        <div style="text-align:center; width:100%; vertical-align:middle;">
            <img src="<?php echo $data['pagepointer']; ?>images/support/promo-image.png" />
        </div>
	</div>
	<!-- ./span6 -->
	<div class="span6">
        <fieldset class="special-format">
            <legend>Frequently Asked Questions</legend>
            
            <div class="row-fluid">
                <div class="span12">
                    <div class="row">
                        <div class="span2">
                            <div style="text-align:center; width:100%; vertical-align:middle; margin-top:10px;">
                                <img src="<?php echo $data['pagepointer']; ?>images/support/kb.png" />
                            </div>
                        </div>
                        <div class="span6">
                            <p class="muted1">
                            Find solutions to similar issues
                            <hr />
                            </p>
                            <p class="lead">
                            Expore Our Knowledgebase
                            </p>
                        </div>
                    </div>
                    <!-- ./row -->
                    
                    <div class="row" style="padding:0 10px;">
                        <div class="input-append">
                            <form method="get" action="">
                            <input type="text" placeholder="Search Knowledgebase" id="appendedInputButton" class="form-gen-element span4" style="border-radius:0; border-right:none;" name="s" value="<?php if( isset($_GET['s']) && $_GET['s'] )echo $_GET['s']; ?>" />
                            <select class="form-gen-element" style="border-radius:0;" name="categories">
                                <option value="all">Categories</option>
                                <option value="">-------------</option>
                                <?php
                                    
                                    if( isset( $data[ 'faq_categories' ] ) && is_array( $data[ 'faq_categories' ] ) ){
                                        foreach( $data[ 'faq_categories' ] as $k => $v ){
                                        ?><option value="<?php echo $k; ?>"><?php echo ucwords( $v ); ?></option><?php
                                        }
                                    }
                                ?>
                            </select>
                            <input type="submit" class="btn btn-primary" value="Go" style="padding-bottom:9px;" />
                            <input type="hidden" value="faq" name="page" />
                            </form>
                        </div>
                    </div>
                    <!-- ./row -->
                </div>
                <!-- ./span12 -->
                <div class="span12">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row">
                                <div class="span3">
                                    <div style="text-align:center; width:100%; vertical-align:middle; margin-top:10px;">
                                        <img src="<?php echo $data['pagepointer']; ?>images/support/icon-buying.png" />
                                    </div>
                                </div>
                                <!-- ./span3 -->
                                <div class="span6">
                                    <p><strong><a href="?page=faq&categories=1&s=.">Buying</a></strong><p>
                                    <p>
                                        Find solutions to similar issues
                                    </p>
                                </div>
                                <!-- ./span6 -->
                            </div>
                            <!-- ./row -->
                        </div>
                        <!-- ./span6 -->
                        <div class="span6">
                            
                            <div class="row">
                                <div class="span3">
                                    <div style="text-align:center; width:100%; vertical-align:middle; margin-top:10px;">
                                        <img src="<?php echo $data['pagepointer']; ?>images/support/icon-delivery.png" />
                                    </div>
                                </div>
                                <!-- ./span3 -->
                                <div class="span6">
                                    <p><strong><a href="?page=faq&categories=4&s=.">Delivery</a></strong><p>
                                    <p>
                                        Find solutions to similar issues
                                    </p>
                                </div>
                                <!-- ./span6 -->
                            </div>
                            <!-- ./row -->
                            
                        </div>
                        <!-- ./span6 -->
                    </div>
                    <!-- ./row -->
                    <hr />
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row">
                                <div class="span3">
                                    <div style="text-align:center; width:100%; vertical-align:middle; margin-top:10px;">
                                        <img src="<?php echo $data['pagepointer']; ?>images/support/icon-selling.png" />
                                    </div>
                                </div>
                                <!-- ./span3 -->
                                <div class="span6">
                                    <p><strong><a href="?page=faq&categories=2&s=.">Selling</a></strong><p>
                                    <p>
                                        Find solutions to similar issues
                                    </p>
                                </div>
                                <!-- ./span6 -->
                            </div>
                            <!-- ./row -->
                        </div>
                        <!-- ./span6 -->
                        <div class="span6">
                            
                            <div class="row">
                                <div class="span3">
                                    <div style="text-align:center; width:100%; vertical-align:middle; margin-top:10px;">
                                        <img src="<?php echo $data['pagepointer']; ?>images/support/icon-payment.png" />
                                    </div>
                                </div>
                                <!-- ./span3 -->
                                <div class="span6">
                                    <p><strong><a href="?page=faq&categories=3&s=.">Payment</a></strong><p>
                                    <p>
                                        Find solutions to similar issues
                                    </p>
                                </div>
                                <!-- ./span6 -->
                            </div>
                            <!-- ./row -->
                            
                        </div>
                        <!-- ./span6 -->
                    </div>
                    <!-- ./row -->
                </div>
                <!-- ./span9 -->
            </div>
            <!-- ./row -->
            
        </fieldset>
    </div>
    <!-- ./span6 -->
</div>
<!-- ./row -->

</div>
<!-- ./container -->