<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
// No direct access to this file
defined('_JEXEC') or die;
require_once(JPATH_ADMINISTRATOR.'/components/com_j2store/helpers/router.php');
$count= 3;
?>
<style type="text/css">
	.j2store-productcompare-img {
		width: <?php echo(int)$this->params->get('image_thumbnail_width', 120);?>px;
	}
</style>

<div class="j2store-product-compare">
	<div class="j2store-product-compare-container">
		<div class="row-fluid">
			<div class="span12">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
						<tr>
							<th colspan="100%" >
								<h3 class="center"><?php echo JText::_('J2STORE_PRODUCT_COMPARISON')?></h3>
							</th>
						</tr>
						</thead>

						<?php
						if(isset($vars->products) && $vars->products):

							$image_path = JUri::root();
							$image_type = $this->params->get('image_type', 'thumbnail');
							$main_image="";
							$count = count($vars->products) > 0 ?  count($vars->products) + 1  : 1;
							JFactory::getDocument()->addScript ( JURI::root ( true ) . '/plugins/j2store/app_productcompare/app_productcompare/assets/js/compare.js' );
							JFactory::getDocument()->addStyleSheet( JURI::root ( true ) . '/plugins/j2store/app_productcompare/app_productcompare/assets/css/compare.css' );
							?>
							<tbody>
							<tr>
								<th></th>
								<!-- Product Container  -->
								<?php foreach($vars->products as $item):?>
									<td>
										<a href="javascript:void(0);" onclick="removeFromCompare(this);" class="product-compare-remove link-button button-light" data-compare-id="<?php echo $vars->aid;?>"
										   data-compare-product-id="<?php echo $item->j2store_product_id; ?>"
										   data-compare-variant-id="<?php echo $item->variant->j2store_variant_id; ?>">
											<i class="fa fa-trash"></i>
										</a>
										<br/>
										<?php $product_image = $item->thumb_image;
										if($image_type =='mainimage'){
											$product_image = $item->main_image;
										}
										?>
										<?php if(JFile::exists(JPATH_SITE.'/'.JPath::clean($product_image))):?>
											<img itemprop="image" alt="<?php echo $item->product_name ;?>" class="thumbnail j2store-productcompare-img j2store-productcompare-thumb-image-<?php echo $item->j2store_product_id; ?> img-responsive"								src="<?php echo $image_path.$product_image;?>" />
										<?php else:?>
											<img itemprop="image" alt="<?php echo $item->product_name ;?>"							class="thumbnail j2store-productcompare-img j2store-productcompare-thumb-image-<?php echo $item->j2store_product_id; ?>"
												 src="<?php echo JURI::root ( true ) . '/plugins/j2store/app_productcompare/app_productcompare/assets/images/placholder.png';?>" />
											<!-- Placholder image comes here  --> <?php endif; ?>

										<h5>
											<?php if($vars->params->get('product_view_type', 'list') == 'list') {
												$qoptions = array (
													'option' => 'com_j2store',
													'view' => 'products',
													'task' => 'view',
													'id' => $item->j2store_product_id
												);
												$pro_menu = J2StoreRouterHelper::findProductMenu ( $qoptions );
												$menu_id = isset($pro_menu->id) ? $pro_menu->id:null;
												if($item->product_source != "com_content"){
													$item->product_link  = $item->product_view_url;
												}else{
													$item->product_link  = JRoute::_('index.php?option=com_j2store&view=products&task=view&id='.$item->j2store_product_id.'&Itemid='.$menu_id);
												}
											}else{
												$item->product_link  = $item->product_view_url;
											}?>
											<a class="product-title"
											   href="<?php echo $item->product_link; ?>"> <?php echo $item->product_name;?>
											</a>
										</h5>

										<?php if($this->params->get('show_product_price',1)):?>
											<?php if($item->pricing->base_price != $item->pricing->price):?>
												<div class="compare-product-price-container">
												<?php $class=''; ?>
												<?php if(isset($item->pricing->is_discount_pricing_available)) $class='strike'; ?>
												<span class="base-price <?php echo $class?>">
															<?php echo J2Store::product()->displayPrice($item->pricing->base_price, $item,$vars->params);?>
														</span>
											<?php endif; ?>
											<span class="sale-price">
													<?php echo J2Store::product()->displayPrice($item->pricing->price, $item ,$vars->params);?>
												</span>
											<?php if($item->pricing->base_price != $item->pricing->price):?>
												</div>
											<?php endif; ?>
										<?php endif; ?>
										<?php if($this->params->get('show_product_cart',1)):?>

											<?php if(count($item->options)):?>

												<!-- we have options so we just redirect -->
												<a href="<?php echo $item->product_link; ?>" class="<?php echo $vars->params->get('choosebtn_class', 'link-button button-cyan'); ?>">
													<?php echo JText::_('J2STORE_VIEW_PRODUCT_DETAILS'); ?>
												</a>

											<?php else: ?>

												<div class="cart-action-complete" style="display: none;">
													<p class="text-success">
														<?php echo JText::_('J2STORE_ITEM_ADDED_TO_CART');?>
														<a href="<?php echo $item->checkout_link; ?>"
														   class="j2store-checkout-link"> <?php echo JText::_('J2STORE_CHECKOUT'); ?>
														</a>
													</p>
												</div>
												<?php
												$button_class = $vars->params->get('addtocart_button_class', 'link-button button-violet') ;
												if(!empty($item->addtocart_text)) {
													$cart_text = JText::_($item->addtocart_text);
												} else {
													$cart_text = JText::_('J2STORE_ADD_TO_CART');
												}
												$action = 'index.php?option=com_j2store&view=carts&task=addItem&product_id='.$item->j2store_product_id;
												?>

												<?php if(!$item->variant->availability): ?>
													<?php
													$button_class = 'link-button button-orange';
													$cart_text = JText::_('J2STORE_OUT_OF_STOCK');
													$action = 'index.php?option=com_j2store&view=products&task=view&id='.$item->j2store_product_id;
													?>
													<a class="<?php echo $button_class;?>" href="<?php echo JRoute::_($action); ?>" >
														<?php echo $cart_text; ?>
													</a>
												<?php else:?>
													<a class="<?php echo $button_class;?> j2store_add_to_cart_button"
													   href="<?php echo JRoute::_($action); ?>"
													   data-quantity="1"
													   data-product_id="<?php echo $item->j2store_product_id;?>"
													   rel="nofollow">
														<?php echo $cart_text; ?>
													</a>
												<?php endif; ?>

											<?php endif; ?>
										<?php endif; ?>
									</td>
								<?php endforeach;?>
							</tr>
							<?php if($this->params->get('show_product_sku',1)):?>
								<!--  Product Sku -->
								<tr>
									<th><?php echo JText::_('J2STORE_PRODUCT_SKU');?></th>
									<?php foreach($vars->products as $item):?>
										<td><?php if(!empty($item->variant->sku)) : ?> <span
												itemprop="sku" class="sku"> <?php echo $item->variant->sku; ?>
								</span> <?php endif; ?>
										</td>
									<?php endforeach;?>
								</tr>
							<?php endif;?>

							<!--  Manufacturer -->
							<?php if($this->params->get('show_product_brand',1)):?>
								<tr>
									<th><?php echo JText::_('J2STORE_PRODUCT_MANUFACTURER_NAME'); ?>
									</th>
									<?php foreach($vars->products as $item):?>
										<td><?php echo $item->manufacturer; ?></td>
									<?php endforeach;?>
								</tr>
							<?php endif;?>
							<!-- Stock -->
							<?php if($this->params->get('show_product_stock',1)):?>
								<tr>
									<th><?php echo JText::_('J2STORE_PRODUCT_SHOW_STOCK_LABEL'); ?></th>
									<?php foreach($vars->products as $item):?>
										<td>
											<div class="product-stock-container">
												<?php if($item->variant->availability): ?>
													<span
														class="<?php echo $item->variant->availability ? 'instock':'outofstock'; ?>">
											<?php echo J2Store::product()->displayStock($item->variant, $vars->params); ?>
										</span>
												<?php else: ?>
													<span class="outofstock">
										<?php echo JText::_('J2STORE_OUT_OF_STOCK'); ?>
										</span>
												<?php endif; ?>
											</div> <?php if($item->variant->allow_backorder == 2 && !$item->variant->availability): ?>
												<span class="backorder-notification"> <?php echo JText::_('J2STORE_BACKORDER_NOTIFICATION'); ?>
								</span> <?php endif; ?>
										</td>
									<?php endforeach;?>
								</tr>
							<?php endif;?>

							<!-- Desc  Summary -->
							<?php if($this->params->get('show_product_desc',1)):?>
								<tr>
									<th><?php echo JText::_('J2STORE_PRODUCT_DESCRIPTION'); ?></th>
									<?php foreach($vars->products as $item):?>
										<td><?php if(isset($item->product_short_desc) && $item->product_short_desc):?>
												<?php  echo $item->product_short_desc; ?> <!-- <a class="read-more"
										href="<?php echo $item->product_link?>"> <?php echo JText::_('J2STORE_READ_MORE')?>
										</a>
										--> <?php endif;?>
										</td>
									<?php endforeach;?>
								</tr>
							<?php endif;?>

							<?php if($this->params->get('show_product_weight',1)):?>
								<tr>
									<th><?php echo JText::_('J2STORE_PRODUCT_WEIGHT');?></th>
									<?php foreach($vars->products as $item):?>
										<td><?php if($item->variant->weight):?> <?php echo round($item->variant->weight, 2); ?>
												<?php echo $item->variant->weight_unit;?> <?php endif;?>
										</td>
									<?php endforeach;?>
								</tr>
							<?php endif;?>

							<?php if($this->params->get('show_product_dimensions',1)):?>
								<tr>
									<th><?php echo JText::_('J2STORE_PRODUCT_DIMENSIONS');?></th>
									<?php foreach($vars->products as $item):?>
										<td>
											<?php echo round($item->variant->length,2);?> <?php echo $item->variant->length_unit;?>
											x <?php echo round($item->variant->height,2);?> <?php echo $item->variant->length_unit;?>
											x <?php echo round($item->variant->width,2) ;?> <?php echo $item->variant->length_unit;?>
										</td>
									<?php endforeach;?>
								</tr>
							<?php endif;?>
							<!-- Filters / Specs -->
							<?php if($this->params->get('show_product_specification',1)  && count($vars->filters)): ?>
								<tr>
									<th colspan="<?php echo $count;?>"><?php echo JText::_('J2STORE_PRODUCT_SPECIFICATIONS')?>
									</th>
								</tr>
								<?php foreach($vars->filters as  $group_name => $myfilters):?>
									<?php if($group_name):?>
										<tr>
											<th><?php echo $group_name; ?></th>
											<?php foreach($vars->products as $product):  ?>
												<?php  $filter = F0FModel::getTmpInstance('Products', 'J2StoreModel')->getProductFilters($product->j2store_product_id); ?>
												<td>
													<?php if(!empty($filter)):?>
														<?php foreach($filter as $singlefilter):?>
															<?php if($singlefilter['filters']):?>
																<?php foreach($singlefilter['filters'] as $filter):?>
																	<?php if($filter->group_name == $group_name):?>
																		<?php echo $filter->filter_name;?>
																		<br/>
																	<?php endif;?>
																<?php endforeach;?>
															<?php endif;?>
														<?php endforeach;?>
													<?php endif;?>
												</td>
											<?php endforeach;?>
										</tr>
									<?php endif;?>
								<?php endforeach; ?>
							<?php endif; ?>
							</tbody>
							<tfooter>
								<tr>
									<td colspan="<?php echo $count;?>" >
										<?php if($vars->continue_url->type != 'previous'): ?>
											<input class="btn btn-primary" type="button" onclick="window.location='<?php echo $vars->continue_url->url; ?>';" value="<?php echo JText::_('J2STORE_CART_CONTINUE_SHOPPING'); ?>" />
										<?php else:?>
											<input class="btn btn-primary" type="button" onclick="window.history.back();" value="<?php echo JText::_('J2STORE_CART_CONTINUE_SHOPPING'); ?>" />
										<?php endif;?>

										<input type="button" onclick="j2storeClearAllcompare(this);" class="btn btn-danger"  value="<?php echo JText::_('J2STORE_CLEAR_ALL');?>"
											   data-compare-id="<?php echo $vars->aid;?>"
										/>
									</td>
								</tr>
							</tfooter>
						<?php else:?>
							<tfooter>
								<tr>
									<td colspan="<?php echo $count;?>" >
										<?php echo JText::_('J2STORE_NO_ITEMS_FOUND');?>

										<?php if($vars->continue_url->type != 'previous'): ?>
											<input class="link-button button-cyan" type="button" onclick="window.location='<?php echo $vars->continue_url->url; ?>';" value="<?php echo JText::_('J2STORE_CART_CONTINUE_SHOPPING'); ?>" />
										<?php else:?>
											<input class="link-button button-cyan" type="button" onclick="window.history.back();" value="<?php echo JText::_('J2STORE_CART_CONTINUE_SHOPPING'); ?>" />
										<?php endif;?>

									</td>
								</tr>
							</tfooter>
						<?php endif;?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
