<?php
/**
 * @version		$Id: default_item.php 11873 2012-03-23 08:54:33Z tuyetvt $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$app 		= JFactory::getApplication();
$template 	= $app->getTemplate();
$jsnUtils   = JSNTplUtils::getInstance();

// Create a shortcut for params.
$params = &$this->item->params;
$canEdit	= $this->item->params->get('access-edit');
$images = json_decode($this->item->images);
?>
<?php if ($jsnUtils->isJoomla3()):
$info    = $this->item->params->get('info_block_position', 0);
if ($this->item->state == 0) : ?>
	<div class="system-unpublished">
	<?php endif; ?>

	<?php if ($params->get('show_title')) : ?>
		<h2 class="item-title">
		<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
			<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>"> <?php echo $this->escape($this->item->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->item->title); ?>
		<?php endif; ?>
		</h2>
	<?php endif; ?>

	<?php if ($this->item->state == 0) : ?>
		<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
	<?php endif; ?>
	
	<?php // Todo Not that elegant would be nice to group the params ?>
	<?php $useDefList = (($params->get('show_modify_date')) || ($params->get('show_publish_date')) || $params->get('show_create_date')
	|| ($params->get('show_hits')) || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') || $params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit); ?>
	<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
	<div class="jsn-article-toolbar">
		<div class="article-info muted">
			<dl class="article-info">
			<dt class="article-info-term"><?php  echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></dt>
			<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
				<dd class="createdby">
					<span class="icon-user"></span>
					<?php $author = $this->item->author; ?>
					<?php $author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author); ?>
					<?php if (!empty($this->item->contactid ) && $params->get('link_author') == true) : ?>
						<?php
						echo JText::sprintf('COM_CONTENT_WRITTEN_BY',
							JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$this->item->contactid), $author)
						); ?>
					<?php else :?>
						<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
					<?php endif; ?>
				</dd>
			<?php endif; ?>
			<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
				<dd class="parent-category-name">
					<?php	$title = $this->escape($this->item->parent_title);
					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';?>
					<?php if ($params->get('link_parent_category') and !empty($this->item->parent_slug)) : ?>
						<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
					<?php else : ?>
						<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
					<?php endif; ?>
				</dd>
			<?php endif; ?>
			
			<?php if ($params->get('show_publish_date')) : ?>
				<dd class="published">
					<span class="icon-calendar"></span> <?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
				</dd>
			<?php endif; ?>

			<?php if ($info == 0): ?>
				<?php if ($params->get('show_modify_date')) : ?>
					<dd class="modified">
						<span class="icon-calendar"></span> <?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
					</dd>
				<?php endif; ?>
				<?php if ($params->get('show_create_date')) : ?>
					<dd class="create">
						<span class="icon-calendar"></span>
						<?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))); ?>
					</dd>
				<?php endif; ?>

				<?php if ($params->get('show_hits')) : ?>
					<dd class="hits">
						<span class="icon-eye-open"></span> <?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
					</dd>
				<?php endif; ?>
			<?php endif; ?>
			</dl>
		</div>
	
	<?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit) : ?>
	<div class="btn-group pull-right"> <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" role="button"> <span class="icon-cog"></span><span class="caret"></span> </a>
		<ul class="dropdown-menu">
			<?php if ($params->get('show_print_icon')) : ?>
			<li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $this->item, $params); ?> </li>
			<?php endif; ?>
			<?php if ($params->get('show_email_icon')) : ?>
			<li class="email-icon"> <?php echo JHtml::_('icon.email', $this->item, $params); ?> </li>
			<?php endif; ?>
			<?php if ($canEdit) : ?>
			<li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
			<?php endif; ?>
		</ul>
	</div>
	<?php endif; ?>
	<div class="clearfix"></div>
	</div>
	<?php endif; ?>
	<div class="intro-text">
	<?php if ($params->get('show_category')) : ?>
		<dd class="category-name">
			<?php 	$title = $this->escape($this->item->category_title);
			$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)).'">'.$title.'</a>';?>
			<?php if ($params->get('link_category') and $this->item->catslug) : ?>
				<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
			<?php else : ?>
				<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
			<?php endif; ?>
		</dd>
	<?php endif; ?>
	<?php  if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
	<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
	<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image"> <img
			<?php if ($images->image_intro_caption):
				echo 'class="caption"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';
			endif; ?>
			src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>"/> </div>
	<?php endif; ?>
	<?php if (!$params->get('show_intro')) : ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>
	<?php endif; ?>
	<?php echo $this->item->event->beforeDisplayContent; ?> <?php echo $this->item->introtext; ?>
	</div>
	<?php if ($this->params->get('show_tags', 1)) : ?>
		<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
		<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
	<?php endif; ?>
	<?php if ($params->get('show_readmore') && $this->item->readmore) :
	if ($params->get('access-view')) :
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
	else :
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
		$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		$link = new JURI($link1);
		$link->setVar('return', base64_encode($returnURL));
	endif;
	?>
	<p class="readmore"><a class="btn" href="<?php echo $link; ?>"> <span class="icon-chevron-right"></span>
	<?php if (!$params->get('access-view')) :
			echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
		elseif ($readmore = $this->item->alternative_readmore) :
			echo $readmore;
			if ($params->get('show_readmore_title', 0) != 0) :
				echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
			endif;
		elseif ($params->get('show_readmore_title', 0) == 0) :
			echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
		else :
			echo JText::_('COM_CONTENT_READ_MORE');
			echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
		endif; ?>
	</a></p>
	<?php endif; ?>
	<?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>
<?php echo $this->item->event->afterDisplayContent; ?>
<?php else :
JHtml::addIncludePath(JPATH_THEMES.DIRECTORY_SEPARATOR.$template.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'com_content');
JHtml::_('behavior.tooltip');
JHtml::core();
$showParentCategory = $params->get('show_parent_category');
$showCategory = ($params->get('show_category',0));
$showInfo = ($params->get('show_author') OR $params->get('show_create_date') OR $params->get('show_publish_date') OR $params->get('show_hits'));
$showTools = ($params->get('show_print_icon') || $canEdit || ($this->params->get( 'show_print_icon' ) || $this->params->get('show_email_icon')));

?>

<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
<?php endif; ?>
<div class="jsn-article">
<?php if ($params->get('show_title')) : ?>
	<h2 class="contentheading">
		<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
			<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
			<?php echo $this->escape($this->item->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->item->title); ?>
		<?php endif; ?>
	</h2>
<?php endif; ?>

<?php if (!$params->get('show_intro')) : ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>
<?php endif; ?>

<?php echo $this->item->event->beforeDisplayContent; ?>

	<?php if ($showParentCategory || $showCategory) : ?>
	<div class="jsn-article-metadata">
		<?php if ($showParentCategory) : ?>
				<span class="parent-category-name">
					<?php	$title = $this->escape($this->item->parent_title);
					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';?>
					<?php if ($params->get('link_parent_category') && $this->item->parent_slug) : ?>
						<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
					<?php else : ?>
						<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
					<?php endif; ?>
				</span>
		<?php endif; ?>	
	</div>
	<?php endif; ?>
	
	<?php if ($showInfo || $showTools) : ?>
	<div class="jsn-article-toolbar">
		<?php if ($showTools) : ?>
		<ul class="jsn-article-tools pull-right">
				<?php if ($this->params->get( 'show_print_icon' )) : ?>
					<li class="jsn-article-print-button"><?php echo JHtml::_('icon.print_popup',  $this->item, $params); ?></li>
				<?php endif; ?>
				<?php if ($this->params->get('show_email_icon')) : ?>
					<li class="jsn-article-email-button"><?php echo JHtml::_('icon.email',  $this->item, $params); ?></li>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<li class="jsn-article-icon-edit"><?php echo JHtml::_('icon.edit', $this->item, $params); ?></li>
				<?php endif; ?>
		</ul>
		<?php endif; ?>
		<?php if ($showInfo) : ?>
			<div class="jsn-article-info">
				<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
					<p class="small author">
						<?php $author =  $this->item->author; ?>
						<?php $author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author);?>

							<?php if (!empty($this->item->contactid ) &&  $params->get('link_author') == true):?>
								<?php 	echo JText::sprintf('COM_CONTENT_WRITTEN_BY' , 
								 JHTML::_('link',JRoute::_('index.php?option=com_contact&view=contact&id='.$this->item->contactid),$author)); ?>
							<?php else :?>
								<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
							<?php endif; ?>
					</p>
				<?php endif; ?>
				<?php if ($params->get('show_create_date')) : ?>
					<p class="createdate">
						<?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHTML::_('date',$this->item->created, JText::_('DATE_FORMAT_LC2'))); ?>
					</p>
				<?php endif; ?>
				<?php if ($params->get('show_publish_date')) : ?>
					<p class="publishdate">
						<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHTML::_('date',$this->item->publish_up, JText::_('DATE_FORMAT_LC2'))); ?>
					</p>
				<?php endif; ?>	
				<?php if ($params->get('show_hits')) : ?>
					<p class="hits">
						<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="clearbreak"></div>
	</div>
	<?php endif; ?>
	<div class="intro-text">
	<?php if ($showCategory) : ?>
			<span class="category-name">
				<?php 	$title = $this->escape($this->item->category_title);
						$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)).'">'.$title.'</a>';?>
				<?php if ($params->get('link_category') AND $this->item->catslug) : ?>
					<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
				<?php else : ?>
					<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
				<?php endif; ?>
			</span>
	<?php endif; ?>		
	<?php  if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
	<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
	<div class="img-intro-<?php echo htmlspecialchars($imgfloat); ?>">
		<img
		<?php if ($images->image_intro_caption):
			echo 'class="caption"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';
		endif; ?>
		src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>"/>
	</div>
	<?php endif; ?>
	<?php echo $this->item->introtext; ?>
	</div>
	<?php if ($params->get('show_modify_date')) : ?>
		<p class="modifydate">
		<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHTML::_('date',$this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
		</p>
	<?php endif; ?>
	
	<?php if ($params->get('show_readmore') && $this->item->readmore) :
		if ($params->get('access-view')) :
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		else :
			$menu = JFactory::getApplication()->getMenu();
			$active = $menu->getActive();
			$itemId = $active->id;
			$link1 = JRoute::_('index.php?option=com_users&view=login&&Itemid=' . $itemId);
			$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
			$link = new JURI($link1);
			$link->setVar('return', base64_encode($returnURL));
		endif;
	?>
            <a href="<?php echo $link; ?>" class="readon">
                <span>
                    <?php if (!$params->get('access-view')) :
                        echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
                    elseif ($readmore = $this->item->alternative_readmore) :
                        echo $readmore;
                        if ($params->get('show_readmore_title', 0) != 0) :
                            echo JHTML::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
                        endif;
                    elseif ($params->get('show_readmore_title', 0) == 0) :
                        echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');	
                    else :
                        echo JText::_('COM_CONTENT_READ_MORE');
                        echo JHTML::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
                    endif; ?>
                </span>
            </a>
	<?php endif; ?>
	
	</div>
	<?php if ($this->item->state == 0) : ?>
	</div>
	<?php endif; ?> 
	<div class="article_separator"></div>
	<?php echo $this->item->event->afterDisplayContent; ?>
	<?php endif; ?>