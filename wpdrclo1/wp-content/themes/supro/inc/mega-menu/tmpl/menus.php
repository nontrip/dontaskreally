<# if ( data.depth == 0 ) { #>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Mega Menu Content', 'supro' ) ?>"
   data-panel="mega"><?php esc_html_e( 'Mega Menu', 'supro' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Mega Menu Background', 'supro' ) ?>"
   data-panel="background"><?php esc_html_e( 'Background', 'supro' ) ?></a>
<div class="separator"></div>
<#  } else if ( data.depth == 1 ) { #>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Menu Content', 'supro' ) ?>"
   data-panel="content"><?php esc_html_e( 'Menu Content', 'supro' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu General', 'supro' ) ?>"
   data-panel="general"><?php esc_html_e( 'General', 'supro' ) ?></a>
<#  } else { #>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Menu General', 'supro' ) ?>"
   data-panel="general"><?php esc_html_e( 'General', 'supro' ) ?></a>
<#  } #>
