<?php

  function getVideoMimeType($videoPath) {
      $extension = pathinfo($videoPath, PATHINFO_EXTENSION);
      
      switch ($extension) {
          case 'mp4':
              return 'video/mp4';
          case 'avi':
              return 'video/avi';
          case 'mov':
              return 'video/quicktime';
      }
  }
  
?>