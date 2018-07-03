<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

$display_tw  = $this->params->get('show_twitter_icon',1);
$display_fb  = $this->params->get('show_facebook_icon',1);
$display_gp = $this->params->get('show_google_plus_icon',1);
$display_pinterest  = $this->params->get('show_pinterest_icon',1);
$display_linkedin  = $this->params->get('show_linkedin_icon',1);
$doc = JFactory::getDocument();
$doc->addScript(JUri::root().'plugins/j2store/app_socialmedia/app_socialmedia/media/js/j2storesocialmedia.js');
$doc->addStyleSheet(JUri::root().'plugins/j2store/app_socialmedia/app_socialmedia/media/css/socialmedia.css');
$width =  $this->params->get('socialmedia_image_width',30) ? $this->params->get('socialmedia_image_width',30) : 30 ;

$checkout = JText::_($this->params->get('socialmedia_share_text','PLG_J2STORE_APP_SOCIALMEDIA_CHECKOUT'));
$checkout .= ' '.$vars->product_name.' ';

if (!empty($vars->product_price)) {
	$checkout .= $vars->product_price ; 	
}


?>

<style type="text/css">
 .app_socialmedia img{
	width:<?php echo $width.'px';?>;
}
</style>
<?php if ( $display_fb || $display_tw || $display_gp  || $display_linkedin || $display_pinterest) : ?>
<div id="j2store_app_shareButton">
	<span>Share this product:</span>
	<?php if ( $display_tw ) : ?>
		<a href="javascript:void(0);" class="app_socialmedia"  onclick="j2store_socialsharing_twitter_click('<?php echo $checkout. $this->getSocialBookMarkUri(); ?>')">
			<?php if($this->params->get('twitter_icon','') &&  JFile::exists(JPATH_SITE.'/'.$this->params->get('twitter_icon',''))):?>
				<img alt="twitter" src="<?php echo JUri::root().$this->params->get('twitter_icon');?>"  />
			<?php else:?>
				<img alt="twitter" src="<?php echo JUri::root().'plugins/j2store/app_socialmedia/app_socialmedia/media/images/twitter.svg'?>" />
			<?php endif;?>

		</a>
	<?php endif;?>

	<?php if ( $display_fb ) : ?>
		<a href="javascript:void(0);" class="app_socialmedia"  onclick="j2store_socialsharing_facebook_click('<?php echo $checkout. $this->getSocialBookMarkUri(); ?>')">
			<?php if($this->params->get('facebook_icon','') &&  JFile::exists(JPATH_SITE.'/'.$this->params->get('facebook_icon',''))):?>
				<img  alt="facebook" src="<?php echo JUri::root().$this->params->get('facebook_icon');?>"/>
			<?php else:?>
				<img  alt="facebook" src="<?php echo JUri::root().'plugins/j2store/app_socialmedia/app_socialmedia/media/images/facebook.svg'?>"/>
			<?php endif;?>
		 </a>
	<?php endif;?>

	<?php if ( $display_gp ) : ?>
		<a href="javascript:void(0);"  class="app_socialmedia" onclick="j2store_socialsharing_google_click('<?php echo $checkout.$this->getSocialBookMarkUri(); ?>')">
			<?php if($this->params->get('googleplus_icon','') &&  JFile::exists(JPATH_SITE.'/'.$this->params->get('googleplus_icon',''))):?>
				<img alt="googleplus" src="<?php echo JUri::root().$this->params->get('googleplus_icon');?>"/>
			<?php else:?>
				<img alt="googleplus" src="<?php echo JUri::root().'plugins/j2store/app_socialmedia/app_socialmedia/media/images/google_plus.svg'?>"/>
			<?php endif;?>
		 </a>
	<?php endif;?>

	<?php if ( $display_pinterest ):
			if (!empty($vars->media_url)) { // only if a media exists, show the pintrest button. It shares only images otherwise it throws errors
			?>
			<a href="javascript:void(0);"  class="app_socialmedia" onclick="j2store_socialsharing_pinterest_click('<?php echo $checkout. $this->getSocialBookMarkUri(); ?>','<?php echo $vars->media_url;?>')">
				<?php if($this->params->get('pinterest_icon','') &&  JFile::exists(JPATH_SITE.'/'.$this->params->get('pinterest_icon',''))):?>
					<img alt="pinterest" src="<?php echo JUri::root().$this->params->get('pinterest_icon');?>"/>
				<?php else:?>
					<img alt="pinterest" src="<?php echo JUri::root().'plugins/j2store/app_socialmedia/app_socialmedia/media/images/pinterest.svg'?>"/>
				<?php endif;?>

			 </a>
	<?php 
		 }// end check if media exits
		endif;?>

	<?php if ( $display_linkedin ) : ?>
		<a href="javascript:void(0);" class="app_socialmedia" onclick="j2store_socialsharing_linkedin_click('<?php echo $checkout. $this->getSocialBookMarkUri(); ?>')">
			<?php if($this->params->get('linkedin_icon','') &&  JFile::exists(JPATH_SITE.'/'.$this->params->get('linkedin_icon',''))):?>
				<img alt="linkedin" src="<?php echo JUri::root().$this->params->get('linkedin_icon');?>"/>
			<?php else:?>
				<img alt="linkedin" src="<?php echo JUri::root().'plugins/j2store/app_socialmedia/app_socialmedia/media/images/linkedin.svg'?>"/>
			<?php endif;?>
		 </a>
	<?php endif; ?>
	<div class="reset"></div>
</div>
<?php endif; ?>


