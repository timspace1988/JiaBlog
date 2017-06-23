<?php
//As we register this helpers file in bootstrap/autoload file, all functions in file will be always loaded and available in JiaBlog application


  /**
   * database configuration for local and product environment
   */
  function get_db_config() {
      if(getenv('IS_IN_HEROKU')){
          $url = parse_url(getenv("DATABASE_URL"));

          return $db_config = [
              'connection' => 'pgsql',
              'host' => $url["host"],
              'database'  => substr($url["path"], 1),
              'username'  => $url["user"],
              'password'  => $url["pass"],
          ];
      }else{
          return $db_config = [
                'connection' => env('DB_CONNECTION', 'mysql'),
                'host' => env('DB_HOST', 'localhost'),
                'database'  => env('DB_DATABASE', 'forge'),
                'username'  => env('DB_USERNAME', 'forge'),
                'password'  => env('DB_PASSWORD', ''),
            ];
      }
  }

  /**
   * return sizes in a readable format instead of a count of bytes
   */
  function human_filesize($bytes, $decimals = 2){
      $size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
      $factor = floor((strlen($bytes) - 1) / 3);//the value of $factor determines whichi unit(KB, MB or GB...) would be used

      return sprintf("%.{$decimals}f", $bytes/pow(1024, $factor)).@$size[$factor];
      //@ is an error control operator in php, it suppresses errors. It simplely make error handler shut-up for specific call
  }

  /**
   * check if mime type is an image,
   *@return boolean
   */
   function is_image($mimeType){
       return starts_with($mimeType, 'image/');
   }
