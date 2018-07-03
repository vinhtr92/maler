<?php
/**
 * @version    $Id$
 * @package    JSN Extension Framework 2
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file.
defined('_JEXEC') or die('Restricted access');

// Get Joomla document object.
$doc = JFactory::getDocument();

// @formatter:off
?>
<!DOCTYPE html>
<html lang="<?php echo $doc->language; ?>" dir="<?php echo $doc->direction; ?>">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<?php
	// Load and render document head.
	$head = $doc->loadRenderer('head');

	echo $head->render('');
	?>
	<style type="text/css">
		#media-selector > div {
			height: 100vh !important;
		}
	</style>
</head>
<body>
	<div id="media-selector"></div>
	<script type="text/javascript">
		(function renderMediaSelector() {
			if (window.BBMediaSelector) {
				const config = {
					baseURL: '<?php echo JUri::root(); ?>',
					getAllFiles: '<?php echo "{$this->baseUrl}&action=getListFiles"; ?>',
					uploadFile: '<?php echo "{$this->baseUrl}&action=uploadFile"; ?>',
					createFolder: '<?php echo "{$this->baseUrl}&action=createFolder"; ?>',
					deleteFolder: '<?php echo "{$this->baseUrl}&action=deleteFolder"; ?>',
					deleteFile: '<?php echo "{$this->baseUrl}&action=deleteFile"; ?>',
					renameFolder: '<?php echo "{$this->baseUrl}&action=renameFolder"; ?>',
					renameFile: '<?php echo "{$this->baseUrl}&action=renameFile"; ?>',
				};

				ReactDOM.render(
					React.createElement(BBMediaSelector, {config: config, fileType: 'TYPE_FILE'}),
					document.getElementById('media-selector')
				);

				var
				updater = '<?php echo JFactory::getApplication()->input->getString('handler'); ?>',
				fieldid = '<?php echo JFactory::getApplication()->input->getString('fieldid'); ?>';

				if ( window.parent && (updater != '' || fieldid != '' || window.parent.jInsertFieldValue) ) {
					var
					selected,
					button = window.parent.document.querySelector('.modal.in .modal-footer .btn-primary, .modal.show .modal-footer .btn-primary'),
					addEvent = function(elm, evt, fn) {
						if (typeof elm.addEventListener == 'function') {
							elm.addEventListener(evt, fn);
						} else if (typeof elm.attachEvent == 'function') {
							elm.attachEvent(evt, fn);
						}
					},
					removeEvent = function(elm, evt, fn) {
						if (typeof elm.removeEventListener == 'function') {
							elm.removeEventListener(evt, fn);
						} else if (typeof elm.detachEvent == 'function') {
							elm.detachEvent(evt, fn);
						}
					},
					triggerEvent = function(elm, evt) {
						if (typeof elm.dispatchEvent == 'function') {
							elm.dispatchEvent( new window.Event(evt) );
						} else if (typeof elm.fireEvent == 'function') {
							elm.fireEvent( 'on' + evt, document.createEventObject() );
						}
					},
					select = function(event) {
						event.preventDefault();

						// Make sure there is a selection.
						if ( ! selected ) {
							return alert('<?php echo JText::_('JSN_EXTFW_MEDIA_SELECTOR_NO_FILE_SELECTED'); ?>');
						}

						try {console.log(updater ? window.parent[updater] : '');
							// If there is a callback function, call it.
							if (updater && window.parent[updater]) {
								return window.parent[updater](selected, fieldid);
							}

							// If default select handler available, call it.
							if (window.parent.jInsertFieldValue) {
								return window.parent.jInsertFieldValue(selected, fieldid);
							}

							// Otherwise, set new value for the affected field.
							var field = window.parent.document.getElementById(fieldid);

							if (field) {
								field.value = selected;

								// Trigger a change event on the affected field.
								return triggerEvent(field, 'change');
							}
						} catch (e) {
							// Do nothing.
						}
					},
					change = function(event) {
						selected = event.detail;

						if (button) {
							// Enable select button.
							button.disabled = false;
						} else {
							select(event);
						}
					},
					deselect = function(event) {
						selected = null;

						if (button) {
							// Disable select button.
							button.disabled = true;
						}
					};

					// Listen to 'select-file' event on the document.
					addEvent(document, 'select-file', change);

					// Listen to 'deselect-file' event on the document.
					addEvent(document, 'deselect-file', deselect);

					if (!button) {
						// Automatically generate a button to select media file.
						var btn = window.parent.document.querySelector('.modal.in .modal-footer .btn, .modal.show .modal-footer .btn');

						if (btn) {
							button = document.createElement(btn.nodeName);

							for (var i = 0; i < btn.attributes.length; i++) {
								button.setAttribute(btn.attributes[i].name, btn.attributes[i].value);
							}

							button.classList.add('btn-primary');
							button.classList.add('select-image');
							button.textContent = '<?php echo JText::_('JSN_EXTFW_SELECT'); ?>';
						}

						btn.parentNode.insertBefore(button, btn);
					}

					// Listen to 'click' event on the select button.
					addEvent(button, 'click', select);

					// Listen to 'beforeunload' event on the window.
					window.onbeforeunload = function(event) {
						// Stop listening to 'click' event on the select button.
						removeEvent(button, 'click', select);
					};
				}
			} else {
				setTimeout(renderMediaSelector, 100);
			}
		})();
		// change modal height
		(function setHeightForModal() {
		 const modal = window.top.document.querySelectorAll('#imageModal_jform_images_image_intro')[0]
		 if(modal) {
		     const modalBody = modal.querySelectorAll('.modal-body')[0]
		     if(modalBody) {
		         modalBody.style.height = '100%'
		         modalBody.style.maxHeight = 'none'
		         const iframe = modalBody.querySelectorAll('iframe')[0]
		         if(iframe) {
		             iframe.style.transition = '0.1s'
		             iframe.style.height = window.top.screen.height * 0.7 + 'px'
		         }
		     }
		 }
		})();
	</script>
</body>
</html>
