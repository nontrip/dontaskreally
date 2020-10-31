<div class="instashow-demo">
    <form class="instashow-demo-form">
        <input class="instashow-demo-form-access-token" type="hidden" name="accessToken" value="<?php echo $access_token; ?>">

        <div class="instashow-admin-accordion">
            <div class="instashow-admin-accordion-item active">
                <div class="instashow-admin-accordion-item-trigger"><?php _e('Source', INSTASHOW_LITE_TEXTDOMAIN); ?></div>

                <div class="instashow-admin-accordion-item-content">
                    <div class="instashow-demo-source instashow-demo-field-group-disabled instashow-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-source.jpg', INSTASHOW_LITE_FILE); ?>">

                        <a class="instashow-demo-field-available-in-pro" href="<?php echo INSTASHOW_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>

                    <div class="instashow-demo-field-group">
                        <div class="instashow-demo-cache instashow-demo-field">
                            <div class="instashow-demo-field-name-inline instashow-demo-field-name"><?php _e('Cache media time', INSTASHOW_LITE_TEXTDOMAIN); ?></div>

                            <span class="instashow-demo-range-container">
                                <input class="instashow-demo-range-input" type="text" name="cacheMediaTime">
                                <span class="instashow-demo-range" data-min="0" data-step="100" data-max="86400"></span>
                            </span>

                            <span class="instashow-demo-field-hint"><?php _e('ms', INSTASHOW_LITE_TEXTDOMAIN); ?></span>
                        </div>
                    </div>

                    <div class="instashow-demo-filter instashow-demo-field-group-disabled instashow-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-filter.jpg', INSTASHOW_LITE_FILE); ?>">

                        <a class="instashow-demo-field-available-in-pro" href="<?php echo INSTASHOW_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>

                    <div class="instashow-demo-limit instashow-demo-field-group-disabled instashow-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-limit.jpg', INSTASHOW_LITE_FILE); ?>">
                    </div>
                </div>
            </div>

            <div class="instashow-admin-accordion-item">
                <div class="instashow-admin-accordion-item-trigger"><?php _e('Sizes', INSTASHOW_LITE_TEXTDOMAIN); ?></div>

                <div class="instashow-admin-accordion-item-content">
                    <div class="instashow-demo-sizes instashow-demo-field-group-disabled instashow-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-sizes.jpg', INSTASHOW_LITE_FILE); ?>">

                        <a class="instashow-demo-field-available-in-pro" href="<?php echo INSTASHOW_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>

                    <div class="instashow-demo-field-group">
                        <div class="instashow-demo-field-group-name">
                            <?php _e('Grid', INSTASHOW_LITE_TEXTDOMAIN); ?>
                        </div>

                        <div class="instashow-demo-columns instashow-demo-field">
                            <label>
                                <span class="instashow-demo-field-name-inline instashow-demo-field-name"><?php _e('Columns', INSTASHOW_LITE_TEXTDOMAIN); ?></span>

                                <div class="instashow-demo-numeric" data-min="1">
                                    <div class="instashow-demo-numeric-decrease"></div>
                                    <input type="text" name="columns" autocomplete="off">
                                    <div class="instashow-demo-numeric-increase"></div>
                                </div>
                            </label>
                        </div>

                        <div class="instashow-demo-easing instashow-demo-field">
                            <div class="instashow-demo-field-name-inline instashow-demo-field-name"><?php _e('Rows', INSTASHOW_LITE_TEXTDOMAIN); ?></div>

                            <select class="instashow-demo-select" name="rows">
                                <option value="1" selected><?php _e('1', INSTASHOW_LITE_TEXTDOMAIN); ?></option>
                                <option value="2"><?php _e('2', INSTASHOW_LITE_TEXTDOMAIN); ?></option>
                            </select>
                        </div>

                        <div class="instashow-demo-gutter instashow-demo-field-disabled instashow-demo-field">
                            <img src="<?php echo plugins_url('assets/img/pro-option-disabled-gutter.jpg', INSTASHOW_LITE_FILE); ?>">
                        </div>
                    </div>

                    <div class="instashow-demo-responsive instashow-demo-field-group-disabled instashow-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-responsive.jpg', INSTASHOW_LITE_FILE); ?>">

                        <a class="instashow-demo-field-available-in-pro" href="<?php echo INSTASHOW_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>
                </div>
            </div>

            <div class="instashow-admin-accordion-item">
                <div class="instashow-admin-accordion-item-trigger"><?php _e('UI', INSTASHOW_LITE_TEXTDOMAIN); ?></div>

                <div class="instashow-demo-ui instashow-admin-accordion-item-content">
                    <div class="instashow-demo-field-group">
                        <div class="instashow-demo-controls instashow-demo-field-col-1-2">
                            <div class="instashow-demo-field">
                                <div class="instashow-demo-field-name">
                                    <?php _e('Controls', INSTASHOW_LITE_TEXTDOMAIN); ?>
                                </div>

                                <label class="instashow-demo-controls-item">
                                    <input type="hidden" name="arrowsControl" value="false">
                                    <input class="instashow-demo-checkbox" type="checkbox" name="arrowsControl" value="true">
                                    <span class="instashow-demo-icon-control-arrow-white instashow-demo-icon-active instashow-demo-icon"></span>
                                    <span class="instashow-demo-icon-control-arrow-blue instashow-demo-icon"></span>
                                    <span class="instashow-demo-controls-item-label"><?php _e('Arrows', INSTASHOW_LITE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="instashow-demo-controls-item">
                                    <input type="hidden" name="dragControl" value="false">
                                    <input class="instashow-demo-checkbox" type="checkbox" name="dragControl" value="true">
                                    <span class="instashow-demo-icon-control-drag-white instashow-demo-icon-active instashow-demo-icon"></span>
                                    <span class="instashow-demo-icon-control-drag-blue instashow-demo-icon"></span>
                                    <span class="instashow-demo-controls-item-label"><?php _e('Drag', INSTASHOW_LITE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="instashow-demo-field-col-1-2">
                            <div class="instashow-demo-auto instashow-demo-field">
                                <div class="instashow-demo-field-name"><?php _e('Autorotation', INSTASHOW_LITE_TEXTDOMAIN); ?></div>

                                <span class="instashow-demo-range-container">
                                    <input class="instashow-demo-range-input" type="text" name="auto">
                                    <span class="instashow-demo-range" data-min="0" data-step="100" data-max="10000"></span>
                                </span>

                                <span class="instashow-demo-field-hint"><?php _e('ms', INSTASHOW_LITE_TEXTDOMAIN); ?></span>
                            </div>

                            <div class="instashow-demo-auto-hover-pause instashow-demo-field">
                                <label>
                                    <input type="hidden" name="autoHoverPause" value="false">
                                    <input class="instashow-demo-checkbox" type="checkbox" name="autoHoverPause" value="true">
                                    <span class="instashow-demo-checkbox-label"><?php _e('Pause on hover', INSTASHOW_LITE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="instashow-demo-field-group">
                        <div class="instashow-demo-field-col-1-2">
                            <div class="instashow-demo-field">
                                <div class="instashow-demo-field-name"><?php _e('Animation effect', INSTASHOW_LITE_TEXTDOMAIN); ?></div>

                                <div class="instashow-demo-multiswitch">
                                    <label class="instashow-demo-multiswitch-item">
                                        <input type="radio" name="effect" value="slide" checked>
                                        <span class="instashow-demo-multiswitch-item-label"><?php _e('Slide', INSTASHOW_LITE_TEXTDOMAIN); ?></span>
                                    </label>

                                    <label class="instashow-demo-multiswitch-item">
                                        <input type="radio" name="effect" value="fade">
                                        <span class="instashow-demo-multiswitch-item-label"><?php _e('Fade', INSTASHOW_LITE_TEXTDOMAIN); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="instashow-demo-field-col-1-2">
                            <div class="instashow-demo-speed instashow-demo-field">
                                <div class="instashow-demo-field-name"><?php _e('Animation speed', INSTASHOW_LITE_TEXTDOMAIN); ?></div>

                                <span class="instashow-demo-range-container">
                                    <input class="instashow-demo-range-input" type="text" name="speed">
                                    <span class="instashow-demo-range" data-min="0" data-step="100" data-max="3000"></span>
                                </span>

                                <span class="instashow-demo-field-hint"><?php _e('ms', INSTASHOW_LITE_TEXTDOMAIN); ?></span>
                            </div>

                            <div class="instashow-demo-easing instashow-demo-field">
                                <div class="instashow-demo-field-name-inline instashow-demo-field-name"><?php _e('Easing', INSTASHOW_LITE_TEXTDOMAIN); ?></div>

                                <select class="instashow-demo-select" name="easing">
                                    <option value="linear"><?php _e('linear', INSTASHOW_LITE_TEXTDOMAIN); ?></option>
                                    <option value="ease" selected><?php _e('ease', INSTASHOW_LITE_TEXTDOMAIN); ?></option>
                                    <option value="ease-in"><?php _e('ease-in', INSTASHOW_LITE_TEXTDOMAIN); ?></option>
                                    <option value="ease-out"><?php _e('ease-out', INSTASHOW_LITE_TEXTDOMAIN); ?></option>
                                    <option value="ease-in-out"><?php _e('ease-in-out', INSTASHOW_LITE_TEXTDOMAIN); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="instashow-demo-field-group">
                        <div class="instashow-demo-field">
                            <span class="instashow-demo-field-name-inline instashow-demo-field-name"><?php _e('Popup image switch speed', INSTASHOW_LITE_TEXTDOMAIN); ?></span>

                            <span class="instashow-demo-range-container">
                                <input class="instashow-demo-range-input" type="text" name="popupSpeed">
                                <span class="instashow-demo-range" data-min="0" data-step="100" data-max="3000"></span>
                            </span>

                            <span class="instashow-demo-field-hint"><?php _e('ms', INSTASHOW_LITE_TEXTDOMAIN); ?></span>
                        </div>

                        <div class="instashow-demo-popup-easing instashow-demo-field">
                            <span class="instashow-demo-field-name-inline instashow-demo-field-name"><?php _e('Popup image switch easing', INSTASHOW_LITE_TEXTDOMAIN); ?></span>

                            <select class="instashow-demo-select" name="popupEasing">
                                <option value="linear"><?php _e('linear', INSTASHOW_LITE_TEXTDOMAIN); ?></option>
                                <option value="ease" selected><?php _e('ease', INSTASHOW_LITE_TEXTDOMAIN); ?></option>
                                <option value="ease-in"><?php _e('ease-in', INSTASHOW_LITE_TEXTDOMAIN); ?></option>
                                <option value="ease-out"><?php _e('ease-out', INSTASHOW_LITE_TEXTDOMAIN); ?></option>
                                <option value="ease-in-out"><?php _e('ease-in-out', INSTASHOW_LITE_TEXTDOMAIN); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="instashow-demo-field-group-disabled instashow-demo-field-group">
                        <div class="instashow-demo-field-col-1-2">
                            <div class="instashow-demo-direction instashow-demo-field-disabled instashow-demo-field">
                                <img src="<?php echo plugins_url('assets/img/pro-option-disabled-direction.jpg', INSTASHOW_LITE_FILE); ?>">
                            </div>
                        </div>

                        <div class="instashow-demo-field-col-1-4">
                            <div class="instashow-demo-scroll-control instashow-demo-field-disabled instashow-demo-field">
                                <img src="<?php echo plugins_url('assets/img/pro-option-disabled-scroll-control.jpg', INSTASHOW_LITE_FILE); ?>">
                            </div>
                        </div>

                        <div class="instashow-demo-field-col-1-4">
                            <div class="instashow-demo-free-mode instashow-demo-field-disabled instashow-demo-field">
                                <img src="<?php echo plugins_url('assets/img/pro-option-disabled-free-mode.jpg', INSTASHOW_LITE_FILE); ?>">
                            </div>

                            <div class="instashow-demo-scrollbar instashow-demo-field-disabled instashow-demo-field">
                                <img src="<?php echo plugins_url('assets/img/pro-option-disabled-scrollbar.jpg', INSTASHOW_LITE_FILE); ?>">
                            </div>
                        </div>

                        <a class="instashow-demo-field-available-in-pro" href="<?php echo INSTASHOW_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>
                    
                    <div class="instashow-demo-popup-deep-linking instashow-demo-field-disabled instashow-demo-field">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-popup-deep-linking.jpg', INSTASHOW_LITE_FILE); ?>">
                    </div>

                    <div class="instashow-demo-lang instashow-demo-field-disabled instashow-demo-field">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-lang.jpg', INSTASHOW_LITE_FILE); ?>">
                    </div>

                    <div class="instashow-demo-mode instashow-demo-field-disabled instashow-demo-field">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-mode.jpg', INSTASHOW_LITE_FILE); ?>">
                    </div>
                </div>
            </div>

            <div class="instashow-admin-accordion-item">
                <div class="instashow-admin-accordion-item-trigger"><?php _e('Info', INSTASHOW_LITE_TEXTDOMAIN); ?></div>

                <div class="instashow-admin-accordion-item-content">
                    <div class="instashow-demo-info instashow-demo-field-group-disabled instashow-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-info.jpg', INSTASHOW_LITE_FILE); ?>">

                        <a class="instashow-demo-field-available-in-pro" href="<?php echo INSTASHOW_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>

                    <div class="instashow-demo-popup-info instashow-demo-field-group-disabled instashow-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-popup-info.jpg', INSTASHOW_LITE_FILE); ?>">

                        <a class="instashow-demo-field-available-in-pro" href="<?php echo INSTASHOW_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>
                </div>
            </div>

            <div class="instashow-admin-accordion-item">
                <div class="instashow-admin-accordion-item-trigger"><?php _e('Style', INSTASHOW_LITE_TEXTDOMAIN); ?></div>

                <div class="instashow-admin-accordion-item-content">
                    <div class="instashow-demo-colors instashow-demo-field-group-disabled instashow-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-colors.jpg', INSTASHOW_LITE_FILE); ?>">

                        <a class="instashow-demo-field-available-in-pro" href="<?php echo INSTASHOW_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>
                </div>
            </div>

            <div class="instashow-demo-shortcode-container instashow-admin-accordion-item-detached instashow-admin-accordion-item">
                <div class="instashow-admin-accordion-item-trigger"><span class="instashow-admin-icon-shortcode instashow-admin-icon"></span><span><?php _e('Get Shortcode', INSTASHOW_LITE_TEXTDOMAIN); ?></span></div>

                <div class="instashow-admin-accordion-item-content">
                    <p><?php _e('Copy this shortcode and paste it into any page or post.', INSTASHOW_LITE_TEXTDOMAIN); ?></p>
                    <textarea class="instashow-demo-shortcode" spellcheck="false" readonly></textarea>
                </div>
            </div>
        </div>
    </form>

    <div class="instashow-demo-preview-container">
        <div class="instashow-demo-preview"></div>
    </div>
</div>