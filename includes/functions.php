<?php

////////////////////////////
// FUNCTIONS
////////////////////////////

//RETURN GENERIC INPUT HTML
function fca_slc_input ( $name, $placeholder = '', $value = '', $type = 'input' ) {

	$html = "<div class='fca-lpc-field fca-lpc-field-$type'>";
	
		switch ( $type ) {
			
			case 'checkbox':
				$checked = !empty( $value ) ? "checked='checked'" : '';
				
				$html .= "<div class='onoffswitch'>";
					$html .= "<input style='display:none;' type='checkbox' id='fca_slc[$name]' class='onoffswitch-checkbox fca-lpc-input-$type fca-lpc-$name' name='fca_slc[$name]' $checked>"; 
					$html .= "<label class='onoffswitch-label' for='fca_slc[$name]'><span class='onoffswitch-inner' data-content-on='ON' data-content-off='OFF'><span class='onoffswitch-switch'></span></span></label>";
				$html .= "</div>";
				break;
				
			case 'textarea':
				$html .= "<textarea placeholder='$placeholder' class='fca-lpc-input-$type fca-lpc-$name' name='fca_slc[$name]'>$value</textarea>";
				break;
				
			case 'image':
				$html .= "<input type='hidden' class='fca-lpc-input-$type fca-lpc-$name' name='fca_slc[$name]' value='$value'>";
				$html .= "<button type='button' class='button-secondary fca-lpc-image-upload-btn'>" . __('Add Image', 'locker-cat') . "</button>";
				$html .= "<img class='fca-lpc-image' style='max-width: 252px' src='$value'>";
		
				$html .= "<div class='fca-lpc-image-hover-controls'>";
					$html .= "<button type='button' class='button-secondary fca-lpc-image-change-btn'>" . __('Change', 'locker-cat') . "</button>";
					$html .= "<button type='button' class='button-secondary fca-lpc-image-revert-btn'>" . __('Remove', 'locker-cat') . "</button>";
				$html .=  '</div>';
				break;
			case 'color':
				$html .= "<input type='text' placeholder='$placeholder' class='fca-lpc-input-$type fca-lpc-$name' name='fca_slc[$name]' value='$value'>";
				break;
			case 'editor':
				ob_start();
				wp_editor( $value, $name, array() );
				$html .= ob_get_clean();
				break;
			case 'datepicker':
				$html .= "<input type='text' placeholder='$placeholder' class='fca-lpc-input-$type fca-lpc-$name' name='fca_slc[$name]' value='$value'>";
				break;
			
			case 'wysi':
					$html .= "<div class='fca-wysiwyg-nav' style='display:none'>";
						$html .= '<div class="fca-wysiwyg-group fca-wysiwyg-text-group">';
							$html .= '<button type="button" data-wysihtml5-command="bold" class="fca-nav-bold fca-nav-rounded-left" ><span class="dashicons dashicons-editor-bold"></span></button>';
							$html .= '<button type="button" data-wysihtml5-command="italic" class="fca-nav-italic fca-nav-no-border" ><span class="dashicons dashicons-editor-italic"></span></button>';
							$html .= '<button type="button" data-wysihtml5-command="underline" class="fca-nav-underline fca-nav-rounded-right" ><span class="dashicons dashicons-editor-underline"></span></button>';
						$html .= "</div>";
						$html .= '<div class="fca-wysiwyg-group fca-wysiwyg-alignment-group">';
							$html .= '<button type="button" data-wysihtml5-command="justifyLeft" class="fca-nav-justifyLeft fca-nav-rounded-left" ><span class="dashicons dashicons-editor-alignleft"></span></button>';
							$html .= '<button type="button" data-wysihtml5-command="justifyCenter" class="fca-nav-justifyCenter fca-nav-no-border" ><span class="dashicons dashicons-editor-aligncenter"></span></button>';
							$html .= '<button type="button" data-wysihtml5-command="justifyRight" class="fca-nav-justifyRight fca-nav-rounded-right" ><span class="dashicons dashicons-editor-alignright"></span></button>';
						$html .= "</div>";
						
						$html .= '<div class="fca-wysiwyg-group fca-wysiwyg-link-group">';
							$html .= '<button type="button" data-wysihtml5-command="createLink" style="border-right: 0;" class="fca-wysiwyg-link-group fca-nav-rounded-left"><span class="dashicons dashicons-admin-links"></span></button>';
							$html .= '<button type="button" data-wysihtml5-command="unlink" class="fca-wysiwyg-link-group fca-nav-rounded-right"><span class="dashicons dashicons-editor-unlink"></span></button>';
							$html .= '<div class="fca-wysiwyg-url-dialog" data-wysihtml5-dialog="createLink" style="display: none">';
								$html .= '<input data-wysihtml5-dialog-field="href" value="http://">';
								$html .= '<a class="button button-secondary" data-wysihtml5-dialog-action="cancel">' . __('Cancel', 'quiz-cat') . '</a>';
								$html .= '<a class="button button-primary" data-wysihtml5-dialog-action="save">' . __('OK', 'quiz-cat') . '</a>';
							$html .= "</div>";
						$html .= "</div>";
						
						$html .= '<button class="fca-wysiwyg-view-html action" type="button" data-wysihtml5-action="change_view">HTML</button>';
				
					$html .= "</div>";
					$html .= "<textarea class='fca-wysiwyg-html fca-lpc-input-$type fca-lpc-$name' name='fca_slc[$name]'>$value</textarea>";
					break;
				
			default: 
				$html .= "<input type='$type' placeholder='$placeholder' class='fca-lpc-input-$type fca-lpc-$name' name='fca_slc[$name]' value='$value'>";
		}
	
	$html .= '</div>';
	
	return $html;
	
}