<!-- ajax theme -->
<?php
if (isset($content_text)) {
  echo $content_text;
}
if (isset($content_view)) {
  if (isset($content_data)) {
    $data = array();
    if (!empty($content_data)) {
      foreach ($content_data as $key => $value) {
        $data[$key] = $value;
      }
    }
    $this->load->view($content_view, $data);
  } else {
    $this->load->view($content_view);
  }
}
?>
