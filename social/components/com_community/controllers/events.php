<?php
/**
 * @package   JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license   GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Events Controller
 */
class CommunityEventsController extends CommunityBaseController
{
	protected $_disabledMessage = '';

	/**
	 * Responsible to return necessary contents to the Invitation library
	 * so that it can add the mails into the queue
	 **/
	public function inviteUsers($cid, $users, $emails, $message)
	{
		//CFactory::load( 'libraries' , 'invitation' );

		$model = CFactory::getModel('Events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($cid);

		$my = CFactory::getUser();
		$mainframe = JFactory::getApplication();
		$status = $event->getUserStatus($my->id);

		$invitedCount = 0;

		foreach ($users as $id) {
			$date = JFactory::getDate();
			$eventMember = JTable::getInstance('EventMembers', 'CTable');
			$eventMember->eventid = $event->id;
			$eventMember->memberid = $id;
			$eventMember->status = COMMUNITY_EVENT_STATUS_INVITED;
			$eventMember->invited_by = $my->id;
			$eventMember->created = $date->toSql();

			$eventMember->store();
			$invitedCount++;
		}

		//now update the invited count in event
		$event->invitedcount = $event->invitedcount + $invitedCount;
		$event->store();

		// Send notification to the invited user.
		//CFactory::load( 'libraries' , 'notification' );

		$params = new CParameter('');
		$params->set('url', CRoute::getExternalURL('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id));
		$params->set('event', $event->title);
		$params->set('event_url', 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);

		$htmlTemplate = new CTemplate();
		$htmlTemplate->set('eventTitle', $event->title);
		$htmlTemplate->set('message', $message);

		$html = $htmlTemplate->fetch('email.events.invite.html');

		$textTemplate = new CTemplate();
		$textTemplate->set('eventTitle', $event->title);
		$textTemplate->set('message', $message);
		$text = $textTemplate->fetch('email.events.invite.text');

		$inviteMail = new CInvitationMail('events_invite', $html, $text, JText::sprintf('COM_COMMUNITY_EVENTS_JOIN_INVITE', $event->title), $params);
		$allowed = array(COMMUNITY_EVENT_STATUS_INVITED, COMMUNITY_EVENT_STATUS_ATTEND, COMMUNITY_EVENT_STATUS_WONTATTEND, COMMUNITY_EVENT_STATUS_MAYBE);
		$accessAllowed = ((in_array($status, $allowed)) && $status != COMMUNITY_EVENT_STATUS_BLOCKED) ? true : false;
		$accessAllowed = COwnerHelper::isCommunityAdmin() ? true : $accessAllowed;

		//CFactory::load( 'helpers' , 'event' );

		if (!($accessAllowed && $event->allowinvite) && !$event->isAdmin($my->id)) {
			$inviteMail->setError(JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN'));
		}

		return $inviteMail;
	}

	public function __construct()
	{
		$this->_disabledMessage = JText::_('COM_COMMUNITY_EVENTS_DISABLED');
	}

	public function editEventsWall($wallId)
	{
		//CFactory::load( 'helpers' , 'owner' );
		//CFactory::load( 'helpers' , 'time');

		$wall = JTable::getInstance('Wall', 'CTable');
		$wall->load($wallId);

		$event = JTable::getInstance('Event', 'CTable');
		$event->load($wall->contentid);

		$my = CFactory::getUser();

		// @rule: We only allow editing of wall in 15 minutes
		$now = JFactory::getDate();
		$interval = CTimeHelper::timeIntervalDifference($wall->date, $now->toSql());
		$interval = abs($interval);

		if (($event->isCreator($my->id) || $event->isAdmin($my->id) || COwnerHelper::isCommunityAdmin() || $my->id == $wall->post_by) && (COMMUNITY_WALLS_EDIT_INTERVAL > $interval)) {
			return true;
		}
		return false;
	}

	/**
	 * Full application view
	 */
	public function app()
	{
		$view = $this->getView('events');

		echo $view->get('appFullView');
	}

	/*
	 * Display nearby events
	 * @param   $location	Current location get from Google Map API v3 OR user input
	 *
	 */
	public function ajaxDisplayNearbyEvents($location)
	{
		$response = new JAXResponse();

		$config = CFactory::getConfig();

		$filter = JFilterInput::getInstance();
		$location = $filter->clean($location, 'string');

		$advance = array();
		$advance['radius'] = $config->get('event_nearby_radius');
		
		if( $config->get('eventradiusmeasure') == COMMUNITY_EVENT_UNIT_KM){	//find out if radius is in km or miles
			$advance['radius']  =	$advance['radius'] * 0.621371192;
		} 

		
		$advance['fromlocation'] = $location;

		$model = CFactory::getModel('events');
		$objs = $model->getEvents(null, null, null, null, null, null, null, $advance);

		$events = array();

		$tmpl = new CTemplate();

		if ($objs) {
			foreach ($objs as $row) {
				$event = JTable::getInstance('Event', 'CTable');
				$event->bind($row);
				$events[] = $event;
			}
			unset($objs);
		}

		// Get list of nearby events
		$tmpl->set('events', $events);
		$tmpl->set('radius', $config->get('event_nearby_radius'));
		$tmpl->set('measurement', $config->get('eventradiusmeasure'));
		$tmpl->set('location', $location);
		$html = $tmpl->fetch('events.nearbylist');
		$response->addScriptCall('__callback', $html);

		return $response->sendResponse();
	}

	/**
	 *  Ajax function to prompt warning during group deletion
	 *
	 * @param    $groupId    The specific group id to unpublish
	 **/
	public function ajaxWarnEventDeletion($eventId)
	{
		$response = new JAXResponse();

		$filter = JFilterInput::getInstance();
		$eventId = $filter->clean($eventId, 'int');

		$title = JText::_('COM_COMMUNITY_EVENTS_DELETE');
		$content = JText::_('COM_COMMUNITY_EVENTS_DELETE_WARNING');

		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		if ($event->isRecurring()) {
			$content .= '<br><br><input type="radio" id="deletecurrent" name="deletetype" value="current" checked><label for="deletecurrent"><span class="label title">&nbsp;&nbsp;' . JText::_('COM_COMMUNITY_EVENTS_DELETE_MESSAGE_ONLY_THIS') . '</span><br>';
			$content .= '<br><input type="radio" id="deletefuture" name="deletetype" value="future"><label for="deletefuture"><span class="label title">&nbsp;&nbsp;' . JText::_('COM_COMMUNITY_EVENTS_DELETE_MESSAGE_FOLLOWING_THIS') . '</span><br><br>';
		}

		$selected = 'joms.jQuery(\'input:radio[name=deletetype]:checked\').val()';
		$actions = '<input type="button" class="button" onclick="jax.call(\'community\', \'events,ajaxDeleteEvent\', \'' . $eventId . '\', 1,' . $selected . ');" value="' . JText::_('COM_COMMUNITY_DELETE') . '"/>';
		$actions .= '<input type="button" class="button" onclick="cWindowHide();" value="' . JText::_('COM_COMMUNITY_CANCEL_BUTTON') . '"/>';

		$response->addAssign('cwin_logo', 'innerHTML', $title);
		$response->addScriptCall('cWindowAddContent', $content, $actions);

		return $response->sendResponse();
	}

	/**
	 * Ajax function to add new admin to the event
	 *
	 * @param memberid    Members id
	 * @param groupid    Eventid
	 *
	 **/
	public function ajaxManageAdmin($memberId, $eventId, $task)
	{
		$response = new JAXResponse();

		$filter = JFilterInput::getInstance();
		$memberId = $filter->clean($memberId, 'int');
		$eventId = $filter->clean($eventId, 'int');
		$task = $filter->clean($task, 'string');

		$my = CFactory::getUser();
		$model = $this->getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);

		if (!$handler->manageable()) {
			$response->addScriptCall('joms.jQuery("#notice").html("' . JText::_('COM_COMMUNITY_PERMISSION_DENIED_WARNING') . '");');
			$response->addScriptCall('joms.jQuery("#notice").attr("class","error");');
		} else {
			$member = JTable::getInstance('EventMembers', 'CTable');
			$keys = array('eventId'=>$event->id, 'memberId'=>$memberId);
			$member->load($keys);

			$response->addScriptCall('joms.jQuery("#member_' . $memberId . '").css("border","3px solid green");');

			switch ($task) {
				case 'add' :
					$member->permission = 2;
					$message = JText::_('COM_COMMUNITY_EVENTS_ADD_ADMIN');
					break;
				case 'remove' :
					$member->permission = 3;
					$message = JText::_('COM_COMMUNITY_EVENTS_REVERT_ADMIN');
					break;
				default:
					break;
			}

			$member->store();
			$response->addScriptCall('joms.jQuery("#notice").html("' . $message . '");');
			$response->addScriptCall('joms.jQuery("#notice").attr("class","info");');
		}

		return $response->sendResponse();
	}

	/**
	 * Ajax function to display the join event
	 *
	 * @params $eventid    A string that determines the evnt id
	 **/
	public function ajaxRequestInvite($eventId, $redirectUrl)
	{
		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}

		$response = new JAXResponse();

		$filter = JFilterInput::getInstance();
		$eventId = $filter->clean($eventId, 'int');
		$redirectUrl = $filter->clean($redirectUrl, 'string');

		$my = CFactory::getUser();
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		$eventMembers = JTable::getInstance('EventMembers', 'CTable');
		$keys = array('eventId'=>$eventId,'memberId'=>$my->id);
		$eventMembers->load($keys);

		$isMember = $eventMembers->exists();
		$tmpl = new CTemplate();
		$tmpl->set('isMember', $isMember);
		$tmpl->set('event', $event);
		$contents = $tmpl->fetch('ajax.events.requestinvite');
		$actions = '<input onclick="cWindowHide();" type="submit" value="' . JText::_('COM_COMMUNITY_BUTTON_CLOSE_BUTTON') . '" class="button" name="Submit"/>';

		if (!$isMember) {
			$actions = '<form name="jsform-events-ajaxrequestinvite" method="post" action="' . CRoute::_('index.php?option=com_community&view=events&task=requestInvite') . '">';
			$actions .= '<input type="submit" value="' . JText::_('COM_COMMUNITY_YES_BUTTON') . '" class="button" name="Submit"/>';
			$actions .= '<input type="hidden" value="' . $eventId . '" name="eventid" />';
			$actions .= '<input onclick="cWindowHide();" type="button" value="' . JText::_('COM_COMMUNITY_NO_BUTTON') . '" class="button" name="Submit" />';
			$actions .= '</form>';
		}

		// Change cWindow title
		$response->addAssign('cwin_logo', 'innerHTML', JText::_('COM_COMMUNITY_EVENTS_REQUEST_INVITATION_TITLE'));
		$response->addScriptCall('cWindowAddContent', $contents, $actions);

		return $response->sendResponse();
	}

	/**
	 * A user decided to ignore this event. Once he 'ignore' an event. He
	 * cannot be invited or contacted by event admin
	 **/
	public function ajaxIgnoreEvent($eventId)
	{
		$response = new JAXResponse();

		$filter = JFilterInput::getInstance();
		$eventId = $filter->clean($eventId, 'int');

		$model = $this->getModel('events');
		$my = JFactory::getUser();

		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		ob_start();
		?>
    <div id="community-event-leave">
        <p><?php echo JText::sprintf('COM_COMMUNITY_EVENTS_LEAVE_MESSAGE', $event->title); ?></p>
    </div>
	<?php
		$contents = ob_get_contents();
		ob_end_clean();

		$actions = '<form name="jsform-events-ajaxignoreevent" method="post" action="' . CRoute::_('index.php?option=com_community&view=events&task=ignore') . '">';
		$actions .= '<input type="submit" value="' . JText::_('COM_COMMUNITY_YES_BUTTON') . '" class="button" name="Submit"/>';
		$actions .= '<input type="hidden" value="' . $eventId . '" name="eventid" />';
		$actions .= '<input onclick="cWindowHide();return false" type="button" value="' . JText::_('COM_COMMUNITY_NO_BUTTON') . '" class="button" name="Submit"/>';
		$actions .= '</form>';

		// Change cWindow title
		$response->addAssign('cwin_logo', 'innerHTML', JText::_('COM_COMMUNITY_EVENTS_IGNORE'));
		$response->addScriptCall('cWindowAddContent', $contents, $actions);

		return $response->sendResponse();
	}

	/**
	 * Ajax function to approve a specific member when event admin or site admin tries to approve an invitation.
	 *
	 * @params    string    id    The member's id that needs to be approved.
	 * @params    string    groupid    The group id that the user is in.
	 **/
	public function ajaxApproveInvite($memberId, $eventId)
	{
		$response = new JAXResponse();

		$filter = JFilterInput::getInstance();
		$memberId = $filter->clean($memberId, 'int');
		$eventId = $filter->clean($eventId, 'int');

		$my = CFactory::getUser();
		$model = $this->getModel('events');

		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);

		if (!$handler->manageable()) {
			$response->addScriptCall(JText::_('COM_COMMUNITY_NOT_ALLOWED_TO_ACCESS_SECTION'));
		} else {
			// Load required tables
			$member = JTable::getInstance('EventMembers', 'CTable');
			$keys = array('eventId'=>$eventId,'memberId'=>$memberId);
			$member->load($keys);

			$member->attend();
			$member->store();

			// Build the URL.
			$url = CUrl::build('events', 'viewevent', array('eventid' => $event->id), true);
			$user = CFactory::getUser($memberId);

			$tmplData = array();
			$tmplData['url'] = CRoute::getExternalURL('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false);
			$tmplData['event'] = $event->title;
			$tmplData['user'] = $user->getDisplayName();
			$tmplData['approval'] = 1;

			// Send email to evnt member once their invitation is approved
			//CFactory::load( 'libraries' , 'notification' );

			$params = new CParameter('');
			$params->set('url', CRoute::getExternalURL('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false));
			$params->set('eventTitle', $event->title);
			$params->set('event', $event->title);
			$params->set('event_url', 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);
			CNotificationLibrary::add('events_invitation_approved', $event->creator, $user->id, JText::sprintf('COM_COMMUNITY_EVENTS_EMAIL_SUBJECT', $event->title),
				'', 'events.invitation.approved', $params);

			$response->addScriptCall('joms.jQuery("#member_' . $memberId . '").css("border","3px solid blue");');
			$response->addScriptCall('joms.jQuery("#notice").html("' . JText::_('COM_COMMUNITY_EVENTS_REQUEST_APPROVED') . '");');
			$response->addScriptCall('joms.jQuery("#notice").attr("class","info");');
			$response->addScriptCall('joms.jQuery("#events-approve-' . $memberId . '").remove();');

		}
		$this->cacheClean(array(COMMUNITY_CACHE_TAG_EVENTS));
		return $response->sendResponse();
	}

	/**
	 *  Ajax function to delete a event
	 *
	 * @param    $eventId    The specific event id to unpublish
	 **/
	public function ajaxDeleteEvent($eventId, $step = 1, $action = '')
	{
		$response = new JAXResponse();

		$filter = JFilterInput::getInstance();
		$eventId = $filter->clean($eventId, 'int');
		$step = $filter->clean($step);

		//CFactory::load( 'libraries' , 'activities' );
		//CFactory::load( 'helpers' , 'owner' );
		//CFactory::load( 'models' , 'events' );

		$model = CFactory::getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		$membersCount = $event->getMembersCount('accepted');

		$my = CFactory::getUser();
		$isMine = ($my->id == $event->creator);

		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);

		// only check for step 1 as the following steps will remove member, including event creator which will violate permission
		if (!$handler->manageable() && $step == 1) {
			$content = JText::_('COM_COMMUNITY_EVENTS_NOT_ALLOW_DELETE');
			$actions = '<input type="button" class="button" onclick="cWindowHide();" value="' . JText::_('COM_COMMUNITY_CANCEL') . '"/>';

			$response->addScriptCall('cWindowAddContent', $content, $actions);

			return $response->sendResponse();
		}

		$response->addScriptCall('cWindowResize', 160);

		$doneMessage = ' - <span class=\'success\'>' . JText::_('COM_COMMUNITY_DONE') . '</span><br />';
		$failedMessage = ' - <span class=\'failed\'>' . JText::_('COM_COMMUNITY_FAILED') . '</span><br />';

		switch ($step) {
			case 1:
				// Nothing gets deleted yet. Just show a messge to the next step
				if (empty($eventId)) {
					$content = JText::_('COM_COMMUNITY_EVENTS_INVALID_ID_ERROR');
				} else {
					$content = '<strong>' . JText::sprintf('COM_COMMUNITY_EVENTS_DELETING', $event->title) . '</strong><br/>';
					$content .= JText::_('COM_COMMUNITY_EVENTS_DELETE_MEMBERS');
					$response->addScriptCall('jax.call(\'community\', \'events,ajaxDeleteEvent\', \'' . $eventId . '\', 2, \'' . $action . '\');');

					$this->triggerEvents('onBeforeEventDelete', $event);
				}
				$response->addScriptCall('cWindowAddContent', $content);
				break;
			case 2:
				// Delete all event members
				if ($event->deleteAllMembers($action)) {
					$content = $doneMessage;
				} else {
					$content = $failedMessage;
				}
				$content .= JText::_('COM_COMMUNITY_EVENTS_DELETE_WALLS');
				$response->addScriptCall('joms.jQuery("#cWindowContent").append("' . $content . '");');
				$response->addScriptCall('jax.call(\'community\', \'events,ajaxDeleteEvent\', \'' . $eventId . '\', 3, \'' . $action . '\');');
				break;
			case 3:
				// Delete all event wall
				if ($event->deleteWalls()) {
					$content = $doneMessage;
				} else {
					$content = $failedMessage;
				}
				$response->addScriptCall('joms.jQuery("#cWindowContent").append("' . $content . '");');
				$response->addScriptCall('jax.call(\'community\', \'events,ajaxDeleteEvent\', \'' . $eventId . '\', 4, \'' . $action . '\');');
				break;
			case 4:
				// Delete event master record
				$eventData = $event;
				$deleted = false;
				$eventList = array();

				// delete the current event only
				if ($action == 'current') {
					$event->published = 3;
					$deleted = $event->store();
					// Delete all event in the series
				} else if ($action == 'future') {

					$eventList = $model->getEventChilds($event->parent, array('eventid' => $event->id));
					foreach ($eventList as $key => $value) {
						$event->load($value['id']);
						$event->published = 3;
						$deleted = $event->store();
					}
					// Delete non recurring event
				} else {
					$deleted = $event->delete();
				}

				if ($deleted) {
					// Delete featured event.
					//CFactory::load( 'libraries' , 'featured' );
					$featured = new CFeatured(FEATURED_EVENTS);

					if (!empty($eventList)) {
						foreach ($eventList as $key => $value) {
							$obj = new stdClass();
							$obj->avatar = $value['avatar'];
							$obj->thumb = $value['thumb'];
							$obj->id = $value['id'];
							// Delete Avatar
							$this->deleteEventAvatar($obj, true);
							// Delete feature
							$featured->delete($value['id']);
						}
					} else {
						// Delete avatar
						$this->deleteEventAvatar($eventData);
						// Delete featuer
						$featured->delete($eventId);
					}


					$html = '<div class=\"info\" style=\"display: none;\">' . JText::_('COM_COMMUNITY_EVENTS_DELETED') . '</div>';
					$response->addScriptCall('joms.jQuery("' . $html . '").prependTo("#community-wrap").fadeIn();');
					$response->addScriptCall('joms.jQuery("#community-groups-wrap").fadeOut();');

					$content = JText::_('COM_COMMUNITY_EVENTS_DELETED');

					//trigger for onGroupDelete
					$this->triggerEvents('onAfterEventDelete', $eventData);

					$this->cacheClean(array(COMMUNITY_CACHE_TAG_FRONTPAGE, COMMUNITY_CACHE_TAG_EVENTS, COMMUNITY_CACHE_TAG_EVENTS_CAT, COMMUNITY_CACHE_TAG_ACTIVITIES));
				} else {
					$content = JText::_('COM_COMMUNITY_EVENTS_DELETING_ERROR');
				}

				$redirectURL = CRoute::_('index.php?option=com_community&view=events&task=myevents&userid=' . $my->id);

				$actions = '<input type="button" class="button" id="eventDeleteDone" onclick="cWindowHide(); window.location=\'' . $redirectURL . '\';" value="' . JText::_('COM_COMMUNITY_DONE_BUTTON') . '"/>';

				$response->addScriptCall('cWindowAddContent', $content, $actions);

				break;
			default:
				break;
		}

		return $response->sendResponse();
	}

	public function deleteEventAvatar($eventData, $series = false)
	{
		jimport('joomla.filesystem.file');

		$model = CFactory::getModel('events');

		$avatarInused = $model->isImageInUsed($eventData->avatar, 'avatar', $eventData->id, $series);
		if ($eventData->avatar != "components/com_community/assets/eventAvatar.png" && !empty($eventData->avatar) && !$avatarInused) {
			$path = explode('/', $eventData->avatar);

			$file = JPATH_ROOT . '/' . $path[0] . '/' . $path[1] . '/' . $path[2] . '/' . $path[3];
			if (JFile::exists($file)) {
				JFile::delete($file);
			}
		}

		$thumbInused = $model->isImageInUsed($eventData->thumb, 'thumb', $eventData->id, $series);
		if ($eventData->thumb != "components/com_community/assets/event_thumb.png" && !empty($eventData->avatar) && !$thumbInused) {
			//$path = explode('/', $eventData->avatar);
			//$file = JPATH_ROOT .'/'. $path[0] .'/'. $path[1] .'/'. $path[2] .'/'. $path[3];
			$file = JPATH_ROOT . '/' . CString::str_ireplace('/', '/', $eventData->thumb);
			if (JFile::exists($file)) {
				JFile::delete($file);
			}
		}

	}

	/**
	 * Unblock this user for this event
	 */
	public function ajaxUnblockGuest($userid, $eventid)
	{
		$my = CFactory::getUser();



		$response = new JAXResponse();

		$filter = JFilterInput::getInstance();
		$eventId = $filter->clean($eventId, 'int');
		$userid = $filter->clean($userid, 'int');

		// @todo: caller needs to be admin
		$model = CFactory::getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventid);

		// Make sure I am the group admin
		if ($event->isAdmin($my->id)) {
			// Make sure the user is not an admin
			if (COwnerHelper::isCommunityAdmin($userid)) {
				// Should not require exact string since it should never
				// gets executed unless user try to inject ajax code
				$response->addAlert(JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN'));
			} else {
				$guest = JTable::getInstance('EventMembers', 'CTable');
				$keys = array('eventId'=>$eventId,'memberId'=>$userid);
				$guest->load($keys);

				$guest->status = COMMUNITY_EVENT_STATUS_MAYBE;
				$guest->store();

				// Update event stats count
				$event->updateGuestStats();
				$event->store();

				$header = JText::_('COM_COMMUNITY_EVENTS_UNBLOCK');
				$message = JText::_('COM_COMMUNITY_EVENTS_UNBLOCKED_MESSAGE');

				$response->addAssign('cwin_logo', 'innerHTML', $header);

				$actions = '<button  class="button" onclick="window.location.reload()">' . JText::_('COM_COMMUNITY_BUTTON_CLOSE_BUTTON') . '</button>';

				$response->addScriptCall('cWindowAddContent', $message, $actions);
			}
		} else {
			$response->addAlert(JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN'));
		}

		return $response->sendResponse();
	}

	/**
	 * Block this user from this event
	 */
	public function ajaxBlockGuest($userid, $eventid)
	{
		$my = CFactory::getUser();



		$response = new JAXResponse();

		$filter = JFilterInput::getInstance();
		$eventId = $filter->clean($eventId, 'int');
		$userid = $filter->clean($userid, 'int');

		// @todo: caller needs to be admin
		$model = CFactory::getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventid);

		$actions = '';
		// Make sure I am the group admin
		if ($event->isAdmin($userid) || COwnerHelper::isCommunityAdmin($my->id)) {
			$guest = JTable::getInstance('EventMembers', 'CTable');
			$keys = array('eventId'=>$eventid,'memberId'=>$userid);

			$guest->load($keys);

			// Set status to "BLOCKED"
			$guest->status = COMMUNITY_EVENT_STATUS_BLOCKED;
			$guest->store();

			// Update event stats count
			$event->updateGuestStats();
			$event->store();

			$header = JText::_('COM_COMMUNITY_EVENTS_BLOCK');
			$message = JText::_('COM_COMMUNITY_EVENTS_BLOCKED_MESSAGE');

			$response->addAssign('cwin_logo', 'innerHTML', $header);

			$actions = '<button  class="button" onclick="window.location.reload()">' . JText::_('COM_COMMUNITY_BUTTON_CLOSE_BUTTON') . '</button>';
		} else {
			$message = JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN');

		}

		$response->addScriptCall('cWindowAddContent', $message, $actions);

		return $response->sendResponse();
	}

	/**
	 * AJAX remove user from event
	 *
	 */
	public function ajaxRemoveGuest($userid, $eventid)
	{
		$my = CFactory::getUser();



		$response = new JAXResponse();

		$filter = JFilterInput::getInstance();
		$userid = $filter->clean($userid, 'int');
		$eventId = $filter->clean($eventid, 'int');

		$model = CFactory::getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventid);

		// Site admin can remove guest
		// Event creator can remove guest
		// The guest himself can remove himself
		if ($event->isAdmin($my->id) || COwnerHelper::isCommunityAdmin($my->id) || $my->id == $userid) {
			// Delete guest from event
			$event->removeGuest($userid, $eventid);

			// Update event stats count
			$event->updateGuestStats();
			$event->store();

			$message = JText::_('COM_COMMUNITY_EVENTS_GUEST_REMOVED');
		} else {
			$message = JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN');
		}

		$header = JText::_('COM_COMMUNITY_EVENTS_REMOVE_GUEST');
		$actions = '<button class="button" onclick="window.location.reload()">' . JText::_('COM_COMMUNITY_BUTTON_CLOSE_BUTTON') . '</button>';

		$response->addAssign('cwin_logo', 'innerHTML', $header);
		$response->addScriptCall('cWindowAddContent', $message, $actions);

		return $response->sendResponse();
	}

	/**
	 *Ajax remove guest confirmation prompt
	 *
	 */
	public function ajaxConfirmRemoveGuest($userid, $eventid)
	{

		$objResponse = new JAXResponse();

		$filter = JFilterInput::getInstance();
		$userid = $filter->clean($userid, 'int');
		$eventId = $filter->clean($eventid, 'int');

		// Get html
		$user = CFactory::getUser($userid);

		ob_start();
		?>
    <p><?php echo JText::sprintf('COM_COMMUNITY_EVENTS_REMOVE_GUEST_WARNING', $user->getDisplayName()); ?></p>
    <br/>
    <input type="checkbox" name="block"/><?php echo JText::_('COM_COMMUNITY_ALSO_BLOCK_GUEST'); ?>
	<?php
		$html = ob_get_contents();
		ob_end_clean();

		// Get action
		ob_start();
		?>
    <button class="button" onclick="joms.events.removeGuest(<?php echo $userid; ?>, <?php echo $eventid; ?>)"
            name="yes">
		<?php echo JText::_('COM_COMMUNITY_YES_BUTTON'); ?>
    </button>

    <button class="button" onclick="cWindowHide();" name="no">
		<?php echo JText::_('COM_COMMUNITY_NO_BUTTON'); ?>
    </button>
	<?php
		$actions = ob_get_contents();
		ob_end_clean();

		$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('COM_COMMUNITY_EVENTS_REMOVE_GUEST'));
		$objResponse->addScriptCall('cWindowAddContent', $html, $actions);

		return $objResponse->sendResponse();
	}

	/**
	 *AJAX confirm block guest
	 *
	 */
	public function ajaxConfirmBlockGuest($userid, $eventid)
	{
		$response = new JAXResponse();

		$filter = JFilterInput::getInstance();
		$userid = $filter->clean($userid, 'int');
		$eventId = $filter->clean($eventId, 'int');

		$header = JText::_('COM_COMMUNITY_EVENTS_BLOCK');
		$message = JText::_('COM_COMMUNITY_EVENTS_BLOCK_WARNING');

		$actions = '<button class="button" onclick="joms.events.blockGuest(' . $userid . ',' . $eventid . ');">' . JText::_('COM_COMMUNITY_YES') . '</button>';
		$actions .= '&nbsp;<button class="button" onclick="cWindowHide();">' . JText::_('COM_COMMUNITY_NO') . '</button>';

		$response->addAssign('cwin_logo', 'innerHTML', $header);
		$response->addScriptCall('cWindowAddContent', $message, $actions);

		return $response->sendResponse();
	}

	/**
	 *AJAX confirm unblock guest
	 *
	 */
	public function ajaxConfirmUnblockGuest($userid, $eventid)
	{
		$response = new JAXResponse();

		$filter = JFilterInput::getInstance();
		$userid = $filter->clean($userid, 'int');
		$eventId = $filter->clean($eventId, 'int');

		$header = JText::_('COM_COMMUNITY_EVENTS_UNBLOCK');
		$message = JText::_('COM_COMMUNITY_EVENTS_UNBLOCK_WARNING');
		$actions = '<button  class="button" onclick="joms.events.unblockGuest(' . $userid . ',' . $eventid . ');">' . JText::_('COM_COMMUNITY_YES') . '</button>';
		$actions .= '&nbsp;<button class="button" onclick="cWindowHide();">' . JText::_('COM_COMMUNITY_NO') . '</button>';

		$response->addAssign('cwin_logo', 'innerHTML', $header);
		$response->addScriptCall('cWindowAddContent', $message, $actions);

		return $response->sendResponse();
	}

	/**
	 * Ajax function to join an event invitation
	 *
	 **/
	public function ajaxJoinInvitation($eventId)
	{
		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}

		$objResponse = new JAXResponse();
		$my = CFactory::getUser();

		$filter = JFilterInput::getInstance();
		$eventId = $filter->clean($eventId, 'int');

		// Get events model
		$model = CFactory::getModel('events');
		$event = JTable::getInstance('Event', 'CTable');

		// Check the event availability
		if ($event->load($eventId)) {
			$guest = JTable::getInstance('EventMembers', 'CTable');
			$keys = array('eventId'=>$event->id,'memberId'=>$my->id);
			$guest->load($keys);

			// Set status to "CONFIRMED"
			$guest->status = COMMUNITY_EVENT_STATUS_ATTEND;
			$guest->store();

			// Update event stats count
			$event->updateGuestStats();
			$event->store();

			//CFactory::load( 'helpers' , 'event' );
			$handler = CEventHelper::getHandler($event);

			// Activity stream purpose if the event is a public event
			if ($handler->isPublic()) {
				$actor = $my->id;
				$target = 0;
				$content = '';
				$cid = $event->id;
				$app = 'events';
				$act = $handler->getActivity('events.join', $actor, $target, $content, $cid, $app);
				$act->eventid = $event->id;

				$params = new CParameter('');
				$action_str = 'events.join';
				$params->set('eventid', $event->id);
				$params->set('action', $action_str);
				$params->set('event_url', 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);

				// Add activity logging

				CActivityStream::add($act, $params->toString());
			}
			$url = $handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);
			$objResponse->addScriptCall('joms.jQuery("#events-invite-' . $event->id . '").html("<span class=\"community-invitation-message\">' . JText::sprintf('COM_COMMUNITY_EVENTS_ACCEPTED', $event->title, $url) . '</span>")');
		} else {
			$objResponse->addScriptCall('joms.jQuery("#events-invite-' . $event->id . '").html("<span class=\"community-invitation-message\">' . JText::_('COM_COMMUNITY_EVENTS_DELETED_ERROR') . '</span>")');
		}
		$this->cacheClean(array(COMMUNITY_CACHE_TAG_EVENTS, COMMUNITY_CACHE_TAG_ACTIVITIES));

