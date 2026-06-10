<?php

return array(
  'tubearchivistButton' => array(
    'configure' => array(
      'api_token' => 'API token',
      'api_token_description' => '<ul class="rb_listedNumbers">
        <li>Go to your TubeArchivist instance and navigate to \'<c><your_tubearchivist_instance>/settings/application</c>\'</li>
        <li>Copy the API token listed under the Integrations tab</li>
        <li>Enter your TubeArchivist instance url and API token and hit \'Save\'</li>
      </ul>
      <span>Details can be found on <a href="https://github.com/Joedmin/freshrss-tubearchivist-button" target="_blank">GitHub</a>!',
      'connect_to_tubearchivist' => 'Save',
      'username' => 'Username',
      'instance_url' => 'TubeArchivist instance url',
      'keyboard_shortcut' => 'Keyboard shortcut',
      'extension_disabled' => 'You need to enable the extension before you can connect to TubeArchivist!',
      'connected_to_tubearchivist' => 'You are connected as <b>%s</b> to TubeArchivist at <b>%s</b>.',
      'revoke_access' => 'Disconnect from TubeArchivist!',
      'save_changes' => 'Save',
      'button_location' => 'TubeArchivist button position. The keyboard shortcut works even when the \'Hidden\' option is selected.',
      'button_location_header_bottom' => 'Top and bottom line',
      'button_location_header' => 'Top line',
      'button_location_bottom' => 'Bottom line',
      'button_location_hidden' => 'Hidden',
    ),
    'notifications' => array(
      'added_article_to_tubearchivist' => 'Successfully added <a href="%s" target="_blank">\'%s\'</a> to TubeArchivist!',
      'failed_to_add_article_to_tubearchivist' => 'Adding article to TubeArchivist failed! TubeArchivist API error code: %s',
      'ajax_request_failed' => 'Ajax request failed!',
      'authorized_success' => 'Authorization successful!',
      'authorized_aborted' => 'Authorization aborted!',
      'authorized_failed' => 'Authorization failed! TubeArchivist API error code: %s',
      'request_access_failed' => 'Access request failed! TubeArchivist API error code: %s',
      'article_not_found' => 'Can\'t find article!',
      'authorization_revoked' => 'Authorization successfully revoked!',
      'changes_saved_sucessfully' => "Changes saved successfully!",
      'changes_failed' => "Could not save changes! Value '%s' is not supported!",
    )
  ),
);
