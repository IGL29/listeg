<?php

function messageValidate($title, $value, $sectionId, $recipientId) 
{ 
  $title = htmlspecialchars($title);
  $value = htmlspecialchars($value);
  $sectionId = htmlspecialchars($sectionId);
  $recipientId = htmlspecialchars($recipientId);

  $isValid = false;

  if (strlen($title) && strlen($value) && strlen($sectionId)) {
    $isValid = true;
  }
  return [['title' => $title, 'value' => $value, 'sectionId' => $sectionId, 'recipientId' => $recipientId], $isValid];
}