		return $objResponse->sendResponse();
	}

	/**
	 * Ajax function to reject an event invitation
	 *
	 **/
	public function ajaxRejectInvitation($eventId)
	{
		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}

		$objResponse = new JAXResponse();
		$my = CFactory::getUser();

		$filter = JFilterInput::getInstance();
		$eventId = $filter->clean($eventId, 'int');

		// Get events model
		$model = CFactory::getModel('events');
		$event = JTable::getInstance('Event', 'CTable');

		// Check the event availability
		if ($event->load($eventId)) {
			$guest = JTable::getInstance('EventMembers', 'CTable');
			$keys = array('eventId'=>$event->id,'memberId'=>$my->id);
			$guest->load($keys);

			// Set status to "REJECTED"
			$guest->status = COMMUNITY_EVENT_STATUS_WONTATTEND;
			$guest->store();

			// Update event stats count
			$event->updateGuestStats();
			$event->store();

			$url = CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);

			$objResponse->addScriptCall('joms.jQuery("#events-invite-' . $event->id . '").html("<span class=\"community-invitation-message\">' . JText::sprintf('COM_COMMUNITY_EVENTS_REJECTED', $event->title, $url) . '</span>")');
		} else {
			$objResponse->addScriptCall('joms.jQuery("#events-invite-' . $event->id . '").html("<span class=\"community-invitation-message\">' . JText::_('COM_COMMUNITY_EVENTS_DELETED_ERROR') . '</span>")');
		}

		return $objResponse->sendResponse();
	}

	/**
	 * Ajax retreive Featured Events Information
	 * @since 2.4
	 */
	public function ajaxShowEventFeatured($eventId, $allday)
	{
		$my = CFactory::getUser();
		$objResponse = new JAXResponse();
		//CFactory::load( 'models' , 'events' );
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		// Get event link

		// Get Avatar
		$avatar = $event->getAvatar('avatar');

		/// Event date
		$config = CFactory::getConfig();
		$format = ($config->get('eventshowampm')) ? JText::_('COM_COMMUNITY_EVENTS_TIME_FORMAT_12HR') : JText::_('COM_COMMUNITY_EVENTS_TIME_FORMAT_24HR');

		$startDate = $event->getStartDate(false);
		$endDate = $event->getEndDate(false);
		$allday = false;

		if (($startDate->format('%Y-%m-%d') == $endDate->format('%Y-%m-%d')) && $startDate->format('%H:%M:%S') == '00:00:00' && $endDate->format('%H:%M:%S') == '23:59:59') {
			$format = JText::_('COM_COMMUNITY_EVENT_TIME_FORMAT_LC1');
			$allday = true;
		}

		if ($allday) {
			$eventDate = JText::sprintf('COM_COMMUNITY_EVENTS_ALLDAY_DATE', CTimeHelper::getFormattedTime($event->startdate, $format));
		} else {
			$eventDate = JText::sprintf('COM_COMMUNITY_EVENTS_DURATION', CTimeHelper::getFormattedTime($event->startdate, $format), CTimeHelper::getFormattedTime($event->enddate, $format));
		}

		// Get event link
		$eventLink = CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);


		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);
		$now = new JDate();
		$isPastEvent = ($event->getEndDate(false)->toSql() < $now->toSql()) ? true : false;
		// Get RSVP
		$rsvp = '';
		if ($my->id != 0) {
			if ($handler->isAllowed() && !$isPastEvent) {
				$rsvp = '<select onchange="joms.events.submitRSVP(' . $event->id . ',this)">';
				if ($event->getMemberStatus($my->id) == 0) {
					$rsvp .= '<option class="noResponse" selected="selected">' . JText::_('COM_COMMUNITY_GROUPS_INVITATION_RESPONSE') . '</option>';
				}
				$rsvp .= '<option class="attend"';
				if ($event->getMemberStatus($my->id) == COMMUNITY_EVENT_STATUS_ATTEND) {
					$rsvp .= ' selected="selected" ';
				}
				$rsvp .= 'value="' . COMMUNITY_EVENT_STATUS_ATTEND . '">' . JText::_('COM_COMMUNITY_EVENTS_RSVP_ATTEND') . '</option>';
				$rsvp .= '<option class="notAttend"';
				if ($event->getMemberStatus($my->id) >= COMMUNITY_EVENT_STATUS_WONTATTEND) {

					$rsvp .= 'selected="selected"';
				}
				$rsvp .= 'value="' . COMMUNITY_EVENT_STATUS_WONTATTEND . '">' . JText::_('COM_COMMUNITY_EVENTS_RSVP_NOT_ATTEND') . '</option>';
				$rsvp .= '</select>';
			} else {
				$rsvp = JText::_('COM_COMMUNITY_EVENTS_PASSED');
			}
		}

		// Get unfeature icon
		$eventUnfeature = '<a class="album-action remove-featured" title="' . JText::_('COM_COMMUNITY_REMOVE_FEATURED') . '" onclick="joms.featured.remove(\'' . $event->id . '\',\'events\');" href="javascript:void(0);">' . JText::_('COM_COMMUNITY_REMOVE_FEATURED') . '</a>';

		// Get like
		//CFactory::load( 'libraries' , 'like' );
		$likes = new CLike();
		$likesHTML = $likes->getHTML('events', $eventId, $my->id);
		$objResponse->addScriptCall('updateEvent', $eventId, $event->title, $event->getCategoryName(), $likesHTML, $avatar, $eventDate, $event->location, $event->summary, $eventLink, $rsvp, $eventUnfeature);
		$objResponse->sendResponse();
	}

	/**
	 * Method is called from the reporting library. Function calls should be
	 * registered here.
	 *
	 * return    String    Message that will be displayed to user upon submission.
	 **/
	public function reportEvent($link, $message, $eventId)
	{
		//CFactory::load( 'libraries' , 'reporting' );

		$report = new CReportingLibrary();
		$report->createReport(JText::_('COM_COMMUNITY_EVENTS_BAD'), $link, $message);

		$action = new stdClass();
		$action->label = 'COM_COMMUNITY_EVENTS_UNPUBLISH';
		$action->method = 'events,unpublishEvent';
		$action->parameters = $eventId;
		$action->defaultAction = true;

		$report->addActions(array($action));

		return JText::_('COM_COMMUNITY_REPORT_SUBMITTED');
	}

	public function unpublishEvent($eventId)
	{
		//CFactory::load( 'models' , 'events' );

		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);
		$event->published = '0';
		$event->store();

		return JText::_('COM_COMMUNITY_EVENTS_UNPUBLISHED');
	}

	/**
	 * Displays the default events view
	 **/
	public function display($cacheable=false, $urlparams=false)
	{
		$config = CFactory::getConfig();

		if (!$config->get('enableevents')) {
			echo JText::_('COM_COMMUNITY_EVENTS_DISABLED');
			return;
		}

		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);

		echo $view->get(__FUNCTION__);
	}

	/*
	 * Export an event
	 */
	public function export()
	{
		$config = CFactory::getConfig();

		if (!$config->get('enableevents')) {
			echo JText::_('COM_COMMUNITY_EVENTS_DISABLED');
			return;
		}

		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);

		$eventId = JRequest::getInt('eventid', '0');

		$model = $this->getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		echo $view->get(__FUNCTION__, $event);
		exit;
	}

	/**
	 * A user decided to ignore an event
	 * Banned used cannot be ignored
	 */
	public function ignore()
	{
		$eventId = JRequest::getInt('eventid', '', 'POST');
		CError::assert($eventId, '', '!empty', __FILE__, __LINE__);

		$model = $this->getModel('events');
		$my = CFactory::getUser();

		if ($my->id == 0) {
			return $this->blockUnregister();
		}

		$eventMembers = JTable::getInstance('EventMembers', 'CTable');
		$keys = array('eventId'=>$eventId,'memberId'=>$my->id);
		$eventMembers->load($keys);

		$message = '';

		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		// Add user in ignore list
		if ($eventMembers->id != 0) {
			$eventMembers->status = COMMUNITY_EVENT_STATUS_IGNORE;
			$eventMembers->store();

			//now we need to update the events various count.
			$event->updateGuestStats();
			$event->store();
			$message = JText::_('COM_COMMUNITY_EVENTS_IGNORE_MESSAGE');
		}

		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);
		$mainframe = JFactory::getApplication();
		$mainframe->redirect($handler->getIgnoreRedirectLink(), $message);
	}

	/**
	 * Method is used to receive POST requests from specific user
	 * that wants to join a event
	 *
	 * @return    void
	 **/
	public function requestInvite()
	{
		$mainframe = JFactory::getApplication();
		$eventId = JRequest::getInt('eventid', '', 'POST');

		// Add assertion to the event id since it must be specified in the post request
		CError::assert($eventId, '', '!empty', __FILE__, __LINE__);

		// Get the current user's object
		$my = CFactory::getUser();

		if ($my->id == 0) {
			return $this->blockUnregister();
		}

		// Load necessary tables
		$model = CFactory::getModel('events');

		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		$eventMembers = JTable::getInstance('EventMembers', 'CTable');
		$keys = array('eventId'=>$eventId,'memberId'=>$my->id);
		$eventMembers->load($keys);
		$isMember = $eventMembers->exists();

		if ($isMember) {
			$url = CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid=' . $eventId, false);
			$mainframe->redirect($url, JText::_('COM_COMMUNITY_EVENTS_ALREADY_MEMBER'));
		} else {

			// Set the properties for the members table
			$eventMembers->eventid = $event->id;
			$eventMembers->memberid = $my->id;

			//CFactory::load( 'helpers' , 'owner' );

			//required approval
			//$eventMembers->approval	= 1;

			//@todo: need to set the privileges
			$date = JFactory::getDate();
			$eventMembers->status = COMMUNITY_EVENT_STATUS_REQUESTINVITE; // for now just set it to approve for the demo purpose
			$eventMembers->permission = '3'; //always a member
			$eventMembers->created = $date->toSql();

			// Get the owner data
			$owner = CFactory::getUser($event->creator);

			$store = $eventMembers->store();

			// Add assertion if storing fails
			CError::assert($store, true, 'eq', __FILE__, __LINE__);
			// Build the URL.
			//$url	= CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $group->id ) , true );
			$url = CRoute::getExternalURL('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false);

			// Add notification
			//CFactory::load( 'libraries' , 'notification' );
			//CFactory::load( 'helpers' , 'event' );

			$emails = array();
			$emails[] = $owner->id;

			$params = new CParameter('');
			$params->set('url', 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);
			$params->set('event', $event->title);
			$params->set('event_url', 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);
			CNotificationLibrary::add('event_join_request', $my->id, $emails, JText::sprintf('COM_COMMUNITY_EVENT_JOIN_REQUEST_SUBJECT'), '', 'events.joinrequest', $params);


			// Notify admin via email if user is unapproved or approved.
			// @todo: If user is not approve yet, display links to approve , reject
			$tmplData = array();
			$tmplData['url'] = $url;
			$tmplData['event'] = $event->title;
			$tmplData['user'] = $my->getDisplayName();
			$tmplData['status'] = $eventMembers->status;

			//trigger for on event request invite
			$this->triggerEvents('onEventRequestInvite', $event, $my->id);


			$mainframe->redirect($url, JText::_('COM_COMMUNITY_EVENTS_INVITATION_REQUEST_SUCCESS'));
		}
	}

	/*
	 * Method to display myevent page
	 */
	public function myevents()
	{
		$my = JFactory::getUser();
		if ($my->id == 0) {
			return $this->blockUnregister();
		}
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);

		echo $view->get(__FUNCTION__);
	}

	/*
	 * Method to display myinvites page
	 */
	public function myinvites()
	{
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);

		echo $view->get(__FUNCTION__);
	}

	/*
	 * Method to display pastevents page
	 */
	public function pastevents()
	{
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);

		echo $view->get(__FUNCTION__);
	}

	public function saveImport()
	{
		$mainframe = JFactory::getApplication();
		$jinput 	= $mainframe->input;
		$events = $jinput->get('events', '', 'NONE');
		$my = CFactory::getUser();
		$config = CFactory::getConfig();
		$groupId = JRequest::getInt('groupid', 0, 'GET');
		$groupLink = $groupId > 0 ? '&groupid=' . $groupId : '';

		//CFactory::load( 'helpers' , 'event' );

		if (!empty($events)) {
			$errors = array();

			foreach ($events as $index) {
				$event = JTable::getInstance('Event', 'CTable');
				$event->title = JRequest::getString('event-' . $index . '-title', '');
				$event->startdate = JRequest::getString('event-' . $index . '-startdate', '');
				$event->enddate = JRequest::getString('event-' . $index . '-enddate', '');
				$event->offset = JRequest::getString('event-' . $index . '-offset', '0');
				$event->description = JRequest::getString('event-' . $index . '-description', '');
				$event->catid = JRequest::getString('event-' . $index . '-catid', '');
				$event->location = JRequest::getString('event-' . $index . '-location', '');
				$event->allowinvite = JRequest::getInt('event-' . $index . '-invite');
				$event->creator = $my->id;
				$event->summary = JRequest::getString('event-' . $index . '-summary', '');
				$event->ticket = JRequest::getInt('event-' . $index . '-ticket', 0);
				$event->repeat = JRequest::getString('event-' . $index . '-repeat', 0);
				$event->repeatend = JRequest::getString('event-' . $index . '-repeatend');
				$postData['limit'] = JRequest::getInt('event-' . $index . '-limit', 0);
				$error = array();

				$handler = CEventHelper::getHandler($event);
				$event->contentid = $handler->getContentId();
				$event->type = $handler->getType();

				if (empty($event->title)) {
					$error[] = JText::_('COM_COMMUNITY_EVENTS_TITLE_ERROR');
				}

				if (empty($event->startdate)) {
					$error[] = JText::_('COM_COMMUNITY_EVENTS_START_DATE_ERROR');
				} else {
					$event->startdate = CTimeHelper::getFormattedUTC($event->startdate, $event->offset);
				}

				if (empty($event->enddate)) {
					$error[] = JText::_('COM_COMMUNITY_EVENTS_END_DATE_ERROR');
				} else {
					$event->enddate = CTimeHelper::getFormattedUTC($event->enddate, $event->offset);
				}

				if (empty($event->catid)) {
					$error[] = JText::_('COM_COMMUNITY_EVENTS_CATEGORY_ERROR');
				}

				$eventChild = array();
				// check event recurrence limit.
				if (!empty($event->repeat)) {
					$repeatLimit = 'COMMUNITY_EVENT_RECURRING_LIMIT_' . strtoupper($event->repeat);
					if (defined($repeatLimit)) {
						$eventChild = $this->_generateRepeatList($event, $postData);
						if (count($eventChild) > constant($repeatLimit)) {
							$error[] = $event->title . ' - ' . sprintf(JText::_('COM_COMMUNITY_EVENTS_REPEAT_LIMIT_ERROR'), constant($repeatLimit));
						}
					}
				}

				if (!empty($error)) {
					$errors[] = $error;
				}

				//@rule: If event moderation is enabled, event should be unpublished by default
				$event->published = $this->isPublished();
				$event->created = JFactory::getDate()->toSql();

				// @rule: Only store event when no errors.
				if (empty($error)) {
					$event->store();
					// Save event member
					if (!$event->isRecurring()) {

						$this->_saveMember($event);

						// Increment the member count.
						$event->updateGuestStats();

						// Apparently the updateGuestStats does not store the item. Need to store it again.
						$event->store();
					} else {
						$event->parent = $event->id;
					}
					// Save recurring event's child.
					$this->_saveRepeatChild($event, $eventChild, true, $postData);

					// Create event activity stream.
					$this->_addActivityStream($event);

					//add notification: New group event is added
					$this->_addGroupNotification($event);

				}
			}

			if (!empty($errors)) {
				foreach ($errors as $err) {
					$errorMessage .= implode(',', $err) . "\n";
				}

				$mainframe->redirect(CRoute::_('index.php?option=com_community&view=events&task=import' . $groupLink, false), $errorMessage, 'error');
			} else {
				$message = !$event->isPublished() ? JText::_('COM_COMMUNITY_EVENTS_IMPORT_SUCCESS_MODERATED') : JText::_('COM_COMMUNITY_EVENTS_IMPORT_SUCCESS');

				$mainframe->redirect(CRoute::_('index.php?option=com_community&view=events&task=import' . $groupLink, false), $message);
			}
		} else {
			$mainframe->redirect(CRoute::_('index.php?option=com_community&view=events&task=import' . $groupLink, false), JText::_('COM_COMMUNITY_EVENTS_NOT_SELECTED'), 'error');
		}
	}

	/**
	 * Method to display import event form
	 *
	 **/
	public function import()
	{
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);
		$events = array();
		$mainframe = JFactory::getApplication();
		$jinput 	= $mainframe->input;
		$config = CFactory::getConfig();
		$groupId = JRequest::getInt('groupid', 0, 'GET');
		$groupLink = $groupId > 0 ? '&groupid=' . $groupId : '';

		if (!$config->get('event_import_ical')) {
			$mainframe->redirect(CRoute::_('index.php?option=com_community&view=events' . $groupLink, false), JText::_('COM_COMMUNITY_EVENTS_IMPORT_DISABLED'), 'error');
		}

		if (JRequest::getMethod() == 'POST') {
			$type = $jinput->get('type', 'file', 'NONE');
			$valid = false;

			if ($type == 'file') {
				$fileFilter = new JInput($_FILES);
				$file = $fileFilter->get('file', '', 'array');
				//$file = JRequest::getVar('file', '', 'FILES');
				$valid = $file['type'] == 'text/calendar' || $file['type'] == 'application/octet-stream';
				$path = $file['tmp_name'];

				if ($valid && JFile::exists($path)) {
					$contents = JFile::read($path);
				}
			}

			if ($type == 'url') {
				//CFactory::load( 'helpers' , 'remote' );
				$file = $jinput->get('url', '', 'STRING');
				$contents = CRemoteHelper::getContent($file, true);
				preg_match('/Content-Type: (.*)/im', $contents, $matches);
				$valid = isset($matches[1]) && stripos(JString::trim($matches[1]), 'text/calendar') !== false;
			}

			//CFactory::load( 'libraries' , 'ical' );
			$ical = new CICal($contents);

			if ($ical->init() && $valid) {
				$events = $ical->getItems();
			} else {
				$mainframe->redirect(CRoute::_('index.php?option=com_community&view=events&task=import' . $groupLink, false), JText::_('Unable to load .ics file'), 'error');
			}
		}
		echo $view->get(__FUNCTION__, $events);
	}

	/*
	 * Method to create an event
	 */
	public function create()
	{
		$document = JFactory::getDocument();
		$mainframe = JFactory::getApplication();
		$jinput = $mainframe->input;
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);
		$eventId = JRequest::getInt('eventid', '0');
		$config = CFactory::getConfig();

		$model = $this->getModel('events');
		$event = JTable::getInstance('Event', 'CTable');

		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);

		$groupId = JRequest::getInt('groupid', '', 'REQUEST');
		$my = CFactory::getUser();
		$isBanned = false;

		if ($groupId) {
			$group = JTable::getInstance('Group', 'CTable');
			$group->load($groupId);

			// Check if the current user is banned from this group
			$isBanned = $group->isBanned($my->id);
		}

		if ($my->id == 0) {
			return $this->blockUnregister();
		}

		if (!$handler->creatable() || $isBanned) {
			echo JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN');
			return;
		}


		if (CLimitsHelper::exceededEventCreation($my->id)) {
			$eventLimit = $config->get('eventcreatelimit');
			echo JText::sprintf('COM_COMMUNITY_EVENTS_LIMIT', $eventLimit);
			return;
		}

		if ($jinput->post->get('action', '', 'STRING') == 'save') {
			$eid = $this->save($event);

			if ($eid !== FALSE) {
				$mainframe = JFactory::getApplication();

				$event = JTable::getInstance('Event', 'CTable');
				$event->load($eid);

				//trigger for onGroupCreate
				$this->triggerEvents('onEventCreate', $event);

				//CFactory::load( 'helpers' , 'event' );
				$url = CEventHelper::getHandler($event)->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false);
				$message = JText::sprintf('COM_COMMUNITY_EVENTS_CREATED_NOTICE', $event->title);

				if (!$event->isPublished()) {
					$message = JText::sprintf('COM_COMMUNITY_EVENTS_MODERATION_NOTICE', $event->title);
				}
				$mainframe->redirect($url, $message);
				return;
			}
		}

		/* Begin: COPY EVENT */
		if (!empty($eventId)) {
			$event->load($eventId);

			if ($my->id != $event->creator && $event->permission == COMMUNITY_PRIVATE_EVENT) {
				$url = CEventHelper::getHandler($event)->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false);
				$message = JText::_('COM_COMMUNITY_EVENTS_PRIVITE_COPY_ERROR');
				$mainframe->redirect($url, $message);
				return;
			}

			// Consider system will create new id
			$event->id = null;
		} else
		{
			// set event local date.
			$date = CTimeHelper::getLocaleDate();
			$event->startdate = $date->format('Y-m-d');
			$event->enddate = $event->startdate;
		}
		/* End: COPY EVENT */

		echo $view->get(__FUNCTION__, $event);

	}

	public function ajaxCreate($postData, $objResponse)
	{
		$objResponse = new JAXResponse();

		$filter = JFilterInput::getInstance();
		$postData = $filter->clean($postData, 'array');

		//prevent XSS injection
		foreach ($postData as &$data) {
			$data = strip_tags($data);
		}

		$config = CFactory::getConfig();
		$my = CFactory::getUser();

		if (!JRequest::checkToken()) {
			$objResponse->addScriptCall('__throwError', JText::_('COM_COMMUNITY_INVALID_TOKEN'));
			$objResponse->sendResponse();
		}


		if (CLimitsHelper::exceededEventCreation($my->id)) {
			$eventLimit = $config->get('eventcreatelimit');
			$objResponse->addScriptCall('__throwError', JText::sprintf('COM_COMMUNITY_EVENTS_LIMIT', $eventLimit));
			$objResponse->sendResponse();
		}

		//CFactory::load( 'helpers' , 'event' );
		$event = JTable::getInstance('Event', 'CTable');
		$event->load();

		if ($postData['element'] == 'groups')
			$event->contentid = $postData['target'];

		$handler = CEventHelper::getHandler($event);

		if (!$handler->creatable()) {
			$objResponse->addScriptCall('__throwError', JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN'));
			$objResponse->sendResponse();
		}

		// Format startdate and eendate with time before we bind into event object
		$this->_formatStartEndDate($postData);

		$event->bind($postData);

		if (empty($event->title)) {
			$objResponse->addScriptCall('__throwError', JText::_('COM_COMMUNITY_EVENTS_TITLE_ERROR'));
			$objResponse->sendResponse();
		}

		if (empty($event->location)) {
			$objResponse->addScriptCall('__throwError', JText::_('COM_COMMUNITY_EVENTS_LOCATION_ERR0R'));
			$objResponse->sendResponse();
		}

		// @rule: Start date cannot be empty
		if (empty($event->startdate)) {
			$objResponse->addScriptCall('__throwError', JText::_('COM_COMMUNITY_EVENTS_ENDDATE_ERROR'));
			$objResponse->sendResponse();
		}

		// @rule: End date cannot be empty
		if (empty($event->enddate)) {
			$objResponse->addScriptCall('__throwError', JText::_('COM_COMMUNITY_EVENTS_ENDDATE_ERROR'));
			$objResponse->sendResponse();
		}

		require_once(JPATH_COMPONENT . '/helpers/time.php');

		if (!isset($postData['allday'])) {
			$postData['allday'] = 0;
		}

		if (CTimeHelper::timeIntervalDifference($event->startdate, $event->enddate) > 0) {
			$objResponse->addScriptCall('__throwError', JText::_('COM_COMMUNITY_EVENTS_STARTDATE_GREATER_ERROR'));
			$objResponse->sendResponse();
		}

		// @rule: Event must not end in the past
		$now = CTimeHelper::getLocaleDate();

		// if all day event.
		$isToday = false;
		if ($postData['allday'] == '1') {
			$isToday = date("Y-m-d", strtotime($event->enddate)) == date("Y-m-d", strtotime($now->toSql(true))) ? true : $isToday;
		}

		if (CTimeHelper::timeIntervalDifference($now->toSql(true), $event->enddate) > 0 && !$isToday) {
			$objResponse->addScriptCall('__throwError', JText::_('COM_COMMUNITY_EVENTS_ENDDATE_GREATER_ERROR'));
			$objResponse->sendResponse();
		}

		$eventChild = array();

		// check event recurrence limit.
		if (!empty($event->repeat)) {
			$repeatLimit = 'COMMUNITY_EVENT_RECURRING_LIMIT_' . strtoupper($event->repeat);
			if (defined($repeatLimit)) {
				$eventChild = $this->_generateRepeatList($event, $postData);
				if (count($eventChild) > constant($repeatLimit)) {
					$objResponse->addScriptCall('__throwError', $event->title . ' - ' . sprintf(JText::_('COM_COMMUNITY_EVENTS_REPEAT_LIMIT_ERROR'), constant($repeatLimit)));
				}
			}
		}

		$event->creator = $my->id;

		//@rule: If event moderation is enabled, event should be unpublished by default
		$event->published = $this->isPublished();
		$event->created = JFactory::getDate()->toSql();
		$event->contentid = $handler->getContentId();
		$event->type = $handler->getType();
		$event->store();

		if (!$event->isRecurring()) {
			$this->_saveMember($event);
			// Increment the member count.
			$event->updateGuestStats();
			// Apparently the updateGuestStats does not store the item. Need to store it again.
			$event->store();
		} else {
			$event->parent = $event->id;
		}

		// Save recurring event's child.
		$this->_saveRepeatChild($event, $eventChild, true, $postData);

		$actor = $my->id;
		$target = 0;
		$content = '';
		$cid = $event->id;
		$app = 'events';
		$act = $handler->getActivity('events.create', $actor, $target, $content, $cid, $app);
		$url = $handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false, true, false);

		$act->groupid = ($event->type == 'group') ? $event->contentid : null;
		$act->eventid = $event->id;
		$act->location = $event->location;


		$act->comment_id = $event->id;
		$act->comment_type = 'groups.event';

		$act->like_id = $event->id;
		$act->like_type = 'group.event';

		$cat_url = $handler->getFormattedLink('index.php?option=com_community&view=events&task=display&categoryid=' . $event->catid, false, true, false);

		$params = new CParameter('');
		$action_str = 'events.create';
		$params->set('action', $action_str);
		$params->set('event_url', $url);
		$params->set('event_category_url', $cat_url);

		// Add activity logging

		CActivityStream::add($act, $params->toString());


		// add user points
		//CFactory::load( 'libraries' , 'userpoints' );
		CUserPoints::assignPoint($action_str);

		$this->triggerEvents('onEventCreate', $event);

		$this->cacheClean(array(COMMUNITY_CACHE_TAG_FRONTPAGE, COMMUNITY_CACHE_TAG_EVENTS, COMMUNITY_CACHE_TAG_EVENTS_CAT, COMMUNITY_CACHE_TAG_ACTIVITIES));

		return $event;
	}

	private function _formatStartEndDate(&$postData)
	{
		if (isset($postData['starttime-ampm']) && $postData['starttime-ampm'] == 'PM' && $postData['starttime-hour'] != 12) {
			$postData['starttime-hour'] = $postData['starttime-hour'] + 12;
		}

		if (isset($postData['endtime-ampm']) && $postData['endtime-ampm'] == 'PM' && $postData['endtime-hour'] != 12) {
			$postData['endtime-hour'] = $postData['endtime-hour'] + 12;
		}

		if (isset($postData['starttime-ampm']) && $postData['starttime-ampm'] == 'AM' && $postData['starttime-hour'] == 12) {
			$postData['starttime-hour'] = 0;
		}

		if (isset($postData['endtime-ampm']) && $postData['endtime-ampm'] == 'AM' && $postData['endtime-hour'] == 12) {
			$postData['endtime-hour'] = 0;
		}

		// When the All-day is selected, means the startdate & enddate should be same.
		// The time should have to start from 00:00:00 until 23:59:59
		if (array_key_exists('allday', $postData) && $postData['allday'] == '1') {
			$postData['startdate'] = $postData['startdate'] . ' 00:00:00';
			$postData['enddate'] = $postData['enddate'] . ' 23:59:59';

		} else {
			$postData['startdate'] = $postData['startdate'] . ' ' . $postData['starttime-hour'] . ':' . $postData['starttime-min'] . ':00';
			$postData['enddate'] = $postData['enddate'] . ' ' . $postData['endtime-hour'] . ':' . $postData['endtime-min'] . ':00';
		}

		unset($postData['startdatetime']);
		unset($postData['enddatetime']);
		unset($postData['starttime-hour']);
		unset($postData['starttime-min']);
		unset($postData['starttime-ampm']);
		unset($postData['endtime-hour']);
		unset($postData['endtime-min']);
		unset($postData['endtime-ampm']);
		unset($postData['privacy']);
	}

	/**
	 * Controller method responsible to display the edit task.
	 *
	 * @return    none
	 **/
	public function edit()
	{
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);
		$eventId = JRequest::getInt('eventid', '0');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		if (empty($event->id)) {
			return JError::raiseWarning(404, JText::_('COM_COMMUNITY_EVENTS_NOT_AVAILABLE_ERROR'));
		}

		$my = CFactory::getUser();

		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);

		if (!$handler->manageable()) {
			echo JText::_('COM_COMMUNITY_RESTRICTED_ACCESS');
			return;
		}

		if (JRequest::getMethod() == 'POST') {
			$eid = $this->save($event);

			if ($eid !== FALSE) {
				$mainframe = JFactory::getApplication();
				$event->load($eventId);

				//trigger for onGroupCreate
				$this->triggerEvents('onEventUpdate', $event);

				$mainframe->redirect($handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false), JText::sprintf('COM_COMMUNITY_EVENTS_UPDATE_NOTICE', $event->title));
				return;
			}

			$action_str = 'events.update';

			// add user points
			//CFactory::load( 'libraries' , 'userpoints' );
			CUserPoints::assignPoint($action_str);
		}

		$this->cacheClean(array(COMMUNITY_CACHE_TAG_EVENTS_CAT, COMMUNITY_CACHE_TAG_ACTIVITIES));
		echo $view->get(__FUNCTION__, $event);
	}

	/**
	 * A new event has been created
	 */
	public function created()
	{
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);

		echo $view->get(__FUNCTION__);
	}

	/**
	 * Send an email announcement to members
	 */
	public function announce()
	{
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);
		$my = CFactory::getUser();

		$eventId = JRequest::getInt('eventid', '0');

		$model = $this->getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);
		if (!$event->isAdmin($my->id)) {
			echo "no access";
			return;
		}

		echo $view->get(__FUNCTION__, $event);

	}

	/**
	 * Method to save the group
	 * @return false if create fail, return the group id if create is successful
	 **/
	public function save(&$event)
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('COM_COMMUNITY_INVALID_TOKEN'));

		$mainframe = JFactory::getApplication();
		$jinput 	= $mainframe->input;
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);

		if (JString::strtoupper(JRequest::getMethod()) != 'POST') {
			$view->addWarning(JText::_('COM_COMMUNITY_PERMISSION_DENIED_WARNING'));
			return false;
		}

		// Get my current data.
		$my = CFactory::getUser();
		$validated = true;
		$model = $this->getModel('events');

		$eventId = JRequest::getInt('eventid', '0');
		$isNew = ($eventId == '0') ? true : false;
		$postData = JRequest::get('post');
		$repeataction = $jinput->get('repeataction', NULL, 'NONE'); //JRequest::getVar('repeataction');

		if (!isset($postData['allday'])) {
			$postData['allday'] = 0;
		}

		//format startdate and eendate with time before we bind into event object
		$this->_formatStartEndDate($postData);

		$event->load($eventId);

		// record event original start and end date
		$postData['oldstartdate'] = $event->startdate;
		$postData['oldenddate'] = $event->enddate;

		$event->bind($postData);

		if (!array_key_exists('permission', $postData)) {
			$event->permission = 0;
		}

		if (!array_key_exists('allowinvite', $postData)) {
			$event->allowinvite = 0;
		} elseif (isset($postData['endtime-ampm']) && $postData['endtime-ampm'] == 'AM' && $postData['endtime-hour'] == 12) {
			$postData['endtime-hour'] = 00;
		}

		$inputFilter = CFactory::getInputFilter(true);

		// Despite the bind, we would still need to capture RAW description
		$event->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$event->description = $inputFilter->clean($event->description);

		// @rule: Test for emptyness
		if (empty($event->title)) {
			$validated = false;
			$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_EVENTS_TITLE_ERROR'), 'error');
		}

		if (empty($event->location)) {
			$validated = false;
			$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_EVENTS_LOCATION_ERR0R'), 'error');
		}

		// @rule: Test if the event is exists
		if ($model->isEventExist($event->title, $event->location, $event->startdate, $event->enddate, $eventId, $event->parent)) {
			$validated = false;
			$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_EVENTS_TAKEN_ERROR'), 'error');
		}

		// @rule: Description cannot be empty
		/*if( empty( $event->description ) )
		{
			$validated = false;
			$mainframe->enqueueMessage( JText::_('COM_COMMUNITY_EVENTS_TAKEN_ERROR'), 'error');
		}*/

		// @rule: Start date cannot be empty
		if (empty($event->startdate)) {
			$validated = false;
			$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_EVENTS_STARTDATE_ERROR'), 'error');
		}

		// @rule: End date cannot be empty
		if (empty($event->enddate)) {
			$validated = false;
			$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_EVENTS_ENDDATE_ERROR'), 'error');
		}

		// @rule: Number of ticket must at least be 0
		if (Jstring::strlen($event->ticket) <= 0) {
			$validated = false;
			$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_EVENTS_TICKET_EMPTY_ERROR'), 'error');
		}

		$now = CTimeHelper::getLocaleDate();

		require_once (JPATH_COMPONENT . '/helpers/time.php');
		if (CTimeHelper::timeIntervalDifference($event->startdate, $event->enddate) > 0) {
			$validated = false;
			$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_EVENTS_STARTDATE_GREATER_ERROR'), 'error');
		}

		// if all day event.
		$isToday = false;
		if ($postData['allday'] == '1') {
			$isToday = date("Y-m-d", strtotime($event->enddate)) == date("Y-m-d", strtotime($now->toSql(true))) ? true : $isToday;
		}

		// @rule: Event must not end in the past

		if (CTimeHelper::timeIntervalDifference($now->toSql(true), $event->enddate) > 0 && !$isToday && $isNew) {
			$validated = false;
			$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_EVENTS_ENDDATE_GREATER_ERROR'), 'error');
		}

		$eventChild = array();
		// check event recurrence limit.
		if (!empty($event->repeat) && ($isNew || $postData['repeataction'] == 'future')) {
			$repeatLimit = 'COMMUNITY_EVENT_RECURRING_LIMIT_' . strtoupper($event->repeat);
			if (defined($repeatLimit)) {
				$eventChild = $this->_generateRepeatList($event);
				if (count($eventChild) > constant($repeatLimit)) {
					$validated = false;
					$mainframe->enqueueMessage(sprintf(JText::_('COM_COMMUNITY_EVENTS_REPEAT_LIMIT_ERROR'), constant($repeatLimit)), 'error');
				}
			}
		}

		if ($validated) {
			// If show event timezone is disabled, we need to set the event offset to 0.
			$config = CFactory::getConfig();

			if (!$config->get('eventshowtimezone')) {
				$event->offset = 0;
			}

			// Set the default thumbnail and avatar for the event just in case
			// the user decides to skip this
			if ($isNew) {
				$event->creator = $my->id;
				$config = CFactory::getConfig();

				//@rule: If event moderation is enabled, event should be unpublished by default
				$event->published = $this->isPublished();
				$event->created = JFactory::getDate()->toSql();

				$handler = CEventHelper::getHandler($event);
				$event->contentid = $handler->getContentId();
				$event->type = $handler->getType();
			}

			$event->store();

			// Save event members
			if ($isNew && !$event->isRecurring()) {
				$this->_saveMember($event);

				// Increment the member count
				$event->updateGuestStats();
				$event->store();
			}

			if ($isNew) {
				$event->parent = !empty($event->repeat) ? $event->id : 0;
			}

			// Save recurring event's child.
			$this->_saveRepeatChild($event, $eventChild, $isNew, $postData);

			// Stream and notification
			if ($isNew) {
				// add activity stream
				$this->_addActivityStream($event);

				//add user points
				$action_str = 'events.create';
				//CFactory::load( 'libraries' , 'userpoints' );
				CUserPoints::assignPoint($action_str);

				//add notification: New group event is added
				$this->_addGroupNotification($event);
			}

			$validated = $event->id;

			$this->cacheClean(array(COMMUNITY_CACHE_TAG_FRONTPAGE, COMMUNITY_CACHE_TAG_EVENTS, COMMUNITY_CACHE_TAG_EVENTS_CAT, COMMUNITY_CACHE_TAG_ACTIVITIES));
		}

		return $validated;
	}

	public function printpopup()
	{
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);
		$id = JRequest::getInt('eventid', '', 'REQUEST');

		$event = JTable::getInstance('Event', 'CTable');
		$event->load($id);

		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);

		if (!$handler->showPrint()) {
			echo JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN');
			return;
		}

		echo $view->get(__FUNCTION__, $event);
		exit;
	}

	/**
	 * Controller method responsible to display the sendmail task
	 *
	 * @return    none
	 **/
	public function sendmail()
	{
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);
		$mainframe = JFactory::getApplication();
		$jinput 	= $mainframe->input;
		$id = JRequest::getInt('eventid', '');
		$message = JRequest::getVar('message', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$title = $jinput->get('title', '', 'STRING');
		$my = CFactory::getUser();

		//CFactory::load( 'helpers' , 'owner' );
		//CFactory::load( 'models' , 'events' );
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($id);

		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);

		if (empty($id) || !$handler->manageable()) {
			echo JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN');
			return;
		}

		if (JRequest::getMethod() == 'POST') {
			// Check for request forgeries
			JRequest::checkToken() or jexit(JText::_('COM_COMMUNITY_INVALID_TOKEN'));

			$members = $event->getMembers(COMMUNITY_EVENT_STATUS_ATTEND, null);

			$errors = false;

			if (empty($message)) {
				$errors = true;
				$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_INBOX_MESSAGE_REQUIRED'), 'error');
			}

			if (empty($title)) {
				$errors = true;
				$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_TITLE_REQUIRED'), 'error');
			}

			if (!$errors) {
				// Add notification
				//CFactory::load( 'libraries' , 'notification' );
				$emails = array();
				$total = 0;

				foreach ($members as $member) {
					$user = CFactory::getUser($member->id);

					// Do not sent email notification to self
					if ($my->id != $user->id) {
						$total += 1;
						$emails[] = $user->id;
					}
				}

				$params = new CParameter('');
				$params->set('url', $handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false, true));
				$params->set('title', $title);
				$params->set('event', $event->title);
				$params->set('event_url', $handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false, true));
				$params->set('message', $message);
				CNotificationLibrary::add('events_sendmail', $my->id, $emails, JText::sprintf('COM_COMMUNITY_EVENT_SENDMAIL_SUBJECT'), '', 'events.sendmail', $params);

				$mainframe->redirect($handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false), JText::sprintf('COM_COMMUNITY_EVENTS_EMAIL_SENT', $total));
			}
		}

		echo $view->get(__FUNCTION__);
	}

	public function viewevent()
	{
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);

		echo $view->get(__FUNCTION__);

	}

	public function viewguest()
	{

		$mainframe = JFactory::getApplication();
		$jinput 	= $mainframe->input;
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);
		$my = CFactory::getUser();

		$eventId = $jinput->request->get('eventid', '', 'INT');
		$listype = $jinput->request->get('type', '', 'NONE');

		$model = $this->getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);
		//CFactory::load( 'helpers' , 'owner' );
		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);

		// Restrict view of specific usertype to group admin only
		if (($listype == COMMUNITY_EVENT_STATUS_BLOCKED
			|| $listype == COMMUNITY_EVENT_STATUS_REQUESTINVITE
			|| $listype == COMMUNITY_EVENT_STATUS_IGNORE)
			&& !$handler->manageable()
		) {
			echo JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN');
			return;
		}

		// If an event is a secret event, non-invited user should not be able
		// to view it (can only view admins)
		if (!$handler->isPublic()) {
			$myStatus = $event->getUserStatus($my->id);
			if ($listype != 'admins' && (!$handler->manageable()
				&& ($myStatus != COMMUNITY_EVENT_STATUS_INVITED)
				&& ($myStatus != COMMUNITY_EVENT_STATUS_ATTEND)
				&& ($myStatus != COMMUNITY_EVENT_STATUS_WONTATTEND)
				&& ($myStatus != COMMUNITY_EVENT_STATUS_MAYBE)
			)
			) {
				$mainframe->redirect($handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false), JText::_('COM_COMMUNITY_PRIVATE_EVENT_NOTICE'), 'error');
				return;
			}
		}

		echo $view->get(__FUNCTION__);
	}

	/**
	 * Show Invite
	 */
	public function invitefriends()
	{
		$mainframe	= JFactory::getApplication();
		$jinput 	= $mainframe->input;
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);
		$my = CFactory::getUser();

		$invited = $jinput->post->get('invite-list', '', 'NONE'); //JRequest::getVar('invite-list', '', 'POST');
		$inviteMessage = $jinput->post->get('invite-message', '', 'STRING'); //JRequest::getVar('invite-message', '', 'POST');
		$eventId = JRequest::getInt('eventid', '', 'REQUEST');

		$model = $this->getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		if ($my->id == 0) {
			return $this->blockUnregister();
		}

		$status = $event->getUserStatus($my->id);
		$allowed = array(COMMUNITY_EVENT_STATUS_INVITED, COMMUNITY_EVENT_STATUS_ATTEND, COMMUNITY_EVENT_STATUS_WONTATTEND, COMMUNITY_EVENT_STATUS_MAYBE);
		$accessAllowed = ((in_array($status, $allowed)) && $status != COMMUNITY_EVENT_STATUS_BLOCKED) ? true : false;
		$accessAllowed = COwnerHelper::isCommunityAdmin() ? true : $accessAllowed;

		if (!($accessAllowed && $event->allowinvite) && !$event->isAdmin($my->id)) {
			echo JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN');
			return;
		}

		if (JRequest::getMethod() == 'POST') {
			// Check for request forgeries
			JRequest::checkToken() or jexit(JText::_('COM_COMMUNITY_INVALID_TOKEN'));

			if (!empty($invited)) {
				$mainframe = JFactory::getApplication();
				$invitedCount = 0;

				foreach ($invited as $invitedUserId) {
					$date = JFactory::getDate();
					$eventMember = JTable::getInstance('EventMembers', 'CTable');
					$eventMember->eventid = $event->id;
					$eventMember->memberid = $invitedUserId;
					$eventMember->status = COMMUNITY_EVENT_STATUS_INVITED;
					$eventMember->invited_by = $my->id;
					$eventMember->created = $date->toSql();
					$eventMember->store();
					$invitedCount++;
				}

				//now update the invited count in event
				$event->invitedcount = $event->invitedcount + $invitedCount;
				$event->store();

				// Send notification to the invited user.
				//CFactory::load( 'libraries' , 'notification' );

				$params = new CParameter('');
				$params->set('url', CRoute::getExternalURL('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id));
				$params->set('eventTitle', $event->title);
				$params->set('event', $event->title);
				$params->set('event_url', 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);
				$params->set('message', $inviteMessage);
				CNotificationLibrary::add('events_invite', $my->id, $invited, JText::sprintf('COM_COMMUNITY_EVENTS_JOIN_INVITE'), '', 'events.invite', $params);

				$view->addInfo(JText::_('COM_COMMUNITY_EVENTS_INVITATION_SENT'));
			} else {
				$view->addWarning(JText::_('COM_COMMUNITY_INVITE_NEED_AT_LEAST_1_FRIEND'));
			}
		}
		echo $view->get(__FUNCTION__);
	}

	/**
	 * Responsible to update a specific user's rsvp
	 **/
	public function updatestatus()
	{
		//return;




		$mainframe = JFactory::getApplication();
		$my = CFactory::getUser();

		$memberid = JRequest::getInt('memberid', '', 'POST');
		$eventId = JRequest::getInt('eventid', '', 'REQUEST');
		$status = JRequest::getInt('status', '', 'REQUEST');
		$target = NULL;

		if ($my->id == 0) {
			$url = CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid=' . $eventId, false);
			return $this->blockUnregister($url);
		}

		// Check for request forgeries
		// This need to be below id test to make sure login is properly processe
		if (JRequest::getMethod() == 'POST') {
			JRequest::checkToken() or jexit(JText::_('COM_COMMUNITY_INVALID_TOKEN') . 'EVENTS');
		} else {
			$mainframe->redirect(CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid=' . $eventId, false));
			return;
		}

		$model = $this->getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);

		if (!$handler->isAllowed()) {
			echo JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN');
			return;
		}

		// If a number of ticket is specified and adding one more person exceed
		// the ticket number, we have to decline them
		if (($event->ticket) && (($status == COMMUNITY_EVENT_STATUS_ATTEND) && ($event->confirmedcount + 1) > $event->ticket)) {
			$mainframe->redirect($handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false), JText::_('COM_COMMUNITY_EVENTS_TICKET_FULL'));
			return;
		}

		$eventMember = JTable::getInstance('EventMembers', 'CTable');
		$keys = array('eventId'=>$eventId,'memberId'=>$memberid);
		$eventMember->load($keys);

		$date = JFactory::getDate();

		if ($eventMember->permission != 1 && $eventMember->permission != 2) {
			//always a member
			$eventMember->permission = '3';
		}

		$eventMember->created = $date->toSql();
		$eventMember->status = $status;
		$eventMember->store();

		$event->updateGuestStats();
		$event->store();

		//activities stream goes here.
		$url = $handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false);
		$statustxt = JText::_('COM_COMMUNITY_EVENTS_NO');

		if ($status == COMMUNITY_EVENT_STATUS_ATTEND) {
			$statustxt = JText::_('COM_COMMUNITY_EVENTS_YES');
		}

		if ($status == COMMUNITY_EVENT_STATUS_MAYBE) {
			$statustxt = JText::_('COM_COMMUNITY_EVENTS_MAYBE');
		}

		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);

		// We update the activity only if a user attend an event and the event was set to public event
		if ($status == COMMUNITY_EVENT_STATUS_ATTEND && $handler->isPublic()) {
			$command = 'events.attendence.attend';
			$actor = $my->id;
			$target = 0;
			$content = '';
			$cid = $event->id;
			$app = 'events.attend';
			$act = $handler->getActivity($command, $actor, $target, $content, $cid, $app);
			$act->eventid = $event->id;

			$params = new CParameter('');
			$action_str = 'events.attendence.attend';
			$params->set('eventid', $event->id);
			$params->set('action', $action_str);
			$params->set('event_url', $handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false, true, false));

			// Add activity logging

			CActivityStream::addActor($act, $params->toString());
		}

		//trigger goes here.

		$appsLib = CAppPlugins::getInstance();
		$appsLib->loadApplications();

		$params = array();
		$params[] =$event;
		$params[] = $my->id;
		$params[] = $status;

		if (!is_null($target))
			$params[] = $target;
		$this->cacheClean(array(COMMUNITY_CACHE_TAG_EVENTS, COMMUNITY_CACHE_TAG_ACTIVITIES));
		$mainframe->redirect($handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false), JText::_('COM_COMMUNITY_EVENTS_RESPONSES_UPDATED'));

	}

	public function search()
	{
		$config = CFactory::getConfig();
		$mainframe = JFactory::getApplication();
		$my = JFactory::getUser();

		if ($my->id == 0 && !$config->get('enableguestsearchevents')) {
			$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_RESTRICTED_ACCESS'), 'notice');
			return $this->blockUnregister();
		}

		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);

		echo $view->get(__FUNCTION__);

	}

	public function uploadAvatar()
	{
		$mainframe = JFactory::getApplication();
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->getName());
		$view = $this->getView($viewName, '', $viewType);

		$eventid = JRequest::getInt('eventid', '0', 'REQUEST');

		$model = $this->getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventid);

		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);

		if (!$handler->manageable()) {
			echo JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN');
			return;
		}

		if (JRequest::getMethod() == 'POST') {
			JRequest::checkToken() or jexit(JText::_('COM_COMMUNITY_INVALID_TOKEN'));

			//CFactory::load( 'libraries' , 'apps' );

			$my = CFactory::getUser();
			$config = CFactory::getConfig();
			$appsLib = CAppPlugins::getInstance();
			$saveSuccess = $appsLib->triggerEvent('onFormSave', array('jsform-events-uploadavatar'));

			if (empty($saveSuccess) || !in_array(false, $saveSuccess)) {
				//CFactory::load( 'helpers' , 'image' );

				$fileFilter = new JInput($_FILES);
				$file = $fileFilter->get('filedata', '', 'array');
				//$file = JRequest::getVar('filedata', '', 'FILES', 'array');

				if (!CImageHelper::isValidType($file['type'])) {
					$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_IMAGE_FILE_NOT_SUPPORTED'), 'error');
					$mainframe->redirect($handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id . '&task=uploadAvatar', false));
				}

				if (empty($file)) {
					$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_NO_POST_DATA'), 'error');
				} else {

					$uploadLimit = (double)$config->get('maxuploadsize');
					$uploadLimit = ($uploadLimit * 1024 * 1024);

					// @rule: Limit image size based on the maximum upload allowed.
					if (filesize($file['tmp_name']) > $uploadLimit && $uploadLimit != 0) {
						$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_VIDEOS_IMAGE_FILE_SIZE_EXCEEDED'), 'error');
						$mainframe->redirect($handler->getFormattedLink('index.php?option=com_community&view=events&task=uploadavatar&eventid=' . $event->id, false));
					}

					if (!CImageHelper::isValid($file['tmp_name'])) {
						$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_IMAGE_FILE_NOT_SUPPORTED'), 'error');
						$mainframe->redirect($handler->getFormattedLink('index.php?option=com_community&view=events&task=uploadavatar&eventid=' . $event->id, false));
					} else {
						// @todo: configurable width?
						$imageMaxWidth = 160;

						// Get a hash for the file name.
						$fileName = JApplication::getHash($file['tmp_name'] . time());
						$hashFileName = JString::substr($fileName, 0, 24);

						// @todo: configurable path for avatar storage?
						$storage = JPATH_ROOT . '/' . $config->getString('imagefolder') . '/avatar/events';
						$storageImage = $storage . '/' . $hashFileName . CImageHelper::getExtension($file['type']);
						$image = $config->getString('imagefolder') . '/avatar/events/' . $hashFileName . CImageHelper::getExtension($file['type']);

						$storageThumbnail = $storage . '/thumb_' . $hashFileName . CImageHelper::getExtension($file['type']);
						$thumbnail = $config->getString('imagefolder') . '/avatar/events/' . 'thumb_' . $hashFileName . CImageHelper::getExtension($file['type']);

						// Generate full image
						if (!CImageHelper::resizeProportional($file['tmp_name'], $storageImage, $file['type'], $imageMaxWidth)) {
							$mainframe->enqueueMessage(JText::sprintf('COM_COMMUNITY_ERROR_MOVING_UPLOADED_FILE', $storageImage), 'error');
							$mainframe->redirect(CRoute::_('index.php?option=com_community&view=events&task=uploadavatar&eventid=' . $event->id, false));
						}

						// Generate thumbnail
						if (!CImageHelper::createThumb($file['tmp_name'], $storageThumbnail, $file['type'])) {
							$mainframe->enqueueMessage(JText::sprintf('COM_COMMUNITY_ERROR_MOVING_UPLOADED_FILE', $storageImage), 'error');
							$mainframe->redirect(CRoute::_('index.php?option=com_community&view=events&task=uploadavatar&eventid=' . $event->id, false));
						}

						// Autorotate avatar based on EXIF orientation value
						if ($file['type'] == 'image/jpeg') {
							$orientation = CImageHelper::getOrientation($file['tmp_name']);
							CImageHelper::autoRotate($storageImage, $orientation);
							CImageHelper::autoRotate($storageThumbnail, $orientation);
						}

						// Update the event with the new image
						$event->setImage($image, 'avatar');
						$event->setImage($thumbnail, 'thumb');

						//CFactory::load( 'helpers' , 'event' );
						$handler = CEventHelper::getHandler($event);

						if ($handler->isPublic()) {
							$actor = $my->id;
							$target = 0;
							$content = '<img class="event-thumb" src="' . rtrim(JURI::root(), '/') . '/' . $image . '" style="border: 1px solid #eee;margin-right: 3px;" />';
							$cid = $event->id;
							$app = 'events';
							$act = $handler->getActivity('events.avatar.upload', $actor, $target, $content, $cid, $app);
							$act->eventid = $event->id;

							$params = new CParameter('');
							$params->set('event_url', $handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false, true, false));


							CActivityStream::add($act, $params->toString());
						}

						//add user points
						//CFactory::load( 'libraries' , 'userpoints' );
						CUserPoints::assignPoint('event.avatar.upload');

						//$this->cacheClean( array(COMMUNITY_CACHE_TAG_EVENTS, COMMUNITY_CACHE_TAG_FRONTPAGE,COMMUNITY_CACHE_TAG_ACTIVITIES) );

						$mainframe = JFactory::getApplication();
						$mainframe->redirect($handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $eventid, false), JText::_('COM_COMMUNITY_EVENTS_AVATAR_UPLOADED'));
						exit;
					}
				}
			}
		}

		echo $view->get(__FUNCTION__);
	}

	/*
	 * group event name
	 * object array
     */

	public function triggerEvents($eventName, &$args, $target = null)
	{
		CError::assert($args, 'object', 'istype', __FILE__, __LINE__);

		require_once(COMMUNITY_COM_PATH . '/libraries/apps.php');
		$appsLib = CAppPlugins::getInstance();
		$appsLib->loadApplications();

		$params = array();
		$params[] =$args;

		if (!is_null($target))
			$params[] = $target;

		$appsLib->triggerEvent($eventName, $params);
		return true;
	}

	/**
	 * Ajax function to save a new wall entry
	 *
	 * @param message    A message that is submitted by the user
	 * @param uniqueId    The unique id for this group
	 *
	 **/
	public function ajaxSaveWall($message, $uniqueId)
	{
		$response = new JAXResponse();
		$my = CFactory::getUser();

		$filter = JFilterInput::getInstance();
		$message = $filter->clean($message, 'string');
		$uniqueId = $filter->clean($uniqueId, 'int');

		// Load necessary libraries
		//CFactory::load( 'libraries',	'wall' );
		//CFactory::load( 'helpers',		'url' );
		//CFactory::load( 'libraries',	'activities' );

		$model = $this->getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($uniqueId);
		$message = strip_tags($message);

		// Only those who response YES/NO/MAYBE can write on wall
		$eventMembers = JTable::getInstance('EventMembers', 'CTable');
		$keys = array('eventId'=>$uniqueId,'memberId'=>$my->id);
		$eventMembers->load($keys);

		$allowedStatus = array(COMMUNITY_EVENT_STATUS_ATTEND,
			COMMUNITY_EVENT_STATUS_WONTATTEND,
			COMMUNITY_EVENT_STATUS_MAYBE);
		//CFactory::load( 'helpers' , 'owner' );
		$config = CFactory::getConfig();

		if ((!in_array($eventMembers->status, $allowedStatus) && !COwnerHelper::isCommunityAdmin() && $config->get('lockeventwalls')) || $my->id == 0) {
			// Should not even be here unless use try to manipulate ajax call
			JError::raiseError(500, 'PERMISSION DENIED');
		}

		// If the content is false, the message might be empty.
		if (empty($message)) {
			$response->addAlert(JText::_('COM_COMMUNITY_EMPTY_MESSAGE'));
		} else {
			$isAdmin = $event->isAdmin($my->id);

			// @rule: Spam checks
			if ($config->get('antispam_akismet_walls')) {
				//CFactory::load( 'libraries' , 'spamfilter' );

				$filter = CSpamFilter::getFilter();
				$filter->setAuthor($my->getDisplayName());
				$filter->setMessage($message);
				$filter->setEmail($my->email);
				$filter->setURL(CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid=' . $uniqueId));
				$filter->setType('message');
				$filter->setIP($_SERVER['REMOTE_ADDR']);

				if ($filter->isSpam()) {
					$response->addAlert(JText::_('COM_COMMUNITY_WALLS_MARKED_SPAM'));
					return $response->sendResponse();
				}
			}

			// Save the wall content
			$wall = CWallLibrary::saveWall($uniqueId, $message, 'events', $my, $isAdmin, 'events,events');
			$event->addWallCount();


			//CFactory::load( 'helpers' , 'event' );
			$handler = CEventHelper::getHandler($event);

			if ($handler->isPublic()) {
				$actor = $my->id;
				$target = 0;
				$content = $message;
				$cid = $uniqueId;
				$app = 'events';
				$act = $handler->getActivity('events.wall.create', $actor, $target, $content, $cid, $app);
				$act->eventid = $event->id;

				$params = new CParameter('');
				$params->set('event_url', $handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false, true, false));
				$params->set('action', 'events.wall.create');
				$params->set('wallid', $wall->id);


				CActivityStream::add($act, $params->toString());
			}

			// @rule: Add user points
			//CFactory::load( 'libraries' , 'userpoints' );
			CUserPoints::assignPoint('events.wall.create');

			$response->addScriptCall('joms.walls.insert', $wall->content);
		}

		$this->cacheClean(array(COMMUNITY_CACHE_TAG_EVENTS, COMMUNITY_CACHE_TAG_ACTIVITIES));

		return $response->sendResponse();
	}

	public function ajaxRemoveWall($wallId)
	{
		$filter = JFilterInput::getInstance();
		$wallId = $filter->clean($wallId, 'int');

		CError::assert($wallId, '', '!empty', __FILE__, __LINE__);

		$response = new JAXResponse();


		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}

		//@rule: Check if user is really allowed to remove the current wall
		$my = CFactory::getUser();
		$wallModel = $this->getModel('wall');
		$wall = $wallModel->get($wallId);

		$eventModel = $this->getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($wall->contentid);

		if (!COwnerHelper::isCommunityAdmin() && !$event->isAdmin($my->id)) {
			$response->addScriptCall('alert', JText::_('COM_COMMUNITY_NOT_ALLOWED_TO_REMOVE_WALL'));
		} else {
			if (!$wallModel->deletePost($wallId)) {
				$response->addAlert(JText::_('COM_COMMUNITY_GROUPS_REMOVE_WALL_ERROR'));
			} else {
				if ($wall->post_by != 0) {
					//add user points
					//CFactory::load( 'libraries' , 'userpoints' );
					CUserPoints::assignPoint('wall.remove', $wall->post_by);
				}

			}

			// Substract the count
			$event->substractWallCount();
		}
		$this->cacheClean(array(COMMUNITY_CACHE_TAG_ACTIVITIES));
		return $response->sendResponse();
	}

	/*
	 * Return all the events with the given month/year
	 */
	public function ajaxGetCalendar($month, $year)
	{
		$response = new JAXResponse();
		$filter = JFilterInput::getInstance();

		$year = $filter->clean($year, 'int');
		$month = $filter->clean($month, 'int');

		$calendar_html = CCalendar::generate_calendar($year, $month);

		$response->addScriptCall('joms.events.displayCalendar', $calendar_html);
		return $response->sendResponse();
	}

	public function ajaxGetEvents($day, $month, $year, $group_id)
	{
		$response = new JAXResponse();
		$filter = JFilterInput::getInstance();
		$my = CFactory::getUser();

		$year = $filter->clean($year, 'int');
		$month = $filter->clean($month, 'int');
		$day = $filter->clean($day, 'int');
		$group_id = $filter->clean($group_id, 'int');

		//pass in date parameter
		$date = array();
		$date['date'] = $test = $day . '-' . $month . '-' . $year;
		//$date['enddate'] = strtotime(date("Y-m-d", strtotime($date['startdate'])) . " +1 day"); // add 1 day
		//$date['enddate'] = date("d-m-Y", $date['enddate']); // end date set as 1 day after the input date

		$model = CFactory::getModel('events');
		//@since 2.6
		if ($group_id) {
			$events = $model->getEvents(0, null, null, null, true, false, null, $date, 'group', $group_id);
		} else {
			// this will display every events that is available within groups/private groups that the user participated in
			$events = $model->getEvents(0, null, null, null, true, false, null, $date); //non filtered events, change this part if anything goes wrong

			//group events
			$group_model = CFactory::getModel('groups');
			$groupids = $group_model->getGroupIds($my->id);

			$group_events = array();
			foreach ($groupids as $gid) {
				$group_events = array_merge($group_events, $model->getEvents(0, null, null, null, true, false, null, $date, 'group', $gid));
			}

			$events = array_merge($events, $group_events);
		}

		//only pass needed information
		$event_list = array();

		foreach ($events as $event) {
			$event_ref = JTable::getInstance('Event', 'CTable');
			$event_ref->bind($event);
			$event_list[] = array("title" => $event->title,
				"link" => $event_ref->getLink(),
				"start" => date('j', strtotime($event->startdate)),
				"end" => date('j', strtotime($event->enddate))
			);
		}

		$response->addScriptCall('joms.events.displayDayEvent', json_encode($event_list));
		return $response->sendResponse();
	}


	protected function _viewEnabled()
	{
		$config = CFactory::getConfig();
		return $config->get('enableevents');
	}

	public function ajaxUpdateStatus($eventId, $status)
	{
		$filter = JFilterInput::getInstance();
		$eventId = $filter->clean($eventId, 'int');
		$status = $filter->clean($status, 'int');
		$target = NULL;

		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}

		$my = CFactory::getUser();
		$objResponse = new JAXResponse();

		$memberId = $my->id;

		$modal = $this->getModel('events');
		$event = JTable::getInstance('Event', 'CTable');
		$event->load($eventId);

		//CFactory::load( 'helpers' , 'event' );
		$handler = CEventHelper::getHandler($event);

		if (!$handler->isAllowed()) {
			$objResponse->addAlert(JText::_('COM_COMMUNITY_ACCESS_FORBIDDEN'));
			return $objResponse->sendResponse();
		}

		if (($event->ticket) && (($status == COMMUNITY_EVENT_STATUS_ATTEND) && (($event->confirmedcount + 1) > $event->ticket))) {
			$objResponse->addAlert(JText::_('COM_COMMUNITY_EVENTS_TICKET_FULL'));
			return $objResponse->sendResponse();
		}

		$eventMember = JTable::getInstance('EventMembers', 'CTable');
		$keys = array('eventId'=>$eventId,'memberId'=>$memberId);

		$eventMember->load($keys);

		if ($eventMember->permission != 1 && $eventMember->permission != 2) {
			$eventMember->permission = 3;
		}

		$date = JFactory::getDate();

		$eventMember->created = $date->toSql();
		$eventMember->status = $status;
		$eventMember->store();

		$event->updateGuestStats();
		$event->store();

		// trigger on event join.
		$this->triggerEvents('onEventJoin', $event);

		//activities stream goes here.
		$url = $handler->getFormattedLink('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id, false);

		//CFactory::load( 'helpers' , 'event' );

		// We update the activity only if a user attend an event and the event was set to public event
		if ($status == COMMUNITY_EVENT_STATUS_ATTEND && $handler->isPublic()) {
			$command = 'events.attendence.attend';
			$actor = $my->id;
			$target = 0;
			$content = '';
			$cid = $event->id;
			$app = 'events.attend';
			$act = $handler->getActivity($command, $actor, $target, $content, $cid, $app);
			$act->eventid = $event->id;

			$params = new CParameter('');
			$action_str = 'events.attendence.attend';
			$params->set('eventid', $event->id);
			$params->set('action', $action_str);
			$params->set('event_url', $url);

			// Add activity logging

			CActivityStream::addActor($act, $params->toString());
		}

		//trigger goes here.

		$appsLib = CAppPlugins::getInstance();
		$appsLib->loadApplications();

		$params = array();
		$params[] =$event;
		$params[] = $my->id;
		$params[] = $status;

		if (!is_null($target))
			$params[] = $target;

		$this->cacheClean(array(COMMUNITY_CACHE_TAG_EVENTS, COMMUNITY_CACHE_TAG_ACTIVITIES));


		$html = CEvents::getEventMemberHTML($event->id);

		if ($status == COMMUNITY_EVENT_STATUS_ATTEND)
			$RSVPmessage = JText::_('COM_COMMUNITY_EVENTS_ATTENDING_EVENT_MESSAGE');
		else
			$RSVPmessage = JText::_('COM_COMMUNITY_EVENTS_NOT_ATTENDING_EVENT_MESSAGE');

		$objResponse->addScriptCall('__callback', $html);

		$objResponse->addScriptCall("joms.jQuery('#community-event-rsvp-alert .cdata').html('$RSVPmessage');");
		return $objResponse->sendResponse();
	}

	public function ajaxShowMap()
	{
		$objResponse = new JAXResponse();
		$html = 'Maps Go here';
		$objResponse->addScriptCall('cWindowAddContent', $html);
		return $objResponse->sendResponse();
	}

	public function ajaxAddFeatured($eventId)
	{
		$filter = JFilterInput::getInstance();
		$eventId = $filter->clean($eventId, 'int');

		$objResponse = new JAXResponse();
		//CFactory::load( 'helpers' , 'owner' );

		if (COwnerHelper::isCommunityAdmin()) {
			$model = CFactory::getModel('Featured');

			if (!$model->isExists(FEATURED_EVENTS, $eventId)) {
				//CFactory::load( 'libraries' , 'featured' );
				//CFactory::load( 'models', 'events' );

				$featured = new CFeatured(FEATURED_EVENTS);
				$table = JTable::getInstance('Event', 'CTable');
				$table->load($eventId);

				$my = CFactory::getUser();
				$featured->add($eventId, $my->id);

				$html = JText::sprintf('COM_COMMUNITY_EVENT_IS_FEATURED', $table->title);
			} else {
				$html = JText::_('COM_COMMUNITY_GROUPS_ALREADY_FEATURED');
			}
		} else {
			$html = JText::_('COM_COMMUNITY_NOT_ALLOWED_TO_ACCESS_SECTION');
		}

		$buttons = '<input type="button" class="button" onclick="window.location.reload();" value="' . JText::_('COM_COMMUNITY_BUTTON_CLOSE_BUTTON') . '"/>';

		$objResponse->addScriptCall('cWindowAddContent', $html, $buttons);

		//ClearCache in Featured List
		$this->cacheClean(array(COMMUNITY_CACHE_TAG_FEATURED, COMMUNITY_CACHE_TAG_EVENTS));

		return $objResponse->sendResponse();
	}

	public function ajaxRemoveFeatured($eventId)
	{
		$filter = JFilterInput::getInstance();
		$eventId = $filter->clean($eventId, 'int');

		$objResponse = new JAXResponse();
		//CFactory::load( 'helpers' , 'owner' );

		if (COwnerHelper::isCommunityAdmin()) {
			$model = CFactory::getModel('Featured');

			//CFactory::load( 'libraries' , 'featured' );
			$featured = new CFeatured(FEATURED_EVENTS);
			$my = CFactory::getUser();

			if ($featured->delete($eventId)) {
				$html = JText::_('COM_COMMUNITY_EVENT_REMOVED_FROM_FEATURED');
			} else {
				$html = JText::_('COM_COMMUNITY_REMOVING_EVENT_FROM_FEATURED_ERROR');
			}
		} else {
			$html = JText::_('COM_COMMUNITY_NOT_ALLOWED_TO_ACCESS_SECTION');
		}

		$buttons = '<input type="button" class="button" onclick="window.location.reload();" value="' . JText::_('COM_COMMUNITY_BUTTON_CLOSE_BUTTON') . '"/>';

		$objResponse->addScriptCall('cWindowAddContent', $html, $buttons);

		//ClearCache in Featured List
		$this->cacheClean(array(COMMUNITY_CACHE_TAG_FEATURED, COMMUNITY_CACHE_TAG_GROUPS));

		return $objResponse->sendResponse();
	}

	public function ajaxShowRepeatOption()
	{
		//CFactory::load( 'libraries' , 'events' );

		$header = JText::_('COM_COMMUNITY_EVENTS_REPEAT');

		$message = CEvents::getEventRepeatSaveHTML();

		$selected = 'joms.jQuery(\'input:radio[name=repeattype]:checked\').val()';
		$action = '<button class="button" onclick="joms.jQuery(\'#repeataction\').val(' . $selected . ');joms.jQuery(\'#createEvent\').submit()">' . JText::_('COM_COMMUNITY_SAVE_BUTTON') . '</button><br>';

		$objResponse = new JAXResponse();
		$objResponse->addAssign('cwin_logo', 'innerHTML', $header);
		$objResponse->addScriptCall('cWindowAddContent', $message, $action);

		return $objResponse->sendResponse();
	}

	private function _saveRepeatChild($event, $eventChild, $isNew = true, $postData = '')
	{
		$insertList = array();
		$updateList = array();
		$id = 0;

		if ($isNew) {
			$insertList = $eventChild;
		} else {
			// event edit
			$id = $event->id;
			if (isset($postData['repeataction']) && $postData['repeataction'] == 'future') {

				$newList = $eventChild;
				array_shift($newList);

				$model = CFactory::getModel('Events');
				$oldList = $model->getEventChilds($event->parent, array('id' => $event->id));

				// start update old records.
				$db = JFactory::getDBO();
				$db->setQuery('START TRANSACTION');
				$db->query();

				// Update existing event child.
				$published = $event->published;
				foreach ($oldList as $key => $value) {
					if (isset($newList[$key])) {
						$event->id = $value['id'];
						$event->startdate = $newList[$key]['startdate'];
						$event->enddate = $newList[$key]['enddate'];
						$event->published = $value['published'] == 3 ? $value['published'] : $published;
						$event->store();
					} else {
						break;
					}
				}

				if (count($newList) > count($oldList)) {
					// insert new event child
					$insertList = array_slice($newList, count($oldList));
				} else if (count($oldList) > count($newList)) {
					// delete
					$deleteList = array_slice($oldList, count($newList));
					$id = array();
					foreach ($deleteList as $value) {
						$id[] = $value['id'];
					}
					$model->deleteExpiredEvent($id);
				}

				$db->setQuery('COMMIT');
				$db->query();
			}
		}

		// Insert new records.
		if (count($insertList) > 0) {
			$db = JFactory::getDBO();
			$db->setQuery('START TRANSACTION');
			$db->query();

			foreach ($insertList as $key => $value) {

				$event->id = 0;
				$event->startdate = $value['startdate'];
				$event->enddate = $value['enddate'];
				$event->store();

				$id = $key == 0 && ($id == 0) ? $event->id : $id;

				// Update event member.
				$this->_saveMember($event);

				// Increment the member count
				$event->updateGuestStats();
				$event->store();

			}
			$event->id = $id;

			$db->setQuery('COMMIT');
			$db->query();
		}

	}

	private function _generateRepeatList($event, $postData = '')
	{
		$day = 0;
		$month = 0;

		$eventList = array();
		$limit = isset($postData['limit']) ? (int)$postData['limit'] : 0;
		$defaultLimit = 0;
		$count = 0;

		// Repeat option.
		switch ($event->repeat) {

			case 'daily':
				$day = 1;
				$defaultLimit = COMMUNITY_EVENT_RECURRING_LIMIT_DAILY;
				break;

			case 'weekly':
				$day = 7;
				$defaultLimit = COMMUNITY_EVENT_RECURRING_LIMIT_WEEKLY;
				break;

			case 'monthly':
				$month = 1;
				$defaultLimit = COMMUNITY_EVENT_RECURRING_LIMIT_MONTHLY;
				break;

			default :
				break;
		}

		$strstartdate = strtotime($event->startdate);
		$starttime = date('H', $strstartdate) . ':' . date('i', $strstartdate) . ':' . date('s', $strstartdate);
		$strenddate = strtotime($event->enddate);
		$endtime = date('H', $strenddate) . ':' . date('i', $strenddate) . ':' . date('s', $strenddate);

		$startdate = date('Y-m-d', $strstartdate);
		$enddate = date('Y-m-d', $strenddate);



		$start = strtotime($event->startdate);
		$end = strtotime($event->enddate);

		// if repeatend is empty, generate dummy date to make it valid.
		if ($event->repeatend == '') {
			$repeatend = $event->enddate;
			// if both repeat end and limit never been set, use default limit.
			$limit = $limit == 0 ? $defaultLimit : $limit;
		} else {
			$repeatend = $event->repeatend;
		}

		$addDay = 0;
		$addMonth = 0;

		// Generate list of event childs in given date.
		while ((CTimeHelper::timeIntervalDifference($repeatend, $enddate) >= 0) || ($count < $limit)) {

			// Add event child as new array item.
			$eventList[] = array('startdate' => $startdate . ' ' . $starttime, 'enddate' => $enddate . ' ' . $endtime);

			// Compute the next event child.
			$addDay += $day;
			$addMonth += $month;

			$startdate = date('Y-m-d', mktime(0, 0, 0, date('m', $start) + $addMonth, date('d', $start) + $addDay, date('Y', $start)));
			$enddate = date('Y-m-d', mktime(0, 0, 0, date('m', $end) + $addMonth, date('d', $end) + $addDay, date('Y', $end)));

			$count++;

			// To avoid unnecessary loop.
			if ($count > $defaultLimit) {
				break;
			}
		}
		// SET repeat end date for empty data from import page
		if ($event->repeatend == '') {
			$event->repeatend = $enddate;
		}
		return $eventList;
	}

	private function _saveMember($event)
	{

		// Since this is storing event, we also need to store the creator / admin
		// into the events members table
		$member = JTable::getInstance('EventMembers', 'CTable');
		$member->eventid = $event->id;
		$member->memberid = $event->creator;
		$member->created = JFactory::getDate()->toSql();

		// Creator should always be 1 as approved as they are the creator.
		$member->status = COMMUNITY_EVENT_STATUS_ATTEND;

		// @todo: Setup required permissions in the future
		$member->permission = '1';

		$member->store();
	}

	// Add activity stream for new created event.
	private function _addActivityStream($event)
	{

		CEvents::addEventStream($event);
	}

	// send notification email to group's member for new created event.
	private function _addGroupNotification($event)
	{

		CEvents::addGroupNotification($event);
	}

	private function isPublished()
	{
		$my = CFactory::getUser();
		$config = CFactory::getConfig();

		if (!COwnerHelper::isCommunityAdmin()) {
			// set event published = 2 for new created
			return $config->get('event_moderation') ? 2 : 1;
		} else {
			return 1;
		}
	}
}