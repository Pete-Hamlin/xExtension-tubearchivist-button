<?php

class FreshExtension_tubearchivistButton_Controller extends Minz_ActionController
{
  /** @var TubeArchivistButton\View */
  protected $view;

  public function jsVarsAction(): void
  {
    $extension = Minz_ExtensionManager::findExtension('TubeArchivist Button');
    $this->view->tubearchivist_button_vars = json_encode(array(
      'instance_url' => FreshRSS_Context::userConf()->attributeString('tubearchivist_instance_url'),
      'keyboard_shortcut' => FreshRSS_Context::userConf()->hasParam("tubearchivist_shortcut")
        ? FreshRSS_Context::userConf()->attributeString('tubearchivist_shortcut')
        : '',
      'icons' => array(
        'added_to_tubearchivist' => $extension->getFileUrl('added_to_tubearchivist.svg'),
      ),
      'i18n' => array(
        'added_video_to_tubearchivist' => _t('ext.tubearchivistButton.notifications.added_video_to_tubearchivist', '%s', '%s'),
        'failed_to_add_video_to_tubearchivist' => _t('ext.tubearchivistButton.notifications.failed_to_add_video_to_tubearchivist', '%s'),
        'ajax_request_failed' => _t('ext.tubearchivistButton.notifications.ajax_request_failed'),
        'video_not_found' => _t('ext.tubearchivistButton.notifications.video_not_found'),
      )
    ));

    $this->view->_layout(null);
    $this->view->_path('tubearchivistButton/vars.js');

    header('Content-Type: application/javascript; charset=utf-8');
  }

  public function addAction(): void
  {
    $this->view->_layout(null);

    $entry_id = Minz_Request::paramString('id');
    $entry_dao = FreshRSS_Factory::createEntryDao();
    $entry = $entry_dao->searchById($entry_id);

    if ($entry === null) {
      echo json_encode(array('errorCode' => 404));
      return;
    }

    $post_data = array(
      'data' => array(
        array(
          'youtube_id' => $entry->link(),
          'status' => 'pending',
        )
      )
    );

    // Errors are handled in the JS
    $result = $this->curlPostRequest('/download/', $post_data);
    echo json_encode($result);
  }

  /**
   * @return array<string>
   */
  private function getRequestHeaders(): array
  {
    $api_token = FreshRSS_Context::userConf()->attributeString('tubearchivist_api_token');
    return array(
      'Content-Type: application/json; charset=UTF-8',
      'X-Accept: application/json',
      "Authorization: Token " . $api_token,
    );
  }

  /**
   * @return \CurlHandle
   */
  private function getCurlBase(string $url): \CurlHandle
  {
    $headers = $this->getRequestHeaders();
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    return $curl;
  }

  /**
   * @param array<string,mixed> $post_data
   * @return array<string,mixed>
   */
  private function curlPostRequest(string $endpoint, array $post_data): array
  {
    $instance_url = FreshRSS_Context::userConf()->attributeString('tubearchivist_instance_url');
    $curl = $this->getCurlBase($instance_url . "/api" . $endpoint);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));


    $response = curl_exec($curl);
    error_log($response);

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $response_header = substr($response, 0, $header_size);
    $response_body = substr($response, $header_size);
    $response_headers = $this->httpHeaderToArray($response_header);

    return array(
      'response' => json_decode($response_body),
      'status' => curl_getinfo($curl, CURLINFO_HTTP_CODE),
      'errorCode' => isset($response_headers['x-error-code'])
        ? intval($response_headers['x-error-code'])
        : curl_getinfo($curl, CURLINFO_HTTP_CODE)
    );
  }

  /**
   * @return array<string,string>
   */
  private function httpHeaderToArray(string $header): array
  {
    $headers = array();
    $headers_parts = explode("\r\n", $header);

    foreach ($headers_parts as $header_part) {
      // skip empty header parts
      if (strlen($header_part) <= 0) {
        continue;
      }

      // Filter the beginning of the header which is the basic HTTP status code
      if (strpos($header_part, ':')) {
        $header_name = substr($header_part, 0, strpos($header_part, ':'));
        $header_value = substr($header_part, strpos($header_part, ':') + 1);
        $headers[strtolower($header_name)] = trim($header_value);
      }
    }

    return $headers;
  }
}
