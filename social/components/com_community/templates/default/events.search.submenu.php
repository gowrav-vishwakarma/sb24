<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	posted	boolean	Determines whether the current state is a posted event.
 */
defined('_JEXEC') or die();
?>

<ul class="cSubmenu-Search cResetList">
	<li>
		<form class="cForm" name="jsform-events-search" method="get" action="<?php echo $url; ?>">
			<input type="text" class="input text" name="search" value="" />
			<input type="submit" value="<?php echo JText::_('COM_COMMUNITY_SEARCH'); ?>" class="cButton cButton-Black cButton-Small">
			<!-- <a href="<?php echo CRoute::_('index.php?option=com_community&view=events&task=search');?>"><?php echo JText::_('COM_COMMUNITY_EVENTS_ADVANCE_SEARCH'); ?></a> -->
			
			<?php echo JHTML::_( 'form.token' ) ?>
			<input type="hidden" value="com_community" name="option" />
			<input type="hidden" value="events" name="view" />
			<input type="hidden" value="search" name="task" />
            <input type="hidden" name="posted" value="1">
			<input type="hidden" value="<?php echo CRoute::getItemId();?>" name="Itemid" />
		</form>
	</li>
</ul